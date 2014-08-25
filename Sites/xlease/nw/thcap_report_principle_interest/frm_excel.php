<?php
session_start();
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contype = pg_escape_string($_GET["contype"]);

$checkoption = pg_escape_string($_GET["op1"]);
	
IF($checkoption == 'my')
{
	$month = pg_escape_string($_GET["month"]);
	$year = pg_escape_string($_GET["year"]);
	$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$month' and EXTRACT(YEAR FROM \"receiveDate\") = '$year'";
	$txtheader = 'ประจำเดือน ';
	$show_month = nameMonthTH($month);
	
	// แสดงตามประเภทสัญญาที่เลือก
	if($contype != "")
	{
		$whereContype = str_replace("@","' or \"conType\" = '",$contype);
		$where .= " and (\"conType\" = '$whereContype')";
	}
}
else if($checkoption == 'y')
{
	$year = pg_escape_string($_GET["year"]);
	$where = " EXTRACT(YEAR FROM \"receiveDate\") = '$year'";
	$txtheader = 'ประจำปี  ';
	
	// แสดงตามประเภทสัญญาที่เลือก
	if($contype != "")
	{
		$whereContype = str_replace("@","' or \"conType\" = '",$contype);
		$where .= " and (\"conType\" = '$whereContype')";
	}
}


$show_yy = $year+543;
$datwshow = $txtheader.$show_month." ".$show_yy;

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);


$objPHPExcel->getActiveSheet()->SetCellValue('A1', '(THCAP)รายงานเงินต้นดอกเบี้ยรับ');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', $datwshow);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'วันที่รับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'เลขที่ใบเสร็จ');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'จำนวนเงินที่รับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'เงินต้นรับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'ดอกเบี้ยรับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'ปีลูกหนี้');

$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);


	$sql2="
		SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"receiveAmount\",\"receivePriciple\",\"receiveInterest\",\"conType\"
		FROM \"thcap_temp_int_201201\" a
		LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
		WHERE $where AND \"isReceiveReal\" = '1'

		union

		SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"debt_cut\",\"priciple_cut\",\"interest_cut\",\"conType\"
		FROM \"account\".\"thcap_acc_filease_realize_eff_present\" a
		LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
		WHERE $where

		order by \"conType\", \"contractID\", \"receiptID\"
	";
	$dbquery2=pg_query($sql2);
	$j = 6;
	$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมทั้งหมด
	$sumPricipleAll = 0; // เงินต้นรับชำระ รวมทั้งหมด
	$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมทั้งหมด
	$sumAmountOne += $receiveAmount; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
	$sumPricipleOne += $receivePriciple; // ผลรวม เงินต้นรับชำระ ของแต่ละหน้า
	$sumInterestOne += $receiveInterest; // ผลรวม ดอกเบี้ยรับชำระ ของแต่ละหน้า
	while($res=pg_fetch_array($dbquery2)){
				
				$receiveDate = $res["DATEE"]; // วันที่รับชำระ
				$contractID = $res["contractID"]; // เลขที่สัญญา
				$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
				$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
				$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
				$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
				
				$qry_chk_con_year = pg_query("select \"thcap_get_contractYear\"('$contractID') ");
				$chk_con_year = pg_fetch_result($qry_chk_con_year,0);	
				
				$sumAmountOne += $receiveAmount; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
				$sumPricipleOne += $receivePriciple; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
				$sumInterestOne += $receiveInterest; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
				
				$sumAmountAll += $receiveAmount; // จำนวนเงินที่รับชำระ รวมทั้งหมด
				$sumPricipleAll += $receivePriciple; // เงินต้นรับชำระ รวมทั้งหมด
				$sumInterestAll += $receiveInterest; // ดอกเบี้ยรับชำระ รวมทั้งหมด
												
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $receiveDate);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $receiptID);	
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $receiveAmount);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $receivePriciple);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $receiveInterest);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $chk_con_year);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$j++;
	}		
		
$objPHPExcel->getActiveSheet()->getStyle('C')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$i = $j - 1;
$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D2:D".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E2:E".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F2:F".$i.")");

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานเงินต้นดอกเบี้ยรับ('.$year.'-'.$month.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>