<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from account.tal_voucher WHERE \"vc_id\" LIKE '%$term%' AND \"finish\" = 'TRUE' LIMIT 10");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $vc_id=$res_name["vc_id"];
    
    if(substr($vc_id,0,1) == "I"){
        continue;
    }
    
    $dt['value'] = $vc_id;
    $dt['label'] = "{$vc_id}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
