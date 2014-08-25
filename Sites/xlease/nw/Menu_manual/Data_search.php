<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "select * from  f_menu_manual a
left join \"f_menu\" b on a.\"id_menu\" = b.\"id_menu\"
 where appstatus = '1' and  ((a.\"recheader\" like '%$term%') or (b.\"name_menu\" like '%$term%'))  order by a.id_menu";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$recheader = $row["recheader"]; // ฟิลที่ต้องการส่งค่ากลับ
	$name_menu =trim($row["name_menu"]);
	

	$name = str_replace("'", "\'"," ".$recheader.""." / ".$name_menu);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $recheader." ".$name_menu;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = " ";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>