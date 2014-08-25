<?php
set_time_limit(0);
include("../../../config/config.php"); 
include ("../../../Classes/PHPExcel.php");
include("../../function/nameMonth.php");
$objPHPExcel = new PHPExcel();


//รับค่าข้อมูล
$month = pg_escape_string($_GET['month']); //รับเดือน
$year = pg_escape_string($_GET['year']); //รับปี
$contype = pg_escape_string($_GET['contype']); //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง
$nowdatetxtshow = Date('d-m-Y'); //วันที่ปัจจุบัน
$monthtxtshow = nameMonthTH($month);	//แปลงเดือนเป็นภาษาไทย
//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อแสดงประเภทสัญญาที่แสดงบนหัวรายงาน
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypetxtshow == ""){
		$contypetxtshow = $contypechk[$con];
	}else{
		$contypetxtshow = $contypetxtshow.",".$contypechk[$con];
	}	
}
	
//######################################แสดงข้อมูลรวม
$objPHPExcel->createSheet(NULL, 0);
$objPHPExcel->setActiveSheetIndex(0);

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle("รวมทุกประเภท");

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', "รายงานของเดือน $monthtxtshow ค.ศ. $year");
$objPHPExcel->getActiveSheet()->SetCellValue('C2', "ประเภทสัญญา $contypetxtshow");
$objPHPExcel->getActiveSheet()->SetCellValue('E2', "วันที่พิมพ์ $nowdatetxtshow");

$objPHPExcel->getActiveSheet()->SetCellValue('A4', "คำอธิบาย");
$objPHPExcel->getActiveSheet()->SetCellValue('A5', "- ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
$objPHPExcel->getActiveSheet()->SetCellValue('A6', "- เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
$objPHPExcel->getActiveSheet()->SetCellValue('A7', "- ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
$objPHPExcel->getActiveSheet()->SetCellValue('A8', "- ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
$objPHPExcel->getActiveSheet()->SetCellValue('A9', "- รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
$objPHPExcel->getActiveSheet()->SetCellValue('A10', "- รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
$objPHPExcel->getActiveSheet()->SetCellValue('A11', "- ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
$objPHPExcel->getActiveSheet()->SetCellValue('A12', "- ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");


$objPHPExcel->getActiveSheet()->SetCellValue('A13', 'ลำดับที่');//11
$objPHPExcel->getActiveSheet()->SetCellValue('B13', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C13', 'ชื่อผู้กู้หลัก');
$objPHPExcel->getActiveSheet()->SetCellValue('D13', 'ยอดสินเชื่อเริ่มแรก');
$objPHPExcel->getActiveSheet()->SetCellValue('E13', 'เงินต้นคงเหลือ');
$objPHPExcel->getActiveSheet()->SetCellValue('F13', 'ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('G13', 'รายได้ดอกเบี้ยในรอบปี(รับรู้ไม่เกิน 3 เดือน)');
$objPHPExcel->getActiveSheet()->SetCellValue('H13', 'รวมคงเหลือที่จะต้องรับชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('I13', 'จำนวนวันที่ค้าง');
$objPHPExcel->getActiveSheet()->SetCellValue('J13', 'ดอกเบี้ยคงเหลือทั้งสัญญา(การเงิน)');
$objPHPExcel->getActiveSheet()->SetCellValue('K13', 'ดอกเบี้ยคงเหลือทั้งสัญญา(บัญชี)');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(35);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K13')->getFont()->setBold(true);

$j = 14;	
$numall = 1;

$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$vfocusdate=$year.'-'.$month.'-'.$day;
// วันแรกของปี สำหรับรายการที่ต้องการตัวเลขภายในปีที่เลือก ตั้งแต่ต้นปี
$vfirstdateofyear=$year.'-01-01';
			
//วนแสดงข้อมูล
for($con = 0;$con < sizeof($contypechk) ; $con++){	

	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, "$contypechk[$con]");
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$j++;
	$num = 0;
	
	// ============================================================================================
	//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น (ตรวจสอบแล้ว 2014-02-06)
	// และสัญญาดังกล่าวจะต้องยังไม่ปิดบัญชี
	// ============================================================================================
	$qrycontract=pg_query("	select
								\"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\",'$vfocusdate') as conclosedate
								from
									\"thcap_contract\" 
								where
									\"conStartDate\" <= '$vfocusdate' and 
									\"conType\" = '$contypechk[$con]' 
								order by \"contractID\"
				");

	//นับจำนวนข้อมูลที่ค้นพบ	
	$rownum = pg_num_rows($qrycontract);
	
	// ============================================================================================
	//ดักการแสดงข้อมูล (ตรวจสอบแล้ว 2014-02-06)
	// ============================================================================================
		if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
			$listallrows=0;
			$i=0;
			while($rescon=pg_fetch_array($qrycontract)){
				$contractID=$rescon["contractID"];
				$conclosedate=$rescon["conclosedate"];
				$num++;	
				
				// ============================================================================================
				// ตรวจสอบว่าถ้าสัญญาดังกล่าวปิดสัญญา ไม่ว่าจะด้วยชำระเสร็จสิ้น หรือด้วยขายหนี้ หรือถูกยึดแล้ว จะต้องปิดสัญญา (เฉพาะสัญญาที่ปิดในปีก่อนๆ สำหรับปิดในปีนี้ ยังต้องแสดงอยู่ เพราะต้องแสดงยอดรับรู้รายได้ปีนั้นๆ แต่ให้เงินต้น/ลูกหนี้ เหลือ = 0)
				// ============================================================================================
				if ($conclosedate <= $vfocusdate AND $conclosedate != '' AND $conclosedate < $vfirstdateofyear)
				{
					$num--;
					continue;
				}
				
				// ============================================================================================
				// หาประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
				$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
				list($contype)=pg_fetch_array($qrytype);
						
				// ============================================================================================
				// หาชื่อลูกค้า (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
						$sqlcus = pg_query("SELECT thcap_fullname from\"vthcap_ContactCus_detail\"
						where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
						list($fullname) = pg_fetch_array($sqlcus);
						
						// ============================================================================================
						// ยอดสินเชื่อเริ่มแรก ตามประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){

							$qrystartamt=pg_query("	select 
														\"conLoanAmt\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$qrystartamt=pg_query("	select 
														\"conFinAmtExtVat\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}
						list($conLoanAmt)=pg_fetch_array($qrystartamt);
						$conLoanAmt_prin = $conLoanAmt;

						// ============================================================================================
						//จำนวนเงินต้นคงเหลือ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1 = pg_query("SELECT \"thcap_getPrincipleOfGenCloseMonth\"('$contractID','$year','$month')");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$sql1 = pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$vfocusdate')");	
						}
						
						list($getPrincipleOfGenCloseMonth) = pg_fetch_array($sql1);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getPrincipleOfGenCloseMonth = 0.00;
						}
						$getPrincipleOfGenCloseMonthshow = number_format($getPrincipleOfGenCloseMonth,2);

						// ============================================================================================
						// หาดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระถึงสิ้นเดือนที่เลือก (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1 = pg_query("SELECT \"thcap_getInterestOfGenCloseMonth\"('$contractID','$year','$month')");
							list($getInterestOfGenCloseMonth) = pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
							// ===================================================================================================
							// หาหนี้ลูกหนี้คงเหลือทั้งสัญญา (เงินต้น + ดอกเบี้ย ก่อนภาษีมูลค่าเพิ่ม)
							// ===================================================================================================
								$sumin_prin1=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
								$sql1=pg_query("							
										SELECT
											MIN(\"totaldebt_left\") -- หนี้คงเหลือ
										FROM
											\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
										WHERE 
											\"receiveDate\"::date <= '$vfocusdate'::date AND
											\"contractID\" = '$contractID'
								");
								list($sumin_prin1)=pg_fetch_array($sql1);
							
								// ถ้าไม่มีข้อมูลใดๆเลยอาจจะยังไม่เคยจ่าย ให้ใช้ยอดเงินต้นคงเหลือแรกสุดก่อนรายการ
								if($sumin_prin1=="") {
									$sql1=pg_query("							
											SELECT
												MAX(\"totaldebt_before\"), -- หนี้คงเหลือ
												MAX(\"totalinterest_before\") -- ดอกเบี้ยเริ่มต้น
											FROM
												\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
											WHERE
												\"contractID\" = '$contractID'
									");
									list($sumin_prin1,$start_interest)=pg_fetch_array($sql1);
								}
							
								// เงินต้นทุกกรณี โดยเฉพาะ FL จะต้อง + ค่าซากเข้าไปด้วย
								$residue=""; // ค่าซาก
								$sql1=pg_query("							
										SELECT
											\"conResidualValue\" -- หนี้คงเหลือ
										FROM
											\"public\".\"thcap_lease_contract\"
										WHERE 
											\"contractID\" = '$contractID'
								");
								list($residue)=pg_fetch_array($sql1);
								
								// *todo ยังไม่รองรับการ update ค่าซากที่ปิดสัญญา หากชำระมาแล้ว
								// หากมีค่าซากจะต้องเพิ่มค่าซากลงไปในยอดลูกหนี้ด้วย
								if ($residue=="") 
									$residue = 0.00;
								else
									$sumin_prin1 += $residue;
							
							$getInterestALLacc_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
							$sql1=pg_query("							
									SELECT
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังรับชำระ
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALLacc_as_lastreceivedate)=pg_fetch_array($sql1);
							if ($getInterestALLacc_as_lastreceivedate == "") $getInterestALLacc_as_lastreceivedate = 0.00;

							$getInterestALLacc_as_focusdate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\"<='$vfocusdate'
							");
							list($getInterestALLacc_as_focusdate)=pg_fetch_array($sql1);
							
							$getInterestALL_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือจากการรับชำระทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql2=pg_query("							
									SELECT 
										MIN(\"totalinterest_left\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALL_as_lastreceivedate)=pg_fetch_array($sql2);
							if ($getInterestALL_as_lastreceivedate == "") $getInterestALL_as_lastreceivedate = $start_interest; // ถ้าไม่มีแสดงว่าอาจไม่เคยรับชำระ ดอกเบี้ยจะเท่ากับดอกเบี้ยเริ่มต้น

							
							// ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว
							$getInterestOfGenCloseMonth = $getInterestALLacc_as_focusdate - $getInterestALLacc_as_lastreceivedate;
							
							// ถ้าดอกเบี้ยคงค้าง น้อยกว่า 0 แสดงว่าจ่ายถึงปัจจุบัน
							if($getInterestOfGenCloseMonth < 0){
								$getInterestOfGenCloseMonth = 0.00;
							}
						}
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getInterestOfGenCloseMonth = 0.00;
						}
						
						//$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
						//$getInterestALL_format = number_format($getInterestALL_as_lastreceivedate,2);
						$getInterestOfGenCloseMonthshow = $getInterestOfGenCloseMonth;
						$getInterestALL_format = $getInterestALL_as_lastreceivedate;
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 !!! NOTE: ถ้า comment ในส่วนนี้ออกระบบ จะแสดงยอดลูกหนี้คงเหลือของสัญญา EIR ประเภทนั้นๆ
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') $sumin_prin1 = 0.00;
						
						// ============================================================================================
						// หาดอกเบี้ยที่รับรู้รายได้ไม่เกิน 3 เดือนถึงสิ้นเดือนที่เลือก
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1=pg_query("							
									SELECT 
										SUM(\"realize_amount\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"public\".\"thcap_temp_int_acc\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"intacc_date\"<='$vfocusdate' AND
										\"intacc_date\">='$vfirstdateofyear'
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
						
							
							// - todo แก้ไขเป็น Query ก่อนหน้า ตัวอย่างปัญหา BH-BK01-5500004
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้วในปีนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										(
											( \"voucherID_realize\" IS NOT NULL AND \"voucherDate\" >= '$vfirstdateofyear'::date AND \"voucherDate\" <= '$vfocusdate'::date) OR
											( \"voucherID_realize\" IS NULL AND \"accdate\" >= '$vfirstdateofyear' AND \"accdate\" <= '2012-01-01')
										)
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}
						//$getAccruedInterestshow = number_format($getAccruedInterest,2);
						$getAccruedInterestshow =$getAccruedInterest;
						// ============================================================================================
						// รวมคงเหลือที่จะต้องรับชำระ
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							// หนี้คงเหลือคือเงินต้น + ดอกเบี้ยถึงสิ้นเดือนที่ปิดบัญชี
							$sumin_prin1 = $getPrincipleOfGenCloseMonth + $getInterestOfGenCloseMonth;
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
							
							// หนี้คงเหลือ คือ ยอดผ่อนทั้งหมดทที่เหลือหลังรับชำระล่าสุด
							$sumin_prin1 = $sumin_prin1;
						}
						//$sumin_prin = number_format($sumin_prin1,2);
						$sumin_prin = $sumin_prin1;
						// ============================================================================================
						// จำนวนวันที่ค้าง			
						// ============================================================================================
						$sql1 = pg_query("SELECT \"thcap_get_all_backdays\"('$contractID','$vfocusdate',1)");
						list($thcap_backDueNumDays) = pg_fetch_array($sql1);
						if($thcap_backDueNumDays == ""){ $thcap_backDueNumDays = "-"; }
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางการเงิน)
						// ============================================================================================
						if($getInterestALL_as_lastreceivedate==""){
							$getInterestALL_format="";
							
						}
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางบัญชี)
						// ============================================================================================
						if(	$contype=='HIRE_PURCHASE' or 
							$contype=='LEASING' or
							$contype=='GUARANTEED_INVESTMENT' or
							$contype=='FACTORING' or
							$contype=='PROMISSORY_NOTE' or
							$contype=='SALE_ON_CONSIGNMENT' ) {
							
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\" >= '2013-01-01' AND
										(
											(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
											(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$vfocusdate'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
										)
							");
							list($getInterestLeftAcc)=pg_fetch_array($sql1);
							// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
							if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
								$getInterestLeftAcc = 0.00;
							}
							
							$getInterestLeftAccshow = number_format($getInterestLeftAcc,2); // ตัวเลขสำหรับนำไปแสดง
						}									
			//if($sumin_prin1 > 0){				
							
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $num);
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $fullname);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $conLoanAmt_prin);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $getPrincipleOfGenCloseMonthshow);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $getInterestOfGenCloseMonthshow);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $getAccruedInterestshow);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $sumin_prin);
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $thcap_backDueNumDays);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $getInterestALL_format);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $getInterestLeftAcc);
					$j++;					
					$numall++;
			//}
			//หาผลรวมของจำนวนเงินแต่ละประเภทสัญญา
			$sum_conLoanAmt += $conLoanAmt; //รวมยอดสินเชื่อเริ่มแรก
			$listgetPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; //รวมเงินต้นคงเหลือ
			$listgetInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  //ดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
			$listgetAccruedInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
			$listgetAccruedAccInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
			$listsumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
			$listgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
			$listgetInterestLeftAcc += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
			$listallrows += 1;
			//หาผลรวมของจำนวนเงินทั้งหมด
			$sum_conLoanAmt_all += $conLoanAmt;//รวมยอดสินเชื่อเริ่มแรกทั้งหมด
			$getPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; // รวมเงินต้นคงเหลือ
			$getInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  // รวมดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
			$getAccruedInterestsum += $getAccruedInterest; // รวมดอกเบี้ยรับรู้รายได้ 3 เดือน
			$getInterestLeftAccsum += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
			$sumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
			$sumgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
			
			unset($sumin_prin);
			unset($thcap_backDueNumDays);
			unset($getInterestOfGenCloseMonthshow);
			unset($getPrincipleOfGenCloseMonthshow);
			unset($getInterestOfGenCloseMonth);
			unset($getPrincipleOfGenCloseMonth);
			unset($sumin_prin1);
			unset($getInterestALLacc);
			unset($conLoanAmt);		
			unset($getInterestALLacc_as_lastreceivedate);
			unset($getInterestALLacc_as_focusdate);
			unset($getInterestALL_as_lastreceivedate);
			unset($getInterestLeftAcc);
			
		}//ปิด While	
			
		$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

		$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $sum_conLoanAmt);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, $listgetPrincipleOfGenCloseMonthsum);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, $listgetInterestOfGenCloseMonthsum);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $listgetAccruedInterestsum);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, $listsumin_prinsum);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $listgetInterestALL);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $listgetInterestLeftAcc);
		$j++;	
		unset($sum_conLoanAmt);
		unset($listgetPrincipleOfGenCloseMonthsum);
		unset($listgetInterestOfGenCloseMonthsum);
		unset($listgetAccruedInterestsum);
		unset($listsumin_prinsum);		 
		unset($listgetInterestALL);	
		unset($listgetInterestLeftAcc);	
			
	}
}				
/*if($numall > 1){
	$i = $j - 1;
	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D14:D".$i.")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E14:E".$i.")");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F14:F".$i.")");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G14:G".$i.")");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J14:J".$i.")");
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, "=SUM(K14:K".$i.")");
}*/
//###################################จบแสดงข้อมูลรวม	
$j = $j + 1;
$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวมทั้งสิ้น");
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $sum_conLoanAmt_all);
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $getPrincipleOfGenCloseMonthsum);
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $getInterestOfGenCloseMonthsum);
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $getAccruedInterestsum);
$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $sumin_prinsum);
$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $sumgetInterestALL);
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$j, $getInterestLeftAccsum);
//################################วนแสดงข้อมูลรายประเภท
$page = 1;/*
for($con = 0;$con < sizeof($contypechk) ; $con++){	
	$num = 1;
	if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา
		//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น
		$qrycontract=pg_query("select \"contractID\" from \"thcap_contract\" 
		where  \"conDate\" <= '$focusdate' and \"conType\" = '$contypechk[$con]'
		order by \"contractID\"");
		
		//นับจำนวนข้อมูลที่ค้นพบ	
		$rownum = pg_num_rows($qrycontract);
							
		$objPHPExcel->createSheet(NULL, $page);
		$objPHPExcel->setActiveSheetIndex($page);
		// ตั้งชื่อ Sheet
		$objPHPExcel->getActiveSheet()->setTitle($contypechk[$con]);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', "รายงานของเดือน $monthtxtshow ค.ศ. $year");
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', "ประเภทสัญญา $contypechk[$con]");
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', "วันที่พิมพ์ $nowdatetxtshow");

		$objPHPExcel->getActiveSheet()->SetCellValue('A4', "คำอธิบาย");
		$objPHPExcel->getActiveSheet()->SetCellValue('A5', "- ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
		$objPHPExcel->getActiveSheet()->SetCellValue('A6', "- เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
		$objPHPExcel->getActiveSheet()->SetCellValue('A7', "- ดอกเบี้ยรับรู้รายได้ในเดือนปีนั้นๆ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
		$objPHPExcel->getActiveSheet()->SetCellValue('A8', "- รวมคงเหลือ : เงินต้นคงเหลือ รวมกับ ดอกเบี้ยรับรู้รายได้ในเดือนปีนั้นๆ ");
		$objPHPExcel->getActiveSheet()->SetCellValue('A9', "- ดอกเบี้ยคงเหลือทั้งสัญญา : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");

		$objPHPExcel->getActiveSheet()->SetCellValue('A11', 'ลำดับที่');
		$objPHPExcel->getActiveSheet()->SetCellValue('B11', 'เลขที่สัญญา');
		$objPHPExcel->getActiveSheet()->SetCellValue('C11', 'ชื่อผู้กู้หลัก');
		$objPHPExcel->getActiveSheet()->SetCellValue('D11', 'ยอดสินเชื่อเริ่มแรก');
		$objPHPExcel->getActiveSheet()->SetCellValue('E11', 'เงินต้นคงเหลือ');
		$objPHPExcel->getActiveSheet()->SetCellValue('F11', 'ดอกเบี้ยรับรู้รายได้ในเดือนปีนั้นๆ');
		$objPHPExcel->getActiveSheet()->SetCellValue('G11', 'รวมคงเหลือ');
		$objPHPExcel->getActiveSheet()->SetCellValue('H11', 'จำนวนวันที่ค้าง');
		$objPHPExcel->getActiveSheet()->SetCellValue('I11', 'ดอกเบี้ยคงเหลือทั้งสัญญา');


		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);


		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H11')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('I11')->getFont()->setBold(true);

		$j = 12;
		
		//ดักการแสดงข้อมูล
		if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
			while($rescon=pg_fetch_array($qrycontract)){
				$contractID=$rescon["contractID"];
				
				//หาประเภทสินเชื่อ
				$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
				list($contype)=pg_fetch_array($qrytype);

				//คำสั่งดึงข้อมูล
				if($contype=='LOAN'){
					$qry_data = pg_query("select a.\"genCloseMonth\" , b.\"conLoanAmt\" FROM thcap_temp_int_201201 a 
							left join thcap_contract b on a.\"contractID\" = b.\"contractID\"
							where EXTRACT(MONTH FROM a.\"genCloseMonth\") = '$month' AND EXTRACT(YEAR FROM a.\"genCloseMonth\") = '$year' AND a.\"contractID\" = '$contractID'");
					$numdata=pg_num_rows($qry_data);
					if($numdata>0){
						if($sql_re = pg_fetch_array($qry_data)){
							$genCloseMonth = $sql_re["genCloseMonth"];  //วันที่ปิดรายการของเดือนนั้นๆ
							list($geny,$genm,$gend) = EXPLODE("-",$genCloseMonth); //นำวันที่มาแยกเพื่อเข้า function
							$conLoanAmt = $sql_re["conLoanAmt"]; //ยอดสินเชื่อเริ่มแรก
							
							$month=$genm;
							$year=$geny;
						}
					}else{
						continue;
					}
				}	
				
				$listallrows = 0; //จำนวนข้อมูลของแต่ละประเภทสัญญา				
				
				//ยอดสินเชื่อเริ่มแรก
				if($contype=='LOAN'){
					$conLoanAmt = $sql_re["conLoanAmt"]; //ยอดสินเชื่อเริ่มแรก
				}else if($contype=='HIRE_PURCHASE' or $contype=='LEASING'){
					$qrystartamt=pg_query("select \"totalpriciple_before\" from account.thcap_acc_filease_realize_eff_present where \"contractID\"='$contractID' and \"DueNo\"='1'");
					list($conLoanAmt)=pg_fetch_array($qrystartamt);
				}
															
				//หาชื่อลูกค้า
				$sqlcus = pg_query("SELECT thcap_fullname from\"vthcap_ContactCus_detail\"
				where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
				list($fullname) = pg_fetch_array($sqlcus);
															
				//ยอดสินเชื่อเริ่มแรก
				if($contype=='LOAN'){
					if($conLoanAmt != ""){$conLoanAmt_prin = number_format($conLoanAmt,2);}else{$conLoanAmt_prin = "";}
				}else if($contype=='HIRE_PURCHASE' or $contype=='LEASING'){
					$qrystartamt=pg_query("select \"totalpriciple_before\" from account.thcap_acc_filease_realize_eff_present where \"contractID\"='$contractID' and \"DueNo\"='1'");
					list($conLoanAmt)=pg_fetch_array($qrystartamt);
					$conLoanAmt_prin = number_format($conLoanAmt,2);
				}
						
				//หาวันที่สุดท้ายของเดือน	
				$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
				list($vfocusdate)=pg_fetch_array($qryday);	
				
				//กำหนดวันที่สนใจเพื่อนำเข้า function
				$vfocusdate=$year.'-'.$month.'-'.$vfocusdate;
				
				//จำนวนเงินต้นคงเหลือ
				if($contype=='LOAN'){
					$sql1 = pg_query("SELECT \"thcap_getPrincipleOfGenCloseMonth\"('$contractID','$geny','$genm')");
				}else if($contype=='HIRE_PURCHASE' or $contype=='LEASING'){	
					$sql1 = pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$vfocusdate')");						
				}
				list($getPrincipleOfGenCloseMonth) = pg_fetch_array($sql1);
				$getPrincipleOfGenCloseMonthshow = number_format($getPrincipleOfGenCloseMonth,2);

				//หาดอกเบี้ยรับรู้รายได้ในเดือนปีนั้นๆ 
				if($contype=='LOAN'){
					$sql1 = pg_query("SELECT \"thcap_getInterestOfGenCloseMonth\"('$contractID','$geny','$genm')");
					list($getInterestOfGenCloseMonth) = pg_fetch_array($sql1);
				}else if($contype=='HIRE_PURCHASE' or $contype=='LEASING'){	 //และดอกเบี้ยคงเหลือทั้งสัญญา
					$getInterestALL="";
					$sql1=pg_query("							
					SELECT 
						a.\"contractID\", 
						(CASE WHEN b.\"cut_int\" IS NULL THEN 0 ELSE b.\"cut_int\" END- CASE WHEN c.\"paid_int\" IS NULL THEN 0 ELSE c.\"paid_int\" END) as \"wait_int\", 
						h.\"left_int\"
					FROM thcap_contract a
					LEFT JOIN ( -- ดอกเบี้ยที่ครบกำหนดชำระแล้ว
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\",CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as cut_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" <= '$vfocusdate'
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as b ON a.\"contractID\" = b.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่จ่ายแล้วภายใน สิ้นปี
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\", CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as paid_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" <= '$vfocusdate' AND \"receiveDate\" <= '$vfocusdate' AND \"receiveDate\" IS NOT NULL
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as c ON a.\"contractID\" = c.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่จะครบกำหนด ใน 1 ปี
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\",CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as will_cut_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" > '$vfocusdate' AND \"ptDate\" <= '2013-12-31'
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as d ON a.\"contractID\" = d.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่จะครบกำหนด ใน 1 ปี (ที่่จ่ายแล้ว)
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\", CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as will_paid_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" > '$vfocusdate' AND \"ptDate\" <= '2013-12-31' AND \"receiveDate\" <= '$vfocusdate' AND \"receiveDate\" IS NOT NULL
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as e ON a.\"contractID\" = e.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่จะครบกำหนดเกิน 1 ปี
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\",CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as will_cutmore_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" > '2013-12-31'
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as f ON a.\"contractID\" = f.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่จะครบกำหนดเกิน 1 ปี(ที่่จ่ายแล้ว)
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\", CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as will_paidmore_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"ptDate\" > '2013-12-31' AND \"receiveDate\" <= '$vfocusdate' AND \"receiveDate\" IS NOT NULL
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as g ON a.\"contractID\" = g.\"contractID\"
					LEFT JOIN ( -- ดอกเบี้ยที่เหลือทั้งหมด ณ วันที่สนใจ
						SELECT account.thcap_acc_filease_realize_eff_present.\"contractID\",CASE WHEN SUM(\"interest_cut\") IS NULL THEN 0 ELSE SUM(\"interest_cut\") END as left_int
						FROM account.thcap_acc_filease_realize_eff_present
						WHERE \"receiveDate\" > '$vfocusdate' OR \"receiveDate\" IS NULL
						GROUP by account.thcap_acc_filease_realize_eff_present.\"contractID\"
					) as h ON a.\"contractID\" = h.\"contractID\"
					WHERE a.\"contractID\"='$contractID'
					ORDER BY a.\"contractID\" ASC
					");
					list($contract,$getInterestOfGenCloseMonth,$getInterestALL)=pg_fetch_array($sql1);
				}
				$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
				$getInterestALL_format = number_format($getInterestALL,2);
				
				//รวมคงเหลือ
				$sumin_prin1 = $getInterestOfGenCloseMonth + $getPrincipleOfGenCloseMonth;
				$sumin_prin = number_format($sumin_prin1,2);
				
				//จำนวนวันที่ค้าง			
				$sql1 = pg_query("SELECT \"thcap_backDueNumDays\"('$contractID','$vfocusdate',1)");
				list($thcap_backDueNumDays) = pg_fetch_array($sql1);
				if($thcap_backDueNumDays == ""){ $thcap_backDueNumDays = "-"; }
							
				if($sumin_prin1 > 0){										
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $num);
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $fullname);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $conLoanAmt);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $getPrincipleOfGenCloseMonth);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $getInterestOfGenCloseMonth);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $sumin_prin1);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $thcap_backDueNumDays);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);															
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $getInterestALL);
					$j++;
					$num++;
				}
			}//ปิด While	
			
			if($num > 1){	
				$c = $j - 1;
				$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);

				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

				$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D12:D".$c.")");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E12:E".$c.")");
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F12:F".$c.")");
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G12:G".$c.")");
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I12:I".$c.")");
			}									
		}else{ //หากไม่มีข้อมูล
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j,'ไม่มีข้อมูล');
		}
		$page++;			
	}
	unset($conLoanAmt);
	unset($getPrincipleOfGenCloseMonth);
	unset($getInterestOfGenCloseMonth);
	unset($sumin_prin1);
	unset($thcap_backDueNumDays);
	unset($getInterestALL);
}*/
$namefile = str_replace("-","",$nowdatetxtshow);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="capint_'.$namefile.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>