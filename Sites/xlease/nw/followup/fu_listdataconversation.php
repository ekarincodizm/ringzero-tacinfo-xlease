<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);


$sql1 = "select * from  public.\"fu_company\" fc join \"fu_empcontact\" fe on fc.\"comID\" = fe.\"comID\" where (fc.\"com_name\" like '%$term%') OR (fe.\"empcon_name\" like '%$term%') ";
$results=pg_query($sql1);
$nrows=pg_num_rows($results);	

while($row = pg_fetch_array( $results)) {

	$id = $row["conID"]; // ฟิลที่ต้องการส่งค่ากลับ
	$fullname =trim($row["con_name"]);
	$comid =trim($row["comID"]);
	$fullcomname =trim($row["com_name"]);
	$empname=trim($row["empcon_name"]);


	$name = str_replace("'", "\'"," ".$id.""."/".$fullname.""."/".$fullcomname.""."/".$empname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $comid."#".$fullcomname."#".$fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
	}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);

?>