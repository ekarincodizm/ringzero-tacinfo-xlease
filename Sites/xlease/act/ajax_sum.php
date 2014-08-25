<?php
@ini_set('display_errors', '0');
include("../config/config.php");

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

$code = pg_escape_string($_POST['code']); 
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);

$crif=pg_query("select \"insure\".cal_rate_insforce('$code','$date_start','$date_end')");
$res_crif=pg_fetch_result($crif,0);
$res_crif = preg_replace('/[^a-z0-9,.]/i', '', $res_crif);
$pieces = explode(",", $res_crif);
$gpremium = $pieces[0]+$pieces[1]+$pieces[2];
//$gpremium = number_format($gpremium,2);
echo "$gpremium";
?>