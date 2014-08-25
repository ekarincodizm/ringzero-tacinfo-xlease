<?php
session_start();
include("../../config/config.php");

$receiptID = pg_escape_string($_POST['receiptID']);//ใบเสร็จ
$remark = pg_escape_string($_POST['remark']);//เหตุผล

$nowdate=nowDateTime();
$id_user = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");
$status = 0;

//หาชื่อพนักงาน
$qryname=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$id_user'");
list($name)=pg_fetch_array($qryname);

$remark="บันทึกโดย $name \nวันที่ $nowdate : \n$remark";
//ตรวจสอบว่ามีการระบุหมายเหตุก่อนหน้านี้หรือไม่ ถ้ามีแล้วไม่ต้องระบุอีก
$pgchk=pg_query("select * from thcap_temp_receipt_details where \"receiptID\"='$receiptID' and 
(\"receiptRemark\" is null or \"receiptRemark\" ='' or \"receiptRemark\" ='-' or \"receiptRemark\" ='--')");
$numrow=pg_num_rows($pgchk);

if($numrow>0){ //ถ้ายังไม่ระบุหมายเหตุให้ทำการบันทึก
	$up="update thcap_temp_receipt_details set \"receiptRemark\"='$remark' where \"receiptID\"='$receiptID'";
	if($resup=pg_query($up)){
	}else{
		$status++;
	}
	//ACTIONLOG
	$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ตารางแสดงการผ่อนชำระ', '$nowdate')";
	if($resin=pg_query($sqlaction)){
	}else{ 
		$status++; 
	}
	//ACTIONLOG---
}else{ //กรณีมีการระบุอยู่แล้วไม่ต้องระบุอีก
	$status=-1;
}

if($status==-1){
	pg_query("ROLLBACK");
	echo 1;
}else if($status == 0){
	pg_query("COMMIT");
	echo 2;
}else{
	pg_query("ROLLBACK");
	echo 3;
}
?>