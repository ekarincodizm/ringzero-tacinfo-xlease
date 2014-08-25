<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

pg_query("BEGIN");
$status = 0;

$auto_id = pg_escape_string($_POST["auto_id"]);
$statement = pg_escape_string($_POST["statement"]);
$ud = pg_escape_string($_POST["ud"]);

if($ud == "u")
{
	if($statement == "income")
	{
		$qry_up = "UPDATE account.thcap_ledger_detail SET \"income_statement\" = \"ledger_balance\" WHERE \"auto_id\" = '$auto_id' ";
		$run_up = pg_query($qry_up);
		if($run_up){}else{ $status++; }
	}
	elseif($statement == "balance")
	{
		$qry_up = "UPDATE account.thcap_ledger_detail SET \"balance_sheet\" = \"ledger_balance\" WHERE \"auto_id\" = '$auto_id' ";
		$run_up = pg_query($qry_up);
		if($run_up){}else{ $status++; }
	}
}
elseif($ud == "d")
{
	$qry_del = "UPDATE account.thcap_ledger_detail SET \"income_statement\" = null, \"balance_sheet\" = null WHERE \"auto_id\" = '$auto_id' ";
	$run_del = pg_query($qry_del);
	if($run_del){}else{ $status++; }
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "YES";
}
else
{
	pg_query("ROLLBACK");
	echo "NO";
}
?>