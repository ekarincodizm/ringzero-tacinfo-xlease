<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"Vfuser\" where \"fullname\" like '%$term%' or \"nickname\" like '%$term%'");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$id_user=trim($res_name["id_user"]);
	$fullname=trim($res_name["fullname"]);
	$nickname=trim($res_name["nickname"]);
    
    $dt['value'] = $id_user.":".$fullname;
    $dt['label'] = "{$id_user} : {$fullname} : {$nickname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
