<?php
set_time_limit(150);

function CorporationArray2D($day , $month , $year , $myWhere) // หานิติบุคคลที่จะส่ง NCB ในครั้งนี้
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
	
	$i = 0;
	$textreturn = "{}";
	$query = pg_query("select * from public.\"th_corp\" order by \"corpID\" ");
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$corpID = $result["corpID"]; // รหัสนิติบุคคล
		
		// หาเลขที่สัญญา
		$query_chk_pair = pg_query("select * from public.\"vthcap_ContactCus_detail\" where \"CusID\" = '$corpID' and \"type\" = '2' and \"CusState\" <> '2' $myWhere ");
		$row_query_pair = pg_num_rows($query_chk_pair);
		if($row_query_pair > 0)
		{
			$HaveContract = "no"; // ใช้เช็คว่านิติบุคคลนี้มีสัญญาที่ยังไม่ปิดบัญชีหรือไม่
			$have_conDate = "no"; // ตรวจสอบว่าถึงเวลาส่งหรือยัง
			while($result_pair_chkHave = pg_fetch_array($query_chk_pair))
			{
				$contractID_chkHave = $result_pair_chkHave["contractID"]; // เลขที่สัญญา
				
				//--- ถ้าเคยปิดบัญชีไปเมื่อเดือนก่อนหน้าแล้ว ไม่ต้องเอามาแสดงอีก คือให้หาคนต่อไปเลย คนนี้ไม่เอาแล้ว
				$sql_chk_close_chkHave = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" < '$year-$month-$day' AND \"contractID\" = '$contractID_chkHave' AND \"amountOwn\" <= '0' $myWhere ");
				$row_chk_close_chkHave = pg_num_rows($sql_chk_close_chkHave);
				if($row_chk_close_chkHave > 0)
				{
					
				}
				else
				{
					$HaveContract = "yes";
				}
				
				//--- ตรวจสอบวันที่ทำสัญญา
					$sql_chk_conDate = pg_query("SELECT \"conDate\" FROM \"thcap_contract\" WHERE \"contractID\" = '$contractID_chkHave' ");
					$chk_conDate = pg_fetch_result($sql_chk_conDate,0);
					if($chk_conDate <= "$year-$month-$day")
					{
						$have_conDate = "yes";
					}
				//-- จบการตรวจสอบวันที่ทำสัญญา
			}
			if($HaveContract == "yes")
			{
				//$c = 0; // สัญญาที่
			}
			if($have_conDate == "no")
			{ // ถ้ายังไม่ถึงเวลาส่ง
				continue;
			}
		}
		else
		{ // ถ้าไม่มีสัญญาให้ข้ามคนนี้ไปเลย
			continue;
		}
		
		$corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
		
		// หาเลขที่สัญญา
		$query_pair = pg_query("select * from public.\"vthcap_ContactCus_detail\" where \"CusID\" = '$corpID' and \"type\" = '2' and \"CusState\" <> '2' $myWhere ");
		while($result_pair = pg_fetch_array($query_pair))
		{
			$CS = 0;
			$numdaydebt = "";
			
			$contractID = trim($result_pair["contractID"]); // เลขที่สัญญา
			$contractText = substr($contractID,3,4);
			
			// หา วันที่ทำสัญญา และ วงเงินสินเชื่อ
			$sql_chk_conDate_conCredit = pg_query("SELECT \"conDate\", \"conCredit\" FROM \"thcap_contract\" WHERE \"contractID\" = '$contractID' ");
			$chk_conDate = pg_fetch_result($sql_chk_conDate_conCredit,0); // วันที่ทำสัญญา
			$chk_conCredit = pg_fetch_result($sql_chk_conDate_conCredit,1); // วงเงินสินเชื่อ
			
			//--- ตรวจสอบวันที่ทำสัญญา
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
			
			// หาว่ามีข้อมูลในตาราง thcap_mg_contract_current หรือไม่
			$qry_mg_contract_current = pg_query("select * from thcap_mg_contract_current where \"contractID\" = '$contractID' ");
			$row_mg_contract_current = pg_num_rows($qry_mg_contract_current);
			
			//-- ตรวจสอบว่า โอนหรือขายหนี้ไปบุคคลอื่น แล้วหรือยัง ก่อนหน้าเดือนนี้ ถ้าใช่ ไม่ต้องนำมาแสดงอีก
				if($chk_conCredit == "" || $row_mg_contract_current > 0)
				{ // ถ้าไม่ใช่สัญญาวงเงิน หรือ มีข้อมูล thcap_mg_contract_current
					$bqry_thcap_get_all_isSold = pg_query("select \"thcap_get_all_isSold\"('$contractID','$bdate') ");
					$bthcap_get_all_isSold = pg_result($bqry_thcap_get_all_isSold,0);
					if($bthcap_get_all_isSold == "1"){continue;}
				}
			// ---
			
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
			
			// หาวันที่ปิดบัญชีจริง
			$qry_conClosedDate = pg_query("select thcap_checkcontractcloseddate('$contractID') ");
			$conClosedDate = pg_result($qry_conClosedDate,0);
			
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
			}
			
			
			if($corpID_chk != $corpID)
			{ // คนที่เท่าไหร่
				$i++;
				$corpID_chk = $corpID;
			}
			
			// หาว่ามีคนลำดับที่ $i อยู่แล้วหรือยัง
			$qry_array_check = pg_query("select ta_array_check('$textreturn', '$i')");
			$array_check = pg_fetch_result($qry_array_check,0);
			
			// ถ้ายังไม่มีคำลำดับที่ $i
			if($array_check == 0)
			{
				$qry_array_add = pg_query("select ta_array_add('$textreturn', '$i', '$corpID')");
				$textreturn = pg_fetch_result($qry_array_add,0);
			}
			
			$c++;
			$oneContractID[$c] = $contractID;
		}
	}
	
	return $textreturn;
}
?>