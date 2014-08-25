<?php
session_start();
set_time_limit(60);
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$type = $_GET["type"]; // ประเภท
$Sdate = $_GET["Sdate"]; // วันที่เริ่ม
$Edate = $_GET["Edate"]; // วันที่สิ้นสุด
$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก
$whereContract = $_GET["whereContract"]; // เลขที่สัญญา
$selectStyle = $_GET["selectStyle"]; // รูปแบบการแสดง

$nameMonthTH = nameMonthTH($month);
$yearTH = $year+543;

if($type == "year"){$datwshow="ประจำปี พ.ศ. $yearTH";}
if($type == "month"){$datwshow="เดือน $nameMonthTH ปี พ.ศ. $yearTH";}
if($type == "between"){$datwshow="$Sdate ถึง $Edate";}

if($selectStyle == "allStyle"){$selectStyleText = "แสดงการตั้งหนี้ทั้งหมด";}
elseif($selectStyle == "receiptStyle"){$selectStyleText = "แสดงเฉพาะที่ออกโดยใบเสร็จ";}
elseif($selectStyle == "autoStyle"){$selectStyleText = "แสดงเฉพาะที่สร้างอัตโนมัติโดยระบบ";}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->SetCellValue('A1','(THCAP) รายงานตั้งหนี้ดอกเบี้ย');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2',$selectStyleText);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A3',$datwshow);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'วันที่ตั้งหนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'ชื่อผู้กู้หลัก');
$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'เงินต้น');
$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'อัตราดอกเบี้ย');
$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'วันที่เริ่มคิดดอกเบี้ยรายการนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'วันที่สิ้นสุดการคิดดอกเบี้ยรายการนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'จำนวนวันที่คิดดอกเบี้ยเพิ่ม');
$objPHPExcel->getActiveSheet()->SetCellValue('I4', 'โดย');
$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'จำนวนดอกเบี้ยที่ถูกตั้ง');


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);


$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J4')->getFont()->setBold(true);

if($whereContract != "")
{
	$where_other = "and \"contractID\" = '$whereContract' ";
}
else
{
	$where_other = "";
}

if($selectStyle == "receiptStyle")
{
	$where_other .= "and \"isReceiveReal\" > '0' ";
}
elseif($selectStyle == "autoStyle")
{
	$where_other .= "and \"isReceiveReal\" = '0' ";
}

if($type == "between")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and \"genDate\" >= '$Sdate'
						and \"genDate\" <= '$Edate'
						$where_other
						order by \"genDate\" ");
}
elseif($type == "month")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,6,2) = '$month'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						$where_other
						order by \"genDate\" ");
}
elseif($type == "year")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						$where_other
						order by \"genDate\" ");
}

	
	$a=1;
	$j = 5;
	$allNewInterest = 0; // ยอดรวมทั้งหมด
	$sunNewInterestForMonth = 0; // ยอดรวมของแต่ละเดือน
	while($res=pg_fetch_array($qry)){
				
				$genDate = $res["genDate"]; // วันที่ตั้งหนี้
				$contractID = $res["contractID"]; // เลขที่สัญญา
				$MainCusName = $res["MainCusName"]; // ชื่อผู้กู้หลัก
				$lastPrinciple = $res["lastPrinciple"]; // เงินต้น
				$interestRate = $res["interestRate"]; // อัตราดอกเบี้ย
				$startIntDate = $res["startIntDate"]; // วันที่เริ่มคิดดอกเบี้ยรายการนี้
				$endIntDate = $res["endIntDate"]; //วันที่สิ้นสุดการคิดดอกเบี้ยรายการนี้
				$numIntDays = $res["numIntDays"]; // จำนวนวันที่คิดดอกเบี้ยเพิ่ม
				$isReceiveReal = $res["isReceiveReal"]; // ถ้า isReceiveReal > 0 คือ ด้วยใบเสร็จ = 0 คือด้วยระบบ
				$newInterest = $res["newInterest"]; // จำนวนดอกเบี้ยที่ถูกตั้ง
				
				$allNewInterest += $newInterest; // ยอดรวมทั้งหมด
				
				if($a == 1){$nowMonth = substr($genDate,5,2);}
				
				if($isReceiveReal == 0)
				{
					$txt_isReceiveReal = "สร้างอัตโนมัติโดยระบบ";
				}
				elseif($isReceiveReal > 0)
				{
					$txt_isReceiveReal = "ออกโดยใบเสร็จ";
				}
				else
				{
					$txt_isReceiveReal = "";
				}
				
				// ถ้าเลือกแบบ ปี ให้แสดงยอดรวมของแต่ละเดือนด้วย
				if($type == "year" && $nowMonth != substr($genDate,5,2))
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j,"ผลรวมของเดือน ".nameMonthTH($nowMonth));
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $sunNewInterestForMonth);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);			
					$sunNewInterestForMonth = 0;
					$j++;
					$a = 1;
				}else{
					$a += 1;
				}
												
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $genDate);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $MainCusName);	
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $lastPrinciple);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $interestRate);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $startIntDate);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $endIntDate);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $numIntDays);
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $txt_isReceiveReal);
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $newInterest);
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
				$j++;
				$sunNewInterestForMonth += $newInterest; // ยอดรวมของแต่ละเดือน
				
	}		
		
	if($type == "year")
	{
		$nowMonth != substr($genDate,5,2);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j,"ผลรวมของเดือน ".nameMonthTH($nowMonth));
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $sunNewInterestForMonth);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);			
		$sunNewInterestForMonth = 0;
		$j++;
	}
		
		
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$i = $j - 1;
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "รวมทั้งหมด");
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $allNewInterest);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานเงินต้นดอกเบี้ยรับ.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>