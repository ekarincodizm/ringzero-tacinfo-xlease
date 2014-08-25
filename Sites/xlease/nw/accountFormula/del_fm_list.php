<?php
session_start();

$strID = $_POST["tID"];
include("../../config/config.php");

pg_query("BEGIN");
$status = 0;

$strSQL = "DELETE FROM account.\"all_accFormula\" ";
$strSQL .= "WHERE af_fmid = '$strID'";
$objQuery = pg_query($strSQL);
if($objQuery)
{
	echo "Y";
	$sql_del="delete from account.\"all_accFormulaDetails\" where afd_fmid='$strID' ";
	if($res_del=pg_query($sql_del))
	{
		//echo "s";
	}
	else
	{
		//echo "N";
		$status++;
	}
}
else
{
	echo "N";
}

if($status == 0)
{
	pg_query("COMMIT");
}
else
{
	pg_query("ROLLBACK");
}

?>