<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$ie_vid=pg_escape_string($_GET["h_vedit"]);
$ie_type=pg_escape_string($_GET["vtype"]);
$ie_name=pg_escape_string($_GET["vname"]);
$ie_add=pg_escape_string($_GET["vadd"]);
$ie_tel=pg_escape_string($_GET["vtel"]);





$in_sql="update account.vender 
         SET type_vd='$ie_type',vd_name='$ie_name',vd_address='$ie_add',vd_tel='$ie_tel' 
		 WHERE \"VenderID\"='$ie_vid'";

if($result=pg_query($in_sql))
 {
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไข VENDER', '$add_date')");
	//ACTIONLOG---
  $status ="บันทึกข้อมูลแล้ว";
 }
 else
 {
  $status ="เกิดข้อพิดพลาด หรือ ข้อมูลซ้ำกัน ".$in_sql;
 }

echo $status;



?>