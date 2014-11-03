<?php
include("../../config/config.php");
include("../function/nameMonth.php");
include ("../../Classes/PHPExcel.php");

$user_report = $_SESSION["av_iduser"]; //user ที่ทำการออกรายการ
$date_report = nowDateTime(); //วันเวลาที่ออกรายการ 

$option = pg_escape_string($_GET["option"]);
$contype = pg_escape_string($_GET["contype"]);

//ค้นหาชื่อผู้ออกรายงาน
$qryname=pg_query("select fullname from \"Vfuser\" where id_user='$user_report'");
list($fullname)=pg_fetch_array($qryname);

$show_month="";
if($option=="day"){
	$datecon=$_GET["datecon"];
	$dateshow = "ประจำวันที่ $datecon";
	$condition="AND date(\"doerStamp\") = '$datecon' ";
}else if($option=="my"){
	$month=$_GET["month"];
	$year=$_GET["year"];
	$show_month = nameMonthTH($month);
	$dateshow = "ประจำเดือน $show_month  ปี ค.ศ.$year";
	
	$condition = "AND EXTRACT(MONTH FROM \"doerStamp\") = '$month' AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";
}else if($option=="year"){
	$year=$_GET["year"];
	$dateshow = "ประจำปี ค.ศ. $year";
	$condition = "AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";
}

$contypeqry="";
$txtcon="";
$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == "" ){
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = "(\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
				$txtcon="แสดงรายการที่อนุมัติและลูกค้ามีการจ่ายแล้ว";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = "(\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
				$txtcon="แสดงรายการที่อนุมัติและลูกค้ายังไม่ไ่ด้จ่าย";
			}else{
				$contypeqry = "\"dcNoteStatus\" = '$contype[$con]' ";
				if($contype[$con]==8){
					$txtcon="แสดงรายการระหว่างรออนุมัติ";
				}else if($contype[$con]==0){
					$txtcon="แสดงรายการที่ไม่อนุมัติ";
				}
			}
		}else{
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
				$txtcon=$txtcon.", แสดงรายการที่อนุมัติและลูกค้ามีการจ่ายแล้ว";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
				$txtcon=$txtcon.", แสดงรายการที่อนุมัติและลูกค้ายังไม่ได้จ่าย";
			}else{
				$contypeqry = $contypeqry."OR \"dcNoteStatus\" = '$contype[$con]' ";
				if($contype[$con]==8){
					$txtcon=$txtcon.", แสดงรายการระหว่างรออนุมัติ";
				}else if($contype[$con]==0){
					$txtcon=$txtcon.", แสดงรายการที่ไม่อนุมัติ";
				}
			}
		}		
	}
}

if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
	$condition = $condition.$contypeqry;
}

$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->SetCellValue('A1','(THCAP) รายงานส่วนลด');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->SetCellValue('A2',$dateshow);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A3',"เงื่อนไขรายงาน : $txtcon");
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'เลขที่ CN/DN');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'ชื่อผู้กู้หลัก/ผู้เช่าซื้อ');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'รหัสประเภทค่าใช้จ่าย');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'รายละเอียดหนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'เลขอ้างอิง');
$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'จำนวนหนี้แรกเริ่ม');
$objPHPExcel->getActiveSheet()->SetCellValue('H5', 'จำนวนหนี้เดิมล่าสุด');
$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'จำนวนหนี้ใหม่');
$objPHPExcel->getActiveSheet()->SetCellValue('J5', 'ผู้ทำรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('K5', 'วันเวลาทำรายการ');
$objPHPExcel->getActiveSheet()->SetCellValue('L5', 'ผู้อนุมัติ');
$objPHPExcel->getActiveSheet()->SetCellValue('M5', 'วันเวลาอนุมัติ');
$objPHPExcel->getActiveSheet()->SetCellValue('N5', 'สถานะ');

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

	
$qry = pg_query("SELECT  * FROM account.thcap_dncn_discount_report where \"dcType\" = '2' $condition ");
$row=pg_num_rows($qry);

$j = 6;
if($row>0){
	while($res=pg_fetch_array($qry))
	{
		$dcNoteID = $res["dcNoteID"]; // รหัส CreditNote หรือ DebitNote
		$conid = $res["contractID"];	//เลขที่สัญญา		
		$maincus_fullname = $res["maincus_fullname"]; //-- หาชื่อผู้กู้หลัก
		$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
		$tpdetail = $res["tpdetail"]; // รายละเอียดประเภทค่าใช้จ่าย
		$typePayRefValue = $res["typePayRefValue"];// หา Ref
		$netstart=number_format($res["netstart"],2)."(".number_format($res["vatstart"],2).")"; //จำนวนหนี้แรกเริ่ม
		$netbefore=number_format($res["netbefore"],2)."(".number_format($res["vatbefore"],2).")"; //จำนวนหนี้เดิืมล่าสุด
		$netnow=number_format($res["netnow"],2)."(".number_format($res["vatnow"],2).")"; //จำนวนหนี้ใหม่
		$doer_fullname=$res["doerName"]; //ชื่อผู้ขอ
		$doerStamp = $res["doerStamp"]; //วันที่ขอ
		$appv_fullname=$res["appvName"]; //ชื่อผู้อนุมัติ
		$appvStamp=$res["appvStamp"]; //วันเวลาที่อนุมัติ	
		$status = $res["statusname"];//สถานะการอนุมัติ
		$debtStatus = $res["debtStatus"];//สถานะการจ่าย
		
		if($debtStatus == 5)
		{
			$status = "อนุัมัติและลดหนี้เป็น 0.00";
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $dcNoteID);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $conid);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $maincus_fullname);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $typePayID);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $tpdetail);	
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $typePayRefValue);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $netstart);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $netbefore);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $netnow);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $doer_fullname);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $doerStamp);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $appv_fullname);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$j, $appvStamp);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$j, $status);
		$j++;			
	}		
}else{
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, "ไม่พบรายการ");
}	

$i=$j+2;
$j=$i+1;
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, "ผู้ออกรายงาน : $fullname");
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, "วันเวลาที่ออกรายงาน : $date_report");

// ตั้งชื่อ Sheet
//$objPHPExcel->getActiveSheet()->setTitle($dateshow);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="(thcap)รายงานส่วนลด.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>