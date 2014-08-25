<?php
session_start();
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$checkoption = pg_escape_string($_GET["op1"]);
$selectSort = pg_escape_string($_GET["sort"]); // การเรียงข้อมูลที่เลือก
	
	IF($checkoption == 'my'){
		$month = pg_escape_string($_GET["month"]);
		$year = pg_escape_string($_GET["year"]);
		$whereOther = " and EXTRACT(MONTH FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$month' and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$year'";
		$txtheader = 'ประจำเดือน ';
		$show_month = nameMonthTH($month);
	
	}else if($checkoption == 'y'){
		$year = pg_escape_string($_GET["year"]);
		$whereOther = " and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$year'";
		$txtheader = 'ประจำปี ค.ศ. ';
	}
	
	if($selectSort == "s1")
	{
		$mySort = "order by \"contractID\"";
	}
	elseif($selectSort == "s2")
	{
		$mySort = "order by \"receiptID\"";
	}
	elseif($selectSort == "s3")
	{
		$mySort = "order by \"receiveDate\"";
	}
	elseif($selectSort == "s4")
	{
		$mySort = "order by \"typePayID\"";
	}
	elseif($selectSort == "s5")
	{
		$mySort = "order by \"tpDesc\"";
	}


// ปีที่จะแสดงในรายงาน
//$show_yy = $year+543;
$show_yy = $year;

$datwshow = $txtheader.$show_month." ".$show_yy;

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);


$objPHPExcel->getActiveSheet()->SetCellValue('A1', '(THCAP) รายงานภาษีธุรกิจเฉพาะ');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', $datwshow);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'เลขที่ใบเสร็จ');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'วันที่จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'รหัสค่าใช้จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'ประเภทค่าใช้จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'จำนวนเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'อัตราภาษีธุรกิจเฉพาะ');
$objPHPExcel->getActiveSheet()->SetCellValue('H5', 'จำนวนภาษีธุรกิจเฉพาะ');
$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'จำนวนภาษีโรงเรือน');

$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);


	$sql2="select \"thcap_receiptIDToContractID\"(a.\"receiptID\") as \"contractID\", a.\"typePayRefValue\", a.\"receiptID\", \"thcap_receiptIDToReceiveDate\"(a.\"receiptID\") as \"receiveDate\",
				a.\"debtID\", a.\"typePayID\", a.\"tpDesc\", a.\"netAmt\", b.\"curSBTRate\", 
				(a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
			from thcap_temp_receipt_otherpay a
			left join account.\"thcap_typePay\" b on a.\"typePayID\" = b.\"tpID\"
			left join thcap_temp_otherpay_debt c on a.\"debtID\" = c.\"debtID\"
			where b.\"curSBTRate\" is not null $whereOther
			
			union

			select d.\"contractID\", d.\"contractID\" as \"typePayRefValue\", d.\"receiptID\", d.\"receiveDate\",
				null as \"debtID\", e.\"tpID\" as \"typePayID\", e.\"tpDesc\", d.\"receiveInterest\" as \"netAmt\", e.\"curSBTRate\", 
				(d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
			from thcap_temp_int_201201 d
			left join account.\"thcap_typePay\" e on e.\"tpID\" = account.\"thcap_mg_getInterestType\"(d.\"contractID\")
			where e.\"curSBTRate\" is not null and d.\"isReceiveReal\" = '1' and d.\"receiptID\" is not null and d.\"receiveInterest\" > 0.00 $whereOther
			
			union
			
			select f.\"contractID\", f.\"typePayRefValue\", f.\"receiptID\", f.\"receiveDate\", f.\"debtID\", f.\"typePayID\", f.\"tpDesc\", f.\"netAmt\", f.\"curSBTRate\", f.\"businessTaxes\", f.\"localTaxes\"
			from \"v_thcap_receive_factoring_facfee\" f
			where f.\"curSBTRate\" is not null $whereOther

			union

			select g.\"contractID\", g.\"typePayRefValue\", g.\"receiptID\", g.\"receiveDate\", g.\"debtID\", g.\"typePayID\", g.\"tpDesc\", g.\"netAmt\", g.\"curSBTRate\", g.\"businessTaxes\", g.\"localTaxes\"
			from \"v_thcap_receive_factoring_interest\" g
			where g.\"curSBTRate\" is not null $whereOther
			
			$mySort
	";
	$dbquery2=pg_query($sql2);
	$j = 6;
	while($res=pg_fetch_array($dbquery2)){
				
				$contractID = $res["contractID"]; // เลขที่สัญญา
				$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
				$receiveDate = $res["receiveDate"]; // วันที่รับชำระ
				$typePayRefValue = $res["typePayRefValue"]; // เลขที่อ้างอิง
				$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
				$tpDesc = $res["tpDesc"]; // ชื่อประเภทค่าใช้จ่าย
				$netAmt = $res["netAmt"]; // จำนวนเงิน net
				$curSBTRate = $res["curSBTRate"]; // อัตราภาษีธุรกิจเฉพาะ
				$businessTaxes = $res["businessTaxes"]; // จำนวนภาษีธุรกิจเฉพาะ
				$localTaxes = $res["localTaxes"]; // จำนวนภาษีท้องถิ่น
												
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $contractID);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $receiptID);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $receiveDate);	
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $typePayID);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $tpDesc);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $netAmt);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $curSBTRate);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $businessTaxes);
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $localTaxes);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$j++;
	}		
		
$objPHPExcel->getActiveSheet()->getStyle('E')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$i = $j - 1;
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F6:F".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H6:H".$i.")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I6:I".$i.")");

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle($datwshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(THCAP)รายงานภาษีธุรกิจเฉพาะ('.$year.'-'.$month.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>