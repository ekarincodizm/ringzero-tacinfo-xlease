<?php
session_start();
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
include("../function/nameMonth.php");

$acctype = pg_escape_string($_GET['acctype']);
$option =  pg_escape_string($_GET['option']);
$nowdate = nowDate();

if($option==1){//เมื่อเลือก วันที่นำเงินเข้าธนาคาร
	$datepicker = pg_escape_string($_GET['datepicker']);
	$condition = "AND date(\"bankRevStamp\")='$datepicker' ";
	$txthead="$datepicker";
}else if($option==2){//เมื่อเลือก เดือน-ปี ที่นำเงินเข้าธนาคาร
	$yy = pg_escape_string($_GET["yy"]);
	$mm = pg_escape_string($_GET["mm"]);
	$month=nameMonthTH($mm);
	$condition = "AND EXTRACT(MONTH FROM \"bankRevStamp\") = '$mm' AND EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="เดือน $month ปี ค.ศ.$yy";
}else if($option==3){//เมื่อเลือก ปี ที่นำเงินเข้าธนาคาร
	$yy = pg_escape_string($_GET["yy"]);
	$condition = " AND EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="ปี ค.ศ.$yy";
}

$acctypeloop = explode("@",$acctype);
$bankname="";
for($loop = 0;$loop<sizeof($acctypeloop);$loop++){
	if($acctypeloop[$loop] != "" ){
		$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctypeloop[$loop]'");
		while($re_acc = pg_fetch_array($qry_acc)){
			$BAccount2 = $re_acc['BAccount'];
			$BName2 = $re_acc['BName'];
			$bankname="$BAccount2-$BName2";
		}	
		if(sizeof($acctypeloop)==1){ //กรณีมีแค่ 1 ธนาคารที่เลือก
			$txtbank=$bankname;
		}else{
			if($loop==sizeof($acctypeloop)-1){
				$txtbank=$txtbank.$bankname;
			}else{
				$txtbank=$txtbank.$bankname.", ";
			}
		}
	}
}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->SetCellValue('A1','(THCAP) รายงานเงินโอน');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2','วันที่นำเงินเข้าธนาคาร: '.$txthead);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A3','บัญชี: '.$txtbank);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A4','ออกรายงานวันที่ :'.$nowdate);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'ธนาคาร');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'รหัสรายการเงินโอน');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'ประเภทการนำเข้า');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'สถานะการอนุมัติ');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'เลขที่บัญชี');
$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'รหัสสาขาที่โอน');
$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'จำนวนเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('H5', 'วันเวลาที่บันทึกรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'ผู้ตรวจสอบด้านบัญชี');
$objPHPExcel->getActiveSheet()->SetCellValue('J5', 'สถานะการตรวจสอบฝ่ายบัญชี');
$objPHPExcel->getActiveSheet()->SetCellValue('K5', 'ผู้ตรวจสอบด้านการเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('L5', 'สถานะการตรวจสอบฝ่ายการเงิน');
$objPHPExcel->getActiveSheet()->SetCellValue('M5', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('N5', 'รหัสเช็ค');



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);


$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);

$j = 6;

//วนแสดงตามธนาคารที่เลือก
for($loop = 0;$loop<sizeof($acctypeloop);$loop++){
	
	if($acctypeloop[$loop] != "" ){
		//หาบัญชีและชื่อของธนาคาร
		$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctypeloop[$loop]'");
		if($re_acc = pg_fetch_array($qry_acc)){
			$BAccount = $re_acc['BAccount'];
			$BName = $re_acc['BName'];
			$bankname2="$BAccount-$BName";
			$i = 1;
		}	
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $bankname2);		
				
		//รายละเอียดเงินโอนเรียงตามธนาคารที่  user เลือกให้แสดง
		$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"bankRevAccID\" = '$acctypeloop[$loop]' $condition ORDER BY \"revTranID\" ASC");
		$nubrows = pg_num_rows($query);	
		if($nubrows!=0){						
			while($resvc=pg_fetch_array($query)){
				$revTranID = $resvc['revTranID'];
				$cnID = $resvc['cnID'];
				$revTranStatus = $resvc['revTranStatus'];
				$appvXStatus = $resvc['appvXStatus'];
				$appvYStatus = $resvc['appvYStatus'];
				$txtstatus=$resvc['namestatus'];
				$BAccount = $resvc['BAccount'];
				$bankRevBranch = trim($resvc['bankRevBranch']);
				$bankRevStamp = trim($resvc['bankRevStamp']);
				$bankRevAmt = trim($resvc['bankRevAmt']);
				$doerStamp = $resvc['doerStamp'];				
				$fullnameX = $resvc['fullnameX'];
				$fullnameY = $resvc['fullnameY'];
				$contractID = $resvc['contractID']; // เลขที่สัญญา
				$revChqID = $resvc['revChqID']; // รหัสเช็ค
				
				if($fullnameX == ""){ $fullnameX = "-"; }
				if($fullnameY == ""){ $fullnameY = "-"; }
				
				if($appvXStatus==""){
					$appvXStatus=9;
				}else{
					$appvXStatus=$appvXStatus;
				}
				
				if($appvXStatus==9){
					$txtx="รออนุมัติ";
				}else if($appvXStatus==0){
					$txtx="ไม่อนุมัติ";
				}else if($appvXStatus==1){
					$txtx="อนุมัติ";
				}
				
				$appvYStatus = $resvc['appvYStatus'];
				if($appvYStatus==""){
					$appvYStatus=9;
				}else{
					$appvYStatus=$appvYStatus;
				}
				if($appvYStatus=="9"){
					$txty="รออนุมัติ";
				}else if($appvYStatus==0){
					$txty="ไม่อนุมัติ";
				}else if($appvYStatus==1){
					$txty="อนุมัติ";
				}
				$tranActionID = $resvc['tranActionID'];
				
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $revTranID);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $cnID);	
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $txtstatus);
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $BAccount);
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $bankRevBranch);
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $bankRevStamp);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $bankRevAmt);
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $fullnameX);
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $txtx);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $fullnameY);
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $txty);
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$j, $contractID);
					$objPHPExcel->getActiveSheet()->SetCellValue('N'.$j, $revChqID);
				
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
				$sumbankRevAmt += $bankRevAmt;
				$j++;
			}	
		}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, '-');	
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$j, '-');
					$objPHPExcel->getActiveSheet()->SetCellValue('N'.$j, '-');
					$j++;

		}		
	}
}	

$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "รวมทั้งหมด");
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $sumbankRevAmt);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);





// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle('รายงานเงินโอน');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานเงินโอน.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>