<?php
include("../../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$q = $_GET["q"];

$sql_select=pg_query("select \"CusID\",\"full_name\" from \"VSearchCusCorp\" where (\"full_name\" like '%$q%' )  order by \"full_name\" ASC limit(15) ");
while($res_cn=pg_fetch_array($sql_select)){
    $CusID = trim($res_cn["CusID"]);
    $full_name = trim($res_cn["full_name"]);
	
	$name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", "$full_name");
    echo "<li onselect=\"this.setText('$full_name').setValue('$CusID'); \">$CusID $name</li>";
}
?>