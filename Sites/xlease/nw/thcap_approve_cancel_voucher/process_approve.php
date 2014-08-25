<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$autoID = pg_escape_string($_POST["autoID"]);
$appvStatus = pg_escape_string($_POST["appvStatus"]);
$appvremark = pg_escape_string($_POST["appvremark"]);
	
pg_query("BEGIN WORK");
$status = 0;

$qry_ins = "select \"thcap_process_voucherCancel\"('$autoID', '$app_user', '$appvStatus', '$appvremark')";
if($res=pg_query($qry_ins)){
}else{
	$status++;
	echo $qry_ins;
}

if($status == 0)
{
	pg_query("COMMIT");
	echo 1;
}
else
{
	pg_query("ROLLBACK");
	echo 2;
}
?>