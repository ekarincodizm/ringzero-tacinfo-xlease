<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "select * from  \"vthcap_ContactCus_detail\"

 where (\"thcap_fullname\" like '%$term%') and \"CusState\" = '1' order by \"thcap_NLname\"";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["CusID"];
	$fullname =trim($row["thcap_fullname"]);
	

	$name = str_replace("'", "\'"," ".$id.""." / ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $id."#".$fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>