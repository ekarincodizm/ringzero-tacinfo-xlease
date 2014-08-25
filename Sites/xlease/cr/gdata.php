<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\" from \"VContact\" WHERE \"full_name\" like '%$q%' or \"IDNO\" like '%$q%' or \"C_REGIS\" like '%$q%' or \"car_regis\" like '%$q%' LIMIT 100");
while($res_cn=pg_fetch_array($qry_cn)){
    $id_no = $res_cn["IDNO"];
    $full_name = $res_cn["full_name"];
    $asset_type = $res_cn["asset_type"];
    $C_REGIS = $res_cn["C_REGIS"];
    $car_regis = $res_cn["car_regis"]; if($asset_type == 1){ $regis = $C_REGIS; }else{ $regis = $car_regis; }
        $full_name = str_replace("'", "'", $full_name);
        $display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $full_name);
        $display_idno = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $id_no);
        $display_regis = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $regis);
        echo "<li onselect=\"this.setText('$id_no : $full_name - $regis').setValue('$id_no'); \">$display_idno : $display_name - $display_regis</li>";
}
?>