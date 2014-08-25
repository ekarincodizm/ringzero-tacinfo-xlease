<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"CusID\",\"full_name\" from \"VSearchCus\" WHERE \"full_name\" like '%$term%' group by \"CusID\",\"full_name\"");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);

    $dt['value'] = $CusID."#".$name;
    $dt['label'] = "{$CusID}, {$name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
