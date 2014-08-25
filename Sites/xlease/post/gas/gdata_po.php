<?php
include("../../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select \"poid\",\"carnum\" from gas.\"PoGas\" where (\"poid\" like '%$q%'  OR \"carnum\" like '%$q%') AND idno is null order by \"poid\" ASC ");
while($res_cn=pg_fetch_array($sql_select)){
    $poid = trim($res_cn["poid"]);
    $carnum = trim($res_cn["carnum"]);

    $S_poid = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $poid);
    $S_carnum = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $carnum);
    echo "<li onselect=\"this.setText('$poid : $carnum').setValue('$poid'); \">$S_poid : $S_carnum</li>";
}
?>