<?php
session_start();
include("../../config/config.php");
//require_once ("../../core/core_functions.php");

$cmd = $_REQUEST['cmd'];
$currentdate=nowDate();
$currenttime=date('H:i:s');

$nowdate=nowDateTime();
$iduser = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");
$status = 0;
	
if($cmd=='cal_loan_fine'){ //คำนวณเบี้ยปรับตามวันที่ต้องการ ของสัญญาประเภท LOAN
	$caldate=$_POST['caldate'];
	$contractID=$_POST['contractID'];
	
	//เบี้ยปรับปัจจุบัน
	$qr_get_loan_fine=pg_query("select \"thcap_get_loan_fine\"('$contractID','$caldate')");
	if($rs_get_loan_fine = pg_fetch_array($qr_get_loan_fine)){
		list($loan_fine) = $rs_get_loan_fine;
	}else{
		$status++;
	}
}

if($status == 0){
	pg_query("COMMIT");
	
	if($cmd=='cal_loan_fine'){
		echo number_format($loan_fine,2);
	}else{
		echo "1";
	}
}else{
	pg_query("ROLLBACK");
	if($cmd=='cal_loan_fine'){
		echo 'ERROR';
	}else{
		echo "2";
	}
}
?>