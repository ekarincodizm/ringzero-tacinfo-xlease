<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from account.\"AccountBookHead\" WHERE \"acb_id\" LIKE '%$term%'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $acb_id=trim($res_name["acb_id"]);
    $acb_detail=trim($res_name["acb_detail"]);
    
    $dt['value'] = $acb_id."#".$acb_detail;
    $dt['label'] = "{$acb_id}, {$acb_detail}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
