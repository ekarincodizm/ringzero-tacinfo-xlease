<?php
session_start();

$strID = pg_escape_string($_POST["tID"]);
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$strSQL = "DELETE FROM account.\"FormulaAcc\" ";
$strSQL .="WHERE auto_id = '".$strID."' ";
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