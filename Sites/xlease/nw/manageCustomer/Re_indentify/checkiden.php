<?php
include("../../../config/config.php");

$userid = $_SESSION["av_iduser"];

$qry_user = pg_query("select \"emplevel\" from \"fuser\" where \"id_user\" = '$userid' ");
$emplevel = pg_fetch_result($qry_user,0);

$term = trim($_POST['iden']);

$sql = "SELECT \"N_IDCARD\" FROM \"Fn\" where  trim(replace(\"N_IDCARD\",' ','')) = '$term'";

$results=pg_query($sql);						 
$row = pg_num_rows($results);

if($row == 0 || empty($row) || ($emplevel != "" && $emplevel <= 1))
{
	echo "yes";
}
else
{
	echo "no";
}
?>