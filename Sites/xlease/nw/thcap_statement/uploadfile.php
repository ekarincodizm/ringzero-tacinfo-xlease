<?php
##เมนู (THCAP) LOAD STATEMENT BANK
session_start();
include("../../config/config.php");
require_once ("../../core/core_functions.php");
include("../function/checknull.php");
include("../function/nameMonth.php");
$id_user=$_SESSION["av_iduser"];
$datelog = nowDateTime();
$bankint = $_POST["bankint"]; //ช่องทาง
$dateadd = $_POST["dateadd"]; //วันที่ upload

//หาชื่อธนาคาร
$qryname=pg_query("select \"BName\" from \"BankInt\" where \"BID\"='$bankint'");
list($bname)=pg_fetch_array($qryname);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<?php
if($bname=='KBANK')
{ //กรณีเป็น KBANK
	include "process_load_statement_kbank.php";
}
elseif($bname=='SCB')
{ //กรณีเป็น SCB
	include "process_load_statement_scb.php";
}

?> 
</body>
</html>