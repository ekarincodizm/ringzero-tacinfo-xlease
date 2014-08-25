<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "select * from  \"Fa1\" where (\"A_NAME\" like '%$term%') OR (\"A_SIRNAME\" like '%$term%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = trim($row["CusID"]);
	
	$nname =trim($row["A_NAME"]);
	$lname =trim($row["A_SIRNAME"]);

	$name = str_replace("'", "\'"," ".$id.""." / ".$nname.""." - ".$lname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $id."#".$nname." ".$lname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>