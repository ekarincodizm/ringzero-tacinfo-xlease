<?php
include("../../config/config.php");
include('class.upload.php');
?>
<!-- แก้ไขปัญหาภาษาต่างดาว -->
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<!-- จบการแก้ไขปัญหาภาษาต่างดาว -->
<?php
$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$nowdateTime = date("YmdHis");

pg_query("BEGIN");
$status = 0;

$query = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
while($result = pg_fetch_array($query))
{
	$username = $result["username"]; // username ที่ทำรายการ
}

$corp_regis = $_POST["corp_regis"]; // เลขทะเบียนนิติบุคคล(13 หลัก)

// หาจำนวนที่แก้ไขครั้งล่าสุด
$query_number_edit = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' order by \"corpEdit\" DESC limit 1 ");
$numrows_edit = pg_num_rows($query_number_edit);
if($numrows_edit == 0)
{
	$nextEdit = 0;
}
else
{
	while($res_edit = pg_fetch_array($query_number_edit))
	{
		$nowEdit = $res_edit["corpEdit"];
	}
	$nextEdit = $nowEdit + 1;
}

$query_chk = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" is null ");
$numrows_chk = pg_num_rows($query_chk);
if($numrows_chk == 1)
{
	$error = "มีนิติบุคคลนี้รออนุมัติอยู่แล้ว";
	$status++;
}
else
{
	$query_chk2 = pg_query("select * from public.\"th_corp\" where \"corp_regis\" = '$corp_regis'");
	$numrows_chk2 = pg_num_rows($query_chk2);
	if($numrows_chk2 == 1)
	{
		$error = "มีนิติบุคคลนี้อยู่แล้ว";
		$status++;
	}
	else
	{
		//--------------- ข้อมูลนิติบุคคล
		$corpName_THA = $_POST["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
		$corpName_ENG = $_POST["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
		$trade_name = $_POST["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า 
		$corpType = $_POST["corpType"]; // ประเภทนิติบุคคล
		$corp_regis = $_POST["corp_regis"]; // เลขทะเบียนนิติบุคคล(13 หลัก)
		$TaxNumber = $_POST["TaxNumber"]; // เลขที่ประจำตัวผู้เสียภาษี(10 หลัก)
		$phone = $_POST["phone"]; // โทรศัทพ์
		$tor = $_POST["tor"]; // ต่อ
		$Fax = $_POST["Fax"]; // โทาสาร
		$mail = $_POST["mail"]; // E-mail
		$website = $_POST["website"]; // website
		$datepicker_regis = $_POST["datepicker_regis"]; // วันที่จดทะเบียนบริษัท
		$initial_capital = $_POST["initial_capital"]; // ทุนจดทะเบียนเริ่มแรก
		$authority = $_POST["authority"]; // ผู้มีอำนาจการทำรายการของบริษัท
		$datepicker_last = $_POST["datepicker_last"]; // วันที่ของข้อมูลล่าสุด
		$current_capital = $_POST["current_capital"]; // ทุนจดทะเบียนปัจจุบัน
		$asset_avg = $_POST["asset_avg"]; // สินทรัพย์เฉลี่ย(3 ปีล่าสุด)
		$revenue_avg = $_POST["revenue_avg"]; // รายได้เฉลี่ย(3 ปีล่าสุด)
		$debt_avg = $_POST["debt_avg"]; // หนี้สินเฉลี่ย(3 ปีล่าสุด)
		$net_profit = $_POST["net_profit"]; // กำไรสุทธิ(3 ปีล่าสุด)
		$trends_profit = $_POST["trends_profit"]; // แนวโน้มกำไร
		$BusinessType = $_POST["BusinessType"]; // ประเภทธุรกิจ
		$IndustypeID = $_POST["IndustypeID"]; // ประเภทอุตสาหกรรม
		$explanation = $_POST["explanation"]; // คำอธิบายกิจการ
		$corpNationality = $_POST["corpNationality"]; // สัญชาตินิติบุคคล

		if($phone != "" && $tor != "")
		{
			$phone = $phone."#".$tor;
		}

		if($datepicker_last ==""){$datepicker_last = "NULL";}else{$datepicker_last = "'$datepicker_last'";}
		if($corpName_THA ==""){$corpName_THA = "NULL";}else{$corpName_THA = "'$corpName_THA'";}
		if($corpName_ENG ==""){$corpName_ENG = "NULL";}else{$corpName_ENG = "'$corpName_ENG'";}
		if($trade_name ==""){$trade_name = "NULL";}else{$trade_name = "'$trade_name'";}
		if($corpType ==""){$corpType = "NULL";}else{$corpType = "'$corpType'";}
		if($corp_regis ==""){$corp_regis = "NULL";}else{$corp_regis = "'$corp_regis'";}
		if($TaxNumber ==""){$TaxNumber = "NULL";}else{$TaxNumber = "'$TaxNumber'";}
		if($phone ==""){$phone = "NULL";}else{$phone = "'$phone'";}
		if($Fax ==""){$Fax = "NULL";}else{$Fax = "'$Fax'";}
		if($mail ==""){$mail = "NULL";}else{$mail = "'$mail'";}
		if($website ==""){$website = "NULL";}else{$website = "'$website'";}
		if($initial_capital ==""){$initial_capital = "NULL";}else{$initial_capital = "'$initial_capital'";}
		if($authority ==""){$authority = "NULL";}else{$authority = "'$authority'";}
		if($current_capital ==""){$current_capital = "NULL";}else{$current_capital = "'$current_capital'";}
		if($asset_avg ==""){$asset_avg = "NULL";}else{$asset_avg = "'$asset_avg'";}
		if($revenue_avg ==""){$revenue_avg = "NULL";}else{$revenue_avg = "'$revenue_avg'";}
		if($debt_avg ==""){$debt_avg = "NULL";}else{$debt_avg = "'$debt_avg'";}
		if($net_profit ==""){$net_profit = "NULL";}else{$net_profit = "'$net_profit'";}
		if($trends_profit ==""){$trends_profit = "NULL";}else{$trends_profit = "'$trends_profit'";}
		if($BusinessType ==""){$BusinessType = "NULL";}else{$BusinessType = "'$BusinessType'";}
		if($IndustypeID ==""){$IndustypeID = "NULL";}else{$IndustypeID = "'$IndustypeID'";}
		if($explanation ==""){$explanation = "NULL";}else{$explanation = "'$explanation'";}
		if($corpNationality ==""){$corpNationality = "NULL";}else{$corpNationality = "'$corpNationality'";}
		//--------------- จบข้อมูลนิติบุคคล


		//--------------- ที่อยู่ตามหนังสือรับรอง
		$selete_adds_main = $_POST["selete_adds_main"]; // ใช้ที่อยู่อะไร
		if($selete_adds_main == "main1")
		{
			$homestyle_certificate = $_POST["homestyle_certificate"]; // ลักษณะของที่อยู่
			$hc1_f = $_POST["hc1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hc2_f = $_POST["hc2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hc3_f = $_POST["hc3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hc4_f = $_POST["hc4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hc5_f = $_POST["hc5_f"]; // จำนวนชั้นของ คอนโด
			$hc_other = $_POST["hc_other"]; // อื่นๆ
			$C_HomeNumber = $_POST["C_HomeNumber"]; // บ้านเลขที่
			$C_room = $_POST["C_room"]; // ห้อง
			$C_LiveFloor = $_POST["C_LiveFloor"]; // ชั้น
			$C_Moo = $_POST["C_Moo"]; // หมู่ที่
			$C_Building = $_POST["C_Building"]; // อาคาร/สถานที่
			$C_Village = $_POST["C_Village"]; // หมู่บ้าน
			$C_Lane = $_POST["C_Lane"]; // ซอย
			$C_Road = $_POST["C_Road"]; // ถนน
			$C_District = $_POST["C_District"]; // แขวง/ตำบล
			$C_State = $_POST["C_State"]; // เขต/อำเภอ
			$C_Province = $_POST["C_Province"]; // จังหวัด
			$C_Postal_code = $_POST["C_Postal_code"]; // รหัสไปรษณีย์
			$C_Country = $_POST["C_Country"]; // ประเทศ
			$C_phone = $_POST["C_phone"]; // โทรศัพท์
			$C_tor = $_POST["C_tor"]; // ต่อ
			$C_Fax = $_POST["C_Fax"]; // เบอร์ FAX
			$C_Live_it = $_POST["C_Live_it"]; // อาศัยมาแล้ว
			$C_Completion = $_POST["C_Completion"]; // ปีที่สร้างเสร็จ
			$C_Acquired = $_POST["C_Acquired"]; // ได้มาโดย
			$C_purchase_price = $_POST["C_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($C_Province == "ไม่ระบุ"){$C_Province = "";} // ถ้าไม่ได้ระบุจังหวัด

			if($C_phone != "" && $C_tor != "")
			{
				$C_phone = $C_phone."#".$C_tor;
			}

			if($homestyle_certificate == "บ้านเดี่ยว" && $hc1_f != "")
			{
				$C_floor = "'$hc1_f'";
			}
			elseif($homestyle_certificate == "บ้านแฝด" && $hc2_f != "")
			{
				$C_floor = "'$hc2_f'";
			}
			elseif($homestyle_certificate == "ทาวน์เฮ้าส์" && $hc3_f != "")
			{
				$C_floor = "'$hc3_f'";
			}
			elseif($homestyle_certificate == "อาคารณิชย์" && $hc4_f != "")
			{
				$C_floor = "'$hc4_f'";
			}
			elseif($homestyle_certificate == "คอนโด" && $hc5_f != "")
			{
				$C_floor = "'$hc5_f'";
			}
			else
			{
				$C_floor = "NULL";
			}
			
			if($homestyle_certificate == "อื่นๆ" && $hc_other != "")
			{
				$homestyle_certificate = $hc_other;
			}

			if($hc_other ==""){$hc_other = "NULL";}else{$hc_other = "'$hc_other'";}
			if($C_HomeNumber ==""){$C_HomeNumber = "NULL";}else{$C_HomeNumber = "'$C_HomeNumber'";}
			if($C_room ==""){$C_room = "NULL";}else{$C_room = "'$C_room'";}
			if($C_LiveFloor ==""){$C_LiveFloor = "NULL";}else{$C_LiveFloor = "'$C_LiveFloor'";}
			if($C_Moo ==""){$C_Moo = "NULL";}else{$C_Moo = "'$C_Moo'";}
			if($C_Building ==""){$C_Building = "NULL";}else{$C_Building = "'$C_Building'";}
			if($C_Village ==""){$C_Village = "NULL";}else{$C_Village = "'$C_Village'";}
			if($C_Lane ==""){$C_Lane = "NULL";}else{$C_Lane = "'$C_Lane'";}
			if($C_Road ==""){$C_Road = "NULL";}else{$C_Road = "'$C_Road'";}
			if($C_District ==""){$C_District = "NULL";}else{$C_District = "'$C_District'";}
			if($C_State ==""){$C_State = "NULL";}else{$C_State = "'$C_State'";}
			if($C_Province ==""){$C_Province = "NULL";}else{$C_Province = "'$C_Province'";}
			if($C_Postal_code ==""){$C_Postal_code = "NULL";}else{$C_Postal_code = "'$C_Postal_code'";}
			if($C_Country ==""){$C_Country = "NULL";}else{$C_Country = "'$C_Country'";}
			if($C_phone ==""){$C_phone = "NULL";}else{$C_phone = "'$C_phone'";}
			if($C_Fax ==""){$C_Fax = "NULL";}else{$C_Fax = "'$C_Fax'";}
			if($C_Live_it ==""){$C_Live_it = "NULL";}else{$C_Live_it = "'$C_Live_it'";}
			if($C_Completion ==""){$C_Completion = "NULL";}else{$C_Completion = "'$C_Completion'";}
			if($C_Acquired ==""){$C_Acquired = "NULL";}else{$C_Acquired = "'$C_Acquired'";}
			if($C_purchase_price ==""){$C_purchase_price = "NULL";}else{$C_purchase_price = "'$C_purchase_price'";}
		}
		//--------------- จบที่อยู่ตามหนังสือรับรอง


		//--------------- ที่อยู่สำนักงานใหญ่
		$selete_adds_one = $_POST["selete_adds_one"]; // ใช้ที่อยู่อะไร
		if($selete_adds_one == "one1")
		{
			$homestyle_headquarters = $_POST["homestyle_headquarters"]; // ลักษณะของที่อยู่
			$hh1_f = $_POST["hh1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hh2_f = $_POST["hh2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hh3_f = $_POST["hh3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hh4_f = $_POST["hh4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hh5_f = $_POST["hh5_f"]; // จำนวนชั้นของ คอนโด
			$hh_other = $_POST["hh_other"]; // อื่นๆ
			$H_HomeNumber = $_POST["H_HomeNumber"]; // บ้านเลขที่
			$H_room = $_POST["H_room"]; // ห้อง
			$H_LiveFloor = $_POST["H_LiveFloor"]; // ชั้น
			$H_Moo = $_POST["H_Moo"]; // หมู่ที่
			$H_Building = $_POST["H_Building"]; // อาคาร/สถานที่
			$H_Village = $_POST["H_Village"]; // หมู่บ้าน
			$H_Lane = $_POST["H_Lane"]; // ซอย
			$H_Road = $_POST["H_Road"]; // ถนน
			$H_District = $_POST["H_District"]; // แขวง/ตำบล
			$H_State = $_POST["H_State"]; // เขต/อำเภอ
			$H_Province = $_POST["H_Province"]; // จังหวัด
			$H_Postal_code = $_POST["H_Postal_code"]; // รหัสไปรษณีย์
			$H_Country = $_POST["H_Country"]; // ประเทศ
			$H_phone = $_POST["H_phone"]; // โทรศัพท์
			$H_tor = $_POST["H_tor"]; // ต่อ
			$H_Fax = $_POST["H_Fax"]; // เบอร์ FAX
			$H_Live_it = $_POST["H_Live_it"]; // อาศัยมาแล้ว
			$H_Completion = $_POST["H_Completion"]; // ปีที่สร้างเสร็จ
			$H_Acquired = $_POST["H_Acquired"]; // ได้มาโดย
			$H_purchase_price = $_POST["H_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($H_Province == "ไม่ระบุ"){$H_Province = "";} // ถ้าไม่ได้ระบุจังหวัด
			
			if($H_phone != "" && $H_tor != "")
			{
				$H_phone = $H_phone."#".$H_tor;
			}
			
			if($homestyle_headquarters == "บ้านเดี่ยว" && $hh1_f != "")
			{
				$H_floor = "'$hh1_f'";
			}
			elseif($homestyle_headquarters == "บ้านแฝด" && $hh2_f != "")
			{
				$H_floor = "'$hh2_f'";
			}
			elseif($homestyle_headquarters == "ทาวน์เฮ้าส์" && $hh3_f != "")
			{
				$H_floor = "'$hh3_f'";
			}
			elseif($homestyle_headquarters == "อาคารณิชย์" && $hh4_f != "")
			{
				$H_floor = "'$hh4_f'";
			}
			elseif($homestyle_headquarters == "คอนโด" && $hh5_f != "")
			{
				$H_floor = "'$hh5_f'";
			}
			else
			{
				$H_floor = "NULL";
			}
			
			if($homestyle_headquarters == "อื่นๆ" && $hh_other != "")
			{
				$homestyle_headquarters = $hh_other;
			}

			if($hh_other ==""){$hh_other = "NULL";}else{$hh_other = "'$hh_other'";}
			if($H_HomeNumber ==""){$H_HomeNumber = "NULL";}else{$H_HomeNumber = "'$H_HomeNumber'";}
			if($H_room ==""){$H_room = "NULL";}else{$H_room = "'$H_room'";}
			if($H_LiveFloor ==""){$H_LiveFloor = "NULL";}else{$H_LiveFloor = "'$H_LiveFloor'";}
			if($H_Moo ==""){$H_Moo = "NULL";}else{$H_Moo = "'$H_Moo'";}
			if($H_Building ==""){$H_Building = "NULL";}else{$H_Building = "'$H_Building'";}
			if($H_Village ==""){$H_Village = "NULL";}else{$H_Village = "'$H_Village'";}
			if($H_Lane ==""){$H_Lane = "NULL";}else{$H_Lane = "'$H_Lane'";}
			if($H_Road ==""){$H_Road = "NULL";}else{$H_Road = "'$H_Road'";}
			if($H_District ==""){$H_District = "NULL";}else{$H_District = "'$H_District'";}
			if($H_State ==""){$H_State = "NULL";}else{$H_State = "'$H_State'";}
			if($H_Province ==""){$H_Province = "NULL";}else{$H_Province = "'$H_Province'";}
			if($H_Postal_code ==""){$H_Postal_code = "NULL";}else{$H_Postal_code = "'$H_Postal_code'";}
			if($H_Country ==""){$H_Country = "NULL";}else{$H_Country = "'$H_Country'";}
			if($H_phone ==""){$H_phone = "NULL";}else{$H_phone = "'$H_phone'";}
			if($H_Fax ==""){$H_Fax = "NULL";}else{$H_Fax = "'$H_Fax'";}
			if($H_Live_it ==""){$H_Live_it = "NULL";}else{$H_Live_it = "'$H_Live_it'";}
			if($H_Completion ==""){$H_Completion = "NULL";}else{$H_Completion = "'$H_Completion'";}
			if($H_Acquired ==""){$H_Acquired = "NULL";}else{$H_Acquired = "'$H_Acquired'";}
			if($H_purchase_price ==""){$H_purchase_price = "NULL";}else{$H_purchase_price = "'$H_purchase_price'";}
		}
		elseif($selete_adds_one == "one2")
		{
			$homestyle_headquarters = $_POST["homestyle_certificate"]; // ลักษณะของที่อยู่
			$hh1_f = $_POST["hc1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hh2_f = $_POST["hc2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hh3_f = $_POST["hc3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hh4_f = $_POST["hc4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hh5_f = $_POST["hc5_f"]; // จำนวนชั้นของ คอนโด
			$hh_other = $_POST["hc_other"]; // อื่นๆ
			$H_HomeNumber = $_POST["C_HomeNumber"]; // บ้านเลขที่
			$H_room = $_POST["C_room"]; // ห้อง
			$H_LiveFloor = $_POST["C_LiveFloor"]; // ชั้น
			$H_Moo = $_POST["C_Moo"]; // หมู่ที่
			$H_Building = $_POST["C_Building"]; // อาคาร/สถานที่
			$H_Village = $_POST["C_Village"]; // หมู่บ้าน
			$H_Lane = $_POST["C_Lane"]; // ซอย
			$H_Road = $_POST["C_Road"]; // ถนน
			$H_District = $_POST["C_District"]; // แขวง/ตำบล
			$H_State = $_POST["C_State"]; // เขต/อำเภอ
			$H_Province = $_POST["C_Province"]; // จังหวัด
			$H_Postal_code = $_POST["C_Postal_code"]; // รหัสไปรษณีย์
			$H_Country = $_POST["C_Country"]; // ประเทศ
			$H_phone = $_POST["C_phone"]; // โทรศัพท์
			$H_tor = $_POST["C_tor"]; // ต่อ
			$H_Fax = $_POST["C_Fax"]; // เบอร์ FAX
			$H_Live_it = $_POST["C_Live_it"]; // อาศัยมาแล้ว
			$H_Completion = $_POST["C_Completion"]; // ปีที่สร้างเสร็จ
			$H_Acquired = $_POST["C_Acquired"]; // ได้มาโดย
			$H_purchase_price = $_POST["C_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($H_Province == "ไม่ระบุ"){$H_Province = "";} // ถ้าไม่ได้ระบุจังหวัด
			
			if($H_phone != "" && $H_tor != "")
			{
				$H_phone = $H_phone."#".$H_tor;
			}
			
			if($homestyle_headquarters == "บ้านเดี่ยว" && $hh1_f != "")
			{
				$H_floor = "'$hh1_f'";
			}
			elseif($homestyle_headquarters == "บ้านแฝด" && $hh2_f != "")
			{
				$H_floor = "'$hh2_f'";
			}
			elseif($homestyle_headquarters == "ทาวน์เฮ้าส์" && $hh3_f != "")
			{
				$H_floor = "'$hh3_f'";
			}
			elseif($homestyle_headquarters == "อาคารณิชย์" && $hh4_f != "")
			{
				$H_floor = "'$hh4_f'";
			}
			elseif($homestyle_headquarters == "คอนโด" && $hh5_f != "")
			{
				$H_floor = "'$hh5_f'";
			}
			else
			{
				$H_floor = "NULL";
			}
			
			if($homestyle_headquarters == "อื่นๆ" && $hh_other != "")
			{
				$homestyle_headquarters = $hh_other;
			}
			
			if($hh_other ==""){$hh_other = "NULL";}else{$hh_other = "'$hh_other'";}
			if($H_HomeNumber ==""){$H_HomeNumber = "NULL";}else{$H_HomeNumber = "'$H_HomeNumber'";}
			if($H_room ==""){$H_room = "NULL";}else{$H_room = "'$H_room'";}
			if($H_LiveFloor ==""){$H_LiveFloor = "NULL";}else{$H_LiveFloor = "'$H_LiveFloor'";}
			if($H_Moo ==""){$H_Moo = "NULL";}else{$H_Moo = "'$H_Moo'";}
			if($H_Building ==""){$H_Building = "NULL";}else{$H_Building = "'$H_Building'";}
			if($H_Village ==""){$H_Village = "NULL";}else{$H_Village = "'$H_Village'";}
			if($H_Lane ==""){$H_Lane = "NULL";}else{$H_Lane = "'$H_Lane'";}
			if($H_Road ==""){$H_Road = "NULL";}else{$H_Road = "'$H_Road'";}
			if($H_District ==""){$H_District = "NULL";}else{$H_District = "'$H_District'";}
			if($H_State ==""){$H_State = "NULL";}else{$H_State = "'$H_State'";}
			if($H_Province ==""){$H_Province = "NULL";}else{$H_Province = "'$H_Province'";}
			if($H_Postal_code ==""){$H_Postal_code = "NULL";}else{$H_Postal_code = "'$H_Postal_code'";}
			if($H_Country ==""){$H_Country = "NULL";}else{$H_Country = "'$H_Country'";}
			if($H_phone ==""){$H_phone = "NULL";}else{$H_phone = "'$H_phone'";}
			if($H_Fax ==""){$H_Fax = "NULL";}else{$H_Fax = "'$H_Fax'";}
			if($H_Live_it ==""){$H_Live_it = "NULL";}else{$H_Live_it = "'$H_Live_it'";}
			if($H_Completion ==""){$H_Completion = "NULL";}else{$H_Completion = "'$H_Completion'";}
			if($H_Acquired ==""){$H_Acquired = "NULL";}else{$H_Acquired = "'$H_Acquired'";}
			if($H_purchase_price ==""){$H_purchase_price = "NULL";}else{$H_purchase_price = "'$H_purchase_price'";}
		}
		//--------------- จบที่อยู่สำนักงานใหญ่


		//--------------- ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
		$selete_adds_two = $_POST["selete_adds_two"]; // ใช้ที่อยู่อะไร
		if($selete_adds_two == "two1")
		{
			$homestyle_mailing = $_POST["homestyle_mailing"]; // ลักษณะของที่อยู่
			$hm1_f = $_POST["hm1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hm2_f = $_POST["hm2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hm3_f = $_POST["hm3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hm4_f = $_POST["hm4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hm5_f = $_POST["hm5_f"]; // จำนวนชั้นของ คอนโด
			$hm_other = $_POST["hm_other"]; // อื่นๆ
			$M_HomeNumber = $_POST["M_HomeNumber"]; // บ้านเลขที่
			$M_room = $_POST["M_room"]; // ห้อง
			$M_LiveFloor = $_POST["M_LiveFloor"]; // ชั้น
			$M_Moo = $_POST["M_Moo"]; // หมู่ที่
			$M_Building = $_POST["M_Building"]; // อาคาร/สถานที่
			$M_Village = $_POST["M_Village"]; // หมู่บ้าน
			$M_Lane = $_POST["M_Lane"]; // ซอย
			$M_Road = $_POST["M_Road"]; // ถนน
			$M_District = $_POST["M_District"]; // แขวง/ตำบล
			$M_State = $_POST["M_State"]; // เขต/อำเภอ
			$M_Province = $_POST["M_Province"]; // จังหวัด
			$M_Postal_code = $_POST["M_Postal_code"]; // รหัสไปรษณีย์
			$M_Country = $_POST["M_Country"]; // ประเทศ
			$M_phone = $_POST["M_phone"]; // โทรศัพท์
			$M_tor = $_POST["M_tor"]; // ต่อ
			$M_Fax = $_POST["M_Fax"]; // เบอร์ FAX
			$M_Live_it = $_POST["M_Live_it"]; // อาศัยมาแล้ว
			$M_Completion = $_POST["M_Completion"]; // ปีที่สร้างเสร็จ
			$M_Acquired = $_POST["M_Acquired"]; // ได้มาโดย
			$M_purchase_price = $_POST["M_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($M_Province == "ไม่ระบุ"){$M_Province = "";} // ถ้าไม่ได้ระบุจังหวัด
			
			if($M_phone != "" && $M_tor != "")
			{
				$M_phone = $M_phone."#".$M_tor;
			}
			
			if($homestyle_mailing == "บ้านเดี่ยว" && $hm1_f != "")
			{
				$M_floor = "'$hm1_f'";
			}
			elseif($homestyle_mailing == "บ้านแฝด" && $hm2_f != "")
			{
				$M_floor = "'$hm2_f'";
			}
			elseif($homestyle_mailing == "ทาวน์เฮ้าส์" && $hm3_f != "")
			{
				$M_floor = "'$hm3_f'";
			}
			elseif($homestyle_mailing == "อาคารณิชย์" && $hm4_f != "")
			{
				$M_floor = "'$hm4_f'";
			}
			elseif($homestyle_mailing == "คอนโด" && $hm5_f != "")
			{
				$M_floor = "'$hm5_f'";
			}
			else
			{
				$M_floor = "NULL";
			}
			
			if($homestyle_mailing == "อื่นๆ" && $hm_other != "")
			{
				$homestyle_mailing = $hm_other;
			}

			if($hm_other ==""){$hm_other = "NULL";}else{$hm_other = "'$hm_other'";}
			if($M_HomeNumber ==""){$M_HomeNumber = "NULL";}else{$M_HomeNumber = "'$M_HomeNumber'";}
			if($M_room ==""){$M_room = "NULL";}else{$M_room = "'$M_room'";}
			if($M_LiveFloor ==""){$M_LiveFloor = "NULL";}else{$M_LiveFloor = "'$M_LiveFloor'";}
			if($M_Moo ==""){$M_Moo = "NULL";}else{$M_Moo = "'$M_Moo'";}
			if($M_Building ==""){$M_Building = "NULL";}else{$M_Building = "'$M_Building'";}
			if($M_Village ==""){$M_Village = "NULL";}else{$M_Village = "'$M_Village'";}
			if($M_Lane ==""){$M_Lane = "NULL";}else{$M_Lane = "'$M_Lane'";}
			if($M_Road ==""){$M_Road = "NULL";}else{$M_Road = "'$M_Road'";}
			if($M_District ==""){$M_District = "NULL";}else{$M_District = "'$M_District'";}
			if($M_State ==""){$M_State = "NULL";}else{$M_State = "'$M_State'";}
			if($M_Province ==""){$M_Province = "NULL";}else{$M_Province = "'$M_Province'";}
			if($M_Postal_code ==""){$M_Postal_code = "NULL";}else{$M_Postal_code = "'$M_Postal_code'";}
			if($M_Country ==""){$M_Country = "NULL";}else{$M_Country = "'$M_Country'";}
			if($M_phone ==""){$M_phone = "NULL";}else{$M_phone = "'$M_phone'";}
			if($M_Fax ==""){$M_Fax = "NULL";}else{$M_Fax = "'$M_Fax'";}
			if($M_Live_it ==""){$M_Live_it = "NULL";}else{$M_Live_it = "'$M_Live_it'";}
			if($M_Completion ==""){$M_Completion = "NULL";}else{$M_Completion = "'$M_Completion'";}
			if($M_Acquired ==""){$M_Acquired = "NULL";}else{$M_Acquired = "'$M_Acquired'";}
			if($M_purchase_price ==""){$M_purchase_price = "NULL";}else{$M_purchase_price = "'$M_purchase_price'";}
		}
		elseif($selete_adds_two == "two2")
		{
			$homestyle_mailing = $_POST["homestyle_certificate"]; // ลักษณะของที่อยู่
			$hm1_f = $_POST["hc1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hm2_f = $_POST["hc2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hm3_f = $_POST["hc3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hm4_f = $_POST["hc4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hm5_f = $_POST["hc5_f"]; // จำนวนชั้นของ คอนโด
			$hm_other = $_POST["hc_other"]; // อื่นๆ
			$M_HomeNumber = $_POST["C_HomeNumber"]; // บ้านเลขที่
			$M_room = $_POST["C_room"]; // ห้อง
			$M_LiveFloor = $_POST["C_LiveFloor"]; // ชั้น
			$M_Moo = $_POST["C_Moo"]; // หมู่ที่
			$M_Building = $_POST["C_Building"]; // อาคาร/สถานที่
			$M_Village = $_POST["C_Village"]; // หมู่บ้าน
			$M_Lane = $_POST["C_Lane"]; // ซอย
			$M_Road = $_POST["C_Road"]; // ถนน
			$M_District = $_POST["C_District"]; // แขวง/ตำบล
			$M_State = $_POST["C_State"]; // เขต/อำเภอ
			$M_Province = $_POST["C_Province"]; // จังหวัด
			$M_Postal_code = $_POST["C_Postal_code"]; // รหัสไปรษณีย์
			$M_Country = $_POST["C_Country"]; // ประเทศ
			$M_phone = $_POST["C_phone"]; // โทรศัพท์
			$M_tor = $_POST["C_tor"]; // ต่อ
			$M_Fax = $_POST["C_Fax"]; // เบอร์ FAX
			$M_Live_it = $_POST["C_Live_it"]; // อาศัยมาแล้ว
			$M_Completion = $_POST["C_Completion"]; // ปีที่สร้างเสร็จ
			$M_Acquired = $_POST["C_Acquired"]; // ได้มาโดย
			$M_purchase_price = $_POST["C_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($M_Province == "ไม่ระบุ"){$M_Province = "";} // ถ้าไม่ได้ระบุจังหวัด
			
			if($M_phone != "" && $M_tor != "")
			{
				$M_phone = $M_phone."#".$M_tor;
			}
			
			if($homestyle_mailing == "บ้านเดี่ยว" && $hm1_f != "")
			{
				$M_floor = "'$hm1_f'";
			}
			elseif($homestyle_mailing == "บ้านแฝด" && $hm2_f != "")
			{
				$M_floor = "'$hm2_f'";
			}
			elseif($homestyle_mailing == "ทาวน์เฮ้าส์" && $hm3_f != "")
			{
				$M_floor = "'$hm3_f'";
			}
			elseif($homestyle_mailing == "อาคารณิชย์" && $hm4_f != "")
			{
				$M_floor = "'$hm4_f'";
			}
			elseif($homestyle_mailing == "คอนโด" && $hm5_f != "")
			{
				$M_floor = "'$hm5_f'";
			}
			else
			{
				$M_floor = "NULL";
			}
			
			if($homestyle_mailing == "อื่นๆ" && $hm_other != "")
			{
				$homestyle_mailing = $hm_other;
			}
			
			if($hm_other ==""){$hm_other = "NULL";}else{$hm_other = "'$hm_other'";}
			if($M_HomeNumber ==""){$M_HomeNumber = "NULL";}else{$M_HomeNumber = "'$M_HomeNumber'";}
			if($M_room ==""){$M_room = "NULL";}else{$M_room = "'$M_room'";}
			if($M_LiveFloor ==""){$M_LiveFloor = "NULL";}else{$M_LiveFloor = "'$M_LiveFloor'";}
			if($M_Moo ==""){$M_Moo = "NULL";}else{$M_Moo = "'$M_Moo'";}
			if($M_Building ==""){$M_Building = "NULL";}else{$M_Building = "'$M_Building'";}
			if($M_Village ==""){$M_Village = "NULL";}else{$M_Village = "'$M_Village'";}
			if($M_Lane ==""){$M_Lane = "NULL";}else{$M_Lane = "'$M_Lane'";}
			if($M_Road ==""){$M_Road = "NULL";}else{$M_Road = "'$M_Road'";}
			if($M_District ==""){$M_District = "NULL";}else{$M_District = "'$M_District'";}
			if($M_State ==""){$M_State = "NULL";}else{$M_State = "'$M_State'";}
			if($M_Province ==""){$M_Province = "NULL";}else{$M_Province = "'$M_Province'";}
			if($M_Postal_code ==""){$M_Postal_code = "NULL";}else{$M_Postal_code = "'$M_Postal_code'";}
			if($M_Country ==""){$M_Country = "NULL";}else{$M_Country = "'$M_Country'";}
			if($M_phone ==""){$M_phone = "NULL";}else{$M_phone = "'$M_phone'";}
			if($M_Fax ==""){$M_Fax = "NULL";}else{$M_Fax = "'$M_Fax'";}
			if($M_Live_it ==""){$M_Live_it = "NULL";}else{$M_Live_it = "'$M_Live_it'";}
			if($M_Completion ==""){$M_Completion = "NULL";}else{$M_Completion = "'$M_Completion'";}
			if($M_Acquired ==""){$M_Acquired = "NULL";}else{$M_Acquired = "'$M_Acquired'";}
			if($M_purchase_price ==""){$M_purchase_price = "NULL";}else{$M_purchase_price = "'$M_purchase_price'";}
		}
		elseif($selete_adds_two == "two3")
		{
			$homestyle_mailing = $_POST["homestyle_headquarters"]; // ลักษณะของที่อยู่
			$hm1_f = $_POST["hh1_f"]; // จำนวนชั้นของ บ้านเดี่ยว
			$hm2_f = $_POST["hh2_f"]; // จำนวนชั้นของ บ้านแฝด
			$hm3_f = $_POST["hh3_f"]; // จำนวนชั้นของ ทาวน์เฮ้าส์
			$hm4_f = $_POST["hh4_f"]; // จำนวนชั้นของ อาคารณิชย์
			$hm5_f = $_POST["hh5_f"]; // จำนวนชั้นของ คอนโด
			$hm_other = $_POST["hh_other"]; // อื่นๆ
			$M_HomeNumber = $_POST["H_HomeNumber"]; // บ้านเลขที่
			$M_room = $_POST["H_room"]; // ห้อง
			$M_LiveFloor = $_POST["H_LiveFloor"]; // ชั้น
			$M_Moo = $_POST["H_Moo"]; // หมู่ที่
			$M_Building = $_POST["H_Building"]; // อาคาร/สถานที่
			$M_Village = $_POST["H_Village"]; // หมู่บ้าน
			$M_Lane = $_POST["H_Lane"]; // ซอย
			$M_Road = $_POST["H_Road"]; // ถนน
			$M_District = $_POST["H_District"]; // แขวง/ตำบล
			$M_State = $_POST["H_State"]; // เขต/อำเภอ
			$M_Province = $_POST["H_Province"]; // จังหวัด
			$M_Postal_code = $_POST["H_Postal_code"]; // รหัสไปรษณีย์
			$M_Country = $_POST["H_Country"]; // ประเทศ
			$M_phone = $_POST["H_phone"]; // โทรศัพท์
			$M_tor = $_POST["H_tor"]; // ต่อ
			$M_Fax = $_POST["H_Fax"]; // เบอร์ FAX
			$M_Live_it = $_POST["H_Live_it"]; // อาศัยมาแล้ว
			$M_Completion = $_POST["H_Completion"]; // ปีที่สร้างเสร็จ
			$M_Acquired = $_POST["H_Acquired"]; // ได้มาโดย
			$M_purchase_price = $_POST["H_purchase_price"]; // มูลค่า/ราคาที่ซื้อ
			
			if($M_Province == "ไม่ระบุ"){$M_Province = "";} // ถ้าไม่ได้ระบุจังหวัด
			
			if($M_phone != "" && $M_tor != "")
			{
				$M_phone = $M_phone."#".$M_tor;
			}
			
			if($homestyle_mailing == "บ้านเดี่ยว" && $hm1_f != "")
			{
				$M_floor = "'$hm1_f'";
			}
			elseif($homestyle_mailing == "บ้านแฝด" && $hm2_f != "")
			{
				$M_floor = "'$hm2_f'";
			}
			elseif($homestyle_mailing == "ทาวน์เฮ้าส์" && $hm3_f != "")
			{
				$M_floor = "'$hm3_f'";
			}
			elseif($homestyle_mailing == "อาคารณิชย์" && $hm4_f != "")
			{
				$M_floor = "'$hm4_f'";
			}
			elseif($homestyle_mailing == "คอนโด" && $hm5_f != "")
			{
				$M_floor = "'$hm5_f'";
			}
			else
			{
				$M_floor = "NULL";
			}
			
			if($homestyle_mailing == "อื่นๆ" && $hm_other != "")
			{
				$homestyle_mailing = $hm_other;
			}
			
			if($hm_other ==""){$hm_other = "NULL";}else{$hm_other = "'$hm_other'";}
			if($M_HomeNumber ==""){$M_HomeNumber = "NULL";}else{$M_HomeNumber = "'$M_HomeNumber'";}
			if($M_room ==""){$M_room = "NULL";}else{$M_room = "'$M_room'";}
			if($M_LiveFloor ==""){$M_LiveFloor = "NULL";}else{$M_LiveFloor = "'$M_LiveFloor'";}
			if($M_Moo ==""){$M_Moo = "NULL";}else{$M_Moo = "'$M_Moo'";}
			if($M_Building ==""){$M_Building = "NULL";}else{$M_Building = "'$M_Building'";}
			if($M_Village ==""){$M_Village = "NULL";}else{$M_Village = "'$M_Village'";}
			if($M_Lane ==""){$M_Lane = "NULL";}else{$M_Lane = "'$M_Lane'";}
			if($M_Road ==""){$M_Road = "NULL";}else{$M_Road = "'$M_Road'";}
			if($M_District ==""){$M_District = "NULL";}else{$M_District = "'$M_District'";}
			if($M_State ==""){$M_State = "NULL";}else{$M_State = "'$M_State'";}
			if($M_Province ==""){$M_Province = "NULL";}else{$M_Province = "'$M_Province'";}
			if($M_Postal_code ==""){$M_Postal_code = "NULL";}else{$M_Postal_code = "'$M_Postal_code'";}
			if($M_Country ==""){$M_Country = "NULL";}else{$M_Country = "'$M_Country'";}
			if($M_phone ==""){$M_phone = "NULL";}else{$M_phone = "'$M_phone'";}
			if($M_Fax ==""){$M_Fax = "NULL";}else{$M_Fax = "'$M_Fax'";}
			if($M_Live_it ==""){$M_Live_it = "NULL";}else{$M_Live_it = "'$M_Live_it'";}
			if($M_Completion ==""){$M_Completion = "NULL";}else{$M_Completion = "'$M_Completion'";}
			if($M_Acquired ==""){$M_Acquired = "NULL";}else{$M_Acquired = "'$M_Acquired'";}
			if($M_purchase_price ==""){$M_purchase_price = "NULL";}else{$M_purchase_price = "'$M_purchase_price'";}
		}
		//--------------- จบที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)


		//--------------- บัญชีธนาคารของลูกค้านิติบุคคล
		$rowbank = $_POST["rowbank"]; // จำนวนบัญชีธนาคาร
		for($b=1;$b<=$rowbank;$b++)
		{
			$acc_Number[$b] = $_POST["acc_Number$b"]; // เลขที่บัญชี
			$acc_Name[$b] = $_POST["acc_Name$b"]; // ชื่อบัญชี
			$bankID[$b] = $_POST["bank$b"]; // ธนาคาร
			$branch[$b] = $_POST["branch$b"]; // สาขา
			$acc_type[$b] = $_POST["acc_type$b"]; // ประเภทบัญชี
			
			if($acc_Number[$b] == ""){$acc_Number[$b] = "NULL";}else{$acc_Number[$b] = "'$acc_Number[$b]'";}
			if($acc_Name[$b] == ""){$acc_Name[$b] = "NULL";}else{$acc_Name[$b] = "'$acc_Name[$b]'";}
			if($bankID[$b] == ""){$bankID[$b] = "NULL";}else{$bankID[$b] = "'$bankID[$b]'";}
			if($branch[$b] == ""){$branch[$b] = "NULL";}else{$branch[$b] = "'$branch[$b]'";}
			if($acc_type[$b] == ""){$acc_type[$b] = "NULL";}else{$acc_type[$b] = "'$acc_type[$b]'";}
		}
		//--------------- จบบัญชีธนาคารของลูกค้านิติบุคคล
		
		
		//--------------- กรรมการของลูกค้านิติบุคคล
		$rowBoard = $_POST["rowBoard"]; // จำนวนกรรมการ
		for($c=1;$c<=$rowBoard;$c++)
		{
			$BoardName[$c] = $_POST["BoardName$c"]; // ชื่อกรรมการ
			
			if($BoardName[$c] == "")
			{
				$BoardName[$c] = "NULL";
			}
			else
			{
				$qryBoard_search_cus = pg_query("select * from public.\"VSearchCus\" where \"full_name\" = '$BoardName[$c]' ");
				$numBoard_search_cus = pg_num_rows($qryBoard_search_cus);
				if($numBoard_search_cus == 0)
				{ // ถ้าไม่มีชื่อลูกค้า ให้ใช้ชื่อที่ user กรอกเข้ามา
					$BoardName[$c] = "'$BoardName[$c]'";
				}
				else
				{ // ถ้ามีชื่อลูกค้าอยู่แล้ว ให้เอา ID ไปใช้
					while($res_Board = pg_fetch_array($qryBoard_search_cus))
					{
						$BoardCusID = $res_Board["CusID"];
					}
					$BoardName[$c] = "'$BoardCusID'";
				}
			}
			
			//add file upload 
			$cli = (isset($argc) && $argc > 1);
			if ($cli) {
				if (isset($argv[1])) $_GET['file'] = $argv[1];
				if (isset($argv[2])) $_GET['dir'] = $argv[2];
				if (isset($argv[3])) $_GET['pics'] = $argv[3];
			}

			// set variables
			$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload');
			$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
			
			$files = array();
			foreach ($_FILES["BoardSen$c"] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $files))
						$files[$i] = array();
					$files[$i][$k] = $v;
				}
			}
			foreach ($files as $file) {
				$handle = new Upload($file);
		   
				if($handle->uploaded) {
					// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
					//$prepend = date("YmdHis")."_";
					$handle->file_name_body_pre = $prepend;
					$handle->Process($dir_dest);    
					if ($handle->processed) 
					{
						$pathfile=$handle->file_dst_name;
						
						$Board_oldfile = $pathfile;			
						$Board_newfile = md5_file("upload/$pathfile", FALSE);
						
						$Board_cuttext = split("\.",$pathfile);
						$Board_nubtext = count($Board_cuttext);
						$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
						
						$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
						
						$Boardfile[$c] = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
						
						//echo "$Board_oldfile และ $Board_newfile <br>";
						
						$flgRename = rename("upload/$Board_oldfile", "upload/$Board_newfile");
						if($flgRename)
						{
							//echo "บันทึกสำเร็จ";
						}
						else
						{
							echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
							$status++;
						}
					}
					else
					{
						echo '<fieldset>';
						echo '  <legend>file not uploaded to the wanted location</legend>';
						echo '  Error: ' . $handle->error . '';
						echo '</fieldset>';
						$status++;
						$Boardfile[$c] = "NULL";
					}
				}
				else
				{
					$Boardfile[$c] = "NULL";
				}
			}
			// จบ add file upload
		}
		//--------------- จบกรรมการของลูกค้านิติบุคคล
		
		
		//--------------- ผู้ถือหุ้นของลูกค้านิติบุคคล
		$rowShare = $_POST["rowShare"]; // จำนวนผู้ถือหุ้น
		for($d=1;$d<=$rowShare;$d++)
		{
			$ShareName[$d] = $_POST["ShareName$d"]; // ชื่อผู้ถือหุ้น
			$ShareAmount[$d] = $_POST["ShareAmount$d"]; // จำนวนหุ้น
			$ShareValue[$d] = $_POST["ShareValue$d"]; // มูลค่าหุ้น
			
			if($ShareName[$d] == "")
			{
				$ShareName[$d] = "NULL";
			}
			else
			{
				$qryShare_search_cus = pg_query("select * from public.\"VSearchCus\" where \"full_name\" = '$ShareName[$d]' ");
				$numShare_search_cus = pg_num_rows($qryShare_search_cus);
				if($numShare_search_cus == 0)
				{ // ถ้าไม่มีชื่อลูกค้า ให้ใช้ชื่อที่ user กรอกเข้ามา
					$ShareName[$d] = "'$ShareName[$d]'";
				}
				else
				{ // ถ้ามีชื่อลูกค้าอยู่แล้ว ให้เอา ID ไปใช้
					while($res_Share = pg_fetch_array($qryShare_search_cus))
					{
						$ShareCusID = $res_Share["CusID"];
					}
					$ShareName[$d] = "'$ShareCusID'";
				}
			}
			if($ShareAmount[$d] == ""){$ShareAmount[$d] = "NULL";}else{$ShareAmount[$d] = "'$ShareAmount[$d]'";}
			if($ShareValue[$d] == ""){$ShareValue[$d] = "NULL";}else{$ShareValue[$d] = "'$ShareValue[$d]'";}
			
			//add file upload  ผู้ถือหุ้น
			$cli = (isset($argc) && $argc > 1);
			if ($cli) {
				if (isset($argv[1])) $_GET['file'] = $argv[1];
				if (isset($argv[2])) $_GET['dir'] = $argv[2];
				if (isset($argv[3])) $_GET['pics'] = $argv[3];
			}

			// set variables
			$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload');
			$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
			
			$files = array();
			foreach ($_FILES["ShareSen$d"] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $files))
						$files[$i] = array();
					$files[$i][$k] = $v;
				}
			}
			foreach ($files as $file) {
				$handle = new Upload($file);
		   
				if($handle->uploaded) {
					// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
					//$prepend = date("YmdHis")."_";
					$handle->file_name_body_pre = $prepend;
					$handle->Process($dir_dest);    
					if ($handle->processed) 
					{
						$pathfile=$handle->file_dst_name;
						
						$Share_oldfile = $pathfile;			
						$Share_newfile = md5_file("upload/$pathfile", FALSE);
						
						$Share_cuttext = split("\.",$pathfile);
						$Share_nubtext = count($Share_cuttext);
						$Share_newfile = "$Share_newfile.".$Share_cuttext[$Share_nubtext-1];
						
						$Share_newfile = $nowdateTime."_".$Share_newfile; // ใส่วันเวลาไว้หน้าไฟล์
						
						$Sharefile[$d] = "'$Share_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
						
						//echo "$Share_oldfile และ $Share_newfile <br>";
						
						$flgRename = rename("upload/$Share_oldfile", "upload/$Share_newfile");
						if($flgRename)
						{
							//echo "บันทึกสำเร็จ";
						}
						else
						{
							echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
							$status++;
						}
					}
					else
					{
						echo '<fieldset>';
						echo '  <legend>file not uploaded to the wanted location</legend>';
						echo '  Error: ' . $handle->error . '';
						echo '</fieldset>';
						$status++;
						$Sharefile[$d] = "NULL";
					}
				}
				else
				{
					$Sharefile[$d] = "NULL";
				}
			}
			// จบ add file upload
		}
		//--------------- จบผู้ถือหุ้นของลูกค้านิติบุคคล
		
		
		//--------------- ผู้ติดต่อ
		$rowCommunicant = $_POST["rowCommunicant"]; // จำนวนผู้ติดต่อ
		for($e=1;$e<=$rowCommunicant;$e++)
		{
			$CommunicantName[$e] = $_POST["CommunicantName$e"]; // ชื่อผู้ติดต่อ
			$CommunicantPosition[$e] = $_POST["CommunicantPosition$e"]; // ตำแหน่ง
			$CommunicantCoordinate[$e] = $_POST["CommunicantCoordinate$e"]; // ประสานงานเรื่อง
			$CommunicantPhone[$e] = $_POST["CommunicantPhone$e"]; // เบอร์โทรศัพท์
			$CommunicantMobile[$e] = $_POST["CommunicantMobile$e"]; // เบอร์มือถือ
			$CommunicantEmail[$e] = $_POST["CommunicantEmail$e"]; // อีเมล์

			if($CommunicantName[$e] == ""){$CommunicantName[$e] = "NULL";}else{$CommunicantName[$e] = "'$CommunicantName[$e]'";}
			if($CommunicantPosition[$e] == ""){$CommunicantPosition[$e] = "NULL";}else{$CommunicantPosition[$e] = "'$CommunicantPosition[$e]'";}
			if($CommunicantCoordinate[$e] == ""){$CommunicantCoordinate[$e] = "NULL";}else{$CommunicantCoordinate[$e] = "'$CommunicantCoordinate[$e]'";}
			if($CommunicantPhone[$e] == ""){$CommunicantPhone[$e] = "NULL";}else{$CommunicantPhone[$e] = "'$CommunicantPhone[$e]'";}
			if($CommunicantMobile[$e] == ""){$CommunicantMobile[$e] = "NULL";}else{$CommunicantMobile[$e] = "'$CommunicantMobile[$e]'";}
			if($CommunicantEmail[$e] == ""){$CommunicantEmail[$e] = "NULL";}else{$CommunicantEmail[$e] = "'$CommunicantEmail[$e]'";}
		}
		//--------------- จบผู้ติดต่อ
		
		
		//--------------- ผู้รับมอบของลูกค้านิติบุคคล
		$rowAttorney = $_POST["rowAttorney"]; // จำนวนกรรมการ
		for($f=1;$f<=$rowAttorney;$f++)
		{
			$AttorneyName[$f] = $_POST["AttorneyName$f"]; // ชื่อกรรมการ
			
			if($AttorneyName[$f] == "")
			{
				$AttorneyName[$f] = "NULL";
			}
			else
			{
				$qryAttorney_search_cus = pg_query("select * from public.\"VSearchCus\" where \"full_name\" = '$AttorneyName[$f]' ");
				$numAttorney_search_cus = pg_num_rows($qryAttorney_search_cus);
				if($numAttorney_search_cus == 0)
				{ // ถ้าไม่มีชื่อลูกค้า ให้ใช้ชื่อที่ user กรอกเข้ามา
					$AttorneyName[$f] = "'$AttorneyName[$f]'";
				}
				else
				{ // ถ้ามีชื่อลูกค้าอยู่แล้ว ให้เอา ID ไปใช้
					while($res_Attorney = pg_fetch_array($qryAttorney_search_cus))
					{
						$AttorneyCusID = $res_Attorney["CusID"];
					}
					$AttorneyName[$f] = "'$AttorneyCusID'";
				}
			}
			
			//add file upload  ผู้รับมอบอำนาจ
			$cli = (isset($argc) && $argc > 1);
			if ($cli) {
				if (isset($argv[1])) $_GET['file'] = $argv[1];
				if (isset($argv[2])) $_GET['dir'] = $argv[2];
				if (isset($argv[3])) $_GET['pics'] = $argv[3];
			}

			// set variables
			$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'upload');
			$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
			
			$files = array();
			foreach ($_FILES["AttorneySen$f"] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $files))
						$files[$i] = array();
					$files[$i][$k] = $v;
				}
			}
			foreach ($files as $file) {
				$handle = new Upload($file);
		   
				if($handle->uploaded) {
					// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
					//$prepend = date("YmdHis")."_";
					$handle->file_name_body_pre = $prepend;
					$handle->Process($dir_dest);    
					if ($handle->processed) 
					{
						$pathfile=$handle->file_dst_name;
						
						$Attorney_oldfile = $pathfile;			
						$Attorney_newfile = md5_file("upload/$pathfile", FALSE);
						
						$Attorney_cuttext = split("\.",$pathfile);
						$Attorney_nubtext = count($Attorney_cuttext);
						$Attorney_newfile = "$Attorney_newfile.".$Attorney_cuttext[$Attorney_nubtext-1];
						
						$Attorney_newfile = $nowdateTime."_".$Attorney_newfile; // ใส่วันเวลาไว้หน้าไฟล์
						
						$Attorneyfile[$f] = "'$Attorney_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
						
						//echo "$Attorney_oldfile และ $Attorney_newfile <br>";
						
						$flgRename = rename("upload/$Attorney_oldfile", "upload/$Attorney_newfile");
						if($flgRename)
						{
							//echo "บันทึกสำเร็จ";
						}
						else
						{
							echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
							$status++;
						}
					}
					else
					{
						echo '<fieldset>';
						echo '  <legend>file not uploaded to the wanted location</legend>';
						echo '  Error: ' . $handle->error . '';
						echo '</fieldset>';
						$status++;
						$Attorneyfile[$f] = "NULL";
					}
				}
				else
				{
					$Attorneyfile[$f] = "NULL";
				}
			}
			// จบ add file upload
		}
		//--------------- จบผู้รับมอบของลูกค้านิติบุคคล
		
		
		//--------------- ข้อมูลอื่นๆ
			$Proportion_in_country = $_POST["Proportion_in_country"];
			$Proportion_out_country = $_POST["Proportion_out_country"];
			$Proportion_Cash = $_POST["Proportion_Cash"];
			$Proportion_Credit = $_POST["Proportion_Credit"];
			$Amount_Employee = $_POST["Amount_Employee"];
			
			if($Proportion_in_country == ""){$Proportion_in_country = "NULL";}else{$Proportion_in_country = "'$Proportion_in_country'";}
			if($Proportion_out_country == ""){$Proportion_out_country = "NULL";}else{$Proportion_out_country = "'$Proportion_out_country'";}
			if($Proportion_Cash == ""){$Proportion_Cash = "NULL";}else{$Proportion_Cash = "'$Proportion_Cash'";}
			if($Proportion_Credit == ""){$Proportion_Credit = "NULL";}else{$Proportion_Credit = "'$Proportion_Credit'";}
			if($Amount_Employee == ""){$Amount_Employee = "NULL";}else{$Amount_Employee = "'$Amount_Employee'";}
		//--------------- จบข้อมูลอื่นๆ

		

		//--------------- เริ่มบันทึกข้อมูล
		
		// เพิ่มข้อมูลนิติบุคคล
		$sql_add_corp = "insert into public.\"th_corp_temp\" (\"corpType\",\"corpName_THA\",\"corpName_ENG\",\"trade_name\",\"corp_regis\",\"TaxNumber\",\"phone\",\"Fax\",\"mail\",\"website\",\"date_of_corp\",
						\"initial_capital\",\"authority\",\"current_capital\",\"asset_avg\",\"revenue_avg\",\"debt_avg\",\"net_profit\",\"date_of_last_data\",\"trends_profit\",\"BusinessType\",\"IndustypeID\",
						\"explanation\",\"doerUser\",\"doerStamp\",\"corpEdit\",\"Proportion_in_country\",\"Proportion_out_country\",\"Proportion_Cash\",\"Proportion_Credit\",\"Amount_Employee\",\"CountryCode\")
						values ($corpType,$corpName_THA,$corpName_ENG,$trade_name,$corp_regis,$TaxNumber,$phone,$Fax,$mail,$website,'$datepicker_regis',
								$initial_capital,$authority,$current_capital,$asset_avg,$revenue_avg,$debt_avg,$net_profit,$datepicker_last,$trends_profit,$BusinessType,$IndustypeID,
								$explanation,'$username','$logs_any_time','$nextEdit',$Proportion_in_country,$Proportion_out_country,$Proportion_Cash,$Proportion_Credit,$Amount_Employee,$corpNationality)";
		if($result=pg_query($sql_add_corp))
		{}
		else
		{
			$status++;
		}

		// เพิ่มที่อยู่ตามหนังสือรับรอง
		if($selete_adds_main == "main1")
		{
			$sql_add_address_certificate = "insert into public.\"th_corp_adds_temp\" (\"corp_regis\",\"addsType\",\"addsStyle\",\"HomeNumber\",\"room\",\"LiveFloor\",\"Moo\",\"Building\",\"Village\",\"Lane\",
																\"Road\",\"District\",\"State\",\"ProvinceID\",\"Postal_code\",\"Country\",\"phone\",\"Fax\",\"Live_it\",\"Completion\",\"Acquired\",
																\"purchase_price\",\"doerUser\",\"doerStamp\",\"addsEdit\",\"floor\")
											values ($corp_regis,'1','$homestyle_certificate',$C_HomeNumber,$C_room,$C_LiveFloor,$C_Moo,$C_Building,$C_Village,$C_Lane,
													$C_Road,$C_District,$C_State,$C_Province,$C_Postal_code,$C_Country,$C_phone,$C_Fax,$C_Live_it,$C_Completion,$C_Acquired,
													$C_purchase_price,'$username','$logs_any_time','$nextEdit',$C_floor)";
			if($result=pg_query($sql_add_address_certificate))
			{}
			else
			{
				$status++;
			}
		}

		// เพิ่มที่อยู่สำนักงานใหญ่
		if($selete_adds_one != "one3")
		{
			$sql_add_address_headquarters = "insert into public.\"th_corp_adds_temp\" (\"corp_regis\",\"addsType\",\"addsStyle\",\"HomeNumber\",\"room\",\"LiveFloor\",\"Moo\",\"Building\",\"Village\",\"Lane\",
																\"Road\",\"District\",\"State\",\"ProvinceID\",\"Postal_code\",\"Country\",\"phone\",\"Fax\",\"Live_it\",\"Completion\",\"Acquired\",
																\"purchase_price\",\"doerUser\",\"doerStamp\",\"addsEdit\",\"floor\")
											values ($corp_regis,'2','$homestyle_headquarters',$H_HomeNumber,$H_room,$H_LiveFloor,$H_Moo,$H_Building,$H_Village,$H_Lane,
													$H_Road,$H_District,$H_State,$H_Province,$H_Postal_code,$H_Country,$H_phone,$H_Fax,$H_Live_it,$H_Completion,$H_Acquired,
													$H_purchase_price,'$username','$logs_any_time','$nextEdit',$H_floor)";
			if($result=pg_query($sql_add_address_headquarters))
			{}
			else
			{
				$status++;
			}
		}

		// เพิ่มที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
		if($selete_adds_two != "two4")
		{
			$sql_add_address_mailing = "insert into public.\"th_corp_adds_temp\" (\"corp_regis\",\"addsType\",\"addsStyle\",\"HomeNumber\",\"room\",\"LiveFloor\",\"Moo\",\"Building\",\"Village\",\"Lane\",
																\"Road\",\"District\",\"State\",\"ProvinceID\",\"Postal_code\",\"Country\",\"phone\",\"Fax\",\"Live_it\",\"Completion\",\"Acquired\",
																\"purchase_price\",\"doerUser\",\"doerStamp\",\"addsEdit\",\"floor\")
											values ($corp_regis,'3','$homestyle_mailing',$M_HomeNumber,$M_room,$M_LiveFloor,$M_Moo,$M_Building,$M_Village,$M_Lane,
													$M_Road,$M_District,$M_State,$M_Province,$M_Postal_code,$M_Country,$M_phone,$M_Fax,$M_Live_it,$M_Completion,$M_Acquired,
													$M_purchase_price,'$username','$logs_any_time','$nextEdit',$M_floor)";
			if($result=pg_query($sql_add_address_mailing))
			{}
			else
			{
				$status++;
			}
		}

		// เพิ่มบัญชีธนาคารของลูกค้านิติบุคคล
		if($rowbank == 0 || ($rowbank == 1 && $acc_Number[1] == "NULL"))
		{
			// ถ้าเข้าเงื่อนไขนี้จะไม่ทำอะไร
		}
		else
		{
			for($b=1;$b<=$rowbank;$b++)
			{
				$sql_add_bank = "insert into public.\"th_corp_acc_temp\" (\"corp_regis\",\"acc_Number\",\"bankID\",\"acc_Name\",\"branch\",\"acc_type\",\"doerUser\",\"doerStamp\",\"accEdit\")
								values ($corp_regis,$acc_Number[$b],$bankID[$b],$acc_Name[$b],$branch[$b],$acc_type[$b],'$username','$logs_any_time','$nextEdit')";
				if($result=pg_query($sql_add_bank))
				{}
				else
				{
					$status++;
				}
			}
		}
		
		// เพิ่มกรรมการของลูกค้านิติบุคคล
		if($rowBoard == 0 || ($rowBoard == 1 && $BoardName[1] == "NULL"))
		{
			// ถ้าเข้าเงื่อนไขนี้จะไม่ทำอะไร
		}
		else
		{
			for($c=1;$c<=$rowBoard;$c++)
			{
				$sql_add_Board = "insert into public.\"th_corp_board_temp\" (\"corp_regis\",\"CusID\",\"doerUser\",\"doerStamp\",\"boardEdit\",\"path_signature\")
								values ($corp_regis,$BoardName[$c],'$username','$logs_any_time','$nextEdit',$Boardfile[$c])";
				if($result=pg_query($sql_add_Board))
				{}
				else
				{
					$status++;
				}
			}
		}
		
		// เพิ่มผู้ถือหุ้นของลูกค้านิติบุคคล
		if($rowShare == 0 || ($rowShare == 1 && $ShareName[1] == "NULL"))
		{
			// ถ้าเข้าเงื่อนไขนี้จะไม่ทำอะไร
		}
		else
		{
			for($d=1;$d<=$rowShare;$d++)
			{
				$sql_add_Share = "insert into public.\"th_corp_share_temp\" (\"corp_regis\",\"CusID\",\"share_amount\",\"share_value\",\"doerUser\",\"doerStamp\",\"shareEdit\",\"path_signature\")
								values ($corp_regis,$ShareName[$d],$ShareAmount[$d],$ShareValue[$d],'$username','$logs_any_time','$nextEdit',$Sharefile[$d])";
				if($result=pg_query($sql_add_Share))
				{}
				else
				{
					$status++;
				}
			}
		}
		
		// เพิ่มผู้ติดต่อของลูกค้านิติบุคคล
		if($rowCommunicant == 0 || ($rowCommunicant == 1 && $CommunicantName[1] == "NULL"))
		{
			// ถ้าเข้าเงื่อนไขนี้จะไม่ทำอะไร
		}
		else
		{
			for($e=1;$e<=$rowCommunicant;$e++)
			{
				$sql_add_Communicant = "insert into public.\"th_corp_communicant_temp\" (\"corp_regis\",\"CommunicantName\",\"position\",\"subject\",\"phone\",\"mobile\",\"email\"
									,\"doerUser\",\"doerStamp\",\"communicantEdit\")
								values ($corp_regis,$CommunicantName[$e],$CommunicantPosition[$e],$CommunicantCoordinate[$e],$CommunicantPhone[$e],$CommunicantMobile[$e],$CommunicantEmail[$e]
									,'$username','$logs_any_time','$nextEdit')";
				if($result=pg_query($sql_add_Communicant))
				{}
				else
				{
					$status++;
				}
			}
		}
		
		// เพิ่มผู้รับมอบอำนาจของลูกค้านิติบุคคล
		if($rowAttorney == 0 || ($rowAttorney == 1 && $AttorneyName[1] == "NULL"))
		{
			// ถ้าเข้าเงื่อนไขนี้จะไม่ทำอะไร
		}
		else
		{
			for($f=1;$f<=$rowAttorney;$f++)
			{
				$sql_add_Attorney = "insert into public.\"th_corp_attorney_temp\" (\"corp_regis\",\"CusID\",\"doerUser\",\"doerStamp\",\"attorneyEdit\",\"path_receipt_authority\")
								values ($corp_regis,$AttorneyName[$f],'$username','$logs_any_time','$nextEdit',$Attorneyfile[$f])";
				if($result=pg_query($sql_add_Attorney))
				{}
				else
				{
					$status++;
				}
			}
		}
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ขอเพิ่มลูกค้านิติบุคคล', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<form method=\"post\" name=\"form1\" action=\"frm_addCorp.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	//echo "<br>$sql_add_address_certificate<br>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_addCorp.php\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
//--------------- จบการบันทึกข้อมูล
?>