<?php
session_start();
include("../../config/config.php");

$chk = $_REQUEST['chk'];
$id = $_REQUEST['id'];

//ต่อชื่อเข้าด้วยกัน ที่ต้องแยกมาเนื่องจากไม่สามารถส่งมาทั้งหมดได้ 
if($chk=='customer'){ //ตรวจสอบว่ามีลูกค้าคนนี้จริงหรือไม่
	$qrychk=pg_query("SELECT * FROM \"VSearchCusCorp\" WHERE \"CusID\"='$id'");
	$numrow=pg_num_rows($qrychk);
	
	if($numrow>0){ //แสดงว่ามีค่าจริง
		echo "<input type=\"hidden\" name=\"cusid\" id=\"cusid\" value=\"yes\">";
	}else{
		echo "<input type=\"hidden\" name=\"cusid\" id=\"cusid\" value=\"no\">";
	}
}else if($chk=='bank'){
	$qrychk=pg_query("SELECT * FROM \"BankProfile\" WHERE \"bankID\"='$id'");
	$numrow=pg_num_rows($qrychk);

	if($numrow>0){ //แสดงว่ามีค่าจริง
		echo "<input type=\"hidden\" name=\"bankid\" id=\"bankid\" value=\"yes\">";
	}else{
		echo "<input type=\"hidden\" name=\"bankid\" id=\"bankid\" value=\"no\">";
	}
}



