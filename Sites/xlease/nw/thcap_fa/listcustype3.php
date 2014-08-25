<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "select \"CusID\",\"full_name\" from \"VSearchCusCorp\" WHERE \"full_name\" like '%$term%' and \"type\"='2'";

$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["CusID"];
	$fullname =trim($row["full_name"]);
	    
	$dt['value'] = $id."#".$fullname;
	$dt['label'] = $id."#".$fullname;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>