<?php
		$txtbutton_main = "พิมพ์สัญญาเช่าซื้อทรัพย์สิน";
		$txtbutton_sec = "พิมพ์สัญญาค้ำประกัน";
		
		$linkpdf_main = 'pdf_contract_2556.php';
		$linkpdf_sec = 'pdf_guarantee_thcap.php';
		
		// สัญญาเช่าซื้อ -------------------------------------------------------------------------
		
	// หาข้อมูลสัญญา
$qry_contractDetail = pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractID' ");
while($res_contractDetail = pg_fetch_array($qry_contractDetail))
{
	$av_datestart = $res_contractDetail["conDate"]; // วันที่ทำสัญญา
	$se_total = $res_contractDetail["conTerm"]; // จำนวนงวด
	$fdate = $res_contractDetail["conRepeatDueDay"]; // จ่ายทุกๆวันที่
}
	
// ค้นหารหัสผู้กู้หลัก
$qry_cusMain = pg_query("select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
$f_idno = pg_fetch_result($qry_cusMain,0);

// ข้อมูลผู้กู้หลัก
$qry_cusDetail = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$f_idno' ");
while($res_cusDetail = pg_fetch_array($qry_cusDetail))
{
	$cus_name = $res_cusDetail["full_name"]; // ชื่อเต็มลูกค้า
	$cusType = $res_cusDetail["type"]; // รหัสประเภทลูกค้า 1:ลูกค้าธรรมดา 2:ลูกค้านิติบุคคล
	
	if($cusType == '1')
	{
		$qry_Fn = pg_query("select * from \"Fn\" where \"CusID\" = '$f_idno' ");
		while($res_Fn = pg_fetch_array($qry_Fn))
		{
			$cus_nation = $res_Fn["N_SAN"]; // สัญชาติ
			$cus_type = $res_Fn["N_CARD"]; // ประเภทบัตรประจำตัว
			$cus_nid = ($res_Fn["N_IDCARD"]); // เลขที่บัตร
		}
		
		if($cus_type=="บัตรประชาชน" || $cus_type=="ประชาชน")
		{
			$cus_no_txt = " ผู้ถือบัตรประชาชนเลขที่ $cus_nid";
		}
		else
		{
			$cus_no_txt = " $cus_type เลขที่ $cus_nid";
		}
	}
	elseif($cusType == '2')
	{
		$qry_corp = pg_query("select * from \"th_corp\" where \"corpID\" = '$f_idno' ");
		while($res_corp = pg_fetch_array($qry_corp))
		{
			$CountryCode = $res_corp["CountryCode"]; // รหัสสัญชาติ
			$cus_nid=trim($res_corp["corp_regis"]); // เลขที่บัตร
			
			// หาชื่อสัญชาติ
			$qry_Country = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode' ");
			$cus_nation = pg_fetch_result($qry_Country,0); // สัญชาติ
		}
		
		$cus_no_txt = " เลขทะเบียนนิติบุคคล $cus_nid";
	}
}

//กรณีแสดงสัญชาติ
if($cus_nation==""){
	$cus_nation_txt="";
}else{
	$cus_nation_txt=" สัญชาติ $cus_nation";
}

// หาที่อยู่ลูกค้า
$qry_addr = pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusID\" = '$f_idno' and \"CusState\" = '0' ");
$cus_addr = pg_fetch_result($qry_addr,0);

//ค้นหาข้อมูลตัวรถ
$qry_carDetail = pg_query("select b.*,c.\"car_color\" as color from \"thcap_contract_asset\" a, \"thcap_asset_biz_detail_10\" b
							left join thcap_asset_biz_detail_10_color c on b.\"car_color\"=c.auto_id
							where a.\"assetDetailID\" = b.\"assetDetailID\"
							and a.\"contractID\" = '$contractID'");
while($res_carDetail = pg_fetch_array($qry_carDetail))
{
	$assetDetailID = $res_carDetail["assetDetailID"]; // รหัสสินทรัพย์
	$car_regis = $res_carDetail["regiser_no"]; // ทะเบียนรถ
	$car_year = $res_carDetail["year_regis"]; // ปีที่จดทะเบียน
	$car_number = $res_carDetail["motorcycle_no"]; // เลขที่ตัวถัง
	$car_color = $res_carDetail["color"]; // สีรถ
	$car_mi = $res_carDetail["car_mileage"]; // เลขไมล์
	$fp_fc_category = $res_carDetail["car_type"]; // ชนิดรถ
}

$qry_assetDetail = pg_query("select * from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
while($res_assetDetail = pg_fetch_array($qry_assetDetail))
{
	$asset_brand = $res_assetDetail["brand"]; // รหัสยี่ห้อ
	$asset_model = $res_assetDetail["model"]; // รหัสรุ่น
	$car_engine = $res_assetDetail["productCode"]; // เลขเครื่องยนต์
}
	
// หายี่ห้อ
$qry_assetBrand = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brandID\" = '$asset_brand' ");
$fp_band = pg_fetch_result($qry_assetBrand,0);

// หารุ่น
$qry_assetModel = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"modelID\" = '$asset_model' ");
$fp_model = pg_fetch_result($qry_assetModel,0);


IF($car_brand == "''" OR $car_brand == " " OR $car_brand == "-" OR $car_brand == "-" OR $car_brand == ""){ $car_brand = "--ไม่ระบุ--"; }
IF($car_year == "''" OR $car_year == " " OR $car_year == "-" OR $car_year == "-" OR $car_year == ""){ $car_year = "--ไม่ระบุ--"; }

IF($car_color == "''" OR $car_color == " " OR $car_color == "-" OR $car_color == "-" OR $car_color == ""){ $car_color = "--ไม่ระบุ--"; }
IF($car_regis == "''" OR $car_regis == " " OR $car_regis == "-" OR $car_regis == "-" OR $car_regis == ""){ $car_regis = "--ไม่ระบุ--"; }
$car_province = "กรุงเทพมหานคร"; // todo ปัจจุบัน fix กทม. ไปก่อนเนื่องจากไม่มีข้อมูล และตอนนี้มีเฉพาะ กทม.
IF($car_province == "''" OR $car_province == " " OR $car_province == "-" OR $car_province == "-" OR $car_province == ""){ $car_province = "--ไม่ระบุ--"; }

IF($fp_fc_category == "''" OR $fp_fc_category == " " OR $fp_fc_category == "-" OR $fp_fc_category == "-" OR $fp_fc_category == ""){ $fp_fc_category = "--ไม่ระบุ--"; }

IF($newcar == "''" OR $newcar == " " OR $newcar == "-" OR $newcar == "-" OR $newcar == ""){ $newcar = "--ไม่ระบุ--"; }
IF($car_number == "''" OR $car_number == " " OR $car_number == "-" OR $car_number == "-" OR $car_number == ""){ $car_number = "--ไม่ระบุ--"; }
IF($car_engine == "''" OR $car_engine == " " OR $car_engine == "-" OR $car_engine == "-" OR $car_engine == ""){ $car_engine = "--ไม่ระบุ--"; }

IF($car_mi == "''" OR $car_mi == " " OR $car_mi == "-" OR $car_mi == "-" OR $car_mi == ""){ $car_mi = "--ไม่ระบุ--"; }


// หาราคาเช่าซื้อไม่รวมเงินดาวน์ ไม่รวม VAT
$qry_sumCost = pg_query("select sum(\"debtNet\") as \"sumDebtNet\" from \"thcap_temp_otherpay_debt\"
					where \"contractID\" = '$contractID' and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\")");
$cost = pg_fetch_result($qry_sumCost,0);

// หาวันที่ครบกำหนดชำระงวดแรก
$qry_oneDue = pg_query("select \"debtDueDate\" from \"thcap_temp_otherpay_debt\"
						where \"contractID\" = '$contractID'
						and \"typePayID\" = account.\"thcap_mg_getMinPayType\"(\"contractID\") and \"typePayRefValue\" = '1'");
$fdate_thaidate = pg_fetch_result($qry_oneDue,0);

//หาที่อยู่ในการติดต่อและส่งเอกสาร
$qry_oneDue = pg_query("select \"sentaddress\" from \"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
$con_addr = pg_fetch_result($qry_oneDue,0);

//หาว่ารถใหม่หรือรถใช้แล้ว
$newcar = "X"; // todo ปัจจุบัน fix ไว้ว่าเป็นรถใหม่ไปก่อน
$oldcar = "";

	//กำหนดค่า Session ที่จะส่งไป print
	$_SESSION["contractID"] = $contractID;
	$_SESSION["av_datestart"] = $av_datestart;
	$_SESSION["cus_name"] = $cus_name;
	$_SESSION["cus_nid"] = $cus_nid;
	$_SESSION["cus_addr"] = $cus_addr;
	$_SESSION["fp_type"] = "จักรยานยนต์";    //รถ ปัจจุบัน fix จักรยานยนต์
	$_SESSION["fp_band"] = $fp_band;
	$_SESSION["fp_model"] = $fp_model;
	$_SESSION["car_year"] = $car_year;
	$_SESSION["car_regis"] = $car_regis;
	$_SESSION["car_province"] = $car_province;
	$_SESSION["fp_fc_category"] = $fp_fc_category;
	$_SESSION["car_number"] = $car_number;
	$_SESSION["car_engine"] = $car_engine;
	$_SESSION["car_mi"] = $car_mi;
	$_SESSION["car_color"] = $car_color;
	$_SESSION["cost"] = $cost;
	$_SESSION["fdate_thaidate"] = $fdate_thaidate;
	$_SESSION["se_total"] = $se_total;
	$_SESSION["fdate"] = $fdate;
	$_SESSION["newcar"] = $newcar;
	$_SESSION["oldcar"] = $oldcar;
	//เงือนไขการชำระเงิน ----------------------------------------------------------------------------
		$qry_con = pg_query("select *,(select sum(\"netIniCost\") from thcap_contract_inicost where \"contractID\" ='$conid') as \"netIniCost\",(select sum(\"sumIniCost\") from thcap_contract_inicost where \"contractID\" ='$conid') as \"sumIniCost\" from thcap_contract where \"contractID\" = '$conid'");
		$res_con = pg_fetch_array($qry_con);
			$conDate = $res_con["conDate"];
			$conDownToFinance = $res_con["conDownToFinance"];
			$conDownToFinanceVat = $res_con["conDownToFinanceVat"];
			$conFinAmtExtVat = $res_con["conFinAmtExtVat"];
			$conFinanceAmount = $res_con["conFinanceAmount"];
			$conFirstDue = $res_con["conFirstDue"];			//วันที่ชำระงวดแรก
			$conRepeatDueDay = $res_con["conRepeatDueDay"];	//วันที่ชำระงวดต่อไป
			$netIniCost = $res_con["netIniCost"];
			$sumIniCost = $res_con["sumIniCost"];
			$conTerm = $res_con["conTerm"];					//ระยะเวลาเช่าซื้อทั้งหมด (6)
			$conMinPay = $res_con["conMinPay"];				//งวดละ (7)
			$conLoanIniRate = $res_con["conLoanIniRate"];	//อัตราดอกเบี้ย (8)
			$interestRate = round($conLoanIniRate,4); 
			
			
			//ราคาจอง (2)
			$PaymentAmt = round(0,2); //ราคาเงินจอง (จำนวน)
			$PaymentVat = round(0,2); //ราคาเงินจอง (VAT)
			$PaymentSum = round(0,2); //ราคาเงินจอง (รวมเป็นเงิน)
			//ราคาเงินดาวน์ (3)
			$DownAmt = round($conDownToFinance,2);  			//ราคาเงินดาวน์ (จำนวน)
			$DownVat = round($conDownToFinanceVat,2);	 		//ราคาเงินดาวน์ (VAT)
			$DownSum = round($DownAmt+$DownVat,2);		 		//ราคาเงินดาวน์ (รวมเป็นเงิน)
			//ค่าใช่จ่ายอื่นๆ (4)
			$OtherAmt = round($netIniCost,2); 					//ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(จำนวน)
			$OtherSum = round($sumIniCost,2); 					//ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(รวมเป็นเงิน)
			$OtherVat = round($OtherSum-$netIniCost,2); 		//ราคาค่าใช้จ่ายอื่นๆ ที่ผู้เช่าซื้อขอลงทุนเพิ่ม(VAT)
			//เงินลงทุนเงินคงเหลือ (5)
			$InvestCastAmt = round($conFinAmtExtVat,2);					//เงินลงทุน(จำนวน)
			$InvestCastSum = round($conFinanceAmount,2);				//เงินลงทุน(รวมเป็นเงิน)
			$InvestCastVat = round($InvestCastSum-$InvestCastAmt,2);	//เงินลงทุน(Vat)
			//ราคาเงินสด (1)
			$CashSum = round($PaymentSum+$DownSum+$InvestCastSum-$OtherSum,2);					//ราคาเงินสด (รวมเป็นเงิน)
				$qry_vat = pg_query("select cal_rate_or_money('VAT','$conDate'::date,$CashSum,2)");
				$res_Cash = pg_fetch_result($qry_vat,0);
			$Cash = $res_Cash["cal_rate_or_money"];
			$CashAmt = round($Cash,2);						//ราคาเงินสด (จำนวน)
			$CashVat = round($CashSum-$CashAmt,2);			//ราคาเงินสด (VAT)
			//ดอกเบี้ยเช่าซื้อทั้งหมด (9)
			$LeasingInterestSum = round($InvestCastSum*$conTerm*($interestRate/100),2); 	//ดอกเบี้ยเช่าซื้อทั้งหมด((รวมเป็นเงิน)
				$qry_vatIni = pg_query("select cal_rate_or_money('VAT','$conDate'::date,$LeasingInterestSum,2)");
				$res_Ini = pg_fetch_result($qry_vat,0);
			$Ini = $res_Ini["cal_rate_or_money"];
			$LeasingInterestAmt = round($Ini,2);   											//ดอกเบี้ยเช่าซื้อทั้งหมด(จำนวน)
			$LeasingInterestVat = round($LeasingInterestSum-$LeasingInterestAmt); 			//ดอกเบี้ยเช่าซื้อทั้งหมด(Vat)
			//ราคาเช่าซื้อทั้งหมด รวมดอกเบี้ย (10)
			$NetLeasingSum = round($InvestCastSum+$LeasingInterestSum,2); 		 //ค่าเช่าซื้อทั้งหมด((รวมเป็นเงิน)
			$NetLeasingAmt = round($InvestCastAmt+$LeasingInterestAmt,2); 		//ค่าเช่าซื้อทั้งหมด(จำนวน)
			$NetLeasingVat = round($InvestCastVat+$LeasingInterestVat,2); 		//ค่าเช่าซื้อทั้งหมด(Vat)
			//ค่าเช่าซื้อชำระเป็นงวด (11)
			$LeasingByPeriodSum = round($NetLeasingSum/$conTerm,2); //ค่าเช่าซื้อชำระเป็นงวดๆ((รวมเป็นเงิน)
		
	//กำหนดค่า session ที่จะส่งไป print
			$_SESSION["CashAmt"] = $CashAmt;
			$_SESSION["CashVat"] = $CashVat;
			$_SESSION["CashSum"] = $CashSum;
			
			$_SESSION["PaymentAmt"] = $PaymentAmt;
			$_SESSION["PaymentVat"] = $PaymentVat;
			$_SESSION["PaymentSum"] = $PaymentSum;
			
			$_SESSION["DownAmt"] = $DownAmt;
			$_SESSION["DownVat"] = $DownVat;
			$_SESSION["DownSum"] = $DownSum;
			
			$_SESSION["OtherAmt"] = $OtherAmt;
			$_SESSION["OtherVat"] = $OtherVat;
			$_SESSION["OtherSum"] = $OtherSum;
			
			$_SESSION["InvestCastAmt"] = $InvestCastAmt;
			$_SESSION["InvestCastVat"] = $InvestCastVat;
			$_SESSION["InvestCastSum"] = $InvestCastSum;
			
			$_SESSION["conTerm"] = $conTerm;
			$_SESSION["conMinPay"] = $conMinPay;
			$_SESSION["interestRate"] = $interestRate;
			
			$_SESSION["LeasingInterestAmt"] = $LeasingInterestAmt;
			$_SESSION["LeasingInterestVat"] = $LeasingInterestVat;
			$_SESSION["LeasingInterestSum"] = $LeasingInterestSum;
			
			$_SESSION["NetLeasingAmt"] = $NetLeasingAmt;
			$_SESSION["NetLeasingVat"] = $NetLeasingVat;
			$_SESSION["NetLeasingSum"] = $NetLeasingSum;
			
			$_SESSION["LeasingByPeriodSum"] = $LeasingByPeriodSum;
			
			$_SESSION["conFirstDue"] = $conFirstDue;
			$_SESSION["conRepeatDueDay"] = $conRepeatDueDay;
		
?> 