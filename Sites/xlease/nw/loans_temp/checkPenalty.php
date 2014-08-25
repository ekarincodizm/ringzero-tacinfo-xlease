<?php
include("../../config/config.php");

$contype = trim($_POST['contype']);
$conloanamt = trim($_POST['conloanamt']);
$conMinPay = trim($_POST['conMinPay']);

$sql = "SELECT \"thcap_getDefaultPenaltyRate\"('$contype', '$conloanamt', '$conMinPay') ";
$qry = pg_query($sql);
$results = pg_fetch_result($qry,0);

echo $results;
?>