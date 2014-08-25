<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$qry_dc=pg_query("select * from \"insure\".\"InsureForce\"
                     WHERE (\"InsID\" like '%$q%' OR \"InsFIDNO\" like '%$q%') AND \"Cancel\" = 'FALSE'
                     ORDER BY \"InsID\",\"InsFIDNO\" ASC LIMIT 100 
                     ");
                     
while($res_dc=pg_fetch_array($qry_dc)){
    $InsID = $res_dc["InsID"];
    $InsFIDNO = $res_dc["InsFIDNO"];
    $IDNO = $res_dc["IDNO"];
    //$full_name = $res_dc["full_name"];
        //$full_name = str_replace("'", "'", $full_name);
        $display_InsID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $InsID);
        $display_InsFIDNO = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $InsFIDNO);
        echo "<li onselect=\"this.setText('$InsID : $InsFIDNO - $IDNO').setValue('$InsFIDNO'); \">$display_InsID : $display_InsFIDNO - $IDNO</li>";
}
?>