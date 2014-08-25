<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"CusID\",\"ConID\",(\"CusPreName\" || \"CusFirstName\"|| ' ' || \"CusLastName\") as fullname FROM thcap_cus_temp where (\"CusPreName\" || \"CusFirstName\"|| ' ' || \"CusLastName\") like '%$term%' or \"ConID\" like '%$term%';");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $ConID=$res_name["ConID"];
	$name = trim($res_name["fullname"]);

    $dt['value'] = $ConID."#".$name;
    $dt['label'] = "{$ConID}, {$name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
