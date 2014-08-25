<?php
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");

$objPHPExcel = new PHPExcel();


// $objPHPExcel->getProperties()->setCreator("Thaiace gruop");
// $objPHPExcel->getProperties()->setLastModifiedBy("Paisit@IT");
// $objPHPExcel->getProperties()->setTitle("รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
// $objPHPExcel->getProperties()->setSubject("รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ชื่อผู้กู้หลัก');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด');


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(65);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);


$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);



$month = $_GET['month'];
$year = $_GET['year'];

$sql = pg_query("select distinct \"contractID\",
					\"thcap_getInterestGainOverMonth\"(\"contractID\", '$year', '$month') as \"newInterest\"
					from \"thcap_temp_int_201201\"
					where substr(\"receiveDate\"::character varying,'1','4')::integer = '$year'
					and substr(\"receiveDate\"::character varying,'6','2')::integer = '$month'
					and \"thcap_getInterestGainOverMonth\"(\"contractID\", '$year', '$month') > '0.00'
					order by \"contractID\"");

$j = 2;

while($res=pg_fetch_array($sql))
{

	$contractID = $res["contractID"]; // เลขที่สัญญา
	$newInterest = $res["newInterest"]; // ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด ของเดือนและปีที่เลือก
				
	//หาชื่อลูกค้า
	$sqlcus = pg_query("SELECT thcap_fullname from\"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
	list($fullname) = pg_fetch_array($sqlcus);

	$i = $j - 1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $contractID);
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $fullname);	
	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $newInterest);

	$j++;
}
$i = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle('รายงานดอกเบี้ยประจำเดือน');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_cap&int.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>