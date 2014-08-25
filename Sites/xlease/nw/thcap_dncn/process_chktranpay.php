<?php
include("../../config/config.php");

$BID=$_GET['BID'];

$qrychk=pg_query("select \"isTranPay\" from \"BankInt\" where \"BID\"='$BID'");
list($isTranPay)=pg_fetch_array($qrychk);
	
echo $isTranPay;
											
?>
