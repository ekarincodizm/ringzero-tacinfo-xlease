<?php
include("../../config/config.php");
$term = $_GET['term'];

//ต้องค้นจากเลขที่สัญญาที่มีกรมธรรม์แล้วเท่านั้น
$qry_name=pg_query("select \"contractID\" from \"thcap_mg_contract\" WHERE \"contractID\" LIKE '%$term%' ORDER BY \"contractID\"");

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
