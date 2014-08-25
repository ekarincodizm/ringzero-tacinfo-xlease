<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from public.\"thcap_contract\" where \"contractID\" like '%$term%' order by \"contractID\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$contractID=$res_name["contractID"];
    
    $dt['value'] = $contractID;
    $dt['label'] = "$contractID";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>