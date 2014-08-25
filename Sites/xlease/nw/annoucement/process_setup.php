<?php
session_start();
include("../../config/config.php");

$user_key=$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$method=$_POST["method"];
$typeAnnName=$_POST["typeAnnName"];
$typeStatusUse=$_POST["typeStatusUse"];

pg_query("BEGIN WORK");
$status = 0;

if($method == "add"){
	$qrylastid=pg_query("select \"typeAnnId\" from \"nw_annoucetype\"");
	$numrow=pg_num_rows($qrylastid);
	$typeAnnId=$numrow+1;
	
	$in_sql="insert into \"nw_annoucetype\"(\"typeAnnId\",\"typeAnnName\",\"typeStatusUse\")values('$typeAnnId','$typeAnnName','$typeStatusUse')";
	if($resultins=pg_query($in_sql)){
	}else{
		$status++;
	}
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_key', '(ALL) เพิ่มการตั้งค่า Annoucement', '$add_date')");
//ACTIONLOG---	
}else if($method=="edit"){	
	$typeAnnId=$_POST["typeAnnId"];
	$upfuser="update \"nw_annoucetype\" set 
			\"typeAnnName\"='$typeAnnName',
			\"typeStatusUse\"='$typeStatusUse'
			 where \"typeAnnId\"='$typeAnnId'";
	if($res_up=pg_query($upfuser)){
	}else{
		$status++;
	}
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_key', '(ALL) แก้ไขการตั้งค่า Annoucement', '$add_date')");
//ACTIONLOG---		
}

if($status == 0){
	pg_query("COMMIT");
	echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
	if($method=="edit"){
		echo "<meta http-equiv='refresh' content='2; URL=frm_setupAdd.php?typeAnnId=$typeAnnId&method=edit'>";
	}else{
		echo "<meta http-equiv='refresh' content='2; URL=frm_setupAdd.php'>";
	}	
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	if($method=="edit"){
		echo $upfuser;
		echo "<meta http-equiv='refresh' content='3; URL=frm_setupAdd.php?typeAnnId=$typeAnnId&method=edit'>";
	}else{
		echo $in_sql;
		echo "<meta http-equiv='refresh' content='3; URL=frm_setupAdd.php'>";
	}
}


