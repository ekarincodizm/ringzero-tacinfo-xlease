<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = pg_escape_string($_GET["q"]);

$qry_cn=pg_query("select \"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" from \"Fa1\" WHERE \"A_NAME\" like '%$q%' or \"A_SIRNAME\" like '%$q%' LIMIT 100 ");
while($res_cn=pg_fetch_array($qry_cn)){
    $CusID = trim($res_cn["CusID"]);
    $A_FIRNAME = trim($res_cn["A_FIRNAME"]);
    $A_NAME = trim($res_cn["A_NAME"]);
    $A_SIRNAME = trim($res_cn["A_SIRNAME"]);
        $display_A_NAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $A_NAME);
        $display_A_SIRNAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $A_SIRNAME);
        echo "<li onselect=\"this.setText('$CusID').setValue('$CusID'); \">$A_FIRNAME $display_A_NAME $display_A_SIRNAME</li>";
}
?>