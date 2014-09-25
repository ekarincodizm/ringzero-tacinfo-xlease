<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
require_once ("../../core/core_functions.php");
?>

<?php
$cmd = $_REQUEST['cmd'];
$type = $_REQUEST['type'];
$currentdate=nowDate();
$currenttime=date('H:i:s');

$nowdate=nowDateTime();
$iduser = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");
$status = 0;
	
if($cmd == "save"){
	$ccontinue=0;
	$strerror="";
    $payment = json_decode(stripcslashes($_POST["payment"]));
    $ConID = $_POST["ConID"];

	$revChqDateOld=""; //วันที่รับเช็คก่อนหน้า
	$recnumold=""; //เลขที่ใบรับเช็คก่อนหน้า
	if(sort($payment)){
		foreach($payment as $key => $value){
			$a1 = $value->bankChqNo;
			$a2= $value->orderDate; //วันที่รับเช็ค
			$a3= $value->receiveDate;  //วันที่สั่งจ่าย
			$a4 = $value->bankOutID;
			$a5 = $value->bankOutBranch;
			$a6 = $value->bankOutRegion;
			$a7 = $value->bankChqAmt;
			$a8 = $value->postchq;
			$a9 = $value->inschq;

			if (empty($a1) and empty($a2) and empty($a3) and empty($a4) and empty($a5) and empty($a6) and empty($a7) and empty($a8) and empty($a9)){
					continue;
			}
			//ตรวจสอบว่าไม่ซ้ำกับเลขที่เช็ค และ รหัสธนาคาร
			$q = "select \"bankChqNo\" from \"finance\".\"thcap_receive_cheque\" 
			where \"bankChqNo\"='$a1' and \"bankOutID\"='$a4' and \"revChqStatus\" <> '4'";
			$qr = pg_query($q);
			$row = pg_num_rows($qr);
			if($row>0)
			{
				$bankname = pg_query("select \"bankName\" from \"BankProfile\" where \"bankID\"='$a4'");
				$resu_bankname=pg_fetch_array($bankname);
				$bankname=$resu_bankname['bankName'];
				echo " - เนื่องจากเลขที่เช็ค  :".$a1."  , และธนาคาร :".$bankname."  มีในระบบแล้ว จึงไม่สามารถ บันทึกรายการนี้ได้". "\n";
				$ccontinue++;
				continue;
			}
			
			$qry_auto=pg_query("select \"runningNum\" from \"thcap_running_number\" where \"compID\" = 'THCAP' AND \"fieldName\" = 'revChqID' ");
			$num_auto=pg_num_rows($qry_auto);
			if($num_auto==0){
				$revChqID=1;
			}else{
				if($res_date=pg_fetch_array($qry_auto)){
					$revChqID = $res_date["runningNum"] + 1;
				}
			}
			
			
			if($a8=="1"){
				$ispostchq="1";
			}else{
				$ispostchq="0";
			}
			

			if($a9=="1"){
				$isinschq="1";
			}else{
				$isinschq="0";
			}
			
			$revChqID2=core_generate_frontzero($revChqID,10,'RC');
			
			
			$qry_fullname=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$iduser'");
			$res_fullname=pg_fetch_array($qry_fullname);
			$fullname=$res_fullname["fullname"];
			
			//บันทึกใน detail ก่อนเนื่องจากต้องนำค่าในนี้ไปอัพเดทในตาราง \"finance\".\"thcap_receive_cheque\"
			$in_cheque_detials="insert into \"finance\".\"thcap_receive_cheque_detials\" (\"revChqID\",\"receiverFullName\",\"receiverID\",\"receiverStamp\") 
								values  ('$revChqID2','$fullname','$iduser','$nowdate')";
			if($resultac=pg_query($in_cheque_detials)){   
			}else{
				$result2=$resultac;
				$status+=1;
			}
			
			
			//หาเลขที่ใบรับเช็ค		
			if($revChqDateOld==$a2){
				$recnum=$recnumold;
			}else{
				if($type=='1'){
					$recnum="";
					$recnum=checknull($recnum);
				}
				else{
					$qryrecnum=pg_query("select \"thcap_gen_documentID\"('$ConID','$a2'::date,'8')");
					list($recnum)=pg_fetch_array($qryrecnum);
					$recnum=checknull($recnum);
				}
			}
			
			//ตรวจสอบว่าวันที่รับเช็คเป็นปัจจุบันหรือไม่
			if($currentdate==$a2){
				$time=$currenttime;
			}else{
				$time="23:59:59";
			}
			$revChqDate=$a2." $time";

			//ตรวจสอบว่าไม่ซ้ำกับเลขที่เช็ค และ รหัสธนาคาร อีกครั้ง
			$q = "select \"bankChqNo\" from \"finance\".\"thcap_receive_cheque\" 
			where \"bankChqNo\"='$a1' and \"bankOutID\"='$a4' and \"revChqStatus\" <> '4'";
			$qr = pg_query($q);
			$row = pg_num_rows($qr);
			if($row>0){
				$bankname = pg_query("select \"bankName\" from \"BankProfile\" where \"bankID\"='$a4'");
				$resu_bankname=pg_fetch_array($bankname);
				$bankname=$resu_bankname['bankName'];
				echo " - เนื่องจากเลขที่เช็ค  :".$a1."  , และธนาคาร :".$bankname."  มีในระบบแล้ว จึงไม่สามารถ บันทึกรายการนี้ได้". "\n";
				$ccontinue++;
			}
			else{
				$in_cheque="insert into \"finance\".\"thcap_receive_cheque\" (\"revChqID\",\"cnID\",\"revChqDate\",\"revChqToCCID\",
				\"bankChqNo\",\"bankChqDate\",\"bankChqToCompID\",\"bankOutID\",\"bankOutBranch\",\"bankChqAmt\",\"bankOutRegion\",\"isPostChq\",\"isInsurChq\",
				\"revChqNum\")
				values  ('$revChqID2','CHQ','$revChqDate','$ConID','$a1','$a3','THCAP','$a4','$a5','$a7','$a6','$ispostchq','$isinschq',
				$recnum)";
				if($result=pg_query($in_cheque)){
				}else{
					$result1=$result;
					$status+=1;
				}
			
				$upnum="update \"thcap_running_number\" set \"runningNum\"='$revChqID' where \"compID\" = 'THCAP' AND \"fieldName\" = 'revChqID'";
				if($resnum=pg_query($upnum)){
				}else{
					$result3=$resnum;
					$status++;
				}
				$revChqDateOld=$a2;
				$recnumold=$recnum;
			}
		}
	}
}else if($cmd=="clearapp"){ //ล้างรายการที่การเงินอนุมัติแล้ว
	$revTranID = pg_escape_string($_POST["revTranID"]);
	
	//ตรวจสอบรายการว่ามีการใช้รายการก่อนที่จะ clean หรือไม่ เพราะถ้ามีการใช้แล้วจะไม่สามารถล้างรายการได้
	$qrychk=pg_query("select * from finance.thcap_receive_transfer where \"revTranID\"='$revTranID' AND (\"revTranStatus\" ='3' 
	or (\"revTranStatus\"='6' and \"balanceAmt\"<>\"bankRevAmt\"))");
	$numchk=pg_num_rows($qrychk); //กรณีที่ revTranStatus เท่ากับ 3 หรือ 6 แสดงว่ามีการนำเงินไปใช้แล้ว
	if($numchk==0){ 
		//ตรวจสอบว่าเป็นรายการเช็คหรือไม่
		if($qrychq=pg_query("select \"contractID\",\"revChqID\" from finance.thcap_receive_transfer where \"revTranID\"='$revTranID'")); else $status++;
		$numchq=pg_num_rows($qrychq); //ถ้าพบค่าแสดงว่าเป็นรายการเช็ค
		list($conid,$revChqID)=pg_fetch_array($qrychq);
		
		if($revChqID!=""){ //แสดงว่าเป็นรายการที่เป็นเช็ค
			$txtlog="ล้างรายการที่เป็นเช็คเลขที่  $revChqID";
		}else{
			$txtlog="ล้างรายการผูกเงินโอนเลขที่สัญญา $conid";
		}
		
		//เก็บ log ก่อนลบข้อมูล
		if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
		   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\",remark) 
			SELECT '$txtlog',a.\"revTranID\",'$iduser', '$nowdate',\"BAccount\"||'-'||\"BName\",\"bankRevBranch\",\"bankRevAmt\",\"bankRevStamp\",\"appvYRemask\"
			FROM finance.thcap_receive_transfer a
			LEFT JOIN \"BankInt\" b on a.\"bankRevAccID\"=b.\"BID\"::text
			LEFT JOIN finance.\"thcap_receive_transfer_action\" c on a.\"revTranID\"=c.\"revTranID\"
			WHERE a.\"revTranID\"='$revTranID'")); else $status++;
		//END LOG---
				
		$up="UPDATE finance.thcap_receive_transfer
		SET \"revTranStatus\"=9,\"contractID\"=null, \"dateContact\"=null, \"revChqID\"=null, ststariff=null, \"balanceAmt\"=null, \"chqKeeperID\" = null
		WHERE \"revTranID\"='$revTranID' and \"bankRevAmt\" = \"balanceAmt\" AND (\"revTranStatus\" <> '3' 
		or (\"revTranStatus\"='6' and \"balanceAmt\" = \"bankRevAmt\")) returning \"revTranID\" ";
			
		if($resup=pg_query($up)){
			$numrows=pg_num_rows($resup);  //ถ้า update สำเร็จ ยังไงก็ต้อง 1 record อยู่แล้ว เนื่องจากเป็น PK
		}else{
			$status++;
		}
			
		if($numrows>0){ //กรณีมีการ update ตารางแรก ให้ update ตารางนี้ด้วย
			$up_action="UPDATE finance.thcap_receive_transfer_action
			SET \"appvYID\"=null, \"appvYStamp\"=null,\"appvYRemask\"=null, \"appvYStatus\"=null
			WHERE \"revTranID\"='$revTranID'";
				
			if($resup_action=pg_query($up_action)){
			}else{
				$status++;
			}
		}
			
		if($numrows>0 and $numchq>0){ //กรณีมีการ update ตารางแรกและเป็นรายการเช็ค ให้ update สถานะเช็คว่ารอตัดรายการ
			$upchq="UPDATE finance.thcap_receive_cheque
			SET \"revChqStatus\"=6 WHERE \"revChqID\"='$revChqID'";
			if($resup_chq=pg_query($upchq)){
			}else{
				$status++;
			}
		}
	}
}else if($cmd=="delapp"){
	$sendform="delresult";
	$revTranID=$_POST["revTranID"]; //รหัสเงินโอนที่ต้องการลบ
	$remark_cancel=$_POST["note"];
	//ตรวจสอบอีกครั้งว่ามีการทำรายการนี้หรือยัง โดยถ้ายังไม่ทำสถานะต้องเป็น 9
	$qry=pg_query("select cancel from finance.\"thcap_receive_transfer\" where \"revTranID\"='$revTranID' and \"revTranStatus\"='9'");
	$numrow=pg_num_rows($qry);
	
	if($numrow==0){
		$status=-2;
	}else{
		list($cancel)=pg_fetch_array($qry);
		if($cancel=='t'){ //กรณีมีการลบรายการก่อนหน้านี้
			$status=-1;
		}else{ //กรณียังไม่ถูกลบข้อมูล
			//เก็บ log ก่อนลบข้อมูล
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
			   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\",remark) 
				SELECT 'ยกเลิกรายการเงินโอน',\"revTranID\",'$iduser', '$nowdate',\"BAccount\"||'-'||\"BName\",\"bankRevBranch\",\"bankRevAmt\",\"bankRevStamp\",'$remark_cancel'
				FROM finance.thcap_receive_transfer a
				LEFT JOIN \"BankInt\" b on a.\"bankRevAccID\"=b.\"BID\"::text
				WHERE \"revTranID\"='$revTranID'")); else $status++;
			//END LOG---
			
			$qry_up = "UPDATE finance.thcap_receive_transfer SET  cancel='TRUE',\"remark_cancel\"='$remark_cancel', del_user='$iduser', del_stamp='$nowdate' WHERE \"revTranID\"='$revTranID'";
			if($resup=pg_query($qry_up)){
			}else{
				$status++;
			}
			
			
		}
	}
}else if($cmd=="anonymous"){ // ระบุว่าไม่ใช่เงินของลูกค้า

	$revTranID=$_POST["revTranID"]; //รหัสเงินโอนที่ต้องการ
	
	//ตรวจสอบอีกครั้งว่ามีการทำรายการนี้หรือยัง
	$qry=pg_query("select \"isAnonymous\" from finance.\"thcap_receive_transfer\" where \"revTranID\"='$revTranID' and \"isAnonymous\"='1'");
	$numrow=pg_num_rows($qry);
	
	if($numrow > 0){
		$status=-1;
	}else{
		//เก็บ log ก่อนลบข้อมูล
		if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
		   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
			SELECT 'ไม่ใช่เงินของลูกค้า', \"revTranID\", '$iduser', '$nowdate',\"BAccount\"||'-'||\"BName\",\"bankRevBranch\",\"bankRevAmt\",\"bankRevStamp\"
			FROM finance.thcap_receive_transfer a
			LEFT JOIN \"BankInt\" b on a.\"bankRevAccID\"=b.\"BID\"::text
			WHERE \"revTranID\"='$revTranID'")); else $status++;
		//END LOG---
		
		$qry_up = "UPDATE finance.thcap_receive_transfer SET  \"isAnonymous\" = '1' WHERE \"revTranID\"='$revTranID' AND \"isAnonymous\" = '0' RETURNING \"isAnonymous\"";
		if($resup=pg_query($qry_up)){
			if(pg_num_rows($resup) == 0){
				$status++;
			}
		}else{
			$status++;
		}
		
		// หาข้อมูล bankRevStamp
		$qry_bankRevStamp_Anonymous = pg_query("select \"bankRevStamp\"::date from finance.thcap_receive_transfer where \"revTranID\"='$revTranID' ");
		$bankRevStamp_Anonymous = pg_result($qry_bankRevStamp_Anonymous,0);
		
		if($bankRevStamp_Anonymous >= "2013-01-01")
		{
			$qry_anonymous = "select \"thcap_process_voucherCreate_revtran_anonymous\"('$revTranID','$iduser');";
			if($res_anonymous=pg_query($qry_anonymous)){
				list($res_anonymous)=pg_fetch_array($res_anonymous);
				$qry_anonymous_auto_approve_voucher = "
					-- ทำการอนุมัติ voucher โดยอัตโนมัติ
					SELECT \"thcap_process_voucherApprove\"(
						$res_anonymous,
						'$iduser',
						1, -- 1-รายการauto
						NULL
					)
				";
				if($res_anonymous_auto_approve_voucher=pg_query($qry_anonymous_auto_approve_voucher)){
					list($res_anonymous_auto_approve_voucher)=pg_fetch_array($res_anonymous_auto_approve_voucher);
					$qry_anonymous_update_voucher_ref = "
						-- ทำการอัพเดทข้อมูล voucher ที่อนุมัติได้ในการทำรายการ anonymous กลับไปที่ข้อมูลการโอนเงิน
						UPDATE finance.thcap_receive_transfer
						SET 
							\"isAnonymousVoucherID\" = '$res_anonymous_auto_approve_voucher'
						WHERE
							\"revTranID\" = '$revTranID'
					";
					if($res_anonymous_update_voucher_ref=pg_query($qry_anonymous_update_voucher_ref)){
					}else{
						$status++;
					}
				}else{
					$status++;
				}
			}else{
				$status++;
			}
		}
	}
}else if($cmd=="checkcontract"){
	$contractID=$_POST["contractID"];
	if((strlen($contractID)==15)or(strlen($contractID)==20)){
		$chk="true";
		$ncount=0; //จำนวนนับ ว่า ประเภทสินเชื่อ ในระบบหรือไม่

		if ((substr($contractID,2,1)=="-") and ((substr($contractID,7,1)=="-"))){
			}
		else{
				$chk="false";
			}
		if(strlen($contractID)==20){       //format=XX-XXXX-XXXXXXX ,format=XX-XXXX-XXXXXXX/XXXX
			if(substr($contractID,15,1)=="/"){
			}
			else{
				$chk="false";
			}
		}
		if($chk=="true"){	
			$typecon=substr($contractID,0,2); // returns ประเภทสินเชื่อ 
			//เช็ค ว่าเป็น ประเภทสินเชื่อ ในระบบหรือไม่
			$sql_chk=pg_query("select * from thcap_contract_type");
			while($res_chk=pg_fetch_array($sql_chk)){
				$conType = $res_chk["conType"];
				if($typecon==$conType){
					$ncount++;
				}
			}
			if($ncount==1){ 
				if($qrycheck=pg_query("select * from thcap_contract where \"contractID\"='$contractID'")); else $status++;
				$numcheck=pg_num_rows($qrycheck);
				if($numcheck>0){
					$status=0;
				}else{
					$status++;
				}
			}
			else{
				$chk="falsechktype";
			}
		}
	}
	else{		
		$status++;
	}
	
}else if($cmd=="addreturnchq"){
	$revchq = json_decode(stripcslashes($_POST["revchq"]));
	
	$chk=0; //สำหรับเช็คข้อมูลซ้ำ
	foreach($revchq as $key => $value){
        $revchqid = $value->revchqid;
		
		if (empty($revchqid)){
                continue;
        }
		//ตรวจสอบว่าถูก update ไปก่อนหน้านี้หรือยัง
		$qrychk=pg_query("select * from finance.thcap_receive_cheque 
		where \"revChqStatus\" in('2','8') and \"revChqID\"='$revchqid'");
		$numrows=pg_num_rows($qrychk);
		
		//ถ้าพบว่ายังไม่ update ให้ทำตรวจสอบว่ามีรายการนี้รออนุมัติอยู่หรือไม่
		if($numrows>0){
			$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revchqid'");
			$numchkapp=pg_num_rows($qrychkapp);
			
			if($numchkapp==0){
				$ins="insert into finance.thcap_receive_cheque_return (\"revChqID\",\"add_user\",\"add_stamp\",\"statusChq\") 
				values ('$revchqid','$iduser','$nowdate','2')";
				if($resins=pg_query($ins)){
				}else{
					$status++;
				}
			}else{
				$chk++;
				break;
			}
			
		}else{
			$status=2; //แสดงว่ารายการนี้ได้ถูกทำด้วยเมนูอื่นๆก่อนหน้านี้แล้วจึงไม่สามารถอนุมัติได้
			break;
		}
	}
	if($chk>0){
		$status=-1;
	}
}else if($cmd=="approvereturn"){ //อนุมัติคืนเช็ค

	//ดูว่ากด อนุมัติ/ไม่อนุมัติ
	if(isset($_POST["btnsub"])){
		$statusapp='1';//อนุมัติ
	}else{
		$statusapp='0';//ไม่อนุมัติ
	}
	//$revchq = json_decode(stripcslashes($_POST["revchq"]));
	//$statusapp=$_POST["statusapp"];
	
	if($statusapp=="0"){ //กรณีไม่อนุมัติ
		$chk=0;
		for($ii=0;$ii< count($_POST["revchqid"]);$ii++){
		//foreach($revchq as $key => $value){
			//$revchqid = $value->revchqid;
			$revchqid= $_POST["revchqid"][$ii];
			if (empty($revchqid)){
					continue;
			}	
			
			//ตรวจสอบว่าถูก update ไปก่อนหน้านี้หรือยัง  revChqStatus=3 คืออนุมัติคืนเช็คไปแล้ว
			$qrychk=pg_query("select * from finance.thcap_receive_cheque 
			where \"revChqStatus\" in ('2','3','8') and \"revChqID\"='$revchqid'");
			$numrows=pg_num_rows($qrychk);
			
			//ถ้าพบว่ายังไม่ update ให้ทำตรวจสอบว่ามีรายการนี้อนุมัติแล้วหรือไม่
			if($numrows>0){
				$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revchqid'");
				$numchkapp=pg_num_rows($qrychkapp);
				
				if($numchkapp>0){ //แสดงว่ายังรออนุมัติอยู่ให้ update ว่าไม่อนุมัติ
					$upd="update finance.thcap_receive_cheque_return set \"statusChq\"='$statusapp' ,\"app_user\"='$iduser',\"app_stamp\"='$nowdate' 
					where \"revChqID\"='$revchqid'";
					if($resup=pg_query($upd)){
					}else{
						$status++;
					}
				}else{
					$chk++;
					break;
				}
				
			}else{ //พบว่าถูกทำด้วยเมนูอื่นๆแล้ว ให้ update เป็นไม่อนุมัติไปโดยปริยาย
				$upd="update finance.thcap_receive_cheque_return set \"statusChq\"='$statusapp' ,\"app_user\"='$iduser',\"app_stamp\"='$nowdate' 
					where \"revChqID\"='$revchqid'";
				if($resup=pg_query($upd)){
				}else{
					$status++;
				}
			}
			
		}
		if($chk>0){
			$status=-1;
		}
	}else if($statusapp=="1"){ //กรณีอนุมัติ
		$chk=0;
		//foreach($revchq as $key => $value){
			//$revchqid = $value->revchqid;
		for($ii=0;$ii< count($_POST["revchqid"]);$ii++){
			$revchqid= $_POST["revchqid"][$ii];	
			if (empty($revchqid)){
					continue;
			}
			//ตรวจสอบว่าถูก update ไปก่อนหน้านี้หรือยัง
			$qrychk=pg_query("select * from finance.thcap_receive_cheque 
			where \"revChqStatus\" in ('2','8') and \"revChqID\"='$revchqid'");
			$numrows=pg_num_rows($qrychk);
			
			//ถ้าพบว่ายังไม่ update ให้ทำตรวจสอบว่ามีรายการนี้อนุมัติแล้วหรือไม่
			if($numrows>0){
				$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revchqid'");
				$numchkapp=pg_num_rows($qrychkapp);
				
				if($numchkapp>0){ //แสดงว่ายังรออนุมัติอยู่ให้ update ว่าอนุมัติ
					$upd="update finance.thcap_receive_cheque_return set \"statusChq\"='$statusapp' ,\"app_user\"='$iduser',\"app_stamp\"='$nowdate' 
					where \"revChqID\"='$revchqid'";
					if($resup=pg_query($upd)){
					}else{
						$status++;
					}
					
					//update ในตารางเช็คเป็น "คืนเช็คให้ลูกค้า"
					$updmain="update finance.thcap_receive_cheque set \"revChqStatus\"='3' where \"revChqID\"='$revchqid'";
					if($resup=pg_query($updmain)){
					}else{
						$status++;
					}
				}else{
					$chk++;
					break;
				}
				
			}else{ //แสดงว่ารายการนี้ได้ถูกทำด้วยเมนูอื่นๆก่อนหน้านี้แล้วจึงไม่สามารถอนุมัติได้
				$status=2;
				break;
			}
			
		}
		if($chk>0){
			$status=-1;
		}
	}
	
}else if($cmd=="returnchq"){ //นำเช็คกลับไป "ยืนยันนำเช็คเข้าธนาคาร"
	$revChqID=$_POST["revChqID"];
	
	//update status ให้กลับมายืนยันนำเช็คเข้าเหมือนเดิม
	$upmain="update finance.\"thcap_receive_cheque\" set \"revChqStatus\"='7' where \"revChqID\"='$revChqID'";
	if($res=pg_query($upmain)){
	}else{
		$status++;
	}
	
	//update ข้อมูลกลับไปตอนที่ยังไม่ยืนยัน
	$updatekeep="update finance.thcap_receive_cheque_keeper set \"replyByTakerID\"=null,
		\"replyByTakerStamp\"=null,
		\"bankRevDate\"=null,
		\"bankRevResult\"=null
		where \"revChqID\"='$revChqID'";
	if($resupkeep=pg_query($updatekeep)){
	}else{
		$status++;
	}

}else if($cmd=="returnchq_bounced"){ //นำเช็คกลับไป "ยืนยันนำเช็คเข้าธนาคาร"  เด้ง
	$revChqID = pg_escape_string($_POST["revChqID"]);
	
	// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
	$qry_chk = pg_query("select \"revChqStatus\" from finance.\"thcap_receive_cheque\" where \"revChqID\"='$revChqID' ");
	$chk_revChqStatus = pg_fetch_result($qry_chk,0);
	if($chk_revChqStatus != "2")
	{
		// มีการทำรายการไปก่อนหน้านี้แล้ว
		$status++;
		$error_returnchq_bounced = "มีการทำรายการไปก่อนหน้านี้แล้ว";
	}
	else
	{
		//update status ให้กลับมายืนยันนำเช็คเข้าเหมือนเดิม
		$upmain="update finance.\"thcap_receive_cheque\" set \"revChqStatus\"='7' where \"revChqID\"='$revChqID'";
		if($res=pg_query($upmain)){
		}else{
			$status++;
		}
		
		// หา KeeperID ล่าสุด
		$qry_chqKeeperID = pg_query("SELECT
										max(\"chqKeeperID\") as \"maxid_chqKeeperID\"
									FROM
										finance.\"thcap_receive_cheque_keeper\"
									WHERE
										\"revChqID\" = '$revChqID' AND
										\"keepFrom\" = '2' AND
										\"giveTakerID\" is null ");
		$maxid_chqKeeperID = pg_fetch_result($qry_chqKeeperID,0);
		
		//update ข้อมูลกลับไปตอนที่ยังไม่ยืนยัน (เฉพาะรายการก่อนหน้ารายการล่าสุด 1 รายการเป็นต้นไปเท่านั้น คือ เฉพาะรายการล่าสุดและรายการก่อนล่าสุด)
		$updatekeep="update finance.thcap_receive_cheque_keeper set \"replyByTakerID\"=null,
			\"replyByTakerStamp\"=null,
			\"bankRevDate\"=null,
			\"bankRevResult\"=null
			where \"revChqID\"='$revChqID'
			and \"chqKeeperID\" >= (select max(\"chqKeeperID\") from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID' and \"chqKeeperID\" < '$maxid_chqKeeperID') ";
		if($resupkeep=pg_query($updatekeep)){
		}else{
			$status++;
		}
		
		/*ลบ ข้อมูล ใน  finance.thcap_receive_cheque_keeper โดยจะหา เก็บ log ของ finance.thcap_receive_cheque_keeper 
		แล้วจึงลบข้อมูลที่  finance.thcap_receive_cheque_keeper
		*/
		$inskeep="INSERT INTO finance.thcap_receive_cheque_keeper_log(
				\"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
				\"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
				\"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
				\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
				\"BID\",\"result\")
				SELECT \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
				\"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
				\"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
				\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
				\"BID\",\"result\" FROM finance.thcap_receive_cheque_keeper WHERE \"chqKeeperID\"='$maxid_chqKeeperID'";
		if(pg_query($inskeep)){
		}else{
				$status++;
		}
		
		$del_data=" DELETE FROM finance.\"thcap_receive_cheque_keeper\"	WHERE \"chqKeeperID\" ='$maxid_chqKeeperID'";
		if(pg_query($del_data)){
		}else{
			$status++;
		}
	}
	
}else if($cmd=='calleasefine'){ //คำนวณเบี้ยปรับตามวันที่ต้องการ
	$caldate=$_POST['caldate'];
	$contractID=$_POST['contractID'];
	
	//เบี้ยปรับปัจจุบัน
	$qr_get_lease_fine=pg_query("select \"thcap_get_lease_fine\"('$contractID','$caldate')");
	if($rs_get_lease_fine = pg_fetch_array($qr_get_lease_fine)){
		list($lease_fine) = $rs_get_lease_fine;
	}else{
		$status++;
	}
}

if($ccontinue>0){
	pg_query("ROLLBACK");
	//echo 3;

}
else if ($cmd=="checkcontract")
{	
	if($chk=="true"){
		if($status == 0){
			pg_query("COMMIT");
			echo 1;
		}else{
			pg_query("ROLLBACK");
			echo 2;
		}
	}else if($chk=="falsechktype"){
		pg_query("ROLLBACK");
		echo 3;
	}else if($chk=="false"){
		pg_query("ROLLBACK");
		echo 4;
	}
}
else{
if($status==-1){ //กรณีรายการคืนเช็คขออนุมัติซ้ำ หรืออนุมัติคืนเช็คซ้ำกัน
	pg_query("ROLLBACK");
	if($sendform=="delresult"){
		echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
		$script= '<script language=javascript>';
		$script.= " alert('รายการนี้ได้ลบไปก่อนหน้านี้แล้วกรุณาตรวจสอบ!');
					location.href='frm_Index_finance.php';
					window.opener.document.form[0].btn_save.attr('disabled', false);";
		$script.= '</script>';
		echo $script;
	}
	elseif($cmd=="anonymous"){
		echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
		$script= '<script language=javascript>';
		$script.= " alert('มีการทำรายการไปก่อนหน้านี้แล้วกรุณาตรวจสอบ!');
					location.href='frm_Index_finance.php';";
		$script.= '</script>';
		echo $script;
	}
	else{
	echo "3";
	}
}else if($status==-2){
	pg_query("ROLLBACK");
	if($sendform=="delresult"){
		echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
		$script= '<script language=javascript>';
		$script.= " alert('ไม่พบข้อมูลที่จะลบ อาจตรวจสอบรายการหรือถูกลบจากฐานข้อมูลแล้ว กรุณาตรวจสอบ!');
					location.href='frm_Index_finance.php';
					window.opener.document.form[0].btn_save.attr('disabled', false);";
		$script.= '</script>';
		echo $script;
	}	
	else{
	echo "4";
	}
}else if($status == 0){
	pg_query("COMMIT");
	if($cmd=="clearapp"){
		if($numchk==0){ //กรณียังไม่มีการทำรายการก่อนทำการล้างรายการ
			if($numrows>0){
				echo "1";
			}else{
				if($cmd=='approvereturn'){
					echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
					$script= '<script language=javascript>';
					$script.= " alert('มีบางรายการอนุมัติก่อนหน้านี้แล้ว กรุณาตรวจสอบอีกครั้ง!');
					location.href='frm_returnChqApprove.php';";
					$script.= '</script>';
					echo $script;
				}
				else{
				echo "3";	
				}
			}	
		}else{
			echo "4";
		}		
	}else if($cmd=='calleasefine'){
		echo number_format($lease_fine,2);
	}else{
		if($sendform=="delresult"){
			echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
			$script= '<script language=javascript>';
			$script.= " alert('บันทึกรายการเรียบร้อย');
					location.href='frm_Index_finance.php';
					window.opener.document.form[0].btn_save.attr('disabled', false);";
			$script.= '</script>';
			echo $script;
		}
		else if($cmd=='approvereturn'){
			echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
			$script= '<script language=javascript>';
			$script.= " alert('บันทึกข้อมูลเรียบร้อยแล้ว');
					location.href='frm_returnChqApprove.php';";
			$script.= '</script>';
			echo $script;
		}
		else if($cmd=='anonymous'){
			echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
			$script= '<script language=javascript>';
			$script.= " alert('บันทึกข้อมูลเรียบร้อยแล้ว');
					location.href='frm_Index_finance.php';";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo "1";
		}
	}
}else{
	pg_query("ROLLBACK");
	if($cmd=='calleasefine'){
		echo 'ERROR';
	}else{
		if($sendform=="delresult"){
		echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
		$script= '<script language=javascript>';
		$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');
					location.href='frm_Index_finance.php';
					window.opener.document.form[0].btn_save.attr('disabled', false);";
		$script.= '</script>';
		echo $script;
		}
		else if($cmd=='approvereturn'){
			echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
			$script= '<script language=javascript>';
			$script.= " alert('รายการนี้ถูกเรียกใช้ด้วยเมนูอื่น กรุณาตรวจสอบ หรือไม่อนุมัติเพื่อยกเลิก!');
					  location.href='frm_returnChqApprove.php';";
			$script.= '</script>';
			echo $script;
		}
		else{
			if($cmd == "returnchq_bounced" && $error_returnchq_bounced != "")
			{
				echo "$error_returnchq_bounced";
			}
			else
			{
				echo "2";
			}
		}
	}
}
}
?>