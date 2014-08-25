<?php
session_start();
include("../../config/config.php");

$revChqID = pg_escape_string($_REQUEST['revChqID']); //เช็คที่ map
$receiptID = pg_escape_string($_REQUEST['receiptID']);//ใบเสร็จที่ map กับเช็ค

$nowdate=nowDateTime();
$id_user = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");
$status = 0;
	
//หา chqKeeperID 
$qry_chqKeeperID = pg_query("select \"chqKeeperID\" from finance.thcap_receive_cheque_keeper where \"revChqID\"='$revChqID' and \"bankRevResult\" in ('1','2') ");
$chqKeeperID = pg_fetch_result($qry_chqKeeperID,0);
	
//ตรวจสอบว่าเช็คใบนี้ถูกนำไปใช้ก่อนทำการ map หรือไม่
$qrycheck=pg_query("SELECT \"chqKeeperID\" FROM finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqID\"='$revChqID' and \"revChqStatus\"='6'");
$num_chk=pg_num_rows($qrycheck);

if($num_chk>0){ //แสดงว่ายังไม่ได้นำไป map สามารถ map ได้ 
	//update รหัสเงินโอนที่ผูกกับเลขที่ใบเสร็จให้ map กับเช็ค
	$up="UPDATE finance.thcap_receive_transfer SET \"dateContact\"=null, \"revChqID\"='$revChqID',\"chqKeeperID\"='$chqKeeperID'
	WHERE \"revTranID\"=(SELECT \"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\"='$receiptID'
	AND \"byChannel\"<>'999' AND \"byChannelRef\" in (select \"revTranID\" from finance.thcap_receive_transfer)) 
	returning \"revTranID\"";
	if($resup=pg_query($up)){
		list($revTranID)=pg_fetch_array($resup);
	}else{
		$status++;
	}
	
	//update สถานะ chq ด้วยว่ามีการใช้เช็ค
	$upchq="UPDATE finance.thcap_receive_cheque SET \"revChqStatus\"=1 WHERE \"revChqID\"='$revChqID'";
	if($resupchq=pg_query($upchq)){
	}else{
		$status++;
	}
	
	//LOG
	if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
		  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
		 select 'เชื่อมรายการเช็ครหัส $revChqID กับใบเสร็จ $receiptID',\"revTranID\",'$id_user', '$nowdate',\"BAccount\",
		 \"bankRevBranch\",\"bankRevAmt\",\"bankRevStamp\", \"appvYRemask\"
		 from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID'")); else $status++;
	//LOG---
	
}else{
	$status=-1;
}

if($status==-1){
	pg_query("ROLLBACK");
	echo "1";
}else if($status == 0){
	pg_query("COMMIT");
	echo "2";
}else{
	pg_query("ROLLBACK");
	echo "3";
}
?>