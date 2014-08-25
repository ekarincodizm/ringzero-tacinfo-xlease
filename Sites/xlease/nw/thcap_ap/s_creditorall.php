<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

//หาข้อมูล บุคคล หรือนิติบุคคล  จาก VSearchCusCorp

$qry_name=pg_query("select \"full_name\" as \"fname\",\"CusID\" as \"id_user\" from \"VSearchCusCorp\" 
where \"full_name\" like '%$term%' or \"CusID\" like '%$term%'");

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
