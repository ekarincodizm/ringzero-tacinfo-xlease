<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"auto_id\",\"sendName\" FROM thcap_letter_head where \"sendName\" LIKE '%$term%'  order by \"auto_id\" DESC");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $auto_id=$res_name["auto_id"];
    $type_name=$res_name["sendName"];  
    
    $dt['value'] = $auto_id."#".$type_name;
    $dt['label'] = "{$auto_id}, {$type_name} ";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
