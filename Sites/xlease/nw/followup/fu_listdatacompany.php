<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "select * from  public.\"fu_company\" where (\"comID\" like '%$term%') OR (\"com_name\" like '%$term%') order by \"comID\"";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["comID"]; // ฟิลที่ต้องการส่งค่ากลับ
	$fullname =trim($row["com_name"]);

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