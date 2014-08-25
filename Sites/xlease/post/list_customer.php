<?php
include("../config/config.php");
$term = $_GET['term'];

$sql = "select * from \"VSearchCus\" where (\"full_name\" like '%$term%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {

	$id = trim($row["CusID"]);
	$fullname = trim($row["full_name"]);

	$name = str_replace("'", "\'"," ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>