<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name = pg_query("select \"id_user\",\"fullname\" from \"Vfuser\" WHERE \"fullname\" LIKE '%$term%' ");					 
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
	$id_user=$res_name["id_user"];
	$fullname=$res_name["fullname"];
		
	$dt['value'] = $id_user."-".$fullname;
    $dt['label'] = "{$id_user},{$fullname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>

