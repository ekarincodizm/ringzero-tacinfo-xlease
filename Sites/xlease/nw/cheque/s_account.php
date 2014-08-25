<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"BankInt\" WHERE (\"BAccount\" LIKE '%$term%' or \"BName\" LIKE '%$term%' or \"BBranch\" LIKE '%$term%') and \"isChq\"='1'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $BAccount=$res_name["BAccount"];
    $BName=$res_name["BName"];
    $BBranch=$res_name["BBranch"];
    
    $dt['value'] = $BAccount;
    $dt['label'] = "{$BAccount}, ธนาคาร{$BName}, สาขา{$BBranch}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
