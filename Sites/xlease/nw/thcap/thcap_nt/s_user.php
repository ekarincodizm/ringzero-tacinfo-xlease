<?php
include("../../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"id_user\",\"fullname\" FROM \"Vfuser\"
where \"id_user\" LIKE '%$term%' or \"fullname\" LIKE '%$term%' order by \"id_user\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $id_user = trim($res_name["id_user"]);
	$fullname = trim($res_name["fullname"]);
	
	$name = str_replace("'", "\'"," ".$id_user.""." - ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
    
	$dt['value'] = $id_user."-".$fullname;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
