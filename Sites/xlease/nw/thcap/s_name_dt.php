<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"vthcap_letter\" 
where \"contractID\" like '%$term%' or \"cusName\" LIKE '%$term%'
order by \"contractID\",\"sendDate\"");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $contractID=$res_name["contractID"];
    $cusName=$res_name["cusName"];

    $dt['value'] = $contractID;
    $dt['label'] = "{$contractID},ชื่อลูกค้า:{$cusName}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
