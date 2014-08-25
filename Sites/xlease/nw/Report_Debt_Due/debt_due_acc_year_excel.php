<?php
set_time_limit(0);
include("../../config/config.php"); 
include ("../../Classes/PHPExcel.php");
include("../function/nameMonth.php");
$objPHPExcel = new PHPExcel();


//รับค่าข้อมูล
$datepicker = $_GET["datepicker"];
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
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

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อนำไปหาปีลูกหนี้เฉพาะ ประเภทที่เลือก	
// ============================================================================================
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

	
//######################################แสดงข้อมูลรวม
$objPHPExcel->createSheet(NULL, 0);
$objPHPExcel->setActiveSheetIndex(0);

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle("รวมทุกปี");

$objPHPExcel->getActiveSheet()->SetCellValue('A1', '(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (บัญชี)');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', "วันที่ $datepicker");
$objPHPExcel->getActiveSheet()->SetCellValue('C2', "ประเภทสัญญา $contypetxtshow");
$objPHPExcel->getActiveSheet()->SetCellValue('E2', "วันที่พิมพ์ $nowdatetxtshow");

$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'ลำดับที่');
$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'เลขที่สัญญา');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'รายชื่อลูกหนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี');
	$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'คงค้างชำระ');
	$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'ยังไม่ครบกำหนดชำระ');
$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี');
$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป');
$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'ปรับโครงสร้างหนี้');
$objPHPExcel->getActiveSheet()->SetCellValue('I4', 'อยู่ระหว่างดำเนินคดี');
$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'รวมหนี้คงเหลือทั้งสัญญา');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J4')->getFont()->setBold(true);


// ==========================================================================================
// หาวันสำหรับใช้ในเงื่อนไขการแบ่งจำนวนเงินที่ครบกำหนดชำระลงช่องต่างๆ
// ==========================================================================================
$nextday = date("Y-m-d", strtotime("+1 day", strtotime($datepicker))); // วันต่อไป
$nextyear = date("Y-m-d", strtotime("+1 year", strtotime($datepicker))); // ปีต่อไป
$next_oneyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextyear))); // ถัดไป 1 ปี 1 วัน
$nextfiveyear = date("Y-m-d", strtotime("+5 year", strtotime($datepicker))); // 5 ปีต่อไป
$next_fiveyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextfiveyear))); // ถัดไป 5 ปี 1 วัน

$j = 6;			
//วนแสดงข้อมูล
for($con = 0;$con < sizeof($contypechk) ; $con++){	
	// ==========================================================================================
	// ล้างค่าของประเภทสัญญา
	// ==========================================================================================
	$sum_Overdue = 0.00;
	$sum_ptMinPay_1 = 0.00;
	$sum_ptMinPay_2 = 0.00;
	$sum_ptMinPay_3 = 0.00;
	$sum_restructure = 0.00;
	$sum_sue = 0.00;
	$sum_money_function = 0.00;

	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, "$contypechk[$con]");
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$j++;
	$num = 1;
	
	// ==========================================================================================
	// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี
	// ==========================================================================================
	$qry_debt_due = pg_query("	select \"contractID\"
								from 
									public.\"thcap_contract\" 
								where 
									\"conType\" = '$contypechk[$con]' AND
									\"conDate\" <= '$datepicker' AND
									\"thcap_get_all_isSold\"(\"contractID\", '$datepicker') IS NULL AND
									\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL
								order by \"contractID\"
	");
	$row_debt_due = pg_num_rows($qry_debt_due);
	
	
	// ==========================================================================================
	// กรณีพบข้อมูลจะแสดงรายงาน
	// ==========================================================================================
	$i = 0;
	while($res = pg_fetch_array($qry_debt_due))
	{
		$i++;
		$contractID = $res["contractID"];
		
		// ==========================================================================================
		// ล้างค่าของรายการ
		// ==========================================================================================
		$money_function = 0.00;
		$Overdue = 0.00;
		$ptMinPay_1 = 0.00;
		$ptMinPay_2 = 0.00;
		$ptMinPay_3 = 0.00;
		$amtrestructure = 0.00;
		$amtsue = 0.00;
		
		// ==========================================================================================
		// หาเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก
		// ==========================================================================================
		$inter=pg_query("SELECT \"thcap_amountown\"('$contractID','$datepicker')");
		$resin=pg_fetch_array($inter);
		list($money_function)=$resin;
		
		// ==========================================================================================
		// ถ้า amountown น้อยกว่าหรือเท่ากับ 0 ให้ข้าม loop นี้ไปเลย ให้ไป loop ต่อไปทันที
		// ==========================================================================================
		if($money_function <= 0.00){ $i--; continue; }
		
		// ==========================================================================================
		// ค้นหาชื่อผู้กู้หลัก
		// ==========================================================================================
		$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		if($resnamemain=pg_fetch_array($qry_namemain)){
			$name3=trim($resnamemain["thcap_fullname"]);
		}
		else{
			$name3 = ""; // ถ้าไม่พบชื่อลูกค้า ให้เป็นค่าว่าง
		}
		
		// ==========================================================================================
		//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
		// ==========================================================================================
		$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
		list($issue)=pg_fetch_array($qryissue);

		
		// ==========================================================================================
		//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
		// ==========================================================================================
		$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
		list($isrestructure)=pg_fetch_array($qrystructure);
		
		
		// ==========================================================================================
		//ตรวจสอบเงื่อนไขว่าเงินอยู่ในช่องใด
		// ==========================================================================================
		if($issue==1 and $isrestructure==1) { // อยู่ระหว่างปรับโครงสร้างหนี้
			$amtrestructure = $money_function;
			
		} else if($issue==1) { // อยู่ระหว่างฟ้อง
			$amtsue = $money_function;
			
		} else { // ลูกหนี้ปกติ

			// ==========================================================================================
			// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (คงค้างชำระ)
			// ==========================================================================================
			$sql_str_func = pg_query("select \"thcap_get_all_backamt\"('$contractID', '$datepicker',2)");
			$str_func = pg_fetch_array($sql_str_func);
			list($Overdue) = $str_func;
			
			// ==========================================================================================
			// ตรวจสอบประเภทสัญญาและกำหนด QUERY ที่จะใช้หายอดหนี้ที่จะครบกำหนดชำระ
			// ==========================================================================================
			$sql_tpye_func = pg_query("select \"thcap_get_creditType\"('$contractID')");
			$type_func = pg_fetch_array($sql_tpye_func);
			list($credittype) = $type_func;
			if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
				$queryfind = "select sum(\"ptMinPay\") as \"ptMinPay\" from account.\"thcap_mg_payTerm\"";
			} else if (
						$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
						$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
				$queryfind = "select sum(\"debtnet\") as \"ptMinPay\" from account.\"thcap_acc_filease_realize_eff_present\"";
			}
			
			// ==========================================================================================
			// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ)
			// ==========================================================================================
			$qry_ptMinPay_1 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$nextday' and \"ptDate\" <= '$nextyear' ");
			while($res_ptMinPay_1 = pg_fetch_array($qry_ptMinPay_1))
			{
				$ptMinPay_1 = $res_ptMinPay_1["ptMinPay"];
			}
				
			// ==========================================================================================
			// ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี
			// ==========================================================================================
			$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$next_oneyear_oneday' and \"ptDate\" <= '$nextfiveyear' ");
			while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
			{
				$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
			}
				
			// ==========================================================================================
			// ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป
			// ==========================================================================================
			$qry_ptMinPay_3 = pg_query("$queryfind where \"contractID\" = '$contractID' and\"ptDate\" >= '$next_fiveyear_oneday' ");
			while($res_ptMinPay_3 = pg_fetch_array($qry_ptMinPay_3))
			{
				$ptMinPay_3 = $res_ptMinPay_3["ptMinPay"];
			}
			
			// ********************************* คำนวณให้ลงรายการยอดจะครบกำหนดอย่างถูกต้อง *********************************
			
			// ==========================================================================================
			// กำหนดค่าให้รายการที่ไม่มีค่า = 0 (ที่ต้องกำหนดใหม่เนื่องจาก ไปเอาจาก base มา ได้ค่าเป็น null)
			// ==========================================================================================
			if($Overdue=="") 		$Overdue = 0.00;
			if($ptMinPay_1=="") 	$ptMinPay_1 = 0.00;
			if($ptMinPay_2=="") 	$ptMinPay_2 = 0.00;
			if($ptMinPay_3=="") 	$ptMinPay_3 = 0.00;
			if($amtrestructure=="")	$amtrestructure = 0.00;
			if($amtsue=="") 		$amtsue = 0.00;
			if($money_function=="")	$money_function = 0.00;
			
			// ==========================================================================================
			// นำข้อมูลเข้าช่องโดยสำหรับ LOAN นี้ที่ต้องจ่ายต่อปีเท่าเดิม แต่จ่ายล่วงหน้ามีผลหมดเร็วขึ้น แต่สำหรับ HIRE_PURCHASE / LEASING / GUARANTEED_INVESTMENT / FACTORING / SALE_ON_CONSIGNMENT / PROMISSORY_NOTE หนี้คงที่
			// ==========================================================================================
			if ($money_function > $Overdue + $ptMinPay_1 + $ptMinPay_2 && $ptMinPay_1 > 0 && $ptMinPay_2 > 0) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ เกิน 5 ปี
				if ($ptMinPay_3 == 0.00) { // ถ้างวด 3 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบใน ช่วง 2
					$ptMinPay_2 = $money_function - $Overdue  - $ptMinPay_1;
				} else {
					$ptMinPay_3 = $money_function - $Overdue - $ptMinPay_1 - $ptMinPay_2;
				}
			} else if ($money_function > $Overdue + $ptMinPay_1 && $ptMinPay_1 > 0) {  // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่เกิน 1 ปี แต่ไม่ถึง 5 ปี
				if ($ptMinPay_2 == 0.00) { // ถ้างวด 2 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง 1
					$ptMinPay_1 = $money_function - $Overdue;
				} else {
					$ptMinPay_2 = $money_function - $Overdue - $ptMinPay_1;
				}
				$ptMinPay_3 = 0.00;
			} else if ($money_function > $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่ไม่เกิน 1 ปี
				if ($ptMinPay_1 == 0.00) { // ถ้างวด 1 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง Overdue
					$Overdue = $money_function;
				} else {
					$ptMinPay_1 = $money_function - $Overdue;
				}
				$ptMinPay_2 = 0.00;
				$ptMinPay_3 = 0.00;
			} else if ($money_function <= $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ น้อยกว่าที่ค้างชำระ
				$Overdue = $money_function;
				$ptMinPay_1 = 0.00;
				$ptMinPay_2 = 0.00;
				$ptMinPay_3 = 0.00;
			}
		}
		
		// ==========================================================================================
		// รวมจำนวนเงินที่จะนำไปแสดง
		// ==========================================================================================
		$sum_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ประเภทสัญญา]
		$sumall_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ทั้งหมด]
		
		$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
		$sumall_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
		
		$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
		$sumall_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]

		$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
		$sumall_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ทั้งหมด]
		
		$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
		$sumall_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ทั้งหมด]
		
		$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
		$sumall_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ทั้งหมด]

		$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
		$sumall_sue += $amtsue; // รวมฟ้อง [ทั้งหมด]
		
		// ==========================================================================================
		// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
		// ==========================================================================================
		// สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
		$pgcal=pg_query("select 
		CASE WHEN ('$Overdue'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
		CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric THEN '1' ELSE '0' END as sum_money_function,
		CASE WHEN ('$sumall_Overdue'::numeric(15,2) + '$sumall_ptMinPay_1'::numeric(15,2) + '$sumall_ptMinPay_2'::numeric(15,2) + '$sumall_ptMinPay_3'::numeric(15,2) + '$sumall_restructure'::numeric(15,2) + '$sumall_sue'::numeric(15,2))<>'$sumall_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumall_money_function");
		list($cmoney_function,$csum_money_function,$csumall_money_function)=pg_fetch_array($pgcal);
		
		if($cmoney_function=='1'){
			$money_function = -999;
		}
		
		if($csum_money_function=='1'){
			$sum_money_function = -999;
		}
		
		if($csumall_money_function=='1'){
			$sumall_money_function = -999;
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $name3);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $Overdue);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $ptMinPay_1);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $ptMinPay_2);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $ptMinPay_3);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $amtrestructure);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $amtsue);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $money_function);

		$j++;
	}
	
}
			
$p = $j - 1;
$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D6:D".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E6:E".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F6:F".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G6:G".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H6:H".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I6:I".$p.")");
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J6:J".$p.")");

//###################################จบแสดงข้อมูลรวม	
	

//################################วนแสดงข้อมูลรายปี

// ============================================================================================
// หาีว่ามีสัญญาของลูกหนี้ปีไหนบ้างที่มีรายการรับชำระ ในเดือนปี หรือ เฉพาะปี ที่ผู้ใช้งานต้องการออกรายงาน 
// ============================================================================================
$qry_year=pg_query("
		SELECT 
			DISTINCT(EXTRACT(YEAR FROM \"conDate\")) as \"conyear\" 
		FROM
			thcap_contract 
		WHERE
			\"conDate\"<='$datepicker' AND
			\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL $contypeyear
		ORDER BY \"conyear\" ASC
");

$page = 1;
while($resyear=pg_fetch_array($qry_year)){
	list($contractyear)=$resyear;
	
	$objPHPExcel->createSheet(NULL, $page);
	$objPHPExcel->setActiveSheetIndex($page);
	// ตั้งชื่อ Sheet
	$objPHPExcel->getActiveSheet()->setTitle($contractyear);
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', '(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (บัญชี)');
	$objPHPExcel->getActiveSheet()->SetCellValue('A2', "วันที่ $datepicker");
	$objPHPExcel->getActiveSheet()->SetCellValue('C2', "ประเภทสัญญา $contypetxtshow");
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', "วันที่พิมพ์ $nowdatetxtshow");

	$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'ลำดับที่');
	$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'เลขที่สัญญา');
	$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'รายชื่อลูกหนี้');
	$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'คงค้างชำระ');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'ยังไม่ครบกำหนดชำระ');
	$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี');
	$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป');
	$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'ปรับโครงสร้างหนี้');
	$objPHPExcel->getActiveSheet()->SetCellValue('I4', 'อยู่ระหว่างดำเนินคดี');
	$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'รวมหนี้คงเหลือทั้งสัญญา');

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
	$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J4')->getFont()->setBold(true);


	// ==========================================================================================
	// หาวันสำหรับใช้ในเงื่อนไขการแบ่งจำนวนเงินที่ครบกำหนดชำระลงช่องต่างๆ
	// ==========================================================================================
	$nextday = date("Y-m-d", strtotime("+1 day", strtotime($datepicker))); // วันต่อไป
	$nextyear = date("Y-m-d", strtotime("+1 year", strtotime($datepicker))); // ปีต่อไป
	$next_oneyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextyear))); // ถัดไป 1 ปี 1 วัน
	$nextfiveyear = date("Y-m-d", strtotime("+5 year", strtotime($datepicker))); // 5 ปีต่อไป
	$next_fiveyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextfiveyear))); // ถัดไป 5 ปี 1 วัน

	$j = 6;			
	//วนแสดงข้อมูล
	for($con = 0;$con < sizeof($contypechk) ; $con++){	
		// ==========================================================================================
		// ล้างค่าของประเภทสัญญา
		// ==========================================================================================
		$sum_Overdue = 0.00;
		$sum_ptMinPay_1 = 0.00;
		$sum_ptMinPay_2 = 0.00;
		$sum_ptMinPay_3 = 0.00;
		$sum_restructure = 0.00;
		$sum_sue = 0.00;
		$sum_money_function = 0.00;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, "$contypechk[$con]");
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
		$j++;
		$num = 1;
		
		// ==========================================================================================
		// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี
		// ==========================================================================================
		$qry_debt_due = pg_query("	select \"contractID\"
									from 
										public.\"thcap_contract\" 
									where 
										\"conType\" = '$contypechk[$con]' AND
										\"conDate\" <= '$datepicker' AND
										EXTRACT(YEAR FROM \"conDate\")='$contractyear' AND
										\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL
									order by \"contractID\"
		");
		$row_debt_due = pg_num_rows($qry_debt_due);
		
		
		// ==========================================================================================
		// กรณีพบข้อมูลจะแสดงรายงาน
		// ==========================================================================================
		$i = 0;
		while($res = pg_fetch_array($qry_debt_due))
		{
			$i++;
			$contractID = $res["contractID"];
			
			// ==========================================================================================
			// ล้างค่าของรายการ
			// ==========================================================================================
			$money_function = 0.00;
			$Overdue = 0.00;
			$ptMinPay_1 = 0.00;
			$ptMinPay_2 = 0.00;
			$ptMinPay_3 = 0.00;
			$amtrestructure = 0.00;
			$amtsue = 0.00;
			
			// ==========================================================================================
			// หาเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก
			// ==========================================================================================
			$inter=pg_query("SELECT \"thcap_amountown\"('$contractID','$datepicker')");
			$resin=pg_fetch_array($inter);
			list($money_function)=$resin;
			
			// ==========================================================================================
			// ถ้า amountown น้อยกว่าหรือเท่ากับ 0 ให้ข้าม loop นี้ไปเลย ให้ไป loop ต่อไปทันที
			// ==========================================================================================
			if($money_function <= 0.00){ $i--; continue; }
			
			// ==========================================================================================
			// ค้นหาชื่อผู้กู้หลัก
			// ==========================================================================================
			$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
			if($resnamemain=pg_fetch_array($qry_namemain)){
				$name3=trim($resnamemain["thcap_fullname"]);
			}
			else{
				$name3 = ""; // ถ้าไม่พบชื่อลูกค้า ให้เป็นค่าว่าง
			}
			
			// ==========================================================================================
			//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
			// ==========================================================================================
			$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
			list($issue)=pg_fetch_array($qryissue);

			
			// ==========================================================================================
			//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
			// ==========================================================================================
			$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
			list($isrestructure)=pg_fetch_array($qrystructure);
			
			
			// ==========================================================================================
			//ตรวจสอบเงื่อนไขว่าเงินอยู่ในช่องใด
			// ==========================================================================================
			if($issue==1 and $isrestructure==1) { // อยู่ระหว่างปรับโครงสร้างหนี้
				$amtrestructure = $money_function;
				
			} else if($issue==1) { // อยู่ระหว่างฟ้อง
				$amtsue = $money_function;
				
			} else { // ลูกหนี้ปกติ

				// ==========================================================================================
				// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (คงค้างชำระ)
				// ==========================================================================================
				$sql_str_func = pg_query("select \"thcap_get_all_backamt\"('$contractID', '$datepicker',2)");
				$str_func = pg_fetch_array($sql_str_func);
				list($Overdue) = $str_func;
				
				// ==========================================================================================
				// ตรวจสอบประเภทสัญญาและกำหนด QUERY ที่จะใช้หายอดหนี้ที่จะครบกำหนดชำระ
				// ==========================================================================================
				$sql_tpye_func = pg_query("select \"thcap_get_creditType\"('$contractID')");
				$type_func = pg_fetch_array($sql_tpye_func);
				list($credittype) = $type_func;
				if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
					$queryfind = "select sum(\"ptMinPay\") as \"ptMinPay\" from account.\"thcap_mg_payTerm\"";
				} else if (
							$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
							$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
					$queryfind = "select sum(\"debtnet\") as \"ptMinPay\" from account.\"thcap_acc_filease_realize_eff_present\"";
				}
				
				// ==========================================================================================
				// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ)
				// ==========================================================================================
				$qry_ptMinPay_1 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$nextday' and \"ptDate\" <= '$nextyear' ");
				while($res_ptMinPay_1 = pg_fetch_array($qry_ptMinPay_1))
				{
					$ptMinPay_1 = $res_ptMinPay_1["ptMinPay"];
				}
					
				// ==========================================================================================
				// ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี
				// ==========================================================================================
				$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$next_oneyear_oneday' and \"ptDate\" <= '$nextfiveyear' ");
				while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
				{
					$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
				}
					
				// ==========================================================================================
				// ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป
				// ==========================================================================================
				$qry_ptMinPay_3 = pg_query("$queryfind where \"contractID\" = '$contractID' and\"ptDate\" >= '$next_fiveyear_oneday' ");
				while($res_ptMinPay_3 = pg_fetch_array($qry_ptMinPay_3))
				{
					$ptMinPay_3 = $res_ptMinPay_3["ptMinPay"];
				}
				
				// ********************************* คำนวณให้ลงรายการยอดจะครบกำหนดอย่างถูกต้อง *********************************
				
				// ==========================================================================================
				// กำหนดค่าให้รายการที่ไม่มีค่า = 0 (ที่ต้องกำหนดใหม่เนื่องจาก ไปเอาจาก base มา ได้ค่าเป็น null)
				// ==========================================================================================
				if($Overdue=="") 		$Overdue = 0.00;
				if($ptMinPay_1=="") 	$ptMinPay_1 = 0.00;
				if($ptMinPay_2=="") 	$ptMinPay_2 = 0.00;
				if($ptMinPay_3=="") 	$ptMinPay_3 = 0.00;
				if($amtrestructure=="")	$amtrestructure = 0.00;
				if($amtsue=="") 		$amtsue = 0.00;
				if($money_function=="")	$money_function = 0.00;
				
				// ==========================================================================================
				// นำข้อมูลเข้าช่องโดยสำหรับ LOAN นี้ที่ต้องจ่ายต่อปีเท่าเดิม แต่จ่ายล่วงหน้ามีผลหมดเร็วขึ้น แต่สำหรับ HIRE_PURCHASE / LEASING / GUARANTEED_INVESTMENT / FACTORING / SALE_ON_CONSIGNMENT / PROMISSORY_NOTE หนี้คงที่
				// ==========================================================================================
				if ($money_function > $Overdue + $ptMinPay_1 + $ptMinPay_2 && $ptMinPay_1 > 0 && $ptMinPay_2 > 0) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ เกิน 5 ปี
					if ($ptMinPay_3 == 0.00) { // ถ้างวด 3 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบใน ช่วง 2
						$ptMinPay_2 = $money_function - $Overdue  - $ptMinPay_1;
					} else {
						$ptMinPay_3 = $money_function - $Overdue - $ptMinPay_1 - $ptMinPay_2;
					}
				} else if ($money_function > $Overdue + $ptMinPay_1 && $ptMinPay_1 > 0) {  // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่เกิน 1 ปี แต่ไม่ถึง 5 ปี
					if ($ptMinPay_2 == 0.00) { // ถ้างวด 2 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง 1
						$ptMinPay_1 = $money_function - $Overdue;
					} else {
						$ptMinPay_2 = $money_function - $Overdue - $ptMinPay_1;
					}
					$ptMinPay_3 = 0.00;
				} else if ($money_function > $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่ไม่เกิน 1 ปี
					if ($ptMinPay_1 == 0.00) { // ถ้างวด 1 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง Overdue
						$Overdue = $money_function;
					} else {
						$ptMinPay_1 = $money_function - $Overdue;
					}
					$ptMinPay_2 = 0.00;
					$ptMinPay_3 = 0.00;
				} else if ($money_function <= $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ น้อยกว่าที่ค้างชำระ
					$Overdue = $money_function;
					$ptMinPay_1 = 0.00;
					$ptMinPay_2 = 0.00;
					$ptMinPay_3 = 0.00;
				}
			}
			
			// ==========================================================================================
			// รวมจำนวนเงินที่จะนำไปแสดง
			// ==========================================================================================
			$sum_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ประเภทสัญญา]
			$sumall_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ทั้งหมด]
			
			$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
			$sumall_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
			
			$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
			$sumall_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]

			$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
			$sumall_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ทั้งหมด]
			
			$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
			$sumall_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ทั้งหมด]
			
			$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
			$sumall_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ทั้งหมด]

			$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
			$sumall_sue += $amtsue; // รวมฟ้อง [ทั้งหมด]
			
			// ==========================================================================================
			// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
			// ==========================================================================================
			// สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
			$pgcal=pg_query("select 
			CASE WHEN ('$Overdue'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
			CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric THEN '1' ELSE '0' END as sum_money_function,
			CASE WHEN ('$sumall_Overdue'::numeric(15,2) + '$sumall_ptMinPay_1'::numeric(15,2) + '$sumall_ptMinPay_2'::numeric(15,2) + '$sumall_ptMinPay_3'::numeric(15,2) + '$sumall_restructure'::numeric(15,2) + '$sumall_sue'::numeric(15,2))<>'$sumall_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumall_money_function");
			list($cmoney_function,$csum_money_function,$csumall_money_function)=pg_fetch_array($pgcal);
			
			if($cmoney_function=='1'){
				$money_function = -999;
			}
			
			if($csum_money_function=='1'){
				$sum_money_function = -999;
			}
			
			if($csumall_money_function=='1'){
				$sumall_money_function = -999;
			}
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $i);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $contractID);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $name3);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $Overdue);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $ptMinPay_1);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $ptMinPay_2);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$j, $ptMinPay_3);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, $amtrestructure);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$j, $amtsue);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);	
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, $money_function);

			$j++;
		}
		
	}
				
	$p = $j - 1;
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, "รวม");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, "=SUM(D6:D".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, "=SUM(E6:E".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, "=SUM(F6:F".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, "=SUM(G6:G".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, "=SUM(H6:H".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, "=SUM(I6:I".$p.")");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, "=SUM(J6:J".$p.")");
	
	$page++;
}
			


$namefile = str_replace("-","",$nowdatetxtshow);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="capint_'.$namefile.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>