<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$receiveDate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$receiveUser = $_SESSION["av_iduser"];

$CusID=$_POST["CusID"];
$cusFName=checknull($_POST["cusFName"]);
$cusLName=checknull($_POST["cusLName"]); 
$carRegis=checknull($_POST["carRegis"]); 
$carRadio=checknull($_POST["carRadio"]); 
$startDate=checknull($_POST["startDate"]); 
$note=checknull($_POST["note"]); 

//ค้นหาชื่อพนักงาน
$qryname=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$receiveUser'");
list($fullname)=pg_fetch_array($qryname);

pg_query("BEGIN WORK");
$status = 0;
	
$ins="INSERT INTO \"Cancel_Radio\"(
            \"CusID\", \"cusFName\", \"cusLName\", \"carRadio\", \"carRegis\", \"startDate\", 
            note, \"receiveUser\", \"receiveDate\", id_user)
    VALUES ('$CusID', $cusFName, $cusLName, $carRadio, $carRegis, $startDate, 
            $note, '$fullname', '$receiveDate', '$receiveUser')";
	
if($res_up=pg_query($ins)){
}else{
	$status++;
}
		
if($status == 0){
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$receiveUser', '(TAL) ยกเลิกสัญญาวิทยุ', '$receiveDate')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>บันทึกยกเลิกข้อมูลเรียบร้อยแล้ว</h2></center>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_RadioCancle.php'>";
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_RadioCancle.php'>";
}



