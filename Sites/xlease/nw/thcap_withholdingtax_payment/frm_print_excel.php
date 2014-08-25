<?php
include("../../config/config.php");
include("../function/nameMonth.php");
include ("../../Classes/PHPExcel.php");
$datetime=nowDateTime();
$yy = pg_escape_string($_GET['year']);
if($yy==""){
	$yy = date('Y');	
}
$mm = pg_escape_string($_GET['month']);

$income_tax = pg_escape_string($_GET['income_tax']);

if($income_tax !=""){
	$income=$income_tax;
	$condition=" AND \"fromChannelRef\"='$income_tax'";
}
else{
	$income="ทั้งหมด";
	$condition="";
}

$id_user=$_SESSION["av_iduser"];
//ผู้พิมพ์
$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);

$show_month = nameMonthTH($mm);

if($yy != "" && $mm != ""){
	$datwshow = "ภงด .".$income." ประจำเดือน ".$show_month." ปี ".$yy;
	$datwshow_title="ประจำเดือน ".$show_month." ปี ".$yy;
}else if($yy != "" && $mm == ""){
	$datwshow = "ภงด .".$income." ประจำปี ".$yy;
	$datwshow_title="ประจำปี ".$yy;
}else{
	$datwshow = "แสดงรายการทั้งหมด";
}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->SetCellValue('A1','(THCAP) รายงานจ่ายใบภาษีหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2',$datwshow);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'เลขที่ voucher');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'วันที่มีผล');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'ประเภท ภงด.');
$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'ประเภทเอกสาร');
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'รหัสอ้างอิงตามประเภท');
$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'เลขอ้างอิงของรายละเอียด');
$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'จำนวนเงินที่จ่ายออก');
$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'จำนวนเงินที่จ่ายออก-รับเข้า(เฉพาะภาษีมูลค่าเพิ่ม)');
$objPHPExcel->getActiveSheet()->SetCellValue('I3', 'จำนวนเงินที่จ่ายออก-รับเข้า(ยอดรวมภาษีมูลค่าเพิ่ม)');
$objPHPExcel->getActiveSheet()->SetCellValue('J3', 'จำนวนเงินภาษีหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'เลขที่อ้างอิงใบหัก ณ ที่จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'ผู้ทำรายการ');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);

	if($yy != "" && $mm != ""){		
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(MONTH FROM \"voucherDate\") = '$mm' AND EXTRACT(YEAR FROM \"voucherDate\") = '$yy' $condition ");
		
	}else if($yy != "" && $mm == ""){
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\"
		WHERE EXTRACT(YEAR FROM \"voucherDate\") = '$yy' $condition ");
		
	}else{
		$qry_in=pg_query("SELECT * FROM \"v_thcap_withholdingtax_payment\" ");
			
	}
	$j = 4;
	$icount=0;
	while($res_in=pg_fetch_array($qry_in)){
		$icount+=1;
		//กรณีที่ เลขที่ voucher เหมือนกัน จะแสดงสี เหมือนกัน
		if($icount==1){
			$voucherID_old=$res_in['voucherID'];
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $res_in['voucherID']);
		}
		else{
			if($voucherID_old==$res_in['voucherID']){}
			else{
				$voucherID_old=$res_in['voucherID'];
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $res_in['voucherID']);
			}
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $res_in['voucherDate']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $res_in['fromChannelRef']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $res_in['voucherRefType']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $res_in['voucherRefValue']);	
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $res_in['voucherThisDetailsRef']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $res_in['netAmt']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $res_in['vatAmt']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $res_in['sumAmt']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $res_in['whtAmt']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $res_in['whtRef']);	
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $res_in['doerFull']);

		$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$j++;
	}		
		
	
		
		
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$i = $j - 1;
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G4:G".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H4:H".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I4:I".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J4:J".$i.")");
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow_title);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานจ่ายใบภาษีหัก ณ ที่จ่าย.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>