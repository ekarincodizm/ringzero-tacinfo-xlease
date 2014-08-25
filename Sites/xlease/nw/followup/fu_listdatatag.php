<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "select * from  public.\"fu_tag\" ft
inner join \"fu_conversation\" fcon on ft.\"conID\" = fcon.\"conID\"
left join \"fu_company\" fc on fc.\"comID\" = fcon.\"comID\"

 where (ft.\"tag_name\" like '%$term%') or (fcon.\"con_name\" like '%$term%')  or (fc.\"com_name\" like '%$term%')order by ft.\"tagID\"";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["tagID"]; // ฟิลที่ต้องการส่งค่ากลับ
	$fullname =trim($row["tag_name"]);
	$conname =trim($row["con_name"]);
	$comID =trim($row["comID"]);
	$comname =trim($row["com_name"]);
	$empname =trim($row["empcon_name"]);

	$name = str_replace("'", "\'"," ".$id.""." / ".$fullname.""." / ".$comname.""." / ".$conname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $comID."#".$fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>