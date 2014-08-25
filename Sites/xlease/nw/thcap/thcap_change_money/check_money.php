<?php
include("../../../config/config.php");

$termContract = trim($_POST['id']);
$moneyType = trim($_POST['moneyType']);

$sql = "SELECT \"contractBalance\" FROM \"vthcap_contract_money\" where \"contractID\" = '$termContract' and \"moneyType\" = '$moneyType' ";
$query = pg_query($sql);
$contractBalance = pg_fetch_result($query,0);

echo $contractBalance;
?>