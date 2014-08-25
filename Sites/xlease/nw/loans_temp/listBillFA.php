<?php
include("../../config/config.php");
$term = $_GET['term'];
$main = $_GET['main'];

list($mainID,$mainname) = explode('#',$main);

$sql = "select * from \"thcap_fa_prebill\" WHERE \"numberInvoice\" like '%$term%' and \"userSalebill\" = '$mainID'
		and \"prebillID\" in(select \"prebillID\" from \"thcap_fa_prebill_temp\" where \"statusApp\" = '1')
		order by \"dateAssign\" , \"prebillID\" ";

//ใช้ view ในการเรียกข้อมูล 
//$sql="select * from public.\"vthcap_fa_prebill_select\" WHERE \"numberInvoice\" like '%$term%' and \"userSalebill\" = '$mainID'";

$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($res = pg_fetch_array( $results ))
{
	$prebillID = $res["prebillID"]; // รหัสบิล
	$numberInvoice = $res["numberInvoice"]; // เลขที่ใบแจ้งหนี้
	$userSalebill = $res["userSalebill"]; // รหัสผู้ขายบิล
	$userDebtor = $res["userDebtor"]; // รหัสลูกหนี้
	$totalTaxInvoice = $res["totalTaxInvoice"]; // ยอดในใบแจ้งหนี้รวมภาษี
	$taxInvoice = $res["taxInvoice"]; // จำนวนเงินที่นัดรับเช็คในแต่ละครั้ง
	$dateAssign = $res["dateAssign"]; // วันที่นัดรับเช็ค
	
	
	// หาชื่อผู้ขายบิล
	$qrySalebill = pg_query("select \"full_name\" from \"VSearchCusCorp\" WHERE \"CusID\" = '$userSalebill'");
	$nameSalebill = pg_fetch_result($qrySalebill,0);
	
	// หาชื่อลูกหนี้
	$qryDebtor = pg_query("select \"full_name\" from \"VSearchCusCorp\" WHERE \"CusID\" = '$userDebtor'");
	$nameDebtor = pg_fetch_result($qryDebtor,0);
	
	//$display_name = "$prebillID / จำนวนเงิน: ".number_format($totalTaxInvoice,2)." บาท เลขที่บิล: $numberInvoice / ชื่อผู้ขายบิล: $nameSalebill / ลูกหนี้: $nameDebtor";
	
	$display_name = "$prebillID / เลขที่บิล: $numberInvoice / จำนวนเงินทั้งบิล:  ".number_format($totalTaxInvoice,2)." บาท / ลูกหนี้: $nameDebtor / วันที่นัดรับเช็ค: $dateAssign / จำนวนเงินนัดรับ: ".number_format($taxInvoice,2)." บาท";
    
	$dt['value'] = $prebillID."#".$numberInvoice."#".$totalTaxInvoice."#".$dateAssign."#".$taxInvoice;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>