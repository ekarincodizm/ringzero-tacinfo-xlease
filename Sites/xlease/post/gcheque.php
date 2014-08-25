<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select \"ChequeNo\",\"IDNO\",\"PostID\" from \"VDetailCheque\" WHERE  (\"ChequeNo\" like '%$q%') order by \"ChequeNo\" LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $ChequeNo = $res_cn["ChequeNo"];
    $IDNO = $res_cn["IDNO"];
    $PostID = $res_cn["PostID"];
    
    $sql_n=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\" = '$IDNO'");
    if($res_n=pg_fetch_array($sql_n)){
        $full_name = trim($res_n["full_name"]);
    }
    
    $display_ChequeNo = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $ChequeNo);
    echo "<li onselect=\"this.setText('$ChequeNo#$PostID').setValue('$ChequeNo'); \">$display_ChequeNo , $IDNO - $full_name - $PostID</li>";
}
?>