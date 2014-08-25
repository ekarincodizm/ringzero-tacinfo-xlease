<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "select * from \"thcap_contract\" WHERE \"contractID\" like '%$term%' and \"conCredit\" is not null ";

$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results ))
{
	$contractID = trim($row["contractID"]);
	$conCredit = trim($row["conCredit"]);
	
	$numberConCredit = number_format($conCredit,2); // ทำให้เป็น format จำนวนเงิน

	/*$name = str_replace("'", "\'"," ".$id.""." / ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);*/
    
	$dt['value'] = $contractID;
	$dt['label'] = "$contractID จำนวนวงเงินสินเชื่อแรกเริ่ม($numberConCredit บาท)";
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>