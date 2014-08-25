<?php
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");

// todo
// ปัจจุบันไฟล์นี้ไม่อัพเดทตามเมนู ให้แก้ไขอีกครั้งก่อนนำไปใช้

$searchPoint= pg_escape_string($_GET["date"]);
list($yy,$mm) = explode("-",$searchPoint);
$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";
$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];
$show_yy = $yy+543;
$datwshow = $show_month.$show_yy;

$sql2="	SELECT \"dcNoteDate\", a.\"dcNoteID\", \"contractID\", \"dcNoteDescription\", \"dcNoteAmtNET\", \"dcNoteAmtVAT\", \"dcNoteAmtALL\"
		FROM account.\"thcap_dncn\" a, account.\"thcap_dncn_details\" b
		WHERE a.\"dcNoteID\" = b.\"dcNoteID\" AND cast(\"dcNoteDate\" as varchar) like '$searchPoint%' AND \"dcNoteAmtVAT\" > '0.00'
		ORDER BY \"dcNoteDate\"";

$header = "(THCAP)รายงานภาษีซื้อ";

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'วันที่ภาษี');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'เลขที่ใบกำกับภาษี (หรือใบสำคัญ)');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'เลขที่สัญญา (ถ้ามี)');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ชื่อผู้ออกใบกำกับภาษี');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'รายละเอียดรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'จำนวนเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ภาษีมูลค่าเพิ่ม');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'จำนวนเงินรวม');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

$dbquery2=pg_query($sql2);
$j = 2;
while($rs2=pg_fetch_assoc($dbquery2))
{
	$i = $j - 1;		
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $rs2['dcNoteDate']);
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $rs2['dcNoteID']);
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $rs2['contractID']);	
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, 'บริษัท ไทยเอซ แคปปิตอล จำกัด');
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $rs2['dcNoteDescription']);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $rs2['dcNoteAmtNET']);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $rs2['dcNoteAmtVAT']);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $rs2['dcNoteAmtALL']);
	$j++;
}

$i = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F2:F".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G2:G".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H2:H".$i.")");

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$header.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>