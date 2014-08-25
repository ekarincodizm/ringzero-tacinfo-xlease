<?php
include("../../../config/config.php"); 
include ("../../../Classes/PHPExcel.php");
include("../../function/nameMonth.php");

$strings = $_GET["condition"];
$option = $_GET["option"];
$datecon = $_GET["datecon"];
$opstatus = $_GET["opstatus"];
if($datecon == ""){
	$datecon = nowDate();
}

if($strings=="bankChqDate"){
	$txtcon="แสดงตามวันที่บนเช็ค";
}else{
	$txtcon="แสดงตามวันที่นำเช็คเข้าธนาคาร";
}
if($option == 'day'){
	$condition = " date(a.\"$strings\") = '$datecon' ";
	list($year1,$month1,$day1) = explode("-",$datecon);
	$year1 = $year1+543;
	$monthth = nameMonthTH($month1);
	$txtshow = "$txtcon ของวันที่ ".$day1." ".$monthth." ".$year1;
}else if($option == 'year'){
	$yy = $_GET["yy"];
	$yyth = $yy+543;
	$condition = " EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
	$txtshow = "$txtcon ของปี ".$yyth;
}else{
	$yy = $_GET["yy"];
	$mm = $_GET["mm"];
	$monthth = nameMonthTH($mm);
	$yyth = $yy+543;
	$condition = " EXTRACT(MONTH FROM a.\"$strings\") = '$mm' AND EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
	$txtshow = "$txtcon ของเดือน ".$monthth." ปี ".$yyth;
}
if($opstatus != ""){
	$conditionstatus = "AND \"namestatus\" = '$opstatus'";
	$txtstatus = 'แสดงเฉพาะ : '.$opstatus;	
}


$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'รายงานเช็ค');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2', $txtshow);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('C2', $txtstatus);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'ชื่อ-นามสกุล ลูกค้า');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'เลขที่เช็ค');
$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'วันที่บนเช็ค');
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'ธนาคารที่ออกเช็ค');
$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'จ่ายบริษัท');
$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'ยอดเช็ค(บาท)');
$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'ผู้นำเช็คเข้าธนาคาร');
$objPHPExcel->getActiveSheet()->SetCellValue('I3', 'ธนาคารที่นำเข้า');
$objPHPExcel->getActiveSheet()->SetCellValue('J3', 'วันนำเช็คเข้าธนาคาร');
$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'วันที่เงินเข้าธนาคาร');
$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'สถานะ');



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);


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



$sql2012 = pg_query("SELECT a.*,b.* FROM \"finance\".\"V_thcap_receive_cheque_chqManage\" a left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
					 where $condition $conditionstatus");

$j = 4;

while($re_selcol=pg_fetch_array($sql2012))
{
									$revChqToCCID = $re_selcol["revChqToCCID"];
									$chqKeeperID = $re_selcol["chqKeeperID"];
									$revChqID = $re_selcol["revChqID"];
									$bankChqNo=$re_selcol["bankChqNo"];
									$revChqDate = $re_selcol["revChqDate"]; 
									$bankName = $re_selcol["bankName"]; 
									$bankOutBranch = $re_selcol["bankOutBranch"]; 
									$bankChqToCompID = $re_selcol["bankChqToCompID"]; 
									$bankChqAmt = $re_selcol["bankChqAmt"]; 
									$revChqStatus=$re_selcol["revChqStatus"];
									$bankChqDate=$re_selcol["bankChqDate"];
									//$giveTakerToBankAcc=$re_selcol["giveTakerToBankAcc"];
									$giveTakerID=$re_selcol["giveTakerID"];
									$bankRevResult=$re_selcol["bankRevResult"];
									$chqstampdate=$re_selcol["giveTakerDate"];
									$status=$re_selcol["namestatus"];
									$BID=$re_selcol["BID"];
									
									//ตรวจสอบว่ารออนุมัติคืนลูกค้าอยู่หรือไม่
									// $qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
									// $numchkapp=pg_num_rows($qrychkapp);
									// if($numchkapp>0){
										// $status="อยู่ระหว่างรอขอคืนลูกค้า";
									// }
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);									
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($userfullname) = pg_fetch_array($qry_username);
									
									
									//หาชื่อธนาคาร
									if($BID!=""){
										$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
										list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);					
									}
									
									//หาวันที่เงินเข้าธนาคาร โดยวันที่นำมาจากตาราง finance.thcap_receive_transfer column "bankRevStamp"
									$qrydate=pg_query("SELECT date(\"bankRevStamp\") FROM finance.thcap_receive_transfer WHERE \"revChqID\"='$revChqID' AND \"revTranStatus\" in ('1','6')");
									list($bankRevStamp)=pg_fetch_array($qrydate);
									if($bankRevStamp==""){
										$bankRevStamp="-";
									}
									
									
									if($userfullname == ""){ $userfullname = '-'; }
									if($ourbankname == ""){ $ourbankname = '-'; }
									if($BAccount == ""){ $BAccount = '-'; }
									if($chqstampdate == ""){ $chqstampdate = '-'; }		
		
		$i = $j - 1;		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $revChqToCCID);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $fullname);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $bankChqNo);	
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $bankChqDate);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $bankName);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $bankChqToCompID);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $bankChqAmt);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $userfullname);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $ourbankname."-".$BAccount);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $chqstampdate);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $bankRevStamp);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $status);
		$j++;
		unset($ourbankname);
		unset($BAccount);
}		
							

$i = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G4:G".$i.")");


// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle('(THCAP)รายงานเช็ค ');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_chqeue_report.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>