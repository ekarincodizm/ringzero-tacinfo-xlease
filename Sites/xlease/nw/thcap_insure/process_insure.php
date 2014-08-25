<?php
session_start();
include("../../config/config.php");

$cmd = $_REQUEST['cmd'];
$addUser=$_SESSION["av_iduser"];
$addStamp=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

if($cmd == "addspec"){  //เพิ่มรายการภัยเพิ่มพิเศษ
	$insureSpec = $_POST["insureSpec"];   	
	//บันทึกข้อมูล
	$ins="INSERT INTO thcap_insure_insurespecial(\"specialName\",\"addUser\",\"addStamp\") VALUES ('$insureSpec','$addUser','$addStamp')";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}

	if($status == 0){
		pg_query("COMMIT");
		echo "1";
	}else{
		pg_query("ROLLBACK");
		echo "2";
	}	
}else if($cmd=="showspec"){ //แสดงรายการภัยเพิ่มพิเศษ
	echo "<table width=\"100%\" border=\"0\"  align=\"center\">";
	$qryspecial2=pg_query("SELECT auto_id, \"specialName\" FROM thcap_insure_insurespecial");
	$numspec=pg_num_rows($qryspecial2);
	while($resspec2=pg_fetch_array($qryspecial2)){
	list($specid1,$specname1)=$resspec2;
		
	echo "<tr>
		<td width=\"50\"></td>
		<td colspan=\"2\">
		<input type=\"checkbox\" name=\"spec[]\" id=\"spec$specid1\" value=\"$specid1\"> $specname1
		</td>
		</tr>";
							
	}
	echo "</table>";
}else if($cmd=="addfirst"){ //เพิ่มข้อมูลเบี้ยรวมที่ได้จากการสอบถามจากบริษัทประกันภัย
	$typesearch = $_POST["typesearch"];
	if($typesearch=="0"){
		$consecur = $_POST["securID"];
	}else{
		$consecur = $_POST["val"]; //เลขที่สัญญา
	}
	$costBuilding = $_POST["txt1"]; if($costBuilding==""){$costBuilding="null";}else{ $costBuilding="'".$costBuilding."'";}
	$costFurniture = $_POST["txt2"]; if($costFurniture==""){$costFurniture="null";}else{ $costFurniture="'".$costFurniture."'";}
	$costEngine = $_POST["txt3"]; if($costEngine==""){$costEngine="null";}else{ $costEngine="'".$costEngine."'";}
	$costStock = $_POST["txt4"]; if($costStock==""){$costStock="null";}else{ $costStock="'".$costStock."'";}
	$costOther = $_POST["txt5"]; if($costOther==""){$costOther="null";}else{ $costOther="'".$costOther."'";}
	$textOther = $_POST["textOther"]; if($textOther==""){$textOther="null";}else{ $textOther="'".$textOther."'";}
	$totalChip = $_POST["totalchip"];
	$numberQ = $_POST["numQ"]; if($numberQ==""){$numberQ="null";}else{ $numberQ="'".$numberQ."'";}
	$spec=$_POST["spec"];
	
	for($i=0;$i<sizeof($spec);$i++){
		//ค้นหาข้อความภัยพิเศษในฐานข้อมูล
		$qryspec=pg_query("SELECT \"specialName\" FROM thcap_insure_insurespecial where auto_id='$spec[$i]'");
		list($specialName)=pg_fetch_array($qryspec);
		$txtSpec.="- ".$specialName."\n";
	}
	
	//add ข้อมูลในตาราง
	$ins="INSERT INTO thcap_insure_checkchip(
            \"refDeedContract\", \"costBuilding\", \"costFurniture\", 
            \"costEngine\", \"costStock\", \"textOther\", \"costOther\", \"insureSpecial\", 
            \"totalChip\",\"numberQ\", \"addUser\", \"addStamp\", \"statusApp\",\"statusInsure\")
		VALUES ('$consecur', $costBuilding, $costFurniture, 
				$costEngine, $costStock, $textOther, $costOther, '$txtSpec', 
				'$totalChip',$numberQ,'$addUser', '$addStamp', '2', '$typesearch')";
	if($resins=pg_query($ins)){
	}else{
		$status++;
	}
	
	if($status == 0){
		pg_query("COMMIT");
		echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_IndexChip.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexChip.php'>";
	}	
}else if($cmd=="addRequest"){ //ส่งใบคำขอไปอนุมัติ	
	$ContractID=$_POST["ContractID"];
	$CusID1=$_POST["CusID1"];
	list($CusID1,$name1)=explode("#",$CusID1);
	$CusID2=$_POST["CusID2"];
	list($CusID2,$name2)=explode("#",$CusID2);
	$CusID3=$_POST["CusID3"];
	list($CusID3,$name3)=explode("#",$CusID3);
	$CusID4=$_POST["CusID4"];
	list($CusID4,$name4)=explode("#",$CusID4);
	
	$Cus=$_POST["Cus"];
	$addrCus=$_POST["addrCus"];
	$startDate=$_POST["startDate"];
	$endDate=$_POST["endDate"];
	$userBenefit=$_POST["userBenefit"];
	$userNotify=$_POST["userNotify"];
	$dateNotify=$_POST["dateNotify"];
	$securdeID=$_POST["securdeID"];
	$checkchipID=$_POST["checkchipID"];
	$statusInsure=$_POST["statusInsure"];
	$addrDeed=$_POST["addrDeed"];
	
	//หา editime
	$qry=pg_query("select max(edittime) as edittime from thcap_insure_temp where \"ContractID\"='$ContractID'");
	list($edittime)=pg_fetch_array($qry);
	
	if($edittime==""){
		$edittime=0;
	}else{
		$edittime+=1;
	}
	
	$ins="INSERT INTO thcap_insure_temp(
            \"ContractID\", \"CusID1\", \"CusID2\", \"addrCus\", \"startDate\", 
            \"endDate\", \"userBenefit\", \"userNotify\", \"dateNotify\", 
            \"securdeID\", \"checkchipID\", \"statusApprove\", edittime, 
            \"addUser\", \"addStamp\",\"statusInsure\",\"addrDeed\", \"CusID3\", \"CusID4\")
    VALUES ('$ContractID', '$CusID1', '$CusID2', '$addrCus', '$startDate', 
            '$endDate', '$userBenefit', '$userNotify', '$dateNotify',
            '$securdeID', '$checkchipID', '2', '$edittime', 
            '$addUser', '$addStamp', '$statusInsure','$addrDeed', '$CusID3', '$CusID4');";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}
	
	//ให้ insert ในตารางเก็บลูกค้าด้วยว่าเลขที่คำขอนี้มีใครเป็นลูกค้าบ้าง
	if(sizeof($Cus)>0){
		$qrytemp=pg_query("select \"auto_id\" from thcap_insure_temp where \"ContractID\"='$ContractID' and \"edittime\"='$edittime'");
		list($tempID)=pg_fetch_array($qrytemp);
		for($i=0;$i<sizeof($Cus);$i++){
			list($CusID,$CusName)=explode("#",$Cus[$i]);
			$inscus="INSERT INTO thcap_insure_cus(\"tempID\", \"CusID\") VALUES ('$tempID','$CusID')";
			
			if($resincus=pg_query($inscus)){
			}else{
				$status++;
			}
		}
	}
	
	//update  thcap_insure_checkchip ให้บอกว่าส่งคำขอแล้ว
	$upd="UPDATE thcap_insure_checkchip SET \"statusApp\"='3' WHERE auto_id='$checkchipID'";
	if($resup=pg_query($upd)){
	}else{
		$status++;
	}
	
	if($status == 0){
		pg_query("COMMIT");
		echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_IndexChip.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>ข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexChip.php'>";
	}
		
}else if($cmd=="addLink"){
	$auto_id=$_POST["auto_id"];
	$insurenum=$_POST["insurenum"];
	$oldinsure=$_POST["oldinsure"];
	
	$qryrequest=pg_query("SELECT \"statusInsure\",\"insureNum\",\"ContractID\" FROM thcap_insure_temp 
	where \"auto_id\" = '$auto_id'");
				
	list($statusInsure,$inNum,$contractID)=pg_fetch_array($qryrequest);

	//กรณีเพิ่มประกันภัย
	if($statusInsure=="0"){
		//ตรวจสอบก่อนว่ามีข้อมูลนี้หรือยังถ้ามีแล้วให้ update ข้อมูล
		$qrychk=pg_query("select * from thcap_insure_main where \"auto_tempID\"='$auto_id' and \"statusInsure\"='0'");
		$numchk=pg_num_rows($qrychk);
		
		if($numchk>0){ //กรณีมีข้อมูลนี้อยู่แล้ว ให้ update ข้อมูล
			$upmain="update thcap_insure_main set \"insureNum\"='$insurenum' where \"auto_tempID\"='$auto_id'";
			if($resup=pg_query($upmain)){
			}else{
				$status++;
			}
			
			$uptemp="update thcap_insure_temp set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			$upnew="update thcap_insure_new set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resupnew=pg_query($upnew)){
			}else{
				$status++;
			}
		}else{ //
			$insmain="INSERT INTO thcap_insure_main(
				\"insureNum\",\"statusInsure\" ,\"auto_tempID\")
			VALUES ('$insurenum', 0, $auto_id)";
			
			if($resins=pg_query($insmain)){
			}else{
				$status++;
			}
			
			$uptemp="update thcap_insure_temp set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			$upnew="update thcap_insure_new set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resupnew=pg_query($upnew)){
			}else{
				$status++;
			}
		}
	}else{ //กรณีต่ออายุ
		//ตรวจสอบก่อนว่าข้อมูลก่อนหน้านี้หมดอายุหรือยัง ถ้าหมดแล้วให้ update สถานะเป็น false
		$qrycheckdate=pg_query("select \"endDate\",\"insureNum\" from thcap_insure_temp where \"ContractID\"='$contractID' and \"statusApprove\"='1' and \"insureNum\" is not null order by edittime DESC limit 1");
		list($endDate,$insnum)=pg_fetch_array($qrycheckdate);
			$nowdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
			$endDate=$endDate." 16:00:00";
					
		if($nowdate>$endDate){ //ถ้ามากกว่าแสดงว่าหมดอายุแล้ว
			//ให้ไป update เลขที่กรมธรรม์นี้ด้วยว่าหมดอายุแล้ว
			$upcheckdate="UPDATE thcap_insure_main SET \"Active\"='FALSE' WHERE \"insureNum\"='$insnum'";
			if($rescheckdate=pg_query($upcheckdate)){
			}else{
				$status++;
			}
		}
		
		//ตรวจสอบก่อนว่ามีข้อมูลนี้หรือยังถ้ามีแล้วให้ update ข้อมูล
		$qrychk=pg_query("select * from thcap_insure_main where \"auto_tempID\"='$auto_id' and \"statusInsure\"='1'");
		$numchk=pg_num_rows($qrychk);
		
		if($numchk>0){ //กรณีมีข้อมูลนี้อยู่แล้ว ให้ update ข้อมูล
			$upmain="update thcap_insure_main set \"insureNum\"='$insurenum',\"refInsureNum\"='$oldinsure' where \"auto_tempID\"='$auto_id'";
			if($resup=pg_query($upmain)){
			}else{
				$status++;
			}
			
			$uptemp="update thcap_insure_temp set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			$upnew="update thcap_insure_old set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resupnew=pg_query($upnew)){
			}else{
				$status++;
			}
		}else{ //
			$insmain="INSERT INTO thcap_insure_main(
				\"insureNum\",\"statusInsure\",\"refInsureNum\",\"auto_tempID\")
			VALUES ('$insurenum', 1,'$oldinsure',$auto_id)";
			
			if($resins=pg_query($insmain)){
			}else{
				$status++;
			}
			
			$uptemp="update thcap_insure_temp set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			$upnew="update thcap_insure_old set \"insureNum\"='$insurenum' where \"auto_id\"='$auto_id'";
			if($resupnew=pg_query($upnew)){
			}else{
				$status++;
			}
		}
	}

	
	if($status == 0){
		pg_query("COMMIT");
		echo "1";
	}else{
		pg_query("ROLLBACK");
		echo "2";
	}	

}
	 


?>