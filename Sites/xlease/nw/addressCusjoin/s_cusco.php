<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"CusID\",\"full_name\" from \"VSearchCus\" WHERE \"full_name\" like '%$term%' group by \"CusID\",\"full_name\"");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $full_name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);

	$name = str_replace("'", "\'"," ".$CusID.""." # ".$full_name);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $CusID."#".$full_name;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
