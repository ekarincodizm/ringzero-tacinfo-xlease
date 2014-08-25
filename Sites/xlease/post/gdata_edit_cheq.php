<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select * from \"FCheque\" where 
\"ChequeNo\" like '%$q%' AND \"IsPass\" = 'FALSE' AND \"Accept\" = 'TRUE' AND \"IsReturn\" = 'FALSE'
ORDER BY \"ChequeNo\" ASC LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $ChequeNo = trim($res_cn["ChequeNo"]);
    $PostID = trim($res_cn["PostID"]);

        $D_ChequeNo = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $ChequeNo);
        $D_PostID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $PostID);
        echo "<li onselect=\"this.setText('$ChequeNo : $PostID').setValue('$ChequeNo'); \">$D_ChequeNo : $D_PostID</li>";
}
?>