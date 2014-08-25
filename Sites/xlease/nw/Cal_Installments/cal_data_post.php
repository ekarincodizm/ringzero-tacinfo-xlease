<?php
include("../../config/config.php");
$datestart = $_POST["date"];
$typecal = $_POST["type"];


IF($typecal == 'VAT'){

	$qry_cal = pg_query("SELECT cal_rate_or_money('".$typecal."','".$datestart."')");	
	IF($qry_cal){ list($vat) = pg_fetch_array($qry_cal); }
	echo "$vat";

}
?>