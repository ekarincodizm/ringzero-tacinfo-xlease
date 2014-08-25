<?php
include("../../config/config.php"); 

$term = $_GET['term'];

$sql_select=pg_query("SELECT * FROM thcap_mg_3dreceipt where (\"threceiptID\" like '%$term%' OR \"cusname\" like '%$term%') ORDER BY \"threceiptID\" ");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $ID = trim($res_cn["threceiptID"]);
    $full_name = trim($res_cn["cusname"]);


	$dt['value'] = $ID."#".$full_name;
	$dt['label'] = "{$ID} : {$full_name}";
	$matches[] = $dt;		
}
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>