<?php
//ideafunction.com
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");

$datepicker=$_GET["datepicker"];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง
//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อแสดงประเภทสัญญาที่แสดงบนหัวรายงาน
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypetxtshow == ""){
		$contypetxtshow = $contypechk[$con];
	}else{
		$contypetxtshow = $contypetxtshow.",".$contypechk[$con];
	}	
}


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

$objPHPExcel->getActiveSheet()->SetCellValue('A1', "รายงานอายุหนี้ AGING สิ้นเพียง $datepicker ตามช่วงวัน ประเภทสัญญา  $contypetxtshow");

$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ลำดับที่');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'รายชื่อลูกหนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'ไม่ค้างชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('E2', '01-30');
$objPHPExcel->getActiveSheet()->SetCellValue('F2', '31-60');
$objPHPExcel->getActiveSheet()->SetCellValue('G2', '61-90');
$objPHPExcel->getActiveSheet()->SetCellValue('H2', '91-120');
$objPHPExcel->getActiveSheet()->SetCellValue('I2', '121-150');
$objPHPExcel->getActiveSheet()->SetCellValue('J2', '151-180');
$objPHPExcel->getActiveSheet()->SetCellValue('K2', '181-210');
$objPHPExcel->getActiveSheet()->SetCellValue('L2', '211-240');
$objPHPExcel->getActiveSheet()->SetCellValue('M2', '241-270');
$objPHPExcel->getActiveSheet()->SetCellValue('N2', '271-300');
$objPHPExcel->getActiveSheet()->SetCellValue('O2', '301-330');
$objPHPExcel->getActiveSheet()->SetCellValue('P2', '331-360');
$objPHPExcel->getActiveSheet()->SetCellValue('Q2', 'เกินกว่า 360');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);

$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('N2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('O2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('P2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('Q2')->getFont()->setBold(true);

//วนตามประเภทสัญญาที่เลือก	
$sump1 = 0;	
$sump2 = 0;
$sump3 = 0;
$sump4 = 0;
$sump5 = 0;
$sump6 = 0;
$sump7 = 0;
$sump8 = 0;
$sump9 = 0;
$sump10 = 0;
$sump11 = 0;
$sump12 = 0;
$sump13 = 0;
$sump14 = 0;

$i=0;
$j=3;
for($con = 0;$con < sizeof($contypechk) ; $con++){	
	$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' ORDER BY \"contractID\" ASC");
	$numcontract=pg_num_rows($qrymg);

	
	$sumprinciple0 = 0;
	$sumprinciple = 0;
	$sumprinciple2 = 0;
	$sumprinciple3 = 0;
	$sumprinciple4 = 0;
	$sumprinciple5 = 0;
	$sumprinciple6 = 0;
	$sumprinciple7 = 0;
	$sumprinciple8 = 0;
	$sumprinciple9 = 0;
	$sumprinciple10 = 0;
	$sumprinciple11 = 0;
	$sumprinciple12 = 0;
	$sumprinciple13 = 0;
						
	while($result=pg_fetch_array($qrymg)){
		list($contractID,$conLoanAmt)=$result;
		
		// ชื่อประเภทสินเชื่อแบบเต็ม
		$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID') ");
		list($contype) = pg_fetch_array($qry_chk_con_type);

		//หาชื่อลูกหนี้
		$qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		list($cusname)=pg_fetch_array($qryname);
								
		//หาเงินต้นคงเหลือของแต่ละสัญญา ด้วย function thcap_getPrinciple
		$qryprinciple=pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$datepicker')");
		list($principle)=pg_fetch_array($qryprinciple);
									
		if($principle > '0'){ //ไม่ต้องนำค่าที่เป็น 0.00 มาแสดง
			//นำเข้า function เพื่อหาวันที่ค้าง
			if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
				$qrybackduedate=pg_query("SELECT ('$datepicker'-\"thcap_backDueDate\"('$contractID','$datepicker'))+1");
				list($backduedate)=pg_fetch_array($qrybackduedate);
				$nubdate = $backduedate;
			}else if($contype=='LEASING' OR $contype=='HIRE_PURCHASE'){
				$backduedate=1; //จะไม่เข้าเงื่อนไข $backduedate=="" เนื่องจากของสัญญาประเภทนี้ไม่ได้หาค่า $backduedate
				$qrybackdueday=pg_query("SELECT \"thcap_get_lease_backdays\"('$contractID','$datepicker','1')");
				list($nubdate)=pg_fetch_array($qrybackdueday);
			}
			
			if($backduedate==""){
				$condition="00";
				$principle0=$principle;
				if($principle0!=""){
					$principle000=number_format($principle0,2);
				}else{
					$principle000="";
				}
			}else{										
				if($nubdate == 0){
					$condition="00";
					$principle0=$principle;
					if($principle0!=""){
						$principle000=number_format($principle0,2);
					}else{
						$principle000="";
					}
				}else if($nubdate>=1 and $nubdate <31){
					$condition="01-30";
					$principle1=$principle;
					if($principle1!=""){
						$principle101=number_format($principle1,2);
					}else{
						$principle101="";
					}
				}else if($nubdate>30 and $nubdate <61){
					$condition="31-60";
					$principle2=$principle;
					
					if($principle2!=""){
						$principle22=number_format($principle2,2);
					}else{
						$principle22="";
					}
				}else if($nubdate>60 and $nubdate <91){
					$condition="61-90";
					$principle3=$principle;
					
					if($principle3!=""){
						$principle33=number_format($principle3,2);
					}else{
						$principle33="";
					}
				}else if($nubdate>90 and $nubdate <121){
					$condition="91-120";
					$principle4=$principle;
					
					if($principle4!=""){
						$principle44=number_format($principle4,2);
					}else{
						$principle44="";
					}
				}else if($nubdate>120 and $nubdate <151){
					$condition="121-150";
					$principle5=$principle;
					
					if($principle5!=""){
						$principle55=number_format($principle5,2);
					}else{
						$principle55="";
					}
				}else if($nubdate>150 and $nubdate <181){
					$condition="151-180";
					$principle6=$principle;
					
					if($principle6!=""){
						$principle66=number_format($principle6,2);
					}else{
						$principle66="";
					}
				}else if($nubdate>180 and $nubdate <211){
					$condition="181-210";
					$principle7=$principle;
					
					if($principle7!=""){
						$principle77=number_format($principle7,2);
					}else{
						$principle77="";
					}
				}else if($nubdate>210 and $nubdate <241){
					$condition="211-240";
					$principle8=$principle;
					
					if($principle8!=""){
						$principle88=number_format($principle8,2);
					}else{
						$principle88="";
					}
				}else if($nubdate>240 and $nubdate <271){
					$condition="241-270";
					$principle9=$principle;	
					
					if($principle9!=""){
						$principle99=number_format($principle9,2);
					}else{
						$principle99="";
					}
				}else if($nubdate>270 and $nubdate <301){
					$condition="271-300";
					$principle10=$principle;
					
					if($principle10!=""){
						$principle100=number_format($principle10,2);
					}else{
						$principle100="";
					}									
				}else if($nubdate>300 and $nubdate <331){
					$condition="301-330";
					$principle11=$principle;
					
					if($principle11!=""){
						$principle111=number_format($principle11,2);
					}else{
						$principle111="";
					}									
				}else if($nubdate>330 and $nubdate <361){
					$condition="331-360";
					$principle12=$principle;	
					
					if($principle12!=""){
						$principle122=number_format($principle12,2);
					}else{
						$principle122="";
					}
				}else if($nubdate>360){
					$condition="361";
					$principle13=$principle;
					
					if($principle13!=""){
						$principle133=number_format($principle13,2);
					}else{
						$principle133="";
					}									
				}
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			
			$i+=1;

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $i);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $cusname);
			
			if($condition=="00"){
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $principle0);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, "");
				$principle0=0;
			}
			
			if($condition=="01-30"){
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $principle1);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, "");
				$principle1=0;
			}
			
			if($condition=="31-60"){
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $principle2);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, "");
				$principle2=0;
			}
			
			if($condition=="61-90"){
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $principle3);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, "");
				$principle3=0;
			}
			
			if($condition=="91-120"){
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $principle4);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, "");
				$principle4=0;
			}
			
			if($condition=="121-150"){
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $principle5);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, "");
				$principle5=0;
			}
			
			if($condition=="151-180"){
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $principle6);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, "");
				$principle6=0;
			}
			
			if($condition=="181-210"){
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $principle7);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, "");
				$principle7=0;
			}
			
			if($condition=="211-240"){
				$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, $principle8);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('L'.$j, "");
				$principle8=0;
			}
			
			if($condition=="241-270"){
				$objPHPExcel->getActiveSheet()->SetCellValue('M'.$j, $principle9);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('M'.$j, "");
				$principle9=0;
			}
			
			if($condition=="271-300"){
				$objPHPExcel->getActiveSheet()->SetCellValue('N'.$j, $principle10);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('N'.$j, "");
				$principle10=0;
			}
			
			if($condition=="301-330"){
				$objPHPExcel->getActiveSheet()->SetCellValue('O'.$j, $principle11);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('O'.$j, "");
				$principle11=0;
			}
			
			if($condition=="331-360"){
				$objPHPExcel->getActiveSheet()->SetCellValue('P'.$j, $principle12);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('P'.$j, "");
				$principle12=0;
			}
			
			if($condition=="361"){
				$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$j, $principle13);
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$j, "");
				$principle13=0;
			}
		
			$allsum+=$principle;
			$sumprinciple0+=$principle0;
			$sumprinciple+=$principle1;
			$sumprinciple2+=$principle2;
			$sumprinciple3+=$principle3;
			$sumprinciple4+=$principle4;
			$sumprinciple5+=$principle5;
			$sumprinciple6+=$principle6;
			$sumprinciple7+=$principle7;
			$sumprinciple8+=$principle8;
			$sumprinciple9+=$principle9;
			$sumprinciple10+=$principle10;
			$sumprinciple11+=$principle11;
			$sumprinciple12+=$principle12;
			$sumprinciple13+=$principle13;
			
			$sump1+=$principle0;
			$sump2+=$principle1;
			$sump3+=$principle2;
			$sump4+=$principle3;
			$sump5+=$principle4;
			$sump6+=$principle5;
			$sump7+=$principle6;
			$sump8+=$principle7;
			$sump9+=$principle8;
			$sump10+=$principle9;
			$sump11+=$principle10;
			$sump12+=$principle11;
			$sump13+=$principle12;
			$sump14+=$principle13;

			$j++;
			
			unset($condition);
			unset($principle);
			unset($nubdate);
			
		}//end if	
	}
}
$p=$j-1;

$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวมทั้งสิ้น");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D2:D".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E2:E".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F2:F".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G2:G".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H2:H".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I2:I".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J2:J".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "=SUM(K2:K".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, "=SUM(L2:L".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('M'.$j, "=SUM(M2:M".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('N'.$j, "=SUM(N2:N".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('O'.$j, "=SUM(O2:O".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('P'.$j, "=SUM(P2:P".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j, "=SUM(Q2:Q".$p.")");

$j++;
if($allsum>0){
	$percent0 = $sump1/$allsum*100;
	$percent1 = $sump2/$allsum*100;
	$percent2 = $sump3/$allsum*100;
	$percent3 = $sump4/$allsum*100;
	$percent4 = $sump5/$allsum*100;
	$percent5 = $sump6/$allsum*100;
	$percent6 = $sump7/$allsum*100;
	$percent7 = $sump8/$allsum*100;
	$percent8 = $sump9/$allsum*100;
	$percent9 = $sump10/$allsum*100;
	$percent10 = $sump11/$allsum*100;
	$percent11 = $sump12/$allsum*100;
	$percent12 = $sump13/$allsum*100;
	$percent13 = $sump14/$allsum*100;
}	
		
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "สัดส่วน (%)");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j,  $percent0);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j,  $percent1);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,  $percent2);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,  $percent3);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j,  $percent4);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j,  $percent5);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j,  $percent6);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j,  $percent7);
$objPHPExcel->getActiveSheet()->setCellValue('L'.$j,  $percent8);
$objPHPExcel->getActiveSheet()->setCellValue('M'.$j,  $percent9);
$objPHPExcel->getActiveSheet()->setCellValue('N'.$j,  $percent10);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$j,  $percent11);
$objPHPExcel->getActiveSheet()->setCellValue('P'.$j,  $percent12);
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$j,  $percent13);

	

$t=$j+1;
$objPHPExcel->getActiveSheet()->getStyle('Q'.$t)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$rowcal=$j-1;
$objPHPExcel->getActiveSheet()->getStyle('P'.$t)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('Q'.$t)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('P'.$t, "ลูกหนี้ทั้งสิ้น");
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$t, "=SUM(D".$rowcal.":Q".$rowcal.")");

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle('Aging ตามระยะเวลาค้างชำระ');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_aging.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>
