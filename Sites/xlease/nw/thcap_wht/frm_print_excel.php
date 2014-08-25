<?php
include("../../config/config.php");
include("../function/nameMonth.php");
include ("../../Classes/PHPExcel.php");
$yy = $_GET['year'];
$mm = $_GET['month'];



$show_month = nameMonthTH($mm);
$show_yy = $yy+543;

if($yy != "" && $mm != ""){
	$datwshow = "ประจำเดือน ".$show_month." ปี ".$show_yy;
}else if($yy != "" && $mm == ""){
	$datwshow = "ประจำปี ".$show_yy;
}else{
	$datwshow = "แสดงรายการทั้งหมด";
}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->SetCellValue('A1','(THCAP) รายงานรับใบภาษีหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2',$datwshow);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'เลขที่ใบเสร็จ');
$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'วันที่รับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'จำนวนเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'เลขที่ใบภาษีหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'จำนวนเงินภาษีหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'สถานะ');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);

	if($yy != "" && $mm != ""){
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		where EXTRACT(MONTH FROM \"receiveDate\") = '$mm' and EXTRACT(YEAR FROM \"receiveDate\") = '$yy' AND \"CusState\"=0");
	}else if($yy != "" && $mm == ""){		
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		where EXTRACT(YEAR FROM \"receiveDate\") = '$yy' AND \"CusState\"=0");
	}else{
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		WHERE \"CusState\"=0");
	}

	$j = 4;

	while($res_in=pg_fetch_array($qry_in)){
				

		//หาสถานะ
			if($res_in['recUser'] == ""){ $status='ยังไม่ได้รับ'; }else{ $status='ได้รับแล้ว'; }	
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $res_in['contractID']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $res_in['thcap_fullname']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $res_in['receiptID']);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $res_in['receiveDate']);	
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $res_in['sumdebtAmt']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $res_in['whtRef']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $res_in['sumWht']);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $status);

				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$j++;

				
	}		
		
	
		
		
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$i = $j - 1;
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E4:E".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G4:G".$i.")");
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานรับใบภาษีหัก ณ ที่จ่าย.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>