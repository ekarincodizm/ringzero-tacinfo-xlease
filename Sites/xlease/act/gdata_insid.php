<?php
include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$term = pg_escape_string($_GET["term"]);

//ประกันคุ้มภาคสมัครใจ
$qry_dc=pg_query("select \"InsUFIDNO\", \"C_CARNUM\", \"C_REGIS\" from insure.\"VInsUnforceDetail\"
WHERE \"C_CARNUM\" like '%$term%' or \"C_REGIS\" like '%$term%'");  
                   
$nrows=pg_num_rows($qry_dc);               
while($res_dc=pg_fetch_array($qry_dc)){
    $InsUFIDNO = $res_dc["InsUFIDNO"];
    $C_CARNUM = $res_dc["C_CARNUM"];
    $C_REGIS = $res_dc["C_REGIS"];

	$name = str_replace("'", "\'"," ".$InsUFIDNO.""." / ".$C_CARNUM.""." / ".$C_REGIS);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
	
	$dt['value'] = $InsUFIDNO."#".$C_CARNUM."#".$C_REGIS;
	$dt['label'] = $display_name;
    $matches[] = $dt;
} 

if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>