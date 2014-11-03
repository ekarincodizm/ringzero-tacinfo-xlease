<?php
require_once("../../../config/config.php");

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// ปัจจุบันในส่วนนี้ไม่อัพเดทข้อมูลตามการแก้ไขครั้งล่าสุด 2014-08-26
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

$tab_id = pg_escape_string($_GET['tabid']); //ปีที่ต้องการ
$month = pg_escape_string($_GET["month"]); //รับเดือน
$year = pg_escape_string($_GET["year"]); //รับปี
$contype = pg_escape_string($_GET['contype']); //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

set_time_limit(0);

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

//หาวันที่สุดท้ายของเดือน	
/*$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$focusdate=$year.'-'.$month.'-'.$day;*/

// ============================================================================================
//หาวันที่สุดท้ายของเดือน (ตรวจสอบแล้ว 2014-02-06)
// ============================================================================================
$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$vfocusdate=$year.'-'.$month.'-'.$day;
// วันแรกของปี สำหรับรายการที่ต้องการตัวเลขภายในปีที่เลือก ตั้งแต่ต้นปี
$vfirstdateofyear=$year.'-01-01';

echo "
<table frame=\"box\" width=\"100%\" align=\"center\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#EEEED1\">
	<tr bgcolor=\"#8B8B7A\" align=\"center\">
		<td align=\"center\">เลขที่สัญญา</td>
		<td align=\"center\">ชื่อผู้กู้หลัก</td>
		<td align=\"center\">ยอดสินเชื่อ<br>เริ่มแรก</td>
		<td align=\"center\">เงินต้นคงเหลือ</td>
		<td align=\"center\">ดอกเบี้ยที่เกิดขึ้น<br>ที่ยังไม่ได้รับชำระ</td>
		<td align=\"center\">รายได้ดอกเบี้ยในรอบปี<br>(รับรู้ไม่เกิน 3 เดือน)</td>
		<td align=\"center\">รวมคงเหลือ<br>ที่จะต้องรับชำระ</td>
		<td align=\"center\">จำนวนวันที่ค้าง</td>
		<td align=\"center\">ดอกเบี้ยคงเหลือ<br>ทั้งสัญญา<br>(การเงิน)</td>
		<td align=\"center\">ดอกเบี้ยคงเหลือ<br>ทั้งสัญญา<br>(บัญชี)</td>
	</tr>
";	
		
	if($tab_id==0){
		$allrows = 0; //จำนวนข้อมูลทั้งหมด
		
		//หาปีที่เกี่ยวข้องทั้งหมดมาแสดง
		$qry_year=pg_query("SELECT distinct(EXTRACT(YEAR FROM \"conDate\")) FROM thcap_contract 
				WHERE \"conDate\" <='$vfocusdate' $contypeyear
				ORDER BY EXTRACT(YEAR FROM \"conDate\")");
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			echo "<tr bgcolor=\"#FFCCCC\" align=\"center\" height=\"30\"><td colspan=10><b>-- ปี $contractyear --</b></td></tr>";
			$i = 0;
			
			//วนตามประเภทสัญญาที่เลือก
			for($con = 0;$con < sizeof($contypechk) ; $con++){
				if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา
					//แสดงประเภทอยู่ด้านบนข้อมูล
					echo "<tr bgcolor=\"#8B8B7A\"><td colspan=\"10\"><b>$contypechk[$con]</b></td></tr>";
					
					$numall = 0;					
					// ============================================================================================
					//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น 
					// และสัญญาดังกล่าวจะต้องยังไม่ปิดบัญชี
					// ============================================================================================
					$qrycontract=pg_query("	select
											\"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\",'$vfocusdate') as conclosedate
										from
											\"thcap_contract\" 
										where
											\"conStartDate\" <= '$vfocusdate' and 
											\"conType\" = '$contypechk[$con]' and 
											EXTRACT(YEAR FROM \"conDate\")='$contractyear'
										order by \"contractID\"
				");
					
				//นับจำนวนข้อมูลที่ค้นพบ	
				$rownum = pg_num_rows($qrycontract);
				
				// ============================================================================================
				//ดักการแสดงข้อมูล (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
				if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
					$listallrows=0;
					while($rescon=pg_fetch_array($qrycontract)){
						$contractID=$rescon["contractID"];
						$conclosedate=$rescon["conclosedate"];
						
						// ============================================================================================
						// ตรวจสอบว่าถ้าสัญญาดังกล่าวปิดสัญญา ไม่ว่าจะด้วยชำระเสร็จสิ้น หรือด้วยขายหนี้ หรือถูกยึดแล้ว จะต้องปิดสัญญา (เฉพาะสัญญาที่ปิดในปีก่อนๆ สำหรับปิดในปีนี้ ยังต้องแสดงอยู่ เพราะต้องแสดงยอดรับรู้รายได้ปีนั้นๆ แต่ให้เงินต้น/ลูกหนี้ เหลือ = 0)
						// ============================================================================================
						if ($conclosedate <= $vfocusdate AND $conclosedate != '' AND $conclosedate < $vfirstdateofyear) continue;
						
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
						if(	$contype=='LOAN' or
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
						$conLoanAmt_prin = number_format($conLoanAmt,2);

						// ============================================================================================
						//จำนวนเงินต้นคงเหลือ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						if(	$contype=='LOAN' or
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
						
						$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
						$getInterestALL_format = number_format($getInterestALL_as_lastreceivedate,2);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 !!! NOTE: ถ้า comment ในส่วนนี้ออกระบบ จะแสดงยอดลูกหนี้คงเหลือของสัญญา EIR ประเภทนั้นๆ
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') $sumin_prin1 = 0.00;
						
						// ============================================================================================
						// หาดอกเบี้ยที่รับรู้รายได้ไม่เกิน 3 เดือนถึงสิ้นเดือนที่เลือก
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						$getAccruedInterestshow = number_format($getAccruedInterest,2);
						
						// ============================================================================================
						// รวมคงเหลือที่จะต้องรับชำระ
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						$sumin_prin = number_format($sumin_prin1,2);
						
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
							$color="bgcolor=#DDDDDD";
						}else{
							$color="";
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
						else{
							$color="";
						}
						
						//if($sumin_prin1 > 0){	//หากเงินคงเหลือมากกว่า ศูนย์จึงจะแสดงข้อมูล (แก้ไข 2014-03-10 comment ออก เนื่องจากเปลือง perf ที่มาตรวจสอบทั้งหมดว่าไม่ใช่ตอนสุดท้าย และไล่หายาก)
							//นับจำนวนแถวเพื่อสลับสีแถว ให้อ่านง่ายขึ้น
							if($i%2==0){
								echo "<tr bgcolor=#EED5B7 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED5B7';\" align=center>";
							}ELSE{
								echo "<tr bgcolor=#FFE4C4 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE4C4';\" align=center>";
							}
							
							if($contype=='HIRE_PURCHASE' || $contype=='LEASING' || $contype=='FACTORING' || $contype=='PROMISSORY_NOTE' || $contype=='SALE_ON_CONSIGNMENT')
							{
								$popupRevenue = "<img src=\"../images/detail.gif\" width=\"20px\" height=\"20px\" onclick=\"javascript:popU('../../thcap_installments/frm_Realize.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')\" style=\"cursor:pointer;\">";
							}
							else
							{
								$popupRevenue = "";
							}

							//แสดงข้อมูล
							echo "
								<td width=\"15%\" align=\"left\"><a onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
								<font color=\"red\"><u>$contractID</u></font></a> $popupRevenue</td>
								<td width=\"20%\" align=\"left\">$fullname</td>
								<td width=\"15%\"align=\"right\">$conLoanAmt_prin</td>
								<td width=\"15%\"align=\"right\">$getPrincipleOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getInterestOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getAccruedInterestshow</td>
								<td width=\"15%\"align=\"right\">$sumin_prin</td>
								<td width=\"15%\"align=\"center\">$thcap_backDueNumDays</td>
								<td width=\"15%\"align=\"center\" $color>$getInterestALL_format</td>					
								<td width=\"15%\"align=\"center\" $color>$getInterestLeftAccshow</td>
							";
							$numall++;

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
							$allrows += 1; //จำนวนข้อมูลทั้งหมด 
						//}

						// ============================================================================================
						//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล	
						// ============================================================================================
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
						unset($getInterestLeftAccshow);
						$i++;
					} //end while แสดงเลขที่สัญญาที่เกี่ยวกับ type ที่เลือก

					if($numall > 0){
						// ============================================================================================
						//แสดงผลรวมของแต่ละประเภทสัญญา
						// ============================================================================================
						echo "
							<tr bgcolor=\"#FFC1C1\">
								<td>$listallrows รายการ</td>
								<td colspan=\"1\" align=\"right\"><b>รวม  $contypechk[$con]</b></td>
								<td align=\"right\">".number_format($sum_conLoanAmt,2)."</td>
								<td align=\"right\">".number_format($listgetPrincipleOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetInterestOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetAccruedInterestsum,2)."</td>
								<td align=\"right\">".number_format($listsumin_prinsum,2)."</td>
								<td></td>
								<td align=\"right\">".number_format($listgetInterestALL,2)."</td>
								<td align=\"right\">".number_format($listgetInterestLeftAcc,2)."</td>
							</tr>
						";
					}else{
						echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"10\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
					}								

					// ============================================================================================
					//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล
					// ============================================================================================
					unset($sum_conLoanAmt);
					unset($listgetPrincipleOfGenCloseMonthsum);
					unset($listgetInterestOfGenCloseMonthsum);
					unset($listgetAccruedInterestsum);
					unset($listsumin_prinsum);		 
					unset($listgetInterestALL);	
					unset($listgetInterestLeftAcc);
				}else{ //end if กรณีที่พบข้อมูลจาก type ที่เลือก
				//หากจำนวนข้อมูลที่พบน้อยกว่าศูนย์
					echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"10\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
				}	
				}//จบการแสดงข้อมูล
			}//ปิดการวนประเภทสัญญา
		}
	}else{ //กรณีมีการเลือกปี
		$i = 0;
		$allrows = 0; //จำนวนข้อมูลทั้งหมด
		//วนตามประเภทสัญญาที่เลือก	
		for($con = 0;$con < sizeof($contypechk) ; $con++){
			if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา	
				$numall = 0;
				
				//แสดงประเภทอยู่ด้านบนข้อมูล
				echo "<tr bgcolor=\"#CDB79E\"><td colspan=\"10\"><b>$contypechk[$con]</b></td></tr>";
				
				// ============================================================================================
				//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น 
				// และสัญญาดังกล่าวจะต้องยังไม่ปิดบัญชี
				// ============================================================================================
				$qrycontract=pg_query("	select
											\"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\",'$vfocusdate') as conclosedate
										from
											\"thcap_contract\" 
										where
											\"conStartDate\" <= '$vfocusdate' and 
											\"conType\" = '$contypechk[$con]' and 
											EXTRACT(YEAR FROM \"conDate\")='$tab_id'
										order by \"contractID\"
				");
				
				//นับจำนวนข้อมูลที่ค้นพบ	
				$rownum = pg_num_rows($qrycontract);
				
				// ============================================================================================
				//ดักการแสดงข้อมูล (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
				if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
					$listallrows=0;
					while($rescon=pg_fetch_array($qrycontract)){
						$contractID=$rescon["contractID"];
						$conclosedate=$rescon["conclosedate"];
						
						// ============================================================================================
						// ตรวจสอบว่าถ้าสัญญาดังกล่าวปิดสัญญา ไม่ว่าจะด้วยชำระเสร็จสิ้น หรือด้วยขายหนี้ หรือถูกยึดแล้ว จะต้องปิดสัญญา (เฉพาะสัญญาที่ปิดในปีก่อนๆ สำหรับปิดในปีนี้ ยังต้องแสดงอยู่ เพราะต้องแสดงยอดรับรู้รายได้ปีนั้นๆ แต่ให้เงินต้น/ลูกหนี้ เหลือ = 0)
						// ============================================================================================
						if ($conclosedate <= $vfocusdate AND $conclosedate != '' AND $conclosedate < $vfirstdateofyear) continue;
						
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
						if(	$contype=='LOAN' or
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
						$conLoanAmt_prin = number_format($conLoanAmt,2);

						// ============================================================================================
						//จำนวนเงินต้นคงเหลือ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						$getPrincipleOfGenCloseMonthshow = number_format($getPrincipleOfGenCloseMonth,2);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getPrincipleOfGenCloseMonthshow = 0.00;
							$getPrincipleOfGenCloseMonth = 0.00;
						}

						// ============================================================================================
						// หาดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระถึงสิ้นเดือนที่เลือก (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						
						$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
						$getInterestALL_format = number_format($getInterestALL_as_lastreceivedate,2);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 !!! NOTE: ถ้า comment ในส่วนนี้ออกระบบ จะแสดงยอดลูกหนี้คงเหลือของสัญญา EIR ประเภทนั้นๆ
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') $sumin_prin1 = 0.00;
						
						// ============================================================================================
						// หาดอกเบี้ยที่รับรู้รายได้ไม่เกิน 3 เดือนถึงสิ้นเดือนที่เลือก
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						$getAccruedInterestshow = number_format($getAccruedInterest,2);
						
						// ============================================================================================
						// รวมคงเหลือที่จะต้องรับชำระ
						// ============================================================================================
						if(	$contype=='LOAN' or
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
						$sumin_prin = number_format($sumin_prin1,2);
						
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
							$color="bgcolor=#DDDDDD";
						}else{
							$color="";
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
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้ว
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\" >= '2013-01-01' AND
										(
											(\"voucherID_realize\" IS NULL) OR
											(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$vfocusdate'::date)
										)
							");
							list($getInterestLeftAcc)=pg_fetch_array($sql1);
							// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
							if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
								$getInterestLeftAcc = 0.00;
							}
							
							$getInterestLeftAccshow = number_format($getInterestLeftAcc,2); // ตัวเลขสำหรับนำไปแสดง
						}
						else{
							$color="";
						}
						
						//if($sumin_prin1 > 0){	//หากเงินคงเหลือมากกว่า ศูนย์จึงจะแสดงข้อมูล (แก้ไข 2014-03-10 comment ออก เนื่องจากเปลือง perf ที่มาตรวจสอบทั้งหมดว่าไม่ใช่ตอนสุดท้าย และไล่หายาก)
							//นับจำนวนแถวเพื่อสลับสีแถว ให้อ่านง่ายขึ้น
							if($i%2==0){
								echo "<tr bgcolor=#EED5B7 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED5B7';\" align=center>";
							}ELSE{
								echo "<tr bgcolor=#FFE4C4 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE4C4';\" align=center>";
							}
							
							if($contype=='HIRE_PURCHASE' || $contype=='LEASING' || $contype=='FACTORING' || $contype=='PROMISSORY_NOTE' || $contype=='SALE_ON_CONSIGNMENT')
							{
								$popupRevenue = "<img src=\"../images/detail.gif\" width=\"20px\" height=\"20px\" onclick=\"javascript:popU('../../thcap_installments/frm_Realize.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')\" style=\"cursor:pointer;\">";
							}
							else
							{
								$popupRevenue = "";
							}

							//แสดงข้อมูล
							echo "
								<td width=\"15%\" align=\"left\"><a onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
								<font color=\"red\"><u>$contractID</u></font></a> $popupRevenue</td>
								<td width=\"20%\" align=\"left\">$fullname</td>
								<td width=\"15%\"align=\"right\">$conLoanAmt_prin</td>
								<td width=\"15%\"align=\"right\">$getPrincipleOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getInterestOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getAccruedInterestshow</td>
								<td width=\"15%\"align=\"right\">$sumin_prin</td>
								<td width=\"15%\"align=\"center\">$thcap_backDueNumDays</td>
								<td width=\"15%\"align=\"center\" $color>$getInterestALL_format</td>					
								<td width=\"15%\"align=\"center\" $color>$getInterestLeftAccshow</td>
							";
							$numall++;

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
							$allrows += 1; //จำนวนข้อมูลทั้งหมด 
						//}

						// ============================================================================================
						//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล	
						// ============================================================================================
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
						unset($getInterestLeftAccshow);
						$i++;
					} //end while แสดงเลขที่สัญญาที่เกี่ยวกับ type ที่เลือก

					if($numall > 0){
						// ============================================================================================
						//แสดงผลรวมของแต่ละประเภทสัญญา
						// ============================================================================================
						echo "
							<tr bgcolor=\"#FFC1C1\">
								<td>$listallrows รายการ</td>
								<td colspan=\"1\" align=\"right\"><b>รวม  $contypechk[$con]</b></td>
								<td align=\"right\">".number_format($sum_conLoanAmt,2)."</td>
								<td align=\"right\">".number_format($listgetPrincipleOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetInterestOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetAccruedInterestsum,2)."</td>
								<td align=\"right\">".number_format($listsumin_prinsum,2)."</td>
								<td></td>
								<td align=\"right\">".number_format($listgetInterestALL,2)."</td>
								<td align=\"right\">".number_format($listgetInterestLeftAcc,2)."</td>
							</tr>
						";
					}else{
						echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"10\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
					}								

					// ============================================================================================
					//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล
					// ============================================================================================
					unset($sum_conLoanAmt);
					unset($listgetPrincipleOfGenCloseMonthsum);
					unset($listgetInterestOfGenCloseMonthsum);
					unset($listgetAccruedInterestsum);
					unset($listsumin_prinsum);		 
					unset($listgetInterestALL);	
					unset($listgetInterestLeftAcc);
				}else{
						echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"10\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
				}								
										 
				//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล
					unset($sum_conLoanAmt);
					unset($listgetPrincipleOfGenCloseMonthsum);
					unset($listgetInterestOfGenCloseMonthsum);
					unset($listgetAccruedInterestsum);
					unset($listsumin_prinsum);		 
					unset($listgetInterestALL);	
					unset($listgetInterestLeftAcc);
				}else{ //หากจำนวนข้อมูลที่พบน้อยกว่าศูนย์
					echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"10\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
				}	
			}//จบการแสดงข้อมูล
		}//ปิดการวนประเภทสัญญา		
	
	// แสดงผลรวมทั้งหมด 
	echo "
	<tr bgcolor=\"#33CC66\">
		<td>รวม $allrows รายการ</td>
		<td align=\"right\"><b>รวมทั้งหมด </b></td>
		<td align=\"right\"><b>".number_format($sum_conLoanAmt_all,2)."</b></td>
		<td align=\"right\"><b>".number_format($getPrincipleOfGenCloseMonthsum,2)."</b></td>
		<td align=\"right\"><b>".number_format($getInterestOfGenCloseMonthsum,2)."</b></td>
		<td align=\"right\"><b>".number_format($getAccruedInterestsum,2)."</b></td>
		<td align=\"right\"><b>".number_format($sumin_prinsum,2)."</b></td>		
		<td></td>
		<td align=\"right\"><b>".number_format($sumgetInterestALL,2)."</b></td>	
		<td align=\"right\"><b>".number_format($getInterestLeftAccsum,2)."</b></td>
	</tr>	  
</table>
";
?>