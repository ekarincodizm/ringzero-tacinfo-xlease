<?php
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");

$searchPoint= $_GET["date"];
$cancel = $_GET["cancel"]; //หากมีค่าเป็น 't' แสดงว่าให้แสเงข้อมูลของใบกำกับที่ถูกยกเลิกแล้ว
list($yy,$mm) = explode("-",$searchPoint);
$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";
$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];
$show_yy = $yy+543;
$datwshow = $show_month.$show_yy;

IF($cancel == 't'){
		$sql2="			SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay_cancel\" 
									where cast(\"taxpointDate\" as varchar) like '$searchPoint%'
									ORDER BY \"taxpointDate\"
						";
		$header = "(THCAP)รายงานภาษีขายที่ถูกยกเลิก";				
}else{   
		$sql2="			SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay\" 
									where cast(\"taxpointDate\" as varchar) like '$searchPoint%'
									ORDER BY \"taxpointDate\"
						";
		$header = "(THCAP)รายงานภาษีขาย";				
}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'วันที่ออก');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'เลขที่ใบกำกับภาษี');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ชื่อผู้กู้หลัก');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'รหัสรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'รายละเอียดรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'จำนวนเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ภาษีมูลค่าเพิ่ม');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'รวมรับชำระ');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);



	$dbquery2=pg_query($sql2);
	$j = 2;
	while($rs2=pg_fetch_assoc($dbquery2)){
									
				$i = $j - 1;		
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $rs2['taxpointDate']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $rs2['taxinvoiceID']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $rs2['contractID']);	
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $rs2['cusFullname']);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $rs2['typePayID']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $rs2['tpDesc']." ".$rs2['tpFullDesc']." ".$rs2['typePayRefValue']);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $rs2['netAmt']);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $rs2['vatAmt']);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $rs2['debtAmt']);
				$j++;
	}		
		

$i = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G2:G".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H2:H".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I2:I".$i.")");

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$header.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>