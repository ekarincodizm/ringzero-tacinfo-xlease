<?php
include("../../config/config.php"); 

$term = $_GET['term'];

$sql_select=pg_query("SELECT \"contractID\", \"CusState\", \"CusID\", fullname, type
  FROM \"vthcap_ContactCus_detail\" where (\"contractID\" like '%$term%' OR \"thcap_fullname\" like '%$term%') AND \"CusState\" = 0 ORDER BY \"contractID\" ");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $ID = trim($res_cn["contractID"]);
    $full_name = trim($res_cn["thcap_fullname"]);
	$cusid = trim($res_cn["CusID"]);

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