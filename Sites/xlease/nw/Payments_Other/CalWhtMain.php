<?php
include("../../config/config.php");

$contractID = $_GET["contractID"]; // เลขที่สัญญา
$datewht = $_GET["datewht"]; // วันที่จ่าย

//--หาค่า ภาษีหัก ณ ที่จ่าย จาก function
$defaultWht = pg_query("select \"thcap_cal_InterestWhtToDate\"('$contractID','$datewht')");
$defaultWhtMoney = pg_fetch_result($defaultWht,0);
if($defaultWhtMoney == ""){$defaultWhtMoney = 0.00;}
echo $defaultWhtMoney; // จำนวนเงินภาษีหัก ณ ที่จ่าย
?>