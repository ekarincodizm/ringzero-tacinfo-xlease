<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"thcap_contract\" WHERE \"contractID\" LIKE '%$term%'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $contractID=$res_name["contractID"];
  
    
    $dt['value'] = $contractID;
    $dt['label'] = "{$contractID}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
