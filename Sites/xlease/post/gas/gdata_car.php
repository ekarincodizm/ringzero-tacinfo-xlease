<?php
include("../../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select  \"CarID\",\"C_REGIS\" from \"VCarregistemp\" WHERE (\"C_REGIS\" like '%$q%') limit(10)");
while($res_cn=pg_fetch_array($sql_select)){
    $car_id= trim($res_cn["CarID"]);
    $car_regis = trim($res_cn["C_REGIS"]);

    $S_car_id = preg_replace("/(" . $q . ")/i", "$1>",  $car_id);
    $S_car_regis = preg_replace("/(" . $q . ")/i", "$1", $car_regis);
    echo "<li onselect=\"this.setText('$S_car_id : $S_car_regis').setValue('$car_id'); \">$S_car_id : $S_car_regis</li>";
}
?>