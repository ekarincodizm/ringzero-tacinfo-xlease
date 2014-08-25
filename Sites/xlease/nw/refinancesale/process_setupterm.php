<?php
session_start();
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"];
$height_term=$_POST["height_term"];
$low_term=$_POST["low_term"];
$limit_term=$_POST["limit_term"];
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

$ins = "insert into refinance.\"setup_term\"(\"height_term\",\"low_term\",\"limit_term\",\"setupDate\",\"id_user\") values ('$height_term','$low_term','$limit_term','$curdate','$id_user')";

if($result=pg_query($ins)){
		
}else{
	$status += 1;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ตั้งค่าระบบชักชวน Ref - จัดการจำนวนงวดสูงสุด - ต่ำสุด', '$curdate')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_SetTerm.php'>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}		  
?>