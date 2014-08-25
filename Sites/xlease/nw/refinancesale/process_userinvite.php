<?php
session_start();
include("../../config/config.php");
$method = $_POST["method"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

if($method == ""){
	$method = $_GET["method"];
	$id_user = $_GET["id_user"];
}else{
	$id_user=trim($_POST["id_users"]);
	$id_user = substr($id_user,0,3);
}
// ตรวจสอบข้อมูลที่เลือกว่ามีในฐานข้อมูลหรือไม่ 
$query_check = pg_query("select * from \"fuser\" WHERE \"id_user\" = '$id_user' ");
$num_check=pg_num_rows($query_check);

if($num_check == 0){
	$status = 1;
}else{
	$query = pg_query("select * from refinance.\"user_invite\" where \"id_user\" = '$id_user' and \"status_use\" = 'FALSE'");
	$nrows=pg_num_rows($query);
	if($nrows != 0){
		$method = "edit";
	}

	pg_query("BEGIN WORK");
	$status = 0;

	if($method == "add"){
		$ins = "insert into refinance.\"user_invite\"(\"id_user\",\"status_use\") values ('$id_user','TRUE')";
		if($result=pg_query($ins)){
			
		}else{
			$status += 1;
		}
		
		$echotxt = "บันทึกข้อมูลเรียบร้อยแล้ว";
			//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ตั้งค่าระบบชักชวน Ref - เพิ่มพนักงาน', '$add_date')");
			//ACTIONLOG---
	}else if($method == "edit"){
		$update = "update refinance.\"user_invite\" set \"status_use\" = 'TRUE' where \"id_user\" = '$id_user'";
		if($result1=pg_query($update)){
			
		}else{
			$status += 1;
		}
		$echotxt = "บันทึกข้อมูลเรียบร้อยแล้ว";
			//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ตั้งค่าระบบชักชวน Ref - ลบพนักงาน', '$add_date')");
			//ACTIONLOG---
	}else if($method == "delete"){
		$update = "update refinance.\"user_invite\" set \"status_use\" = 'FALSE' where \"id_user\" = '$id_user'";
		if($result1=pg_query($update)){
			
		}else{
			$status += 1;
		}
		
		$echotxt = "ลบข้อมูลเรียบร้อยแล้ว";
	}
}
if($status == 0){

	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>$echotxt</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_SetUser.php'>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
	echo "<meta http-equiv='refresh' content='2; URL=frm_SetUser.php'>";
}		  
?>