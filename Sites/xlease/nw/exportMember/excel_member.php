<?php
//ideafunction.com
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
$cid=$_POST["cid"];

// สร้าง object ของ Class  PHPExcel  ขึ้นมาใหม่
$objPHPExcel = new PHPExcel();
 
// กำหนดค่าต่างๆ
$objPHPExcel->getProperties()->setCreator("www.ideafunction.com");
$objPHPExcel->getProperties()->setLastModifiedBy("www.ideafunction.com");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
 
// เพิ่มข้อมูลเข้าใน Cell
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'RUNNING');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'IDNO');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'TranIDRef1');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'TranIDRef2');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Installment');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'FIRSTNAME');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'LASTNAME');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CARREGIS');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'CARYEAR');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'RADIORENTAL');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'DUEDATE');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'DUETAX');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

$j=2;
for($i=0;$i<sizeof($cid);$i++){ 
	$qry=pg_query("SELECT f.\"cardSerial\",a.\"IDNO\", a.\"TranIDRef1\", a.\"TranIDRef2\",
	a.\"P_MONTH\",a.\"P_VAT\",a.\"P_FDATE\",b.\"A_FIRNAME\",b.\"A_NAME\",b.\"A_SIRNAME\",
	c.\"C_REGIS\",c.\"C_TAX_ExpDate\",c.\"C_YEAR\",d.\"car_regis\",d.\"car_year\"
		FROM \"Fp_membercard\" f
		left join \"Fp\" a on f.\"IDNO\"=a.\"IDNO\"
		left join \"Fa1\" b on a.\"CusID\"=b.\"CusID\"
		left join \"VCarregistemp\" c on a.\"IDNO\"=c.\"IDNO\"
		left join \"FGas\" d on a.asset_id=d.\"GasID\"
		where f.\"IDNO\"='$cid[$i]'
		order by \"cardSerial\" DESC limit (1)");
	if($result=pg_fetch_array($qry)){
		$a_firname = trim($result["A_FIRNAME"]);
		if($a_firname=="นาย" || $a_firname=="นาง" || $a_firname=="นางสาว" || $a_firname=="น.ส."){
			$txtfirname="คุณ";
		}else{
			$txtfirname=$result["A_FIRNAME"];
		}
		$FIRSTNAME = $txtfirname.$result["A_NAME"];
		$LASTNAME = $result["A_SIRNAME"];
	
		$TranIDRef1=strval($result["TranIDRef1"]);
		$TranIDRef2=strval($result["TranIDRef2"]);
	
		$installment=$result["P_MONTH"]+$result["P_VAT"];
		$duedate=substr($result["P_FDATE"],8,2);
		$mm=substr($result["C_TAX_ExpDate"],5,2);
		$dd=substr($result["C_TAX_ExpDate"],8,2);
		if($mm=="01"){
			$txtm="มกราคม";
		}else if($mm=="02"){
			$txtm="กุมภาพันธ์";
		}else if($mm=="03"){
			$txtm="มีนาคม";
		}else if($mm=="04"){
			$txtm="เมษายน";
		}else if($mm=="05"){
			$txtm="พฤษภาคม";
		}else if($mm=="06"){
			$txtm="มิถุนายน";
		}else if($mm=="07"){
			$txtm="กรกฎาคม";
		}else if($mm=="08"){
			$txtm="สิงหาคม";
		}else if($mm=="09"){
			$txtm="กันยายน";
		}else if($mm=="10"){
			$txtm="ตุลาคม";
		}else if($mm=="11"){
			$txtm="พฤศจิกายน";
		}else if($mm=="12"){
			$txtm="ธันวาคม";
		}
		$duetax=$dd." $txtm";
		
		$C_REGIS = $result["C_REGIS"];
		if($C_REGIS==""){
			$car_regis = $result["car_year"];
		}else{
			$car_regis = $C_REGIS;
		}
		
		$C_YEAR = $result["C_YEAR"];
		if($C_YEAR==""){
			$CARYEAR = $result["car_regis"];
		}else{
			$CARYEAR = $C_YEAR;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $result["cardSerial"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $result["IDNO"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, " $TranIDRef1");
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, " $TranIDRef2");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $installment);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $FIRSTNAME);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $LASTNAME);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $car_regis);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $CARYEAR);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, '343');
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $duedate);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $duetax);

		$j++;
	}
}

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle('ออกบัตรสมาชิก');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_member.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>
