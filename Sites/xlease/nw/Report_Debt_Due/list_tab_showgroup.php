<?php
require_once("../../config/config.php");

// ============================================================================================
// รับค่าที่ผู้ใช้งานเลือกจากหน้าหลัก
// ============================================================================================
$tab_id = $_GET['tabid']; //ปีที่ต้องการ
$datepicker = $_GET['datepicker']; //วันที่สนใจ
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
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

// ==========================================================================================
// หาวันสำหรับใช้ในเงื่อนไขการแบ่งจำนวนเงินที่ครบกำหนดชำระลงช้องต่างๆ
// ==========================================================================================
$nextday = date("Y-m-d", strtotime("+1 day", strtotime($datepicker))); // วันต่อไป
$nextyear = date("Y-m-d", strtotime("+1 year", strtotime($datepicker))); // ปีต่อไป
$next_oneyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextyear))); // ถัดไป 1 ปี 1 วัน
$nextfiveyear = date("Y-m-d", strtotime("+5 year", strtotime($datepicker))); // 5 ปีต่อไป
$next_fiveyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextfiveyear))); // ถัดไป 5 ปี 1 วัน

// ==========================================================================================
// แสดงกรอบอธิบาย COLUMN
// ==========================================================================================
echo "<table width=\"100%\" border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" bgcolor=\"#999999\">";
$column_details_lease = "
	<tr valign=\"top\" bgcolor=\"#79BCFF\" align=\"center\">
		<th rowspan=\"2\">ลำดับที่</th>
		<th rowspan=\"2\">เลขที่สัญญา</th>
		<th rowspan=\"2\">รายชื่อลูกหนี้</th>
		<th colspan=\"4\">ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี</th>
		<th rowspan=\"2\">ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี</th>
		<th rowspan=\"2\">ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป</th>
		<th rowspan=\"2\">ปรับโครงสร้างหนี้</th>
		<th rowspan=\"2\">อยู่ระหว่างดำเนินคดี</th>
		<th rowspan=\"2\">รวมหนี้คงเหลือทั้งสัญญา</th>
	</tr>
	<tr bgcolor=\"#FFCC55\">
		<th colspan=\"2\">คงค้างชำระ</th>
		<th colspan=\"2\">ยังไม่ถึงกำหนดชำระ</th>
	</tr>
";
$column_details_loan = "
	<tr valign=\"top\" bgcolor=\"#79BCFF\" align=\"center\">
		<th rowspan=\"3\">ลำดับที่</th>
		<th rowspan=\"3\">เลขที่สัญญา</th>
		<th rowspan=\"3\">รายชื่อลูกหนี้</th>
		<th colspan=\"4\">ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี</th>
		<th rowspan=\"3\">ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี</th>
		<th rowspan=\"3\">ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป</th>
		<th rowspan=\"3\">ปรับโครงสร้างหนี้</th>
		<th rowspan=\"3\">อยู่ระหว่างดำเนินคดี</th>
		<th rowspan=\"3\">รวมหนี้คงเหลือทั้งสัญญา</th>
	</tr>
	<tr bgcolor=\"#FFCC55\">
		<th colspan=\"2\">คงค้างชำระ</th>
		<th colspan=\"2\">ยังไม่ถึงกำหนดชำระ</th>
	</tr>
	<tr bgcolor=\"#FFCC00\">
		<th>เงินต้น</th>
		<th>ดอกเบี้ย</th>
		<th>เงินต้น</th>
		<th>ดอกเบี้ย</th>
	</tr>
";

	// ==========================================================================================
	// แสดงข้อมูลทั้งหมด
	// ==========================================================================================
	if($tab_id==0){
	
		// ==========================================================================================
		// กำหนดค่าเริ่มต้น ของผลรวม ทั้งหมด
		// ==========================================================================================
		$sumall_money_function = 0.00;
		$sumall_Overdue = 0.00;
		$sumall_Overdue_interestonly = 0.00;
		$sumall_ptMinPay_1 = 0.00;
		$sumall_ptMinPay_1_interestonly = 0.00;
		$sumall_ptMinPay_2 = 0.00;
		$sumall_ptMinPay_3 = 0.00;
		$sumall_restructure = 0.00;
		$sumall_sue = 0.00;

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
		
		// ============================================================================================
		// วนหาข้อมูลในแต่ละปี
		// ============================================================================================
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			echo "<tr bgcolor=\"#CDB38B\" align=\"center\" height=\"30\"><td colspan=13><b>-- ลูกหนี้ปี $contractyear --</b></td></tr>";
			
			// ==========================================================================================
			// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละปี
			// ==========================================================================================
			$sumyear_money_function = 0.00;
			$sumyear_Overdue = 0.00;
			$sumyear_Overdue_interestonly = 0.00;
			$sumyear_ptMinPay_1 = 0.00;
			$sumyear_ptMinPay_1_interestonly = 0.00;
			$sumyear_ptMinPay_2 = 0.00;
			$sumyear_ptMinPay_3 = 0.00;
			$sumyear_restructure = 0.00;
			$sumyear_sue = 0.00;

			// ==========================================================================================
			// วนหาข้อมูลในแต่ละประเภทสัญญา
			// ==========================================================================================
			for($con = 0;$con < sizeof($contypechk) ; $con++){
				
				// ==========================================================================================
				// หาประเภทสัญญา เพื่อใช้ในการจัดการแสดงผล
				// ==========================================================================================
				$qry_getcreditType = pg_query(" select public.\"thcap_get_creditType\"('$contypechk[$con]')");
				$credittype = pg_fetch_result($qry_getcreditType,0);
				
				// ==========================================================================================
				// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี และเป็นสัญญาประเภทที่เลือกและวนถึง
				// ==========================================================================================
				$qry_debt_due = pg_query("
					SELECT \"contractID\"
					FROM 
						public.\"thcap_contract\" 
					WHERE
						\"conType\"='$contypechk[$con]' AND
						EXTRACT(YEAR FROM \"conDate\")='$contractyear' AND
						\"thcap_get_all_isSold\"(\"contractID\", '$datepicker') IS NULL AND
						\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL 
					ORDER BY \"contractID\" 
				");
				$row_debt_due = pg_num_rows($qry_debt_due);

				// ==========================================================================================
				// กรณีไม่พบข้อมูลที่ต้องแสดง
				// ==========================================================================================
				if($row_debt_due == 0)
				{
					echo "<tr><td colspan=12 bgcolor=\"#E9F8FE\" align=center height=50><b>!!! ไม่พบข้อมูล ลูกหนี้ประเภทสัญญา $contypechk[$con] ในปี $contractyear !!!</b></td></tr>";
				}
				else
				{
					// ==========================================================================================
					// กรณีพบข้อมูลที่ต้องแสดง
					// ==========================================================================================
					
					// ==========================================================================================
					// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละประเภทสัญญา
					// ==========================================================================================
					$sum_money_function = 0.00;
					$sum_Overdue = 0.00;
					$sum_Overdue_interestonly = 0.00;
					$sum_ptMinPay_1 = 0.00;
					$sum_ptMinPay_1_interestonly = 0.00;
					$sum_ptMinPay_2 = 0.00;
					$sum_ptMinPay_3 = 0.00;
					$sum_restructure = 0.00;
					$sum_sue = 0.00;
					
					// ==========================================================================================
					// แสดง Header และกรอบ
					// ==========================================================================================
					echo "<tr bgcolor=#FFE4B5><td colspan=13><b>ประเภทสัญญา $contypechk[$con] ลูกหนี้ปี $contractyear</b></td></tr>"; //แสดง header ว่าเป็นสัญญาประเภทใด
					if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
						echo $column_details_loan;
					} else if (
								$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
								$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
						echo $column_details_lease;
					}
					
					$err_check = ""; // เคลียร์ค่าของตัวเตือน error ของสัญญาก่อนๆ (ถ้ามี)

					$i = 0;
					while($res = pg_fetch_array($qry_debt_due))
					{
						$i++;
						$contractID = $res["contractID"];
						
						// ==========================================================================================
						// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละสัญญา
						// ==========================================================================================
						$money_function = 0.00;
						$Overdue = 0.00;
						$Overdue_interestonly = 0.00;
						$ptMinPay_1 = 0.00;
						$ptMinPay_1_interestonly = 0.00;
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
						
						$amtinterestexclude=pg_query("SELECT \"thcap_amountown\"('$contractID','$datepicker','2')");
						$resinterestonly=pg_fetch_array($amtinterestexclude);
						list($amtinterestexclude)=$resinterestonly;
						$Overdue_interestonly = $money_function - $amtinterestexclude;
						$Overdue_interestonly_rule = $money_function - $amtinterestexclude; // สำหรับเก็บค่านี้ไว้เสมอ ใช้เป็นเงื่อนไขใน if-else ต่างๆ

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
						// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (คงค้างชำระ)
						// ==========================================================================================
						$sql_str_func = pg_query("select \"thcap_get_all_backamt\"('$contractID', '$datepicker',2)");
						$str_func = pg_fetch_array($sql_str_func);
						list($Overdue) = $str_func;
							
						if ($Overdue == 0) { // ถ้าลูกหนี้ไม่ค้างชำระ ดอกเบี้ยที่จะครบกำหนดจะอยู่ภายในไม่เกิน 1 ปี
							$ptMinPay_1_interestonly = $Overdue_interestonly;
							$Overdue_interestonly = 0;
						}
							
						if($issue==1 || $isrestructure==1) { // ถ้าลูกหนี้อยู่ระหว่างปรับโครงสร้างหนี้ หรือฟ้อง ในส่วนนี้จะถือว่าไม่มีเลย
							$Overdue = 0;
							$Overdue_interestonly = 0;
							$ptMinPay_1_interestonly = 0;
						}
						
						// ==========================================================================================
						//ตรวจสอบเงื่อนไขว่าเงินอยู่ในช่องใด
						// ==========================================================================================
						if($issue==1 && $isrestructure==1) { // อยู่ระหว่างปรับโครงสร้างหนี้
							$amtrestructure = $money_function;
							
						} else if($issue==1) { // อยู่ระหว่างฟ้อง
							$amtsue = $money_function;
							
						} else { // ลูกหนี้ปกติ
							
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
							// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี
							// ==========================================================================================
							$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$next_oneyear_oneday' and \"ptDate\" <= '$nextfiveyear' ");
							while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
							{
								$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
							}
							
							// ==========================================================================================
							// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 5 ปี ขึ้นไป
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
							if($Overdue_interestonly=="") 		$Overdue_interestonly = 0.00;
							if($ptMinPay_1=="") 	$ptMinPay_1 = 0.00;
							if($ptMinPay_1_interestonly=="") 	$ptMinPay_1_interestonly = 0.00;
							if($ptMinPay_2=="") 	$ptMinPay_2 = 0.00;
							if($ptMinPay_3=="") 	$ptMinPay_3 = 0.00;
							if($amtrestructure=="")	$amtrestructure = 0.00;
							if($amtsue=="") 		$amtsue = 0.00;
							if($money_function=="")	$money_function = 0.00;
							
							// ==========================================================================================
							// จัดการแยกส่วนของ เงินต้นและดอกเบี้ย ออกจากยอดรวม ที่คงค้างชำระ และ จะครบกำหนดชำระใน 1 ปี
							// ==========================================================================================
							if ($Overdue-$Overdue_interestonly < 0 && $Overdue != 0) { // ถ้าดอกเบี้ยใน Overdue มากกว่ายอด Overdue และ ยอด Overdue มีค่า แสดงว่าลูกค้าค้าง แต่ถึงดิวเฉพาะส่วนของดอกเบี้ยที่ค้าง
								$ptMinPay_1_interestonly = $Overdue_interestonly - $Overdue; // ดอกเบี้ยที่ค้างของที่จะครบกำหนดชำระภายใน 1 ปี จะเท่ากับดอกเบี้ยส่วนที่เหลือ ที่นำไปไว้ใน Overdue
								$Overdue_interestonly = $Overdue; // ดอกเบี้ยที่ค้างของที่ Overdue จะเท่ากับยอด Overdue
								$Overdue= 0; // ยอด Overdue ในส่วนของที่เป็น **เงินต้น** จะต้องเหลือ 0
								$ptMinPay_1 = $ptMinPay_1-$ptMinPay_1_interestonly; // ยอดที่จะครบกำหนดชำระใน 1 ปี ของส่วนที่เป็น  **เงินต้น** จะเท่ากับ ยอดรวมที่ต้องจ่าย หักด้วยดอกเบี้ยที่ต้องจ่าย
								$ecc_case = 'P1'; // ตัว check การตกช่อง เพื่อหา error
							} else { // ถ้าดอกเบี้ยใน Overdue น้อยกว่ายอด overdue รวม ยอดเงินต้นใน overdue จะเหลือเท่ากับ ยอดรวม overdue หักด้วย ดอกเบี้ย overdue และเช่นเดียวกันกับ ยอดที่จะครบกำหนดชำระใน 1 ปี
								$Overdue = $Overdue-$Overdue_interestonly; // จำนวนเงินต้นที่ค้าง = จำนวนค้างรวม - ดอกเบี้ยค้าง
								$ptMinPay_1 = $ptMinPay_1-$ptMinPay_1_interestonly; // จำนวนเงิยต้นที่ค้าง = จำนวนเงินค้างรวมภายใน 1 ปี - ดอกเบี้่ยค้างของ 1 ปี
								$ecc_case = 'P2'; // ตัว check การตกช่อง เพื่อหา error
							}

							// ==========================================================================================
							// นำข้อมูลเข้าช่องโดยสำหรับ LOAN นี้ที่ต้องจ่ายต่อปีเท่าเดิม แต่จ่ายล่วงหน้ามีผลหมดเร็วขึ้น แต่สำหรับ HIRE_PURCHASE / LEASING / GUARANTEED_INVESTMENT / FACTORING / SALE_ON_CONSIGNMENT / PROMISSORY_NOTE หนี้คงที่
							// ==========================================================================================
							if ($money_function > $Overdue + $Overdue_interestonly + $ptMinPay_1 + $ptMinPay_1_interestonly + $ptMinPay_2 && $ptMinPay_1 > 0 && $ptMinPay_2 > 0) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ เกิน 5 ปี
								if ($ptMinPay_3 == 0.00) { // ถ้างวด 3 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบใน ช่วง 2
									$ptMinPay_2 = $money_function - $Overdue - $Overdue_interestonly  - $ptMinPay_1 - $ptMinPay_1_interestonly;
									$ecc_case .= 'C1'; // ตัว check การตกช่อง เพื่อหา error
								} else {
									$ptMinPay_3 = $money_function - $Overdue - $Overdue_interestonly - $ptMinPay_1 - $ptMinPay_1_interestonly - $ptMinPay_2;
									$ecc_case .= 'C2'; // ตัว check การตกช่อง เพื่อหา error
								}
							} else if ($money_function > $Overdue + $Overdue_interestonly + $ptMinPay_1 + $ptMinPay_1_interestonly && $ptMinPay_1 + $ptMinPay_1_interestonly > 0) {  // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่เกิน 1 ปี แต่ไม่ถึง 5 ปี
								if ($ptMinPay_2 == 0.00) { // ถ้างวด 2 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง 1
									// หาว่าดอกเบี้ยเหลือที่ไม่ได้หักใน overdue_interestonly เหลืออยู่เท่าไหร่
									$ptMinPay_1_interestonly = $Overdue_interestonly_rule - $Overdue_interestonly;
									// หาว่าเงินต้นที่เหลือที่ต้องชำระทั้งหมดในงวดแรก คือ เท่าไหร่
									$ptMinPay_1 = $money_function - $Overdue - $Overdue_interestonly - $ptMinPay_1_interestonly;
									$ecc_case .= 'C3'; // ตัว check การตกช่อง เพื่อหา error
								} else {
									$ptMinPay_2 = $money_function - $Overdue - $Overdue_interestonly - $ptMinPay_1 - $ptMinPay_1_interestonly;
									$ecc_case .= 'C4'; // ตัว check การตกช่อง เพื่อหา error
								}
								$ptMinPay_3 = 0.00;
							} else if ($money_function > $Overdue + $Overdue_interestonly) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่ไม่เกิน 1 ปี
								if ($ptMinPay_1 + $ptMinPay_1_interestonly == 0.00) { // ถ้างวด 1 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง Overdue
									// ถ้างวดที่ถูกค้าต้องจ่ายต้องจบใน Overdue ก็ให้ แยกดอกเบี้ยเงินต้นออกจากกัน ด้วยดอกเบี้ยค้างจริงที่เคยคิดได้ตอนแรก และถ้าจบให้ overdue ตรงนี้จะต้องไม่มีค่า
									$Overdue = $money_function - $Overdue_interestonly_rule;
									$Overdue_interestonly =  $Overdue_interestonly_rule;
									$ptMinPay_1 = 0;
									$ptMinPay_1_interestonly = 0;
									$ecc_case .= 'C5'; // ตัว check การตกช่อง เพื่อหา error
								} else {
									// หาว่าดอกเบี้ยเหลือที่ไม่ได้หักใน overdue_interestonly เหลืออยู่เท่าไหร่
									$ptMinPay_1_interestonly = $Overdue_interestonly_rule - $Overdue_interestonly;
									// หาว่าเงินต้นที่เหลือที่ต้องชำระทั้งหมดในงวดแรก คือ เท่าไหร่
									$ptMinPay_1 = $money_function - $Overdue - $Overdue_interestonly - $ptMinPay_1_interestonly;
									$ecc_case .= 'C6'; // ตัว check การตกช่อง เพื่อหา error
								}
								$ptMinPay_2 = 0.00;
								$ptMinPay_3 = 0.00;
							} else if ($money_function <= $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ น้อยกว่าที่ค้างชำระ
								// ถ้างวดที่ถูกค้าต้องจ่ายต้องจบใน Overdue ก็ให้ แยกดอกเบี้ยเงินต้นออกจากกัน ด้วยดอกเบี้ยค้างจริงที่เคยคิดได้ตอนแรก
								$Overdue = $money_function - $Overdue_interestonly_rule;
								$Overdue_interestonly =  $Overdue_interestonly_rule;
								
								$ptMinPay_1 = 0.00;
								$ptMinPay_1_interestonly = 0.00;
								$ptMinPay_2 = 0.00;
								$ptMinPay_3 = 0.00;
								
								$ecc_case .= 'C7'; // ตัว check การตกช่อง เพื่อหา error
							}
						}
						
						// ==========================================================================================
						// รวมจำนวนเงินที่จะนำไปแสดง
						// ==========================================================================================
						$sum_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ประเภทสัญญา]
						$sumyear_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ปี]
						$sumall_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ทั้งหมด]
											
						$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
						$sumyear_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
						$sumall_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
						
						$sum_Overdue_interestonly += $Overdue_interestonly;
						$sumyear_Overdue_interestonly += $Overdue_interestonly;
						$sumall_Overdue_interestonly += $Overdue_interestonly;
											
						$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
						$sumyear_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ปี]
						$sumall_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]
						
						$sum_ptMinPay_1_interestonly += $ptMinPay_1_interestonly; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
						$sumyear_ptMinPay_1_interestonly += $ptMinPay_1_interestonly; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ปี]
						$sumall_ptMinPay_1_interestonly += $ptMinPay_1_interestonly; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]

						$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
						$sumyear_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ปี]
						$sumall_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ทั้งหมด]
											
						$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
						$sumyear_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ปี]
						$sumall_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ทั้งหมด]
											
						$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
						$sumyear_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ปี]
						$sumall_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ทั้งหมด]

						$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
						$sumyear_sue += $amtsue; // รวมฟ้อง [ปี]
						$sumall_sue += $amtsue; // รวมฟ้อง [ทั้งหมด]
											
						// ==========================================================================================
						// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
						// ==========================================================================================
						
						// ==========================================================================================
						// 1. ตรวจสอบด้วยการหาค่า SUM ในแนวนอนของแต่ละแถว ทั้งที่เป็นรายสัญญาและเป็นจำนวนรวม สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
						// ==========================================================================================
						$pgcal=pg_query("select 
						CASE WHEN ('$Overdue'::numeric(15,2) + '$Overdue_interestonly'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_1_interestonly'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
						CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_Overdue_interestonly'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_1_interestonly'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sum_money_function,
						CASE WHEN ('$sumyear_Overdue'::numeric(15,2) + '$sumyear_Overdue_interestonly'::numeric(15,2) + '$sumyear_ptMinPay_1'::numeric(15,2) + '$sumyear_ptMinPay_1_interestonly'::numeric(15,2) + '$sumyear_ptMinPay_2'::numeric(15,2) + '$sumyear_ptMinPay_3'::numeric(15,2) + '$sumyear_restructure'::numeric(15,2) + '$sumyear_sue'::numeric(15,2))<>'$sumyear_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumyear_money_function,
						CASE WHEN ('$sumall_Overdue'::numeric(15,2) + '$sumall_Overdue_interestonly'::numeric(15,2) + '$sumall_ptMinPay_1'::numeric(15,2) + '$sumall_ptMinPay_1_interestonly'::numeric(15,2) + '$sumall_ptMinPay_2'::numeric(15,2) + '$sumall_ptMinPay_3'::numeric(15,2) + '$sumall_restructure'::numeric(15,2) + '$sumall_sue'::numeric(15,2))<>'$sumall_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumall_money_function");
						list($cmoney_function,$csum_money_function,$csumyear_money_function,$csumall_money_function)=pg_fetch_array($pgcal);
											
						if($cmoney_function=='1'){
							$money_function = -999;
						}
											
						if($csum_money_function=='1'){
							$sum_money_function = -999;
						}
						
						if($csumyear_money_function=='1'){
							$sumyear_money_function = -999;
						}
											
						if($csumall_money_function=='1'){
							$sumall_money_function = -999;
						}
						
						// ==========================================================================================
						// 2. ตรวจสอบในสัญญา LOAN ส่วนที่เป็นดอกเบี้ย ทั้งที่ถึงกำหนดชำระและยังไม่ถึงกำหนดชำระ หากดูวันที่ genCloseMonth มีข้อมูล ให้สามารถ compare กันได้ และต้องไม่ใช่รายมีปัญหาฟ้อง / ปรับโครงสร้าง เนื่องจากไม่แสดงดอกเบี้ย
						// ==========================================================================================
						$getInterestOfGenCloseMonth = pg_query("SELECT \"LeftInterest\" - '$Overdue_interestonly'::numeric(15,2) - '$ptMinPay_1_interestonly'::numeric(15,2) FROM \"thcap_temp_int_201201\" WHERE \"contractID\"='$contractID' AND \"genCloseMonth\"='$datepicker'");
						list($getInterestOfGenCloseMonth) = pg_fetch_array($getInterestOfGenCloseMonth);
						if($getInterestOfGenCloseMonth != "" && $issue==0 && $isrestructure==0) {
							// ถ้ามีดอกเบี้ยให้ตรวจสอบ จึงตรวจสอบ และถ้าตรวจสอบแล้วดอกเบี้ยที่ได้ ไม่เท่ากับ 0 คือ **ผิด** (เนื่องจาก select มาเป็น ส่วนต่างของระบบกับรายงาน)
							if($getInterestOfGenCloseMonth != 0){
								$ecc_case .= "IX"; // รายนี้มีดอกเบี้ยผิด
							} else {
								$ecc_case .= "IO";
							}
						}
						
						// ==========================================================================================
						// 3. ตรวจสอบสัญญาที่ผิดหลักความจริง
						// ==========================================================================================
						if($Overdue_interestonly <= $Overdue && $ptMinPay_1_interestonly > 0 && $Overdue > 0 && $issue==0 && $isrestructure==0) { // E1. มียอดดอกเบี้ยค้าง ไม่เกิน เงินต้นค้างชำระ และ มียอดดอกเบี้ยค้างที่จะครบกำหนดใน 1 ปี ด้วย
								$ecc_case .= "E1"; // ผิด
						}
						
						// ==========================================================================================
						// 4. ตรวจสอบด้วยมองเป็น CODE
						/*
							ปัจจุบันได้ตรวจสอบ CODE ที่เป็นไป และมีสัญญาให้ทดสอบแล้วผ่าน มีดังนี้
							P1C1	-- ผ่าน	@ 2012-12-31
							P1C2	-- ผ่าน	@ 2012-12-31
							P1C3
							P1C4	-- ผ่าน	@ 2012-12-31
							P1C5
							P1C6
							P1C7
							P2C1	-- ผ่าน	LI-BK01-5500001 @ 2012-12-31
							P2C2	-- ผ่าน	MG-BK01-5500046, MG-BK01-5500047 @ 2012-12-31
							P2C3	-- ผ่าน	FA-BK01-5500001/0023 @ 2012-12-31
							P2C4	-- ผ่าน	MG-BK01-5400001, MG-BK01-5400002 @ 2012-12-31
							P2C5	-- ผ่าน	FA-BK01-5500010/0001 @ 2012-12-31
							P2C6	-- ผ่าน	MG-BK01-5400056, MG-BK01-5500034, @ 2012-12-31
							P2C7
						*/
						// ==========================================================================================
						if( $money_function < 0 ||
							$Overdue < 0.00 ||
							$Overdue_interestonly < 0.00 ||
							$ptMinPay_1 < 0.00 ||
							$ptMinPay_1_interestonly < 0.00 ||
							$ptMinPay_2 < 0.00 ||
							$ptMinPay_3 < 0.00 ||
							$amtrestructure < 0.00 ||
							$amtsue < 0.00){
							$err_check = "<font color=red>ERROR ตัวตรวจสอบความถูกต้องเบื้องต้น : พบจำนวนมีค่าเป็น **จำนวนลบ** อยู่ในรายงาน ที่ถูกต้องจะต้องไม่มี</font>";
						}

						// ==========================================================================================
						// สลับสีในการแสดงผล
						// ==========================================================================================
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
						
						// ==========================================================================================
						// แสดงผลข้อมูลแต่ละรายการตามสัญญา
						// ==========================================================================================
						echo "<td align=\"center\">$i</td>";
						echo "<td align=\"center\"><a onClick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor:pointer;\"><FONT COLOR=#0000FF><u>$contractID</u></FONT></a></td>";
						echo "<td>$name3</td>";
						if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
							echo "<td align=\"right\">".number_format($Overdue,2)."</td>";
							echo "<td align=\"right\">".number_format($Overdue_interestonly,2)."</td>";
							echo "<td align=\"right\">".number_format($ptMinPay_1,2)."</td>";
							echo "<td align=\"right\">".number_format($ptMinPay_1_interestonly,2)."</td>";
						} else if (
									$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
									$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
							echo "<td align=\"right\" colspan=\"2\">".number_format($Overdue + $Overdue_interestonly,2)."</td>";
							echo "<td align=\"right\" colspan=\"2\">".number_format($ptMinPay_1 + $ptMinPay_1_interestonly,2)."</td>";
						}
						echo "<td align=\"right\">".number_format($ptMinPay_2,2)."</td>";
						echo "<td align=\"right\">".number_format($ptMinPay_3,2)."</td>";
						echo "<td align=\"right\">".number_format($amtrestructure,2)."</td>";
						echo "<td align=\"right\">".number_format($amtsue,2)."</td>";
						echo "<td align=\"right\">".number_format($money_function,2)."</td>";
						//echo "<td align=\"right\">".$ecc_case."</td>"; // เปิดการแสดงตัว check case การคำนวณ ว่าอยู่ในรูปแบบการคำนวณใด สำหรับตรวจสอบและแก้ไข
						echo "</tr>";
					}

					
					// ==========================================================================================
					// แสดงข้อมูลผลรวมรวมประเภทสัญญา
					// ==========================================================================================
					if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
						echo $column_details_loan; // แสดงกรอบอธิบาย COLUMN
						echo "<tr bgcolor=\"#FFCCCC\">";
						echo "<td COLSPAN=\"3\">รวมประเภทสัญญา $contypechk[$con] เฉพาะลูกหนี้ปี $contractyear $err_check</th>";
						echo "<td align=\"right\">".number_format($sum_Overdue,2)."</td>";
						echo "<td align=\"right\">".number_format($sum_Overdue_interestonly,2)."</td>";
						echo "<td align=\"right\">".number_format($sum_ptMinPay_1,2)."</td>";
						echo "<td align=\"right\">".number_format($sum_ptMinPay_1_interestonly,2)."</td>";
					} else if (
								$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
								$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
						echo $column_details_lease; // แสดงกรอบอธิบาย COLUMN
						echo "<tr bgcolor=\"#FFCCCC\">";
						echo "<td COLSPAN=\"3\">รวมประเภทสัญญา $contypechk[$con] เฉพาะลูกหนี้ปี $contractyear</th>";
						echo "<td align=\"right\" colspan=\"2\">".number_format($sum_Overdue + $sum_Overdue_interestonly,2)."</td>";
						echo "<td align=\"right\" colspan=\"2\">".number_format($sum_ptMinPay_1 + $sum_ptMinPay_1_interestonly,2)."</td>";
					}
					echo "<td align=\"right\">".number_format($sum_ptMinPay_2,2)."</th>";
					echo "<td align=\"right\">".number_format($sum_ptMinPay_3,2)."</th>";
					echo "<td align=\"right\">".number_format($sum_restructure,2)."</th>";
					echo "<td align=\"right\">".number_format($sum_sue,2)."</th>";
					echo "<td align=\"right\">".number_format($sum_money_function,2)."</th>";
					echo "</tr>";
				}
			}
			// ==========================================================================================
			// แสดงข้อมูลผลรวมรวมปีทุกประเภทสัญญา
			// ==========================================================================================

			if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
				echo $column_details_loan; // แสดงกรอบอธิบาย COLUMN
				echo "<tr bgcolor=\"#FFE4B5\" style=\"font-weight:bold;\">";
				echo "<td COLSPAN=\"3\">รวมลูกหนี้ปี $contractyear ทุกประเภทสัญญา</th>";
				echo "<td align=\"right\">".number_format($sumyear_Overdue,2)."</td>";
				echo "<td align=\"right\">".number_format($sumyear_Overdue_interestonly,2)."</td>";
				echo "<td align=\"right\">".number_format($sumyear_ptMinPay_1,2)."</td>";
				echo "<td align=\"right\">".number_format($sumyear_ptMinPay_1_interestonly,2)."</td>";
			} else if (
						$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
						$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
				echo $column_details_lease; // แสดงกรอบอธิบาย COLUMN
				echo "<tr bgcolor=\"#FFE4B5\" style=\"font-weight:bold;\">";
				echo "<td COLSPAN=\"3\">รวมลูกหนี้ปี $contractyear ทุกประเภทสัญญา</th>";
				echo "<td align=\"right\" colspan=\"2\">".number_format($sumyear_Overdue + $sumyear_Overdue_interestonly,2)."</td>";
				echo "<td align=\"right\" colspan=\"2\">".number_format($sumyear_ptMinPay_1 + $sumyear_ptMinPay_1_interestonly,2)."</td>";
			}
			echo "<td align=\"right\">".number_format($sumyear_ptMinPay_2,2)."</th>";
			echo "<td align=\"right\">".number_format($sumyear_ptMinPay_3,2)."</th>";
			echo "<td align=\"right\">".number_format($sumyear_restructure,2)."</th>";
			echo "<td align=\"right\">".number_format($sumyear_sue,2)."</th>";
			echo "<td align=\"right\">".number_format($sumyear_money_function,2)."</th>";
			echo "</tr>";
		}
		
		// ==========================================================================================
		// แสดงข้อมูลผลรวมทั้งหมด
		// ==========================================================================================
		if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "PERSONAL_LOAN") {
			echo $column_details_loan; // แสดงกรอบอธิบาย COLUMN
			echo "<tr bgcolor=\"#FFE4B5\" style=\"font-weight:bold;\">";
			echo "<td COLSPAN=\"3\">ลูกหนี้ ณ  $datepicker ทุกประเภทสัญญาและทุกปีลูกหนี้ รวมทั้งสิ้น</th>";
			echo "<td align=\"right\">".number_format($sumall_Overdue,2)."</td>";
			echo "<td align=\"right\">".number_format($sumall_Overdue_interestonly,2)."</td>";
			echo "<td align=\"right\">".number_format($sumall_ptMinPay_1,2)."</td>";
			echo "<td align=\"right\">".number_format($sumall_ptMinPay_1_interestonly,2)."</td>";
		} else if (
					$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR $credittype == "GUARANTEED_INVESTMENT" OR
					$credittype == "FACTORING" OR $credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
			echo $column_details_lease; // แสดงกรอบอธิบาย COLUMN
			echo "<tr bgcolor=\"#FFE4B5\" style=\"font-weight:bold;\">";
			echo "<td COLSPAN=\"3\">ลูกหนี้ ณ  $datepicker ทุกประเภทสัญญาและทุกปีลูกหนี้ รวมทั้งสิ้น</th>";
			echo "<td align=\"right\" colspan=\"2\">".number_format($sumall_Overdue + $sumall_Overdue_interestonly,2)."</td>";
			echo "<td align=\"right\" colspan=\"2\">".number_format($sumall_ptMinPay_1 + $sumall_ptMinPay_1_interestonly,2)."</td>";
		}
		echo "<td align=\"right\">".number_format($sumall_ptMinPay_2,2)."</th>";
		echo "<td align=\"right\">".number_format($sumall_ptMinPay_3,2)."</th>";
		echo "<td align=\"right\">".number_format($sumall_restructure,2)."</th>";
		echo "<td align=\"right\">".number_format($sumall_sue,2)."</th>";
		echo "<td align=\"right\">".number_format($sumall_money_function,2)."</th>";
		echo "</tr>";
	}else{
		
		// ==========================================================================================
		// แสดงข้อมูลทตามปี และสัญญาที่เลือก
		// ==========================================================================================
		
		// ==========================================================================================
		// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละปี
		// ==========================================================================================
		$sumyear_Overdue = 0.00;
		$sumyear_ptMinPay_1 = 0.00;
		$sumyear_ptMinPay_2 = 0.00;
		$sumyear_ptMinPay_3 = 0.00;
		$sumyear_restructure = 0.00;
		$sumyear_sue = 0.00;
		$sumyear_money_function = 0.00;
		
		// ==========================================================================================
		// วนหาข้อมูลในแต่ละประเภทสัญญา
		// ==========================================================================================
		for($con = 0;$con < sizeof($contypechk) ; $con++){
		
			$sum_Overdue = 0.00;
			$sum_ptMinPay_1 = 0.00;
			$sum_ptMinPay_2 = 0.00;
			$sum_ptMinPay_3 = 0.00;
			$sum_restructure = 0.00;
			$sum_sue = 0.00;
			$sum_money_function = 0.00;
			
			echo "<tr bgcolor=#FFE4B5><td colspan=10><b>ประเภทสัญญา $contypechk[$con]</b></td></tr>"; //แสดง header ว่าเป็นสัญญาประเภทใด

			// ==========================================================================================
			// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี และเป็นสัญญาประเภทที่เลือกและวนถึง
			// ==========================================================================================
			$qry_debt_due = pg_query("
				SELECT
					\"contractID\" 
				FROM 
					public.\"thcap_contract\" 
				WHERE 
					\"conType\"='$contypechk[$con]' AND
					EXTRACT(YEAR FROM \"conDate\")='$tab_id' AND
					\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL 
				ORDER BY \"contractID\" 
			");
			$row_debt_due = pg_num_rows($qry_debt_due);

			// ==========================================================================================
			// กรณีไม่พบข้อมูลที่ต้องแสดง
			// ==========================================================================================
			if($row_debt_due == 0)
			{
				echo "<tr><td colspan=10 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบข้อมูล-</b></td></tr>";
			}
			else
			{	
				// ==========================================================================================
				// กรณีพบข้อมูลที่ต้องแสดง
				// ==========================================================================================
				$i = 0;
				while($res = pg_fetch_array($qry_debt_due))
				{
					$i++;
					$contractID = $res["contractID"];
					
					// ==========================================================================================
					// กำหนดค่าเริ่มต้น ของผลรวม ของแต่ละสัญญา
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
					if($issue==1 && $isrestructure==1) { // อยู่ระหว่างปรับโครงสร้างหนี้
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
						// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี
						// ==========================================================================================
						$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"ptDate\" >= '$next_oneyear_oneday' and \"ptDate\" <= '$nextfiveyear' ");
						while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
						{
							$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
						}
								
						// ==========================================================================================
						// ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 5 ปี ขึ้นไป
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
					$sumyear_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ปี]
											
					$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
					$sumyear_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
											
					$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
					$sumyear_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ปี]

					$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
					$sumyear_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระ่เกิน 1 ปี แต่ไม่เกิน 5 ปี [ปี]
											
					$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
					$sumyear_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ปี]
											
					$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
					$sumyear_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ปี]

					$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
					$sumyear_sue += $amtsue; // รวมฟ้อง [ปี]

					// ==========================================================================================
					// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
					// ==========================================================================================
					// สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
					$pgcal=pg_query("select 
					CASE WHEN ('$Overdue'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
					CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sum_money_function,
					CASE WHEN ('$sumyear_Overdue'::numeric(15,2) + '$sumyear_ptMinPay_1'::numeric(15,2) + '$sumyear_ptMinPay_2'::numeric(15,2) + '$sumyear_ptMinPay_3'::numeric(15,2) + '$sumyear_restructure'::numeric(15,2) + '$sumyear_sue'::numeric(15,2))<>'$sumyear_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumyear_money_function");
					list($cmoney_function,$csum_money_function,$csumyear_money_function)=pg_fetch_array($pgcal);
											
					if($cmoney_function=='1'){
						$money_function = -999;
					}
											
					if($csum_money_function=='1'){
						$sum_money_function = -999;
					}
						
					if($csumyear_money_function=='1'){
						$sumyear_money_function = -999;
					}

					// ==========================================================================================
					// สลับสีในการแสดงผล
					// ==========================================================================================
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}

					// ==========================================================================================
					// แสดงข้อมูลรายสัญญา
					// ==========================================================================================
					echo "<td align=\"center\">$i</td>";
					echo "<td align=\"center\"><a onClick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor:pointer;\"><FONT COLOR=#0000FF><u>$contractID</u></FONT></a></td>";
					echo "<td>$name3</td>";
					echo "<td align=\"right\">".number_format($Overdue,2)."</td>";
					echo "<td align=\"right\">".number_format($ptMinPay_1,2)."</td>";
					echo "<td align=\"right\">".number_format($ptMinPay_2,2)."</td>";
					echo "<td align=\"right\">".number_format($ptMinPay_3,2)."</td>";
					echo "<td align=\"right\">".number_format($amtrestructure,2)."</td>";
					echo "<td align=\"right\">".number_format($amtsue,2)."</td>";
					echo "<td align=\"right\">".number_format($money_function,2)."</td>";
					echo "</tr>";

				}

				// ==========================================================================================
				// แสดงข้อมูลผลรวมประเภทสัญญา
				// ==========================================================================================
				echo $column_details; // แสดงกรอบอธิบาย COLUMN
				echo "<tr bgcolor=\"#FFCCCC\">";
				echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; รวมประเภทสัญญา $contypechk[$con]</th>";
				echo "<td align=\"right\">".number_format($sum_Overdue,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_ptMinPay_1,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_ptMinPay_2,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_ptMinPay_3,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_restructure,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_sue,2)."</th>";
				echo "<td align=\"right\">".number_format($sum_money_function,2)."</th>";
				echo "</tr>";
			}
		}
		
		// ==========================================================================================
		// แสดงข้อมูลผลรวมปี
		// ==========================================================================================
		echo $column_details; // แสดงกรอบอธิบาย COLUMN
		echo "<tr bgcolor=\"#ffb0e3\" style=\"font-weight:bold;\">";
		echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; รวมทั้งสิ้น</th>";
		echo "<td align=\"right\">".number_format($sumyear_Overdue,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_ptMinPay_1,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_ptMinPay_2,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_ptMinPay_3,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_restructure,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_sue,2)."</th>";
		echo "<td align=\"right\">".number_format($sumyear_money_function,2)."</th>";
		echo "</tr>";
	}
echo "</table>";
?>