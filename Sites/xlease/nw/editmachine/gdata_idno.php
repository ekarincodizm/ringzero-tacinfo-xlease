<?php
include("../../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select * from \"VContact\" where \"IDNO\" like '%$q%' ORDER BY \"IDNO\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $full_name = trim($res_cn["full_name"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
    $car_regis = trim($res_cn["car_regis"]);

        $S_IDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $IDNO);
        $S_full_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $full_name);
        echo "<li onselect=\"this.setText('$IDNO : $full_name : $C_REGIS $car_regis').setValue('$IDNO'); \">$S_IDNO : $S_full_name : $C_REGIS $car_regis</li>";
}
?>