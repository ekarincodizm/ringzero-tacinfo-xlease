<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"id_user\",\"fname\" from fuser where \"id_user\" like '%$term%' or \"fname\" like '%$term%' LIMIT(20)");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $id_user=trim($res_name["id_user"]);
    $fname=$res_name["fname"];
    
    $dt['value'] = $id_user."#".$fname;
    $dt['label'] = "{$id_user}, {$fname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
