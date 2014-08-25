<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT * FROM \"VSearchCusCorp\" WHERE full_name like '%$term%' ORDER BY full_name");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["CusID"];
	$full_name = trim($res_name["full_name"]);
	
	$name = str_replace("'", "\'"," ".$CusID.""." # ".$full_name);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

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
