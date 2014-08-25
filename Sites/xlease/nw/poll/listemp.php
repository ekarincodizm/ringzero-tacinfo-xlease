<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "SELECT \"id_user\", \"fullname\", \"nickname\"
		FROM \"Vfuser\"
		where (\"fullname\" like '%$term%') OR (\"nickname\" like '%$term%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {


	$fullname =trim($row["fullname"]);
	
	$nickname =trim($row["nickname"]);
	
	
	

	$name = str_replace("'", "\'"," ".$fullname.""." / ".$nickname	);
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