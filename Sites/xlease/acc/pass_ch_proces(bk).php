<?php
session_start();
include("../config/config.php");
$ic=pg_escape_string($_GET["icno"]);
$ip=pg_escape_string($_GET["postid"]);
$user=pg_escape_string($_GET["userid"]);

$qry_cc=pg_query("select pass_cheque('$ip','$ic','$user')");
$res_csc=pg_fetch_result($qry_cc,0);

$res_csc;

echo "<meta http-equiv=\"refresh\" content=\"0;URL=receipt_ch.php\" >";

?>