<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$qry_cn=pg_query("select \"CarID\",\"C_CARNAME\",\"C_CARNUM\",\"C_REGIS\",\"C_COLOR\" from \"VCarregistemp\" WHERE \"C_CARNUM\" like '%$q%' or \"C_REGIS\" like '%$q%' LIMIT 100");
while($res_cn=pg_fetch_array($qry_cn)){
    $CarID = trim($res_cn["CarID"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
        $display_C_CARNUM = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_CARNUM);
        $display_C_REGIS = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $C_REGIS);
        echo "<li onselect=\"this.setText('$CarID').setValue('$CarID'); \">$display_C_REGIS - $display_C_CARNUM</li>";
}
?>