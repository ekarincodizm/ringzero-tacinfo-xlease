<?php
session_start();

$strID = $_POST["tID"];
include("../../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$strSQL = "DELETE FROM account.\"all_accFormulaDetails\" ";
$strSQL .="WHERE afd_autoid = '".$strID."' ";
$objQuery =pg_query($strSQL);
if($objQuery)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ลบสูตรทางบัญชี', '$add_date')");
	//ACTIONLOG---
	echo "Y";
}
else
{
	echo "N";
}



?>