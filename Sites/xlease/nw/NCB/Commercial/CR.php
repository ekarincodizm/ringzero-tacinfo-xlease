<?php
set_time_limit(150);

function CR_head($day , $month , $year) // ชื่อไฟล์ของ CR
{
	while(strlen($day) < 2)
	{
		$day = "0".$day;
	}
	
	while(strlen($month) < 2)
	{
		$month = "0".$month;
	}
	
	$textreturn = "CR-1103-$year$month$day-1.csv";
	
	return $textreturn;
}

function CR_text($day , $month , $year , $myWhere) // ข้อความใน CR
{
	$date = "$year-$month-$day"; // วันที่สนใจ
	
	// หา วันเดือนปี ณ สิ้นเดือน ของเดือนก่อนหน้านี้
	if($month == "01")
	{
		$bmonth = "12";
		$byear = $year - 1;
		$bday = search_day($bmonth , $byear);
		$bdate = "$byear-$bmonth-$bday";
	}
	else
	{
		$bmonth = $month - 1;
		if(strlen($bmonth) == 1){$bmonth = "0$bmonth";}
		$byear = $year;
		$bday = search_day($bmonth , $byear);
		$bdate = "$byear-$bmonth-$bday";
	}
	
	$CorporationArray2D = CorporationArray2D($day , $month , $year , $myWhere);
	
	// หาจำนวนนิติบุคคลที่จะนำส่ง NCB ในครั้งนี้
	$qry_numCorp = pg_query("select ta_array_count('$CorporationArray2D')");
	$numCorp = pg_fetch_result($qry_numCorp,0);
	
	// กำหนดค่าให้ตัวแปร array
	$qry_array_list_unique = pg_query("select ta_array_list_unique('$CorporationArray2D') as \"array_list\" ");
	while($res_array_list = pg_fetch_array($qry_array_list_unique))
	{
		$a = $res_array_list["array_list"];
		//echo $a;
		
		// กำหนดค่า
		$qry_array_get = pg_query("select ta_array_get('$CorporationArray2D', '$a') as \"array_get\" ");
		$corpID[$a] = pg_fetch_result($qry_array_get,0);
	}
	
	$textreturn = "";
	
	for($b=1;$b<=$numCorp;$b++)
	{
		$query = pg_query("select * from public.\"th_corp\" where \"corpID\" = '$corpID[$b]' ");
		while($result = pg_fetch_array($query))
		{
			$corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
		}
		
		// หาเลขที่สัญญา
		$query_pair = pg_query("select * from public.\"vthcap_ContactCus_detail\" where \"CusID\" = '$corpID[$b]' and \"type\" = '2' and \"CusState\" <> '2' $myWhere ");
		while($result_pair = pg_fetch_array($query_pair))
		{
			$CS = 0;
			$numdaydebt = "";
			
			$contractID = trim($result_pair["contractID"]); // เลขที่สัญญา
			$contractText = substr($contractID,3,4);
			
			//--- ตรวจสอบวันที่ทำสัญญา
				$sql_chk_conDate = pg_query("SELECT \"conDate\" FROM \"thcap_contract\" WHERE \"contractID\" = '$contractID' ");
				$chk_conDate = pg_fetch_result($sql_chk_conDate,0);
				if($chk_conDate > "$year-$month-$day")
				{
					continue;
				}
			//-- จบการตรวจสอบวันที่ทำสัญญา
			
			//--- ถ้าเคยปิดบัญชีไปเมื่อเดือนก่อนหน้าแล้ว ไม่ต้องเอามาแสดงอีก คือให้หาคนต่อไปเลย คนนี้ไม่เอาแล้ว
				$sql_chk_close = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" < '$year-$month-$day' AND \"contractID\" = '$contractID' AND \"amountOwn\" <= '0' $myWhere ");
				$row_chk_close = pg_num_rows($sql_chk_close);
				if($row_chk_close > 0)
				{
					continue;
				}
			//---
			
			// หาประเภทสินเชื่อ
			$qry_fullconType = pg_query("select \"thcap_get_creditType\"('$contractID')");
			$fullconType = pg_fetch_result($qry_fullconType,0);
			
			// หาข้อมูล CR
			if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
			{
				$queryCR = pg_query("select * from public.\"thcap_lease_contract\" where \"contractID\" = '$contractID' $myWhere ");
				$rowsCR = pg_num_rows($queryCR);
				if($rowsCR == 0){continue;} // ถ้าไม่มีข้อมูลในตาราง thcap_lease_contract แสดงว่าเป็นสัญญาวงเงิน  ไม่ต้องเอา
				
				while($resultCR = pg_fetch_array($queryCR))
				{
					$conDate = $resultCR["conDate"]; // วันที่ทำสัญญา
					$conStartDate = $resultCR["conStartDate"]; // วันที่รับเงินที่ขอกู้
					$conClosedDate = $resultCR["conClosedDate"]; // วันที่ปิดบัญชีจริง
					$conLoanAmt = $resultCR["conFinanceAmount"]; // จำนวนเงินกู้
					$conTerm = $resultCR["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
					$conMinPay = $resultCR["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
					$sendNCB = $resultCR["sendNCB"]; // ต้องส่ง NCB หรือไม่
				}
				if($sendNCB == "0")
				{
					continue;
				}
			}
			else
			{
				$queryCR = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' $myWhere ");
				$rowsCR = pg_num_rows($queryCR);
				if($rowsCR == 0){continue;} // ถ้าไม่มีข้อมูลในตาราง thcap_mg_contract แสดงว่าเป็นสัญญาวงเงิน  ไม่ต้องเอา
				
				while($resultCR = pg_fetch_array($queryCR))
				{
					$conDate = $resultCR["conDate"]; // วันที่ทำสัญญา
					$conStartDate = $resultCR["conStartDate"]; // วันที่รับเงินที่ขอกู้
					$conClosedDate = $resultCR["conClosedDate"]; // วันที่ปิดบัญชีจริง
					$conLoanAmt = $resultCR["conLoanAmt"]; // จำนวนเงินกู้
					$conTerm = $resultCR["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
					$conMinPay = $resultCR["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
					$sendNCB = $resultCR["sendNCB"]; // ต้องส่ง NCB หรือไม่
				}
				if($sendNCB == "0")
				{
					continue;
				}
			}
			
			// ถ้าปีที่ทำสัญญามากกว่า วันที่เลือก
			if(substr($conDate,0,4) > $year){continue;}
			
			// ตรวจสอบก่อนว่า เป็นสัญญาที่มีการใช้วงเงินจากสัญญาอื่นหรือไม่
			$qry_useCredit = pg_query("select * from \"vthcap_contract_creditRef_all\" where \"contractID\" = '$contractID' $myWhere ");
			$row_useCredit = pg_num_rows($qry_useCredit);
			
			if($row_useCredit > 0)
			{ // ถ้ามีการใช้วงเงิน
				$useCredit = "1";
			}
			else
			{ // ถ้าไม่มีการใช้วงเงิน
				$useCredit = "0";
			}
			
			// ถ้าเป็นสัญญาเงินกู้ก่อนปี 2013 และมีการใช้วงเงิน
			//if(strlen($contractID) > 15 && substr($conDate,0,4) < 2013)
			if($useCredit == "1" && substr($conDate,0,4) < 2013)
			{
				$contractID_2 = $contractID; // สัญญาเงินกู้
				$contractID = substr($contractID,0,15);
				
				if($c > 0)
				{
					for($v=1; $v<=$c; $v++)
					{
						if($oneContractID[$v] == $contractID)
						{
							$CS = 1; // มีสัญญานี้แล้ว
						}
					}
					if($CS == 1){continue;} // ถ้าสัญญาซ้ำ ไม่ต้องทำอีก
				}
				
				if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
				{
					$queryCR = pg_query("select '$contractID'::character varying as \"contractID\", min(\"conDate\") as \"conDate\", min(\"conStartDate\") as \"conStartDate\",
												sum(\"conFinanceAmount\")::numeric(15,2) as \"conLoanAmt\", max(\"conTerm\") as \"conTerm\" , min(\"conMinPay\")::numeric(15,2) as \"conMinPay\",
												max(thcap_checkcontractcloseddate(\"contractID\")) as \"conClosedDate\" , max(\"conEndDate\") as \"conEndDate\"
										from \"thcap_lease_contract\"
										where \"conCreditRef\"::text like '%$contractID%'
												and \"contractID\" not in(select a.\"contractID\"
												from \"vthcap_contract_creditRef_all\" a
												left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
												where \"contractCredit\"='$contractID'
												and thcap_checkcontractcloseddate(a.\"contractID\") is not null
												and thcap_checkcontractcloseddate(a.\"contractID\") < '$year-$month-01')
												and \"conDate\" <= '$date' and \"conDate\" <= '2012-12-31'
												and (thcap_checkcontractcloseddate(\"contractID\") <= '$date' or thcap_checkcontractcloseddate(\"contractID\") is null) ");
				}
				else
				{
					$queryCR = pg_query("select '$contractID'::character varying as \"contractID\", min(\"conDate\") as \"conDate\", min(\"conStartDate\") as \"conStartDate\",
												sum(\"conLoanAmt\")::numeric(15,2) as \"conLoanAmt\", max(\"conTerm\") as \"conTerm\" , min(\"conMinPay\")::numeric(15,2) as \"conMinPay\",
												max(thcap_checkcontractcloseddate(\"contractID\")) as \"conClosedDate\" , max(\"conEndDate\") as \"conEndDate\"
										from \"thcap_mg_contract\"
										where \"conCreditRef\"::text like '%$contractID%'
												and \"contractID\" not in(select a.\"contractID\"
												from \"vthcap_contract_creditRef_all\" a
												left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
												where \"contractCredit\"='$contractID'
												and thcap_checkcontractcloseddate(a.\"contractID\") is not null
												and thcap_checkcontractcloseddate(a.\"contractID\") < '$year-$month-01')
												and \"conDate\" <= '$date' and \"conDate\" <= '2012-12-31'
												and (thcap_checkcontractcloseddate(\"contractID\") <= '$date' or thcap_checkcontractcloseddate(\"contractID\") is null) ");
				}
				
				while($resultCR = pg_fetch_array($queryCR))
				{
					$conDate = $resultCR["conDate"]; // วันที่ทำสัญญา
					$conStartDate = $resultCR["conStartDate"]; // วันที่รับเงินที่ขอกู้
					$conClosedDate = $resultCR["conClosedDate"]; // วันที่ปิดบัญชีจริง
					$conLoanAmt = $resultCR["conLoanAmt"]; // จำนวนเงินกู้
					$conTerm = $resultCR["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
					$conMinPay = $resultCR["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
					//$conEndDate = $resultCR["conEndDate"]; // วันที่ครบกำหนดปิดบัญชี
				}
				
				// หาวันที่ทำสัญญาที่น้อยที่สุด
				if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
				{
					$qry_conDate = pg_query("select min(\"conDate\") from \"thcap_lease_contract\" where \"conCreditRef\"::text like '%$contractID%' ");
				}
				else
				{
					$qry_conDate = pg_query("select min(\"conDate\") from \"thcap_mg_contract\" where \"conCreditRef\"::text like '%$contractID%' ");
				}
				$conDate = pg_fetch_result($qry_conDate,0);
				// จบการหาวันที่ทำสัญญาที่น้อยที่สุด
				
				// ดูก่อนว่าปิดสัญญาครบทุกอันหรือยัง
				if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
				{
					$qry_chkConClose = pg_query("select *
												from \"thcap_lease_contract\"
												where \"conCreditRef\"::text like '%$contractID%'
												and \"contractID\" not in(select a.\"contractID\"
												from \"vthcap_contract_creditRef_all\" a
												left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
												where \"contractCredit\"='$contractID'
												and thcap_checkcontractcloseddate(a.\"contractID\") is not null
												and thcap_checkcontractcloseddate(a.\"contractID\") < '$year-$month-01')
												and \"conDate\" <= '$date' and \"conDate\" <= '2012-12-31'
												and thcap_checkcontractcloseddate(\"contractID\") is null ");
				}
				else
				{
					$qry_chkConClose = pg_query("select *
												from \"thcap_mg_contract\"
												where \"conCreditRef\"::text like '%$contractID%'
												and \"contractID\" not in(select a.\"contractID\"
												from \"vthcap_contract_creditRef_all\" a
												left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
												where \"contractCredit\"='$contractID'
												and thcap_checkcontractcloseddate(a.\"contractID\") is not null
												and thcap_checkcontractcloseddate(a.\"contractID\") < '$year-$month-01')
												and \"conDate\" <= '$date' and \"conDate\" <= '2012-12-31'
												and thcap_checkcontractcloseddate(\"contractID\") is null ");
				}
				$row_chkConClose = pg_num_rows($qry_chkConClose);
				if($row_chkConClose > 0){$conClosedDate = "";} // ถ้าในกลุ่มนี้ยังปิดบัญชีไม่ครบ
				// จบการดูก่อนว่าปิดสัญญาครบทุกอันหรือยัง
				
				// ถ้ามีวันที่ปิดบัญชี ตรวจสอบก่อนว่าถูกวันหรือไม่
				if($conClosedDate != "")
				{
					if($conClosedDate > $date)
					{ // ถ้าวันที่ปิดบัญชีล่าสุด มากกว่าวันที่เลือก
						$conClosedDate = "";
					}
				}
				// จบถ้ามีวันที่ปิดบัญชี ตรวจสอบก่อนว่าถูกวันหรือไม่
				
				// ประวัติหนี้และการรับชำระ
				if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
				{
					// วันที่จ่ายล่าสุด
					$qry_Date_Of_Last_Payment = pg_query("SELECT thcap_get_lease_lastpaydate('$contractID', '$date')");
					$lastPayDate = pg_fetch_result($qry_Date_Of_Last_Payment,0);
					
					// เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
					if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
					{
						$qry_amount_owed_or_principal_or_credit_use = pg_query("select \"thcap_amountown\"('$contractID', '$date', '3')");
						$amountOwn = pg_fetch_result($qry_amount_owed_or_principal_or_credit_use,0);
					}
					else
					{
						$qry_amount_owed_or_principal_or_credit_use = pg_query("select thcap_get_lease_totalleft('$contractID', '$date')");
						$amountOwn = pg_fetch_result($qry_amount_owed_or_principal_or_credit_use,0);
					}
					
					// จำนวนยอดค้างผ่อนชำระ
					$qry_amount_past_due = pg_query("select thcap_get_lease_backamt('$contractID', '$date')");
					$amountRemain = pg_fetch_result($qry_amount_past_due,0);
					
					// วันที่ผิดนัดชำระ
					$gry_defaultDate = pg_query("select thcap_get_lease_backdate('$contractID', '$date')");
					$defaultDate = pg_fetch_result($gry_defaultDate,0);
					
					$sql_money = pg_query("SELECT * FROM \"thcap_lease_contract\" WHERE \"contractID\" = '$contractID' ");
					while($resmoney = pg_fetch_array($sql_money))
					{
						$installment_amount = trim($resmoney["conMinPay"]); // tag 21 ยอดจ่ายขั้นต่ำ
						$credit_limit_or_original_loan_amount = trim($resmoney["conLoanAmt"]); // tag 12
						//$conEndDate = $resmoney["conEndDate"]; // วันที่ครบกำหนดปิดบัญชี
					}
				}
				else
				{
					$sql_TL = pg_query("SELECT min(\"defaultDate\") as \"defaultDate\", sum(\"amountRemain\") as \"amountRemain\", max(\"lastPayDate\") as \"lastPayDate\", sum(\"amountOwn\") as \"amountOwn\"
										FROM public.\"thcap_ncb_statement\"
										WHERE \"asOfDate\" = '$year-$month-$day' AND \"contractID\" like '$contractID%'
										AND \"contractID\" not in((select \"contractID\" from \"thcap_mg_contract\" where \"contractID\" like '$contractID%' and substr(\"conDate\"::text,1,4)::integer > 2012)) ");
					while($resTL = pg_fetch_array($sql_TL))
					{
						$defaultDate = trim($resTL["defaultDate"]); // วันที่ผิดนัดชำระ
						$amountRemain = trim($resTL["amountRemain"]); // จำนวนยอดค้างผ่อนชำระ
						$lastPayDate = trim($resTL["lastPayDate"]); // วันที่จ่ายล่าสุด
						$amountOwn = trim($resTL["amountOwn"]); // เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
					}
					
					/*$sql_money = pg_query("SELECT * FROM \"thcap_mg_contract\" WHERE \"contractID\" = '$contractID' ");
					while($resmoney = pg_fetch_array($sql_money))
					{
						$conEndDate = $resmoney["conEndDate"]; // วันที่ครบกำหนดปิดบัญชี
					}*/
				}
				
				$conEndDate = ""; // fix ค่า เนื่องจาก สัญญาวงเงิน จะไม่มีวันที่ครบกำหนดปิดบัญชี
			}
			else
			{
				// ประวัติหนี้และการรับชำระ
				if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
				{
					// วันที่จ่ายล่าสุด
					$qry_Date_Of_Last_Payment = pg_query("SELECT thcap_get_lease_lastpaydate('$contractID', '$date')");
					$lastPayDate = pg_fetch_result($qry_Date_Of_Last_Payment,0);
					
					// เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
					$qry_amount_owed_or_principal_or_credit_use = pg_query("select thcap_get_lease_totalleft('$contractID', '$date')");
					$amountOwn = pg_fetch_result($qry_amount_owed_or_principal_or_credit_use,0);
					
					// จำนวนยอดค้างผ่อนชำระ
					$qry_amount_past_due = pg_query("select thcap_get_lease_backamt('$contractID', '$date')");
					$amountRemain = pg_fetch_result($qry_amount_past_due,0);
					
					// วันที่ผิดนัดชำระ
					$gry_defaultDate = pg_query("select thcap_get_lease_backdate('$contractID', '$date')");
					$defaultDate = pg_fetch_result($gry_defaultDate,0);
					
					$sql_money = pg_query("SELECT * FROM \"thcap_lease_contract\" WHERE \"contractID\" = '$contractID' ");
					while($resmoney = pg_fetch_array($sql_money))
					{
						$installment_amount = trim($resmoney["conMinPay"]); // tag 21 ยอดจ่ายขั้นต่ำ
						$credit_limit_or_original_loan_amount = trim($resmoney["conLoanAmt"]); // tag 12
						$conEndDate = $resmoney["conEndDate"]; // วันที่ครบกำหนดปิดบัญชี
					}
				}
				else
				{
					$sql_TL = pg_query("SELECT * FROM public.\"thcap_ncb_statement\" WHERE \"asOfDate\" = '$year-$month-$day' AND \"contractID\" = '$contractID' ");
					while($resTL = pg_fetch_array($sql_TL))
					{
						$defaultDate = trim($resTL["defaultDate"]); // วันที่ผิดนัดชำระ
						$amountRemain = trim($resTL["amountRemain"]); // จำนวนยอดค้างผ่อนชำระ
						$lastPayDate = trim($resTL["lastPayDate"]); // วันที่จ่ายล่าสุด
						$amountOwn = trim($resTL["amountOwn"]); // เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date) เป็นจำนวนเงินที่ค้าง
					}
					
					$sql_money = pg_query("SELECT * FROM \"thcap_mg_contract\" WHERE \"contractID\" = '$contractID' ");
					while($resmoney = pg_fetch_array($sql_money))
					{
						$conEndDate = $resmoney["conEndDate"]; // วันที่ครบกำหนดปิดบัญชี
					}
				}
				
				// หาวันที่ปิดบัญชีจริง
				$qry_conClosedDate = pg_query("select thcap_checkcontractcloseddate('$contractID') ");
				$conClosedDate = pg_result($qry_conClosedDate,0);
			}
			
			// ถ้ามีวันที่ปิดบัญชี
			if($conClosedDate != "")
			{
				// หาเดือนที่ปิดบัญชี
				$qry_mclose = pg_query("select date_part('month', timestamp '$conClosedDate')");
				$mclose = pg_result($qry_mclose,0);
				
				// หาปีที่ปิดบัญชี
				$qry_yclose = pg_query("select date_part('year', timestamp '$conClosedDate')");
				$yclose = pg_result($qry_yclose,0);
				
				// ถ้าปีที่ปิดบัญชี น้อยกว่า ปีที่เลือก หรือ ปีที่ปิดบัญชีเท่ากับปีที่เลือก แต่เดือนที่ปิดบัญชีน้อยกว่าเดือนที่เลือก
				if(($yclose < $year) || ($yclose == $year && $mclose < $month))
				{
					continue;
				}
			}
			
			// หาจำนวนบุคคลที่ผูกกับเลขที่สัญญานั้นๆ
			//$query_pair_row = pg_query("select * from public.\"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"type\" = '2' ");
			$query_pair_row = pg_query("select * from public.\"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" <> '2' "); // หาทั้งนิติบุคคล และ บุคคลธรรมดา (เพราะ ถ้า I เป็นเดี่ยว ถ้า J เป็นร่วม แต่ว่าบางครั้งมีนิติบุคคลเดียว แต่อาจเป็น J เพราะมีบุคคลธรรมดาอยู่ด้วย)
			$numrows_pair_row = pg_num_rows($query_pair_row);
			
			
			if($conDate != "")
			{
				$conDateText = str_replace("-","",$conDate);
			}
			else
			{
				$conDateText = "19000101";
			}
			
			/*if($conStartDate != "")
			{
				$conStartDateText = str_replace("-","",$conStartDate);
			}
			else
			{
				$conStartDateText = "19000101";
			}*/
			
			// วันครบกำหนด ปิดสัญญา
			if($conEndDate != "")
			{
				$conEndDateText = str_replace("-","",$conEndDate);
			}
			else
			{
				$conEndDateText = "19000101";
			}
			
			if($conLoanAmt != "")
			{
				$conLoanAmtText = floor($conLoanAmt); // ปัดเศษลง
			}
			else
			{
				$conLoanAmtText = "0";
			}
			
			if($conMinPay != "")
			{
				$conMinPayText = floor($conMinPay); // ปัดเศษลง
			}
			else
			{
				$conMinPayText = "0";
			}
			
			if($lastPayDate != "")
			{
				$lastPayDateText = str_replace("-","",$lastPayDate);
			}
			else
			{
				$lastPayDateText = "19000101";
			}
			
			if($conClosedDate != "") // ถ้ามีวันที่ปิดบัญชี
			{
				if($amountOwn <= 0.00)
				{ // ถ้าไม่มีเงินค้างอยู่แล้วจริงๆ
					$conClosedDateText = str_replace("-","",$conClosedDate); // วันที่ปิดบัญชี
					$account_status = "1020002"; // สัญญาปิดบัญชีแล้ว
				}
				else
				{ // ถ้ายังมีเงินค้างอยู่ ถือว่ายังไม่ปิดบัญชี
					$conClosedDateText = "";
					
					// อยู่ในกระบวนการทางกฎหมาย หรือไม่ ของวันที่สนใจ
					$qry_thcap_get_all_isSue = pg_query("select \"thcap_get_all_isSue\"('$contractID','$date') ");
					$thcap_get_all_isSue = pg_result($qry_thcap_get_all_isSue,0);
					
					// อยู่ระหว่างชำระหนี้ตามคำพิพากษาตามยอม หรือไม่ ของวันที่สนใจ
					$qry_thcap_get_all_isRestructure = pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$date') ");
					$thcap_get_all_isRestructure = pg_result($qry_thcap_get_all_isRestructure,0);
					
					// โอนหรือขายหนี้ไปบุคคลอื่น หรือไม่ ของวันที่สนใจ
					$qry_thcap_get_all_isSold = pg_query("select \"thcap_get_all_isSold\"('$contractID','$date') ");
					$thcap_get_all_isSold = pg_result($qry_thcap_get_all_isSold,0);
					
					// โอนหรือขายหนี้ไปบุคคลอื่น หรือไม่ ของเดือนก่อนหน้า ถ้าใช่ ไม่ต้องนำมาแสดงอีก
					$bqry_thcap_get_all_isSold = pg_query("select \"thcap_get_all_isSold\"('$contractID','$bdate') ");
					$bthcap_get_all_isSold = pg_result($bqry_thcap_get_all_isSold,0);
					if($bthcap_get_all_isSold == "1"){continue;}
					
					if($thcap_get_all_isSold == "1")
					{
						$account_status = "1020011"; // โอนหรือขายหนี้ไปบุคคลอื่น
					}
					elseif($thcap_get_all_isSue == "1" && $thcap_get_all_isRestructure == "1")
					{
						$account_status = "1020006"; // อยู่ในกระบวนการทางกฎหมาย
					}
					elseif($thcap_get_all_isSue == "1")
					{
						$account_status = "1020005"; // อยู่ระหว่างชำระหนี้ตามคำพิพากษาตามยอม
					}
					else
					{
						if($defaultDate != "")
						{
							//$numdaydebt = ceil((strtotime($year-$month-$day) - strtotime($defaultDate))/(60*60*24)); // จำนวนวันค้างชำระ
							$qry_numdaydebt = pg_query("select '$year-$month-$day'::date - '$defaultDate'::date ");
							$numdaydebt = pg_fetch_result($qry_numdaydebt,0); // จำนวนวันค้างชำระ
							if($numdaydebt > 90)
							{
								$account_status = "1020004"; // ถ้าค้างชำระเกิด 90 วัน
							}
							else
							{
								$account_status = "1020001"; // บัญชีปกติ
							}
						}
						else
						{
							$account_status = "1020001"; // บัญชีปกติ
						}
					}
				}
			}
			else // ถ้ายังไม่มีวันที่ปิดบัญชี
			{
				$conClosedDateText = ""; // วันที่ปิดบัญชี
				
				// อยู่ในกระบวนการทางกฎหมาย หรือไม่ ของวันที่สนใจ
				$qry_thcap_get_all_isSue = pg_query("select \"thcap_get_all_isSue\"('$contractID','$date') ");
				$thcap_get_all_isSue = pg_result($qry_thcap_get_all_isSue,0);
				
				// อยู่ระหว่างชำระหนี้ตามคำพิพากษาตามยอม หรือไม่ ของวันที่สนใจ
				$qry_thcap_get_all_isRestructure = pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$date') ");
				$thcap_get_all_isRestructure = pg_result($qry_thcap_get_all_isRestructure,0);
				
				// โอนหรือขายหนี้ไปบุคคลอื่น หรือไม่ ของวันที่สนใจ
				$qry_thcap_get_all_isSold = pg_query("select \"thcap_get_all_isSold\"('$contractID','$date') ");
				$thcap_get_all_isSold = pg_result($qry_thcap_get_all_isSold,0);
				
				// โอนหรือขายหนี้ไปบุคคลอื่น หรือไม่ ของเดือนก่อนหน้า ถ้าใช่ ไม่ต้องนำมาแสดงอีก
				$bqry_thcap_get_all_isSold = pg_query("select \"thcap_get_all_isSold\"('$contractID','$bdate') ");
				$bthcap_get_all_isSold = pg_result($bqry_thcap_get_all_isSold,0);
				if($bthcap_get_all_isSold == "1"){continue;}
				
				if($amountOwn <= 0.00)
				{ // ถ้าไม่มีเงินค้างอยู่แล้ว
					$conClosedDateText = str_replace("-","",$lastPayDate); // กำหนดให้วันที่ปิดบัญชี คือวันที่จ่ายล่าสุด
					$account_status = "1020002"; // สัญญาปิดบัญชีแล้ว
				}
				elseif($thcap_get_all_isSold == "1")
				{
					$account_status = "1020011"; // โอนหรือขายหนี้ไปบุคคลอื่น
				}
				elseif($thcap_get_all_isSue == "1" && $thcap_get_all_isRestructure == "1")
				{
					$account_status = "1020006"; // อยู่ในกระบวนการทางกฎหมาย
				}
				elseif($thcap_get_all_isSue == "1")
				{
					$account_status = "1020005"; // อยู่ระหว่างชำระหนี้ตามคำพิพากษาตามยอม
				}
				elseif($defaultDate != "")
				{
					//$numdaydebt = ceil((strtotime($year-$month-$day) - strtotime($defaultDate))/(60*60*24)); // จำนวนวันค้างชำระ
					$qry_numdaydebt = pg_query("select '$year-$month-$day'::date - '$defaultDate'::date ");
					$numdaydebt = pg_fetch_result($qry_numdaydebt,0); // จำนวนวันค้างชำระ
					if($numdaydebt > 90)
					{
						$account_status = "1020004"; // ถ้าค้างชำระเกิด 90 วัน
					}
					else
					{
						$account_status = "1020001"; // บัญชีปกติ
					}
				}
				else
				{
					$account_status = "1020001"; // บัญชีปกติ
				}
			}
			
			//--- หา Payment terms (Tag 10)
			if($conTerm == "1" || $conTerm == "")
			{
				$paymentTermsText = "0";
				$conTerm = "0";
			}
			else
			{
				$pt = 0;
				$sql_paymentTerms = pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' order by \"ptDate\" DESC ");
				while($res_pt = pg_fetch_array($sql_paymentTerms))
				{
					$pt++;
					$ptDateArray[$pt] = $res_pt["ptDate"];
				}
				
				$ptArray = "{}";
				for($p=1; $p<$pt; $p++)
				{
					$t = $p+1;
					$sql_ptArray = pg_query("select array_append('$ptArray'::character varying[], ('$ptDateArray[$p]'::date - '$ptDateArray[$t]'::date)::character varying)");
					$res_ptArray = pg_fetch_array($sql_ptArray);
					list($ptArray) = $res_ptArray;
				}
				
				if($ptArray != "{}")
				{
					$qry_ptPopula = pg_query("select ta_array1d_popularity('$ptArray')");
					$res_ptPopula = pg_fetch_array($qry_ptPopula);
					list($paymentTermsText) = $res_ptPopula;
				}
				else
				{
					$paymentTermsText = "0";
				}
				
				if($paymentTermsText >= 29 && $paymentTermsText <= 31)
				{
					$paymentTermsText = "30";
				}
				else
				{
					$paymentTermsText = ceil($paymentTermsText);
				}
			}
			//--- จบการหา Payment terms
			
			//--- หา credittype (Tag 11)
			$qry_credittype = pg_query("SELECT \"thcap_getNCB_COM_credittype\"('$contractID')");
			$credittypeText = pg_fetch_result($qry_credittype,0);
			
				//-- ถ้าเป็นเลขที่สัญญา FA-BK01-5500006 หรือ FA-BK01-5500008
				if($contractID == "FA-BK01-5500006" || $contractID == "FA-BK01-5500008")
				{
					$credittypeText = "1060032";
				}
			//--- จบการหา credittype
			
			if($defaultDate != "")
			{
				$defaultDateText = str_replace("-","",$defaultDate);
			}
			else
			{
				$defaultDateText = "19000101";
			}
			
			if($numdaydebt == "")
			{
				$past_due_days_text = "1160001";
			}
			else
			{
				$past_due_days_text = past_due_days($numdaydebt);
			}
			
			if($numdaydebt != "")
			{
				$amountRemainText = floor($amountRemain); // ปัดเศษลง
			}
			else
			{
				$amountRemainText = "0";
			}
			
			$amountOwnText = floor($amountOwn); // ปัดเศษลง
			
			if($numrows_pair_row > 1)
			{
				$numrows_pair_row_text = "J"; // ถ้ามีมากกว่าหนึ่งนิติบุคคล แสดงว่าสัญญานี้เป็นแบบ joint
			}
			elseif($numrows_pair_row == 1)
			{
				$numrows_pair_row_text = "I";
			}
			
			$textreturn .= "\"$b\",\"$contractText\",\"$contractID\",\"\",\"1120570\",\"\",\"$conDateText\",\"$conEndDateText\",\"$conClosedDateText\",\"$paymentTermsText\",\"$credittypeText\",\"$conLoanAmtText\",\"1070151\"";
			$textreturn .= ",\"$conTerm\",\"$conMinPayText\",\"$lastPayDateText\",\"19000101\",\"\",\"\",\"$account_status\",\"$defaultDateText\",\"\",\"\",\"\",\"\",\"$past_due_days_text\",\"$amountRemainText\"";
			$textreturn .= ",\"$amountOwnText\",\"$numrows_pair_row_text\",\"\",\"\",\"\"";
			
			$textreturn .= "<br>";
			
			$c++;
			$oneContractID[$c] = $contractID;
		}
	}
	
	if($numrows >= 1){$textreturn = substr($textreturn,0,strlen($textreturn)-4);} // ตัดบรรทัดว่างๆล่างสุดทิ้ง คือตัด <br> สุดท้ายทิ้ง
	
	return $textreturn;
}

function past_due_days($numdaydebt)
{
	if($numdaydebt <= 0)
	{
		return "1160001";
	}
	elseif($numdaydebt >= 1 && $numdaydebt <= 30)
	{
		return "1160002";
	}
	elseif($numdaydebt >= 31 && $numdaydebt <= 60)
	{
		return "1160003";
	}
	elseif($numdaydebt >= 61 && $numdaydebt <= 90)
	{
		return "1160004";
	}
	elseif($numdaydebt >= 91 && $numdaydebt <= 120)
	{
		return "1160005";
	}
	elseif($numdaydebt >= 121 && $numdaydebt <= 150)
	{
		return "1160006";
	}
	elseif($numdaydebt >= 151 && $numdaydebt <= 180)
	{
		return "1160007";
	}
	elseif($numdaydebt >= 181 && $numdaydebt <= 210)
	{
		return "1160008";
	}
	elseif($numdaydebt >= 211 && $numdaydebt <= 240)
	{
		return "1160009";
	}
	elseif($numdaydebt >= 241 && $numdaydebt <= 270)
	{
		return "1160010";
	}
	elseif($numdaydebt >= 271 && $numdaydebt <= 300)
	{
		return "1160011";
	}
	elseif($numdaydebt > 300)
	{
		return "1160012";
	}
}
?>