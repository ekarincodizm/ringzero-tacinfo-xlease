<?php
session_start();
include("../../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$CusID=$_POST["CusID"];
$statusNT=$_POST["statusNT"];
$NTDate=$_POST["NTDate"]; 
$cutYear=$_POST["cutYear"]; 
$ntrec=$_POST["ntrec"]; 
$radiostop=$_POST["radiostop"]; 
if($radiostop==""){
	$radiostop="1900-01-01";
}
if($cutYear==""){
	$cutYear=0;
}
if($NTDate==""){
	$NTDate="1900-01-01";
}
$cutAccount=$_POST["cutAccount"]; 
$cutAccount=str_replace(",","",$cutAccount); 

$statusLock=$_POST["statusLock"];
$statusDate=$_POST["statusDate"];

if($statusDate==1){
	$checkDate=$_POST["checkDate"];
}else{
	$checkDate="1900-01-01";
}
$KeyDate = Date('Y-m-d H:i:s');

pg_query("BEGIN WORK");
$status = 0;
	
$update="update \"Taxiacc\" set 
	\"statusNT\"='$statusNT',
	\"cutAccount\"='$cutAccount',
	\"statusLock\"='$statusLock',
	\"checkDate\"='$checkDate',
	\"id_user\"='$id_user',
	\"KeyDate\"='$KeyDate',
	\"NTDate\"='$NTDate',
	\"cutYear\"='$cutYear',
	\"ntrec\"='$ntrec',
	\"radiostop\"='$radiostop' where \"CusID\"='$CusID'";
	
	if($res_up=pg_query($update)){
	}else{
		$status++;
	}
		
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) อัพเดทการติดตาม 1681', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<center><h2>อัพเดทข้อมูลเรียบร้อยแล้ว</h2></center>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_Update.php'>";
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_Update.php'>";
	}



