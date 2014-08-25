<?php
session_start();
include("../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
 $v_id=pg_escape_string($_GET["idmenu"]);
 $m_name=pg_escape_string($_GET["fmenu_name"]);
 $m_path=pg_escape_string($_GET["f_path"]);
 $m_sta=pg_escape_string($_GET["f_sta"]);
 $m_desc=pg_escape_string($_GET["f_desc"]); //คำอธิบายเมนู
 $m_stsuse=pg_escape_string($_GET["f_stsuse"]); //การใ้ช้งานปัจจุบัน
 $m_alert =pg_escape_string($_GET["f_alert"]);
$sql="update f_menu set  name_menu='$m_name',path_menu='$m_path' , status_menu='$m_sta',
menu_desc='$m_desc',menu_status_use='$m_stsuse',\"isAlert\"='$m_alert' where id_menu='$v_id' ";

 if($result=pg_query($sql))
 {
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) แก้ไขเมนู', '$add_date')");
	//ACTIONLOG---
  $status ="Update f_menu แล้ว จะนำท่านไปยัง manage_menu.php";
 }
 else
 {
  $status ="error Update  f_menu".$sql;
 }

echo "<br>".$status;

?>