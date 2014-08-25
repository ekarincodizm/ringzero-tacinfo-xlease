<?php
include("../../config/config.php");
include("../function/checknull.php");
include("../function/randomDigit.php");
include('class.upload.php');

$addUser = $_SESSION["av_iduser"];
$addStamp = nowDateTime();
$nowdate = date("Y-m-d");
$nowdateTime = date("YmdHis");

$userSalebill=pg_escape_string($_POST["userSalebill"]);
$userSale=explode("#",$userSalebill);
$userSalebill=$userSale[0];

$userDebtor=pg_escape_string($_POST["userDebtor"]);
$userDeb=explode("#",$userDebtor);
$userDebtor=$userDeb[0];

$dateInvoice=pg_escape_string($_POST["dateInvoice"]);
$numberInvoice=pg_escape_string($_POST["numberInvoice"]);
$totalTaxInvoice=pg_escape_string($_POST["totalTaxInvoice"]);
$dateBill=pg_escape_string($_POST["dateBill"]);
$dateAssign=$_POST["dateAssign"]; //วันที่นัดรับเช็ค
$recmoney=$_POST["recmoney"]; //จำนวนเงินที่ไปรับเช็ค
$checkplace=pg_escape_string($_POST["checkplace"]);
if($checkplace=="1"){
	$placeReceiveChq=pg_escape_string($_POST["placeReceiveChq1"]);
}else{
	$placeReceiveChq=pg_escape_string($_POST["placeReceiveChq2"]);
}
$method=$_REQUEST["method"];
if($method==""){
	$sendfrom="showapprove";
	if(isset($_POST["appv"])){
		$method='appedityes';//อนุมัติ
	}else if(isset($_POST["notappv"])){
		$method='appeditno';//ไม่อนุมัติ
	}
}
$note=pg_escape_string($_POST["note"]);

pg_query("BEGIN WORK");
$status = 0;
if(isset($_POST["appv"])){
		$stsapp='1';//ตรวจสอบแล้ส		
}else if (isset($_POST["unappv"])){
		$stsapp='2';//ยกเลิกบิล		
}else{
		$stsapp='0';		
}		
if($method=="approve"){ //กรณีเป็นการอนุมัติเพิ่มบิล
	/*มีการเปลี่ยนแปลงการอนุมัติ คือมีการยกเลิกบิล แทนที่การไม่อนุมัติเดิม 
	แต่เนื่องจากไม่อยากให้กระทบโค้ด จึงยังคง process ในส่วนไม่อนุมัติไว้อยู่ และได้มีการเพิ่มเงื่อนไขกรณีมีการยกเลิกบิลแทน*/
	
	$prebillIDMaster=pg_escape_string($_POST["prebillIDMaster"]);
	$statusSellBuy=pg_escape_string($_POST["chksell"]);
	$ruleSellBuy=pg_escape_string($_POST["rule"]);
	$cusContact=pg_escape_string($_POST["cusContact"]);
	$cusPost=pg_escape_string($_POST["cusPost"]);
	$cusTel=pg_escape_string($_POST["cusTel"]);
	$dateContact=pg_escape_string($_POST["dateContact"]);
	$hour=pg_escape_string($_POST["hour"]);
	$minute=pg_escape_string($_POST["minute"]);
	$dateContact=$dateContact.' '.$hour.':'.$minute;
	$note=pg_escape_string($_POST["note"]);
	$sendfrom="showapprove";
	$edittime=0; //กรณีขออนุมัติเพิ่มบิล edittime=0 เสมอ
	$chkapp = json_decode(stripcslashes($_POST["chkapp"])); //รายการที่เลือกอนุมัติเพิ่มเติม
				
	//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
	$qry_check=pg_query("select \"prebillIDMaster\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\" ='2' and \"edittime\"='0'");
	$num_check=pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
	if($num_check == 0){
		$status=-1;
	}
	
	//ตรวจสอบรายการที่อนุมัติที่เพิ่มเติม ว่าได้อนุมัติไปก่อนหน้านี้แล้วหรือยัง
	$z=0;
	/*foreach($chkapp as $key => $value){
		$prebillIDMaster2 = $value->chk;*/
		for($ichk=0;$ichk< count($_POST["chk"]);$ichk++)
		{
			$prebillIDMaster2= $_POST["chk"][$ichk];
		
		if($prebillIDMaster2!=""){		
			$qry_check2=pg_query("select \"prebillIDMaster\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster2' and \"statusApp\" ='2' and \"edittime\"='0'");
			$num_check2=pg_num_rows($qry_check2); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
			if($num_check2 == 0){
				$z++;
			}
		}
	}
	if($z>0){
		$status=-1; //แสดงว่ามีบางรายการได้อนุมัติไปก่อนหน้านี้แล้ว
	}
	
	if($status!=-1){ //ถ้าทุกรายการยังไม่ได้รับการอนุมัติ
		if($stsapp=="1"){ //กรณีอนุมัติ
			//ดึงข้อมูลจากตาราง thcap_fa_prebill_temp insert ในตาราง thcap_fa_prebill
			$qrytemp=pg_query("select \"auto_id\",\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", \"totalTaxInvoice\",
			\"dateBill\", \"dateAssign\", \"placeReceiveChq\", note,\"taxInvoice\",\"prebillIDMaster\" 
			from thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\" ='2' and \"edittime\"='0' order by \"auto_id\"");
			$p=0;
			while($restemp=pg_fetch_array($qrytemp)){
				$insbill="insert into \"thcap_fa_prebill\"(\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
				\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", note,\"taxInvoice\",\"prebillIDMaster\") values
				('$restemp[userSalebill]','$restemp[userDebtor]','$restemp[dateInvoice]','$restemp[numberInvoice]','$restemp[totalTaxInvoice]',
				'$restemp[dateBill]','$restemp[dateAssign]','$restemp[placeReceiveChq]','$restemp[note]','$restemp[taxInvoice]','$restemp[prebillIDMaster]')
				returning \"prebillID\"";					
				if($resins=pg_query($insbill)){
					list($prebillID)=pg_fetch_array($resins);
				}else{
					$status++;
				}
				
				if($p==0){ //กำหนดเลข prebillMaster เป็นรายการ prebillID ที่ได้ใหม่แทนเลข auto_id
					$prebillIDMaster=$prebillID;
					$auto_id=$restemp["auto_id"]; //เลข auto_temp ในตารางเก็บไฟล์
				}
				
				//update ตาราง temp ให้มี prebillID ตามที่ได้
				$uptemp="update thcap_fa_prebill_temp set \"prebillID\"='$prebillID',\"prebillIDMaster\"='$prebillIDMaster',
				\"statusApp\"='$stsapp', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
				where \"auto_id\"=$restemp[auto_id]";
				if($restemp=pg_query($uptemp)){
				}else{
					$status++;
				}
				
				$p++;
			}
			
			//update ตารางหลัก ให้เก็บ prebillIDMaster ตามที่ได้
			$upmain="update thcap_fa_prebill set \"prebillIDMaster\"='$prebillIDMaster'
			where \"prebillIDMaster\"='$auto_id'";
			if($restemp=pg_query($upmain)){
			}else{
				$status++;
			}

			//update file ให้เก็บค่า prebillID
			$upfile="update thcap_fa_prebill_file set \"prebillID\"='$prebillIDMaster' where \"auto_temp\"='$auto_id'";
			if($resfile=pg_query($upfile)){
			}else{
				$status++;
			}		
			
			//บันทึกรายการอื่นๆ ที่อนุมัติพร้อมกัน
			/*foreach($chkapp as $key => $value){
				$prebillIDMaster2 = $value->chk;*/
				for($ichk=0;$ichk< count($_POST["chk"]);$ichk++)
		{
			$prebillIDMaster2= $_POST["chk"][$ichk];
				
				if($prebillIDMaster2!=""){
					//ดึงข้อมูลจากตาราง thcap_fa_prebill_temp insert ในตาราง thcap_fa_prebill
					$qrytemp2=pg_query("select \"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", \"totalTaxInvoice\",
					\"dateBill\", \"dateAssign\", \"placeReceiveChq\", note,\"taxInvoice\",\"prebillIDMaster\" 
					from thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillIDMaster2' and \"statusApp\" ='2' and \"edittime\"='0'");
					$p=0;
					while($restemp=pg_fetch_array($qrytemp)){

						$insbill2="insert into \"thcap_fa_prebill\"(\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
						\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", note,\"taxInvoice\",\"prebillIDMaster\") values
						('$restemp2[userSalebill]','$restemp2[userDebtor]','$restemp2[dateInvoice]','$restemp2[numberInvoice]','$restemp2[totalTaxInvoice]',
						'$restemp2[dateBill]','$restemp2[dateAssign]','$restemp2[placeReceiveChq]','$restemp2[note]','$restemp2[taxInvoice]','$restemp2[prebillIDMaster]')
						returning \"prebillID\"";					
						if($resins2=pg_query($insbill2)){
							list($prebillID2)=pg_fetch_array($resins2);
						}else{
							$status++;
						}
						
						if($p==0){ //กำหนดเลข prebillMaster เป็นรายการ prebillID ที่ได้ใหม่แทนเลข auto_id
							$prebillIDMaster2=$prebillID2;
							$auto_id2=$restemp2["auto_id"]; //เลข auto_temp ในตารางเก็บไฟล์
						}
						
						//update ตาราง temp ให้มี prebillID ตามที่ได้
						$uptemp2="update thcap_fa_prebill_temp set \"prebillID\"='$prebillID2',\"prebillIDMaster\"='$prebillIDMaster2'
						where \"auto_id\"=$restemp2[auto_id]";
						if($restemp2=pg_query($uptemp2)){
						}else{
							$status++;
						}
						$p++;
					}
					//update ตารางหลัก ให้เก็บ prebillIDMaster ตามที่ได้
					$upmain2="update thcap_fa_prebill set \"prebillIDMaster\"='$prebillIDMaster2'
					where \"prebillIDMaster\"='$auto_id2'";
					if($restemp2=pg_query($upmain2)){
					}else{
						$status++;
					}
			
					//update file ให้เก็บค่า prebillID
					$upfile2="update thcap_fa_prebill_file set \"prebillID\"='$prebillIDMaster2' where \"auto_temp\"='$auto_id2'";
					if($resfile2=pg_query($upfile2)){
					}else{
						$status++;
					}						
				}
			}
			
		}
										
		//ไม่ว่าจะอนุมัติหรือไม่ให้ insert รายละเอียดการอนุมัติลงในตาราง thcap_fa_prebill_appdetail โดยหา prebillID จากเลข group
		//แต่กรณีที่ยกเลิกบิล จะไม่บันทึกส่วนนี้เนื่องจาก ไม่มีการกรอกข้อมูลเพิ่มเติม
		if($stsapp=="0" OR $stsapp=="1"){
			$qrysearch=pg_query("select \"prebillID\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster' and \"edittime\" ='0'");
			while($ressearch=pg_fetch_array($qrysearch)){
				$prebillID=$ressearch["prebillID"];
				
				$insdetail="INSERT INTO thcap_fa_prebill_appdetail(
					\"prebillID\", \"statusSellBuy\", \"ruleSellBuy\", \"cusContact\", \"cusPost\", 
					\"cusTel\", \"dateContact\", note)
				VALUES ('$prebillID', '$statusSellBuy', $ruleSellBuy, '$cusContact', '$cusPost', 
					'$cusTel', '$dateContact', '$note')";
				if($resdetail=pg_query($insdetail)){
				}else{
					$status++;
				}
			}
		
			//บันทึกรายการอื่นๆ ที่อนุมัติพร้อมกัน ในตาราง thcap_fa_prebill_appdetail
			/*foreach($chkapp as $key => $value){
				$prebillIDMaster2 = $value->chk;*/
				for($ichk=0;$ichk< count($_POST["chk"]);$ichk++)
		{
			$prebillIDMaster2= $_POST["chk"][$ichk];
				
				if($prebillIDMaster2!=""){
					$qrysearch2=pg_query("select \"prebillID\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster2' and \"edittime\" ='0'");
					while($ressearch2=pg_fetch_array($qrysearch2)){
						$prebillID2=$ressearch2["prebillID"];
						
						$insdetail2="INSERT INTO thcap_fa_prebill_appdetail(
							\"prebillID\", \"statusSellBuy\", \"ruleSellBuy\", \"cusContact\", \"cusPost\", 
							\"cusTel\", \"dateContact\", note)
						VALUES ('$prebillID2', '$statusSellBuy', $ruleSellBuy, '$cusContact', '$cusPost', 
							'$cusTel', '$dateContact', '$note')";
						if($resdetail2=pg_query($insdetail2)){
						}else{
							$status++;
						}			
					}
				}
			}
		}
		
		//ถ้า $stsapp ที่ส่งมาเท่ากับ 2 แสดงว่ามีการยกเลิกบิล ซึ่งก็ถือว่าไม่อนุมัติ (ในอดีตการไม่อนุมัติก็คือการยกเลิกบิล จึงกำหนดสถานะให้เป็น 0 เหมือนกัน)
		if($stsapp==2){
			$stsapp=0;
		}
		
		if($stsapp==0){
			//update ตาราง thcap_fa_prebill_temp ว่าได้อนุมัติแล้ว โดยหา prebillID
			$qryprebill=pg_query("select \"auto_id\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\" ='2' and \"edittime\"='0'");
			while($resbill=pg_fetch_array($qryprebill)){
				$auto_id=$resbill["auto_id"];
				
				$uptemp="UPDATE \"thcap_fa_prebill_temp\"
				SET \"statusApp\"='$stsapp', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
				WHERE \"auto_id\"='$auto_id' and \"statusApp\"='2' and \"edittime\"='0'";	
				if($resuptemp=pg_query($uptemp)){
				}else{
					$status++;
				}	
			}
		}
		
	}
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$addUser', '(THCAP) FA อนุมัติบิลขอสินเชื่อ', '$addStamp')");
	//ACTIONLOG---

}else if($method=="add"){ //กรณีบันทึกข้อมูล									
?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
	opener.location.reload(true);
	self.close();
}
</script> 
<?php
	//วนเก็บข้อมูล
	for($j=0;$j<sizeof($dateAssign);$j++){
		if(sizeof($dateAssign)==1){ //กรณีมีแค่ 1 แสดงว่ารับเช็คแค่ครั้งเดียว
			$totalTax=$totalTaxInvoice;	
		}else{ //กรณีมีมากกว่า 1 แสดงว่ามีการนัดรับเช็คหลายครั้ง
			$totalTax=$recmoney[$j];
		}
		
		//ในการบันทึกข้อมูลนั้นจะยังไม่มีเลข prebillID จนกว่าจะอนุมัติ
		
		$instemp="INSERT INTO thcap_fa_prebill_temp(
		\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
		\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", 
		note, \"statusApp\", \"addUser\", \"addStamp\",\"taxInvoice\",\"edittime\")
		VALUES ('$userSalebill', '$userDebtor', '$dateInvoice', '$numberInvoice', 
		'$totalTaxInvoice', '$dateBill', '$dateAssign[$j]', '$placeReceiveChq', 
		'$note', '2', '$addUser', '$addStamp','$totalTax','0') returning \"auto_id\" ";
					
		if($restemp=pg_query($instemp)){
			list($auto_id)=pg_fetch_array($restemp);
		}else{
			$status++;
		}
		
		
		if($j==0){ //นำเฉพาะ auto_id แรก ไปเก็บ
			$auto_temp=$auto_id;
		}
		
		//update master ให้เป็นเลข auto_id เพื่อจัดกลุ่มการทำรายการครั้งนี้
		$uptemp="UPDATE thcap_fa_prebill_temp SET \"prebillIDMaster\"='$auto_temp' WHERE \"auto_id\"='$auto_id'";
		if($uptemp=pg_query($uptemp)){
		}else{
			$status++;
		}
	}

	// set variables
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../upload/fa_prebill');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
	$files = array();
			
	foreach ($_FILES["my_field"] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
				$files[$i][$k] = $v;
			
		}
	}					
	foreach ($files as $file){
		$handle = new Upload($file);
							   
		if($handle->uploaded){
			// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
			$handle->Process($dir_dest);							 								
			if ($handle->processed) {
				$pathfile=$handle->file_dst_name;
				
				$Board_oldfile = $pathfile;			
				$Board_newfile = md5_file("../upload/fa_prebill/$pathfile", FALSE);		
											
				$Board_cuttext = split("\.",$pathfile);
				$Board_nubtext = count($Board_cuttext);
				$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
											
				$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
											
				$Boardfile = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
				
				$flgRename = rename("../upload/fa_prebill/$Board_oldfile", "../upload/fa_prebill/$Board_newfile");
				if($flgRename)
				{
					//echo "บันทึกสำเร็จ";
				}
				else
				{
					echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
					$status++;
				}
				
				//เก็บไฟล์ upload เฉพาะรหัส auto_id ของรายการที่ 1 อย่างเดียว โดยจะเก็บ prebillID เป็น null
				$ins="INSERT INTO thcap_fa_prebill_file(\"prebillID\", file,\"edittime\",\"auto_temp\") VALUES (null, $Boardfile,'0','$auto_temp')";					
				if($resup=pg_query($ins)){
				}else{
					$status++;
				}		
			}else{
				echo '<fieldset>';
				echo '  <legend>file not uploaded to the wanted location</legend>';
				echo '  Error: ' . $handle->error . '';
				echo '</fieldset>';
				$status++;
			}
		}
		
		//ตรวจสอบว่ามีไฟล์จริงหรือไม่
		if(!file_exists("../upload/fa_prebill/$Board_newfile")){
			$status++;
		}
	}
	//ACTIONLOG
	$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$addUser', '(THCAP) FA เพิ่มบิลขอสินเชื่อ', '$addStamp')";
	if($reslog=pg_query($sqlaction)){
	}else{
		$status++;
	}
	//ACTIONLOG---
}else if($method=="edit"){ //กรณีแก้ไขข้อมูล	
	$prebillID=$_POST["prebillID"];
	$changeadd=$_POST["changeadd"]; //สำหรับบอกว่าใช้ที่อยู่เดิมหรือไม่ ถ้า=1คือใช้ที่อยู่เดิม
	$prebillOld=$_POST["prebillOld"]; //prebillID เดิม ใช้ตรวจสอบว่าใบใดถูกลบไปบ้าง (array)
	$edittimeold=$_POST["edittime"]; //ครั้งที่แก้ไขของข้อมูลเก่า

	if($changeadd!="1"){
		$placeReceiveChq=$_POST["placeReceiveChq"];
	}
	?>
	<script language="JavaScript" type="text/javascript">
	function RefreshMe(){
		opener.location.reload(true);
		self.close();
	}
	</script> 
	<?php	
	//หาครั้งที่แก้ไขถัดไปของรหัสบิลนี้
	$qryedittime=pg_query("select max(\"edittime\")+1 from vthcap_fa_prebill_temp where \"prebillID\"='$prebillID' group by \"prebillID\"");
	list($edittime)=pg_fetch_array($qryedittime);
	
	//วนตามข้อมูลเก่า
	$qrycompareold=pg_query("SELECT \"prebillID\" FROM vthcap_fa_prebill_temp WHERE \"prebillIDMaster\"='$prebillID' AND \"edittime\"=(SELECT MAX(\"edittime\")  FROM thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillID' and \"statusApp\"='1') and \"stsprocess\"<>'D'");
	
	while($resold=pg_fetch_array($qrycompareold)){
		$prebillcompare=$resold["prebillID"];
		
		//ถ้าพบข้อมูลนี้ใน array แสดงว่ายังไม่ถูกลบ แต่เป็นการ update
		if(in_array($prebillcompare,$prebillOld)){
			//หาว่ามีข้อมูลเก่าใน array นี้หรือไม่
			for($j=0;$j<sizeof($dateAssign);$j++){
				if(sizeof($dateAssign)==1){ //กรณีมีแค่ 1 แสดงว่ารับเช็คแค่ครั้งเดียว ให้ใช้จำนวนเงินจาก totalTaxInvoice
					$totalTax=$totalTaxInvoice;	
				}else{ //กรณีมีมากกว่า 1 แสดงว่ามีการนัดรับเช็คหลายครั้ง
					$totalTax=$recmoney[$j];
				}
				
				//ถ้าเท่ากันแสดงว่ามีการ update ข้อมูลให้ insert ตามปกติ
				if($prebillcompare==$prebillOld[$j]){ 
					$instemp="INSERT INTO thcap_fa_prebill_temp(\"prebillID\",
					\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
					\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", 
					note, \"statusApp\", \"addUser\", \"addStamp\",\"taxInvoice\",\"prebillIDMaster\",edittime,\"stsprocess\")
					VALUES ('$prebillcompare','$userSalebill', '$userDebtor', '$dateInvoice', '$numberInvoice', 
					'$totalTaxInvoice', '$dateBill', '$dateAssign[$j]', '$placeReceiveChq', 
					'$note', '2', '$addUser', '$addStamp','$totalTax','$prebillID','$edittime','U') ";
					
					if($restemp=pg_query($instemp)){
					}else{
						$status++;
					}
					
					break; //หยุดการวน loop ข้อมูลใหม่
				}
			}
		}else{ //แสดงว่าข้อมูลนี้โดนลบ
			//insert ข้อมูลที่ถูกลบ
			$insdel="INSERT INTO thcap_fa_prebill_temp(\"prebillID\",
			\"statusApp\", \"addUser\", \"addStamp\",\"prebillIDMaster\",edittime,\"stsprocess\")
			VALUES ('$prebillcompare','2', '$addUser', '$addStamp','$prebillID','$edittime','D') ";

			if($resdel=pg_query($insdel)){
			}else{
				$status++;
			}				
		}
	}
	
	//insert ข้อมูลที่เพิ่มใหม่
	for($j=0;$j<sizeof($dateAssign);$j++){
		if($prebillOld[$j]==""){ //จะบันทึกเฉพาะรายการที่มี prebill เป็นค่าว่าง
			//รายการแรกจะไม่เท่ากับค่าว่างเสมอ เนื่องจากเป็น PK ดังนั้นจำนวนเงินจึงเท่ากับ $recmoney[$j] อย่างเดียว
			$totalTax=$recmoney[$j];
			
			$instemp="INSERT INTO thcap_fa_prebill_temp(\"prebillID\",
			\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
			\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", 
			note, \"statusApp\", \"addUser\", \"addStamp\",\"taxInvoice\",\"prebillIDMaster\",edittime,\"stsprocess\")
			VALUES (null,'$userSalebill', '$userDebtor', '$dateInvoice', '$numberInvoice', 
			'$totalTaxInvoice', '$dateBill', '$dateAssign[$j]', '$placeReceiveChq', 
			'$note', '2', '$addUser', '$addStamp','$totalTax','$prebillID','$edittime','I') ";
			
			if($restemp=pg_query($instemp)){
			}else{
				$status++;
			}
		}
		
	}	
	//หาว่าเลข auto_id ค่าแรก ในตาราง temp คือค่าอะไร
	$qryauto=pg_query("select \"auto_id\" from thcap_fa_prebill_temp where \"prebillID\"='$prebillID' and \"edittime\"='$edittime'");
	list($auto_id)=pg_fetch_array($qryauto);
	
	//บันทึกไฟล์เก่าลงในตาราง
	$fileold=$_POST["fileold"];
	for($p=0;$p<sizeof($fileold);$p++){
		$ins="INSERT INTO thcap_fa_prebill_file(\"prebillID\", file,edittime,\"auto_temp\") VALUES ('$prebillID', '$fileold[$p]','$edittime','$auto_id')";					
		
		if($resup=pg_query($ins)){
		}else{
			$status++;
		}
	}

	//บันทึกไฟล์ใหม่ที่เพิ่มเติม
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../upload/fa_prebill');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
	$files = array();
			
	foreach ($_FILES["my_field"] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
				$files[$i][$k] = $v;
			
		}
	}					
	foreach ($files as $file){
		$handle = new Upload($file);
							   
		if($handle->uploaded){
			// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
			$handle->Process($dir_dest);							 								
			if ($handle->processed) {
				$pathfile=$handle->file_dst_name;
				
				$Board_oldfile = $pathfile;			
				$Board_newfile = md5_file("../upload/fa_prebill/$pathfile", FALSE);		
											
				$Board_cuttext = split("\.",$pathfile);
				$Board_nubtext = count($Board_cuttext);
				$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
											
				$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
											
				$Boardfile = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
				
				$flgRename = rename("../upload/fa_prebill/$Board_oldfile", "../upload/fa_prebill/$Board_newfile");
				if($flgRename)
				{
					//echo "บันทึกสำเร็จ";
				}
				else
				{
					echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
					$status++;
				}
							
				$ins="INSERT INTO thcap_fa_prebill_file(\"prebillID\", file,edittime,\"auto_temp\") VALUES ('$prebillID', $Boardfile,'$edittime','$auto_id')";					
				if($resup=pg_query($ins)){
				}else{
					$status++;
				}	
			}else{
				echo '<fieldset>';
				echo '  <legend>file not uploaded to the wanted location</legend>';
				echo '  Error: ' . $handle->error . '';
				echo '</fieldset>';
				$status++;
			}
		}
		
		//ตรวจสอบว่ามีไฟล์จริงหรือไม่
		if(!file_exists("../upload/fa_prebill/$Board_newfile")){
			$status++;
		}
	}
	//ACTIONLOG
	$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$addUser', '(THCAP) แก้ไขรายละเอียดบิลขอสินเชื่อ', '$addStamp')";
	if($reslog=pg_query($sqlaction)){
	}else{
		$status++;
	}
	//ACTIONLOG---
}else if($method=="appedityes"){ //กรณีอนุมัติแก้ไข
	$prebillIDMaster=$_POST["prebillID"]; //รายการที่เลือกอนุมัติ
	$edittime=$_POST["edittime"]; //ครั้งที่แก้ไข

	//ดึงทุก record ที่แก้ไข
	$qrydata=pg_query("select \"auto_id\", \"stsprocess\", \"edittime\", \"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", \"totalTaxInvoice\", \"dateBill\",
						\"dateAssign\", \"placeReceiveChq\", \"note\", \"taxInvoice\", \"prebillIDMaster\"
					from thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillIDMaster' and \"edittime\"='$edittime' and \"statusApp\"='2' order by \"auto_id\"");
	
	$rowdata = pg_num_rows($qrydata); // จำนวนข้อมูล
	
	if($rowdata == 0)
	{
		$status++;
		echo "<br>ไม่สามารถทำรายการได้ อาจมีการทำรายการไปก่อนหน้านี้แล้ว<br>";
	}
	
	while($resdata=pg_fetch_array($qrydata)){
		$auto_id=$resdata["auto_id"];
		$stsprocess=$resdata["stsprocess"]; //สถานะการแก้ไข
		$edittime=$resdata["edittime"]; //ครั้งที่แก้ไข
		
		if($stsprocess=='I'){ //กรณีเป็นการเพิ่มข้อมูลให้ insert ข้อมูล
		
			//insert ในตารางหลักก่อนเพื่อให้ได้เลข prebillID ใหม่
			$insbill="insert into \"thcap_fa_prebill\"(\"userSalebill\", \"userDebtor\", \"dateInvoice\", \"numberInvoice\", 
				\"totalTaxInvoice\", \"dateBill\", \"dateAssign\", \"placeReceiveChq\", note,\"taxInvoice\",\"prebillIDMaster\") values
				('$resdata[userSalebill]','$resdata[userDebtor]','$resdata[dateInvoice]','$resdata[numberInvoice]','$resdata[totalTaxInvoice]',
				'$resdata[dateBill]','$resdata[dateAssign]','$resdata[placeReceiveChq]','$resdata[note]','$resdata[taxInvoice]','$resdata[prebillIDMaster]')
				returning \"prebillID\"";					
			if($resins=pg_query($insbill)){
				list($prebillID)=pg_fetch_array($resins); //เลข prebillID ที่ได้จากตารางหลัก
			}else{
				$status++;
			}
				
			//update ตาราง temp ให้มี prebillID ตามที่ได้
			$uptemp="update thcap_fa_prebill_temp set \"prebillID\"='$prebillID'
			where \"auto_id\"=$auto_id";
			if($restemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
		}else if($stsprocess=='U'){	 //กรณีแก้ไขข้อมูล  (หรือข้อมูลเก่าที่ไม่ได้เปลี่ยนแปลง)
			// update ข้อมูล			
			$ins="UPDATE
					thcap_fa_prebill
				SET
					\"userSalebill\" = (select \"userSalebill\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"userDebtor\" = (select \"userDebtor\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"dateInvoice\" = (select \"dateInvoice\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"numberInvoice\" = (select \"numberInvoice\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"totalTaxInvoice\" = (select \"totalTaxInvoice\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"dateBill\" = (select \"dateBill\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"dateAssign\" = (select \"dateAssign\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"placeReceiveChq\" = (select \"placeReceiveChq\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"note\" = (select \"note\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"taxInvoice\" = (select \"taxInvoice\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id'),
					\"prebillIDMaster\" = (select \"prebillIDMaster\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id')
				WHERE
					\"prebillID\" = (select \"prebillID\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id')
				returning \"prebillID\"";
				
			if($resins=pg_query($ins)){
				list($prebillIDUp)=pg_fetch_array($resins);
			}else{
				$status++;
			}
			
			//update ข้อมูลสัญญาในตาราง temp ที่เกี่ยวข้องทั้งหมด ให้เป็นข้อมูลใหม่ โดยค้นหาข้อมูลที่เกี่ยวข้อง
			$query = "SELECT varray.s as \"ar\",varray.a as \"id\"  FROM (SELECT generate_subscripts(\"arrayFaBill\",1) as \"s\",\"autoID\" as \"a\",\"arrayFaBill\" from \"thcap_contract_fa_bill_temp\") varray where \"arrayFaBill\"[varray.s][1] = '$prebillIDUp' ";
			
			$resold=pg_query($query);
			list($s,$a)=pg_fetch_array($resold);
			if(($a!="") and ($s!="")){
				$upcon1="UPDATE \"thcap_contract_fa_bill_temp\"  SET \"arrayFaBill\"['$s'][2] = '$resdata[taxInvoice]' 
				WHERE \"arrayFaBill\"['$s'][1] = '$prebillIDUp' and \"autoID\"='$a'";
				if($resup1=pg_query($upcon1)){}
				else{
					$status++;
				}	
				
				$query_con = "SELECT \"contractID\" from \"thcap_contract_fa_bill_temp\" where  \"autoID\"='$a'";			
				$res_con=pg_query($query_con);
				list($conid)=pg_fetch_array($res_con);
				
				
				$query_conid = pg_query("SELECT \"contractID\" from \"thcap_contract\" where \"contractID\" = '$conid' ");				
				$num_checkconid =pg_num_rows($query_conid);
				if($num_checkconid>0){				
					$query = "SELECT varray.s as \"ar\",varray.a as \"id\"  FROM (SELECT generate_subscripts(\"arrayFaBill\",1) as \"s\",\"contractID\" as \"a\",\"arrayFaBill\" from \"thcap_contract_fa_bill\") varray where \"a\"= '$conid' and \"arrayFaBill\"[varray.s][1] = '$prebillIDUp' ";
					$resold=pg_query($query);
					list($sbill,$abill)=pg_fetch_array($resold);		
				
				
					$upcon2="UPDATE \"thcap_contract_fa_bill\"  SET \"arrayFaBill\"['$sbill'][2] = '$resdata[taxInvoice]' 
					WHERE \"arrayFaBill\"['$sbill'][1] = '$prebillIDUp' and \"contractID\"='$abill'";				
					/*$upcon1="UPDATE \"thcap_contract_fa_bill\"  SET \"arrayFaBill\" = '$arrayfa' where 
					\"contractID\"='$conid'";*/
				
					if($resup1=pg_query($upcon2)){}
					else{	$status++; }	
				}
			}
			
		}else if($stsprocess=='D'){
		 //ถ้าเป็นการลบข้อมูลออกจากระบบ ให้ลบบิลที่ผูกกับสัญญาด้วย
			// delete ข้อมูลออกจากตารางหลัก
			$updel0="DELETE FROM thcap_fa_prebill WHERE \"prebillID\" = (select \"prebillID\" from thcap_fa_prebill_temp where \"auto_id\" = '$auto_id') ";
			if($resdel0=pg_query($updel0)){
			}else{
				$status++;
			}
			//ดึงข้อมูล
			$query = "SELECT varray.s as \"ar\",varray.a as \"id\"  FROM (SELECT generate_subscripts(\"arrayFaBill\",1) as \"s\",\"autoID\" as \"a\",\"arrayFaBill\" from \"thcap_contract_fa_bill_temp\" order by s DESC ) varray where \"arrayFaBill\"[varray.s][1] = '$resdata[prebillID]' ";
			$resold=pg_query($query);
			list($s,$a)=pg_fetch_array($resold);
			//กรณีที่ เป็นค่าว่าง			
			if(($s =='') or ($a=='')){}
		    else{
				$query_con = "SELECT varray.s as el_idx,\"arrayFaBill\"[varray.s][1] as ctr2,\"arrayFaBill\"[varray.s][2] as val  FROM (SELECT generate_subscripts(\"arrayFaBill\",1) as \"s\",\"autoID\" as \"a\",\"arrayFaBill\" from \"thcap_contract_fa_bill_temp\") varray where \"a\" = '$a' ";
				$res=pg_query($query_con);
				$str_temp='{';
				while($resdata=pg_fetch_array($res)){
					$idx=$resdata["el_idx"];
					$ctr2=$resdata["ctr2"];
					$val=$resdata["val"];					
					if($idx==$s){}//ถ้าเป็นที่ทำการลบข้อมูลนั้น ก็ไม่ต้องเอามา
					else{
						$str_temp.='{'.$ctr2.','.$val.'},';
					}
				}
				if($str_temp !='{'){$str_temp=substr($str_temp,0,-1);}				
				$str_temp.='}';
				// 
				$updel1="update thcap_contract_fa_bill_temp set \"arrayFaBill\"='$str_temp'	where \"autoID\"='$a'";
				if($resdel1=pg_query($updel1)){
				}else{
					$status++;
				}
				$query_con = "SELECT \"contractID\" from \"thcap_contract_fa_bill_temp\" where  \"autoID\"='$a'";			
				$res_con=pg_query($query_con);
				list($conid)=pg_fetch_array($res_con);	
				//
				$query_conid = pg_query("SELECT \"contractID\" from \"thcap_contract\" where \"contractID\" = '$conid' ");				
				$num_checkconid =pg_num_rows($query_conid);
				if($num_checkconid>0){	
				
				
					$query_con = "SELECT varray.s as el_idx,\"arrayFaBill\"[varray.s][1] as ctr2,\"arrayFaBill\"[varray.s][2] as val  FROM (SELECT generate_subscripts(\"arrayFaBill\",1) as \"s\",\"contractID\" as \"a\",\"arrayFaBill\" from \"thcap_contract_fa_bill\") varray
					where \"a\" = '$conid' ";
					$res=pg_query($query_con);
					$str='{';
					while($resdata=pg_fetch_array($res)){
						$idx=$resdata["el_idx"];
						$ctr2=$resdata["ctr2"];
						$val=$resdata["val"];					
						if($idx==$s){}
						else{
							$str.='{'.$ctr2.','.$val.'},';
						}
					}
					if($str !='{'){$str_temp=substr($str,0,-1);}					
					$str.='}';

					//update ข้อมูล
					$upcon1="UPDATE \"thcap_contract_fa_bill\"  SET \"arrayFaBill\" = '$str' where 
					\"contractID\"='$conid'";
					if($resup1=pg_query($upcon1)){
					}else{$status++;}	
				}					
			}
		}
	}
	//update ทุกรายการว่าอนุมัติแล้ว
	$upapp="UPDATE \"thcap_fa_prebill_temp\"
	SET \"statusApp\"='1', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
	WHERE \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\"='2'";	
	if($resapp=pg_query($upapp)){
	}else{
		$status++;
	}

}else if($method=="appeditno"){ //กรณีไม่อนุมัติแก้ไข
	$prebillID=$_POST["prebillID"]; //รายการที่ไม่อนุมัติ
	$edittime=$_POST["edittime"]; //ครั้งที่แก้ไข
	
	//update สถานะเป็นไม่อนุมัติ
	$uptemp="UPDATE \"thcap_fa_prebill_temp\"
	SET \"statusApp\"='0', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
	WHERE \"prebillIDMaster\"='$prebillID' and \"edittime\"='$edittime' and \"statusApp\"='2'";	
	if($resuptemp=pg_query($uptemp)){
	}else{
		$status++;
	}	
}else if($method=="cancel"){ //กรณียกเลิกหลังจากอนุมัติไปแล้ว
	$prebillIDMaster=$_POST["prebillIDMaster"];
	
	//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ยกเลิกไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
	$qry_check=pg_query("select \"prebillID\" from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\" ='1'");
	$num_check=pg_num_rows($qry_check); //ถ้าไม่พบ แสดงว่าได้ถูกยกเลิกไปแล้ว
	if($num_check == 0){
		$status=-1;
	}else{
		//update ข้อมูลให้เป็นสถานะยกเลิกแล้ว
		while($resbill2=pg_fetch_array($qry_check)){
			$prebillID2=$resbill2["prebillID"];
	
			$uptemp2="UPDATE \"thcap_fa_prebill_temp\"
			SET \"statusApp\"='0', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
			WHERE \"prebillID\"='$prebillID2' and \"statusApp\"='1'";	
			if($resuptemp2=pg_query($uptemp2)){
			}else{
				$status++;
			}	
		}
	}
}

if($status==-1){	
	pg_query("ROLLBACK");
	if($sendfrom=="showapprove"){
	$script= '<script language=javascript>';
	$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');
				opener.location.reload(true);
				self.close();";
	$script.= '</script>';
	echo $script;}
	else{
	echo "3";}
}else if($status == 0){
	pg_query("COMMIT");	
	if($method=="add"){
		echo "<div style=\"text-align:center;padding-top:50px\"><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";
	}else if($method=="edit"){
		echo "<div style=\"text-align:center;padding-top:50px\"><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_IndexEdit.php'>";	
	}else{
		if($sendfrom=="showapprove"){
			$script= '<script language=javascript>';
			$script.= " alert('บันทึกรายการเรียบร้อย');
			opener.location.reload(true);
			self.close();";
			$script.= '</script>';
			echo $script;	
			}
		else{
		echo "1";}
	}
}else{
	pg_query("ROLLBACK");
	if($method=="add"){
		echo "<div style=\"text-align:center;padding-top:50px\"><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b><input type=\"button\" value=\"กลับ\" onclick=\"location.href='frm_Index.php'\"></div>";
	}else if($method=="edit"){
		echo "<div style=\"text-align:center;padding-top:50px\"><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b><input type=\"button\" value=\"กลับ\" onclick=\"location.href='frm_IndexEdit.php'\"></div>";
	}else{
		if($sendfrom=="showapprove"){
			$script= '<script language=javascript>';
			$script.= " alert('รายการนี้หรือบางรายการได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!');
			opener.location.reload(true);
			self.close();";
			$script.= '</script>';
			echo $script;			
			}
		else{
		echo "2";
		}
	}
}
										
?>
