<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$qry_cn=pg_query("select \"InsID\",\"IDNO\" from \"insure\".\"InsureForce\" WHERE \"InsID\" like '%$q%' LIMIT 100 ");
while($res_cn=pg_fetch_array($qry_cn)){
    $InsID = $res_cn["InsID"];
    $IDNO = $res_cn["IDNO"];
        $display_InsID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $InsID);
        echo "<li onselect=\"this.setText('$InsID : $IDNO').setValue('$InsID'); \">$display_InsID : $IDNO</li>";
}
?>