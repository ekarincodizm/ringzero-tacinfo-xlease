<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "select fc.\"comID\" as comm,fc.\"com_name\",fe.\"empconID\",fe.\"empcon_name\" from  public.\"fu_company\" fc left join \"fu_empcontact\" fe on fe.\"comID\" = fc.\"comID\"

where (fe.\"empconID\" like '%$term%') OR (fe.\"empcon_name\" like '%$term%') OR (fc.\"com_name\" like '%$term%') OR (fc.\"comID\" like '%$term%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {

	$id = $row["empconID"]; // ฟิลที่ต้องการส่งค่ากลับ
	$fullname =trim($row["empcon_name"]);
	
	$comname =trim($row["com_name"]);
	$comID1 =trim($row["comm"]);
	
	if($id == ""){
		$id = "";
		$fullname ="";
		}

	$name = str_replace("'", "\'"," ".$comID1.""." / ".$comname.""."/".$id.""."/".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $comID1."#".$comname."#".$id."#".$fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>