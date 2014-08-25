<?php
session_start();
include("../config/config.php");

$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime();

$m_name=split(",",pg_escape_string($_GET["fmenu_name"]));
$m_path=split(",",pg_escape_string($_GET["f_path"]));
$m_sta=split(",",pg_escape_string($_GET["f_sta"]));
$m_id=split(",",pg_escape_string($_GET["f_id"]));
$m_desc=split(",",pg_escape_string($_GET["f_desc"])); //คำอธิบายเมนู
$m_stsuse=split(",",pg_escape_string($_GET["f_stsuse"])); //การใ้ช้งานปัจจุบัน
$m_alert=split(",",pg_escape_string($_GET["f_alert"]));
$all_menu = sizeof($m_id);

$i = 0;
$res = 0;

pg_query("BEGIN");

while($i<$all_menu)
{
	$menu_id = trim($m_id[$i]);
	$menu_name = trim($m_name[$i]);
	$menu_path = trim($m_path[$i]);
	$menu_status = trim($m_sta[$i]);
	$menu_desc = trim($m_desc[$i]);
	$menu_stsuse = trim($m_stsuse[$i]);
	$menu_alert = trim($m_alert[$i]);
	$sql_in_menu="insert into f_menu(id_menu,name_menu,path_menu,status_menu,menu_desc,menu_status_use,\"isAlert\") values('$menu_id','$menu_name','$menu_path','$menu_status','$menu_desc','$menu_stsuse','$menu_alert')";
	
	if($result=pg_query($sql_in_menu))
	{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มเมนู', '$add_date')");
	//ACTIONLOG---
	}
	else
	{
		$status.="error Insert  f_menu".$sql."<br />";
		$res++;
	}
	
	$i++;
}
if($res==0)
{
	$status ="Insert f_menu แล้ว จะนำท่านไปยัง manage_menu.php";
	pg_query("COMMIT");
}
else
{
	pg_query("ROLLBACK");
}
echo "<br />".$status;

?>