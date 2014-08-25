<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];


$sql_select=pg_query("select A.*,B.* from  \"Fa1\" A 
LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\" 
where (A.\"A_NAME\" like '%$q%') OR (A.\"A_SIRNAME\" like '%$q%') LIMIT 100");
while($res_cn=pg_fetch_array($sql_select)){
    $CusID = $res_cn["CusID"];
    $A_NAME = trim($res_cn["A_NAME"]);
    $A_SIRNAME = trim($res_cn["A_SIRNAME"]);
   
        $display_CusID = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $CusID);
        $display_A_NAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $A_NAME);
        $display_A_SIRNAME = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $A_SIRNAME);
        echo "<li onselect=\"this.setText('$CusID : $A_NAME  $A_SIRNAME').setValue('$CusID'); \">$display_CusID : $display_A_NAME  $display_A_SIRNAME</li>";
}
?>