<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$chk_find = pg_escape_string($_GET["find"]);

if($chk_find=="emp"){
	$qry_name=pg_query("select \"id_user\",\"fullname\"  as \"fname\" from \"Vfuser\" where \"id_user\" like '%$term%' or \"fullname\" like '%$term%'");}
else if($chk_find=="cus"){
	$qry_name=pg_query("select \"full_name\" as \"fname\",\"CusID\" as \"id_user\" from \"VSearchCusCorp\" 
where \"type\"='1' and (\"full_name\" like '%$term%' or \"CusID\" like '%$term%')");
}
else if($chk_find=="cus_corp"){
	$qry_name=pg_query("select \"full_name\" as \"fname\",\"CusID\" as \"id_user\" from \"VSearchCusCorp\" 
where \"type\"='2' and (\"full_name\" like '%$term%' or \"CusID\" like '%$term%')");
}
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
