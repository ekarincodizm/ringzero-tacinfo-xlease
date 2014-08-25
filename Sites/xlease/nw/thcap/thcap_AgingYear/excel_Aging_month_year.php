<?php
//ideafunction.com
include("../../../config/config.php"); 
include ("../../../Classes/PHPExcel.php");

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

//นำค่า array ของประเภทสัญญามาต่อกันเป็นเงื่อนไข เพื่อนำไปค้นหาปีที่ต้องนำมาแสดงในรายงาน
$contypeyear="";
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=''){
		if($contypeyear == ""){
			$contypeyear = "\"conType\"='$contypechk[$con]'";
		}else{
			$contypeyear = $contypeyear." OR \"conType\"='$contypechk[$con]'";
		}	
	}
}
if($contypeyear!=""){
	$contypeyear="and ($contypeyear)";
}


// สร้าง object ของ Class  PHPExcel  ขึ้นมาใหม่
$objPHPExcel = new PHPExcel();
 
// กำหนดค่าต่างๆ
$objPHPExcel->getProperties()->setCreator("www.ideafunction.com");
$objPHPExcel->getProperties()->setLastModifiedBy("www.ideafunction.com");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

//หาปีที่เกี่ยวข้องทั้งหมดมาแสดง
$qry_year=pg_query("SELECT distinct(EXTRACT(YEAR FROM \"conDate\")) FROM thcap_contract 
	WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' $contypeyear
	ORDER BY EXTRACT(YEAR FROM \"conDate\")");
$page=0; //กำหนด sheet ที่จะให้แสดง
while($resyear=pg_fetch_array($qry_year)){
	list($contractyear)=$resyear;
	
	$objPHPExcel->createSheet(NULL, $page);
	// เพิ่มข้อมูลเข้าใน Cell
	$objPHPExcel->setActiveSheetIndex($page);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', "รายงานอายุหนี้ AGING สิ้นเพียง $datepicker ตามช่วงเดือน ประเภทสัญญา  $contypetxtshow");

	$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ลำดับที่');
	$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'เลขที่สัญญา');
	$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'รายชื่อลูกหนี้');
	$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'ไม่ค้างชำระ');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'เกินกำหนด น้อยกว่า 3 เดือน');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'เกินกำหนด 3 เดือน - 6 เดือน');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'เกินกำหนด 6 เดือน - 12 เดือน');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'เกินกว่า 12 เดือน');
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'ปรับโครงสร้างหนี้');
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'อยู่ระหว่างดำเนินคดี');

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	
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

	//วนตามประเภทสัญญาที่เลือก	
	$sump0 = 0;
	$sump1 = 0;	
	$sump2 = 0;
	$sump3 = 0;
	$sump4 = 0;
	$sump5 = 0;
	$sump6 = 0;

	$i=0;
	$j=3;
	for($con = 0;$con < sizeof($contypechk) ; $con++){	
		$qrymg=pg_query("SELECT \"contractID\",\"conLoanAmt\" FROM thcap_contract WHERE (\"conClosedDate\" is NULL OR \"conClosedDate\" > '$datepicker') AND \"conDate\" <= '$datepicker' AND \"conType\" = '$contypechk[$con]' AND EXTRACT(YEAR FROM \"conDate\")='$contractyear' ORDER BY \"contractID\" ASC");
		$numcontract=pg_num_rows($qrymg);

		
		$sumprinciple0 = 0;	
		$sumprinciple1 = 0;
		$sumprinciple2 = 0;
		$sumprinciple3 = 0;
		$sumprinciple4 = 0;
		$sumprinciple5 = 0;
		$sumprinciple6 = 0;
							
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
				//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
				$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
				list($issue)=pg_fetch_array($qryissue);
				if($issue==1){
					$nubmonth='issue';
				}
				//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
				$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
				list($structure)=pg_fetch_array($qrystructure);
				if($structure==1){
					$nubmonth='structure';
				}
				
				if($issue==0 and $structure==0){
					//นำเข้า function เพื่อหาจำนวนเดือนที่ค้าง
					$qrybackduedate=pg_query("SELECT \"thcap_get_all_backmonths\"('$contractID','$datepicker')");
					list($nubmonth)=pg_fetch_array($qrybackduedate);
				}

				if($nubmonth=='structure' or ($issue==1 and $structure==1)){ //อยู่ระหว่างปรับโครงสร้างหนี้
					$condition="5";
					$principle5=$principle;
					if($principle5!=""){
						$principle55=number_format($principle5,2);
					}else{
						$principle55="";
					}
				}else if($nubmonth=='issue'){ //อยู่ระหว่างดำเนินคดี 
					$condition="6";
					$principle6=$principle;
					if($principle6!=""){
						$principle66=number_format($principle6,2);
					}else{
						$principle66="";
					}
				}else if($nubmonth == 0){ //ไม่พบวันค้างชำระ
					$condition="0";
					$principle0=$principle;
					if($principle0!=""){
						$principle000=number_format($principle0,2);
					}else{
						$principle000="";
					}
				}else if($nubmonth<3){ //เกินกำหนด น้อยกว่า 3 เดือน
					$condition="1";
					$principle1=$principle;
					if($principle1!=""){
						$principle101=number_format($principle1,2);
					}else{
						$principle101="";
					}
				}else if($nubmonth>=3 and $nubmonth <=6){ //เกินกำหนด 3 เดือน - 6 เดือน
					$condition="2";
					$principle2=$principle;
					if($principle2!=""){
						$principle22=number_format($principle2,2);
					}else{
						$principle22="";
					}
				}else if($nubmonth>6 and $nubmonth <=12){ //เกินกำหนด 6 เดือน - 12 เดือน
					$condition="3";
					$principle3=$principle;
					if($principle3!=""){
						$principle33=number_format($principle3,2);
					}else{
						$principle33="";
					}
				}else if($nubmonth>12){ //เกินกว่า 12 เดือน
					$condition="4";
					$principle4=$principle;
					if($principle4!=""){
						$principle44=number_format($principle4,2);
					}else{
						$principle44="";
					}
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
				$i+=1;

				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $i);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $cusname);
				
				if($condition=="0"){
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $principle0);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, "");
					$principle0=0;
				}
				
				if($condition=="1"){
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $principle1);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, "");
					$principle1=0;
				}
				
				if($condition=="2"){
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $principle2);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, "");
					$principle2=0;
				}
				
				if($condition=="3"){
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $principle3);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, "");
					$principle3=0;
				}
				
				if($condition=="4"){
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $principle4);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, "");
					$principle4=0;
				}
				
				if($condition=="5"){
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $principle5);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, "");
					$principle5=0;
				}
				
				if($condition=="6"){
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $principle6);
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, "");
					$principle6=0;
				}
				
			
				$allsum+=$principle;
				$sumprinciple0+=$principle0;
				$sumprinciple1+=$principle1;
				$sumprinciple2+=$principle2;
				$sumprinciple3+=$principle3;
				$sumprinciple4+=$principle4;
				$sumprinciple5+=$principle5;
				$sumprinciple6+=$principle6;
				
				$sump0+=$principle0;
				$sump1+=$principle1;
				$sump2+=$principle2;
				$sump3+=$principle3;
				$sump4+=$principle4;
				$sump5+=$principle5;
				$sump6+=$principle6;

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


	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวมทั้งสิ้น");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D2:D".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E2:E".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F2:F".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G2:G".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H2:H".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I2:I".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J2:J".$p.")");

	$j++;
	if($allsum>0){
		$percent0 = number_format($sump0/$allsum*100,2);
		$percent1 = number_format($sump1/$allsum*100,2);
		$percent2 = number_format($sump2/$allsum*100,2);
		$percent3 = number_format($sump3/$allsum*100,2);
		$percent4 = number_format($sump4/$allsum*100,2);
		$percent5 = number_format($sump5/$allsum*100,2);
		$percent6 = number_format($sump6/$allsum*100,2);
	}	
			
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "สัดส่วน (%)");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j,  $percent0);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j,  $percent1);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,  $percent2);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,  $percent3);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j,  $percent4);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j,  $percent5);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j,  $percent6);

		

	$t=$j+1;
	$objPHPExcel->getActiveSheet()->getStyle('J'.$t)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$rowcal=$j-1;
	$objPHPExcel->getActiveSheet()->getStyle('I'.$t)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$t)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$t, "ลูกหนี้ทั้งสิ้น");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$t, "=SUM(D".$rowcal.":J".$rowcal.")");

	// ตั้งชื่อ Sheet
	$objPHPExcel->getActiveSheet()->setTitle('ลูกหนี้ปี '.$contractyear);

	//clear data before next year
	unset($percent0);
	unset($percent1);
	unset($percent2);
	unset($percent3);
	unset($percent4);
	unset($percent5);
	unset($percent6);
	unset($allsum);
	
	//$objPHPExcel->setActiveSheetIndex($page);
	$page++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_aging.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>
