<?php
session_start();

$strID = pg_escape_string($_POST["tID"]);
include("../config/config.php");

$strSQL = "DELETE FROM account.\"FormulaID\" ";
$strSQL .="WHERE fm_id = '".$strID."' ";
$objQuery =pg_query($strSQL);
if($objQuery)
{
	echo "Y";
	$sql_del="delete from account.\"FormulaAcc\" where fm_id='$strID' ";
	if($res_del=pg_query($sql_del))
	{
	 //echo "s";
	}
	else
	{
	//echo "N";
	}

	
}
else
{
	echo "N";
}



?>