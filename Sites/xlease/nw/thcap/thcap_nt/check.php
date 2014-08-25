<?php
session_start();
include("../../../config/config.php");

$chk = $_REQUEST['chk'];
$user_id = $_REQUEST['id'];

//ต่อชื่อเข้าด้วยกัน ที่ต้องแยกมาเนื่องจากไม่สามารถส่งมาทั้งหมดได้ 
if($chk=='proctor'){ //ตรวจสอบว่ามีทนายคนนี้อยู่จริงหรือไม่
	$qrychk=pg_query("SELECT \"id_user\" FROM \"Vfuser\" WHERE \"id_user\"='$user_id'");
	$numrow=pg_num_rows($qrychk);

	if($numrow>0){ //แสดงว่ามีค่าจริง
		echo "<input type=\"hidden\" name=\"proctorid\" id=\"proctorid\" value=\"yes\">";
	}else{
		echo "<input type=\"hidden\" name=\"proctorid\" id=\"proctorid\" value=\"0\">";
	}
}



