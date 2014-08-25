<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$query = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
while($result = pg_fetch_array($query))
{
	$username = $result["username"]; // username ที่ทำรายการ
}

$query_level = pg_query("select * from public.\"fuser\" where \"id_user\" = '$id_user' ");
while($result_level = pg_fetch_array($query_level))
{
	$emplevel = $result_level["emplevel"]; // level ของ พนักงาน
}

$corp_regis = pg_escape_string($_GET["corp"]);
$appv = pg_escape_string($_GET["appv"]);

$RemarkAll = pg_escape_string($_POST["RemarkAll"]); // หมายเหตุ

//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
$querychk = pg_query("select * from public.\"th_corp_temp\" where \"Approved\" is null and \"hidden\" = 'false' and \"corpID\" = '0' and \"corp_regis\"='$corp_regis'");
$numchk = pg_num_rows($querychk);

if($numchk>0){ //แสดงว่ายังไม่ได้อนุมัติให้ทำรายการต่อได้

//--------------- เริ่มบันทึกข้อมูล
pg_query("BEGIN");
$status = 0;

if($appv == "2") // ถ้าไม่อนุมัติ
{
	$query_lastedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ");
	while($res_lastedit = pg_fetch_array($query_lastedit))
	{
		$lastedit = $res_lastedit["maxedit"];
	}
	
	$query_chk_user = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$lastedit' and \"Approved\" is null and \"hidden\" = 'false' ");
	while($result_chk_user = pg_fetch_array($query_chk_user))
	{
		$doerUser = $result_chk_user["doerUser"];
	}
	
	if($emplevel <= 3)
	{
		// ถ้า level น้อยกว่าหรือเท่ากับ 3 สามารถทำงานได้ตามปกติ
	}
	else
	{
		if($username == $doerUser) // ถ้าคนที่เพิ่มข้อมูล กับคนที่อินุมัติเป็นคนเดียวกัน
		{
			$status++;
			$error = "สิทธิของท่านไม่สามารถอนุมัติรายการที่ตนเองทำได้ กรุณาแจ้งหัวหน้าแผนก-ฝ่าย";
		}
	}
		
	if($RemarkAll != "")
	{
		$sql_no_appv_remarkall = "update public.\"th_corp_temp\" set \"RemarkAll\" = '$RemarkAll' 
								where \"corp_regis\" = '$corp_regis' and \"Approved\" is null ";
		if($resultNO_corp_remarkall = pg_query($sql_no_appv_remarkall))
		{}
		else
		{
			$status++;
		}
	}
	
	// ข้อมูลนิติบุคคล
	$sql_no_appv_no_corp = "update public.\"th_corp_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_corp = pg_query($sql_no_appv_no_corp))
	{}
	else
	{
		$status++;
	}
	
	// บัญชีธนาคาร
	$sql_no_appv_no_acc = "update public.\"th_corp_acc_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_acc = pg_query($sql_no_appv_no_acc))
	{}
	else
	{
		$status++;
	}
	
	// ข้อมูลที่อยู่
	$sql_no_appv_no_adds = "update public.\"th_corp_adds_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_adds = pg_query($sql_no_appv_no_adds))
	{}
	else
	{
		$status++;
	}
	
	// ผู้รับมอบอำนาจ
	$sql_no_appv_no_attorney = "update public.\"th_corp_attorney_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_attorney = pg_query($sql_no_appv_no_attorney))
	{}
	else
	{
		$status++;
	}
	
	// กรรมการ
	$sql_no_appv_no_board = "update public.\"th_corp_board_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_board = pg_query($sql_no_appv_no_board))
	{}
	else
	{
		$status++;
	}
	
	// ผู้ติดต่อ
	$sql_no_appv_no_communicant = "update public.\"th_corp_communicant_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_communicant = pg_query($sql_no_appv_no_communicant))
	{}
	else
	{
		$status++;
	}
	
	// ผู้ถือหุ้น
	$sql_no_appv_no_share = "update public.\"th_corp_share_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_share = pg_query($sql_no_appv_no_share))
	{}
	else
	{
		$status++;
	}
}
elseif($appv == "1") // ถ้าอนุมัติ
{
	$query_lastedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ");
	while($res_lastedit = pg_fetch_array($query_lastedit))
	{
		$lastedit = $res_lastedit["maxedit"];
	}
	
	$query_chk_user = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$lastedit' and \"Approved\" is null and \"hidden\" = 'false' ");
	while($result_chk_user = pg_fetch_array($query_chk_user))
	{
		$doerUser = $result_chk_user["doerUser"];
	}
	
	if($emplevel <= 3)
	{
		// ถ้า level น้อยกว่าหรือเท่ากับ 3 สามารถทำงานได้ตามปกติ
	}
	else
	{
		if($username == $doerUser) // ถ้าคนที่เพิ่มข้อมูล กับคนที่อินุมัติเป็นคนเดียวกัน
		{
			$status++;
			$error = "สิทธิของท่านไม่สามารถอนุมัติรายการที่ตนเองทำได้ กรุณาแจ้งหัวหน้าแผนก-ฝ่าย";
		}
	}
	
	// ข้อมูลนิติบุคคล
	$sql_no_appv_no_corp = "update public.\"th_corp_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_corp = pg_query($sql_no_appv_no_corp))
	{}
	else
	{
		$status++;
	}
	
	// บัญชีธนาคาร
	$sql_no_appv_no_acc = "update public.\"th_corp_acc_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_acc = pg_query($sql_no_appv_no_acc))
	{}
	else
	{
		$status++;
	}
	
	// ข้อมูลที่อยู่
	$sql_no_appv_no_adds = "update public.\"th_corp_adds_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_adds = pg_query($sql_no_appv_no_adds))
	{}
	else
	{
		$status++;
	}
	
	// ผู้รับมอบอำนาจ
	$sql_no_appv_no_attorney = "update public.\"th_corp_attorney_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_attorney = pg_query($sql_no_appv_no_attorney))
	{}
	else
	{
		$status++;
	}
	
	// กรรมการ
	$sql_no_appv_no_board = "update public.\"th_corp_board_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_board = pg_query($sql_no_appv_no_board))
	{}
	else
	{
		$status++;
	}
	
	// ผู้ติดต่อ
	$sql_no_appv_no_communicant = "update public.\"th_corp_communicant_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_communicant = pg_query($sql_no_appv_no_communicant))
	{}
	else
	{
		$status++;
	}
	
	// ผู้ถือหุ้น
	$sql_no_appv_no_share = "update public.\"th_corp_share_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$username' , \"appvStamp\" = '$logs_any_time' 
							where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ";
	if($resultNO_share = pg_query($sql_no_appv_no_share))
	{}
	else
	{
		$status++;
	}
	
	
	// หาจำนวนที่แก้ไขครั้งล่าสุด
	$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($res_maxedit = pg_fetch_array($query_maxedit))
	{
		$maxedit = $res_maxedit["maxedit"];
	}
	
	$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_corp = pg_fetch_array($query_corp))
	{
		$corpType = $result_corp["corpType"]; // ประเภทนิติบุคคล
		$corpName_THA = $result_corp["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
		$corpName_ENG = $result_corp["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
		$trade_name = $result_corp["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
		$TaxNumber = $result_corp["TaxNumber"]; // เลขที่ประจำตัวผู้เสียภาษี
		$phone = $result_corp["phone"]; // โทรศัพท์
		$Fax = $result_corp["Fax"]; // Fax
		$mail = $result_corp["mail"];
		$website = $result_corp["website"];
		$date_of_corp = $result_corp["date_of_corp"]; // วันที่จดทะเบียนบริษัท
		$initial_capital = $result_corp["initial_capital"]; // ทุนจดทะเบียนเริ่มแรก
		$authority = $result_corp["authority"]; // ผู้มีอำนาจการทำรายการของบริษัท
		$current_capital = $result_corp["current_capital"]; // ทุนจดทะเบียนปัจจุบัน
		$asset_avg = $result_corp["asset_avg"]; // สินทรัพย์เฉลี่ย
		$revenue_avg = $result_corp["revenue_avg"]; // รายได้เฉลี่ย
		$debt_avg = $result_corp["debt_avg"]; // หนี้สินเฉลี่ย
		$net_profit = $result_corp["net_profit"]; // กำไรสุทธิ
		$date_of_last_data = $result_corp["date_of_last_data"]; // วันที่ของข้อมูลล่าสุด
		$trends_profit = $result_corp["trends_profit"]; // แนวโน้มกำไร
		$BusinessType = $result_corp["BusinessType"]; // ประเภทธุรกิจ
		$IndustypeID = $result_corp["IndustypeID"]; // รหัสประเภทอุตสาหกรรม
		$explanation = $result_corp["explanation"]; // คำอธิบายกิจการ
		$CountryCode = $result_corp["CountryCode"]; // สัญชาตินิติบุคคล
		
		$Proportion_in_country = $result_corp["Proportion_in_country"]; // สัดส่วนการขายในประเทศ
		$Proportion_out_country = $result_corp["Proportion_out_country"]; // สัดส่วนการขายต่างประเทศ
		$Proportion_Cash = $result_corp["Proportion_Cash"]; // สัดส่วนการขายเงินสด
		$Proportion_Credit = $result_corp["Proportion_Credit"]; // สัดส่วนการขายเงินเชื่อ
		$Amount_Employee = $result_corp["Amount_Employee"]; // จำนวนพนักงาน
		
		if($corpName_THA ==""){$corpName_THA = "NULL";}else{$corpName_THA = "'$corpName_THA'";}
		if($corpName_ENG ==""){$corpName_ENG = "NULL";}else{$corpName_ENG = "'$corpName_ENG'";}
		if($trade_name ==""){$trade_name = "NULL";}else{$trade_name = "'$trade_name'";}
		if($corpType ==""){$corpType = "NULL";}else{$corpType = "'$corpType'";}
		if($TaxNumber ==""){$TaxNumber = "NULL";}else{$TaxNumber = "'$TaxNumber'";}
		if($phone ==""){$phone = "NULL";}else{$phone = "'$phone'";}
		if($Fax ==""){$Fax = "NULL";}else{$Fax = "'$Fax'";}
		if($mail ==""){$mail = "NULL";}else{$mail = "'$mail'";}
		if($website ==""){$website = "NULL";}else{$website = "'$website'";}
		if($date_of_corp ==""){$date_of_corp = "NULL";}else{$date_of_corp = "'$date_of_corp'";}
		if($initial_capital ==""){$initial_capital = "NULL";}else{$initial_capital = "'$initial_capital'";}
		if($authority ==""){$authority = "NULL";}else{$authority = "'$authority'";}
		if($current_capital ==""){$current_capital = "NULL";}else{$current_capital = "'$current_capital'";}
		if($asset_avg ==""){$asset_avg = "NULL";}else{$asset_avg = "'$asset_avg'";}
		if($revenue_avg ==""){$revenue_avg = "NULL";}else{$revenue_avg = "'$revenue_avg'";}
		if($debt_avg ==""){$debt_avg = "NULL";}else{$debt_avg = "'$debt_avg'";}
		if($net_profit ==""){$net_profit = "NULL";}else{$net_profit = "'$net_profit'";}
		if($date_of_last_data ==""){$date_of_last_data = "NULL";}else{$date_of_last_data = "'$date_of_last_data'";}
		if($trends_profit ==""){$trends_profit = "NULL";}else{$trends_profit = "'$trends_profit'";}
		if($BusinessType ==""){$BusinessType = "NULL";}else{$BusinessType = "'$BusinessType'";}
		if($IndustypeID ==""){$IndustypeID = "NULL";}else{$IndustypeID = "'$IndustypeID'";}
		if($explanation ==""){$explanation = "NULL";}else{$explanation = "'$explanation'";}
		if($CountryCode ==""){$CountryCode = "NULL";}else{$CountryCode = "'$CountryCode'";}
		
		if($Proportion_in_country ==""){$Proportion_in_country = "NULL";}else{$Proportion_in_country = "'$Proportion_in_country'";}
		if($Proportion_out_country ==""){$Proportion_out_country = "NULL";}else{$Proportion_out_country = "'$Proportion_out_country'";}
		if($Proportion_Cash ==""){$Proportion_Cash = "NULL";}else{$Proportion_Cash = "'$Proportion_Cash'";}
		if($Proportion_Credit ==""){$Proportion_Credit = "NULL";}else{$Proportion_Credit = "'$Proportion_Credit'";}
		if($Amount_Employee ==""){$Amount_Employee = "NULL";}else{$Amount_Employee = "'$Amount_Employee'";}
		
		// เพิ่มข้อมูลนิติบุคคล
		$sql_add_corp = "insert into public.\"th_corp\" (\"corpType\",\"corpName_THA\",\"corpName_ENG\",\"trade_name\",\"corp_regis\",\"TaxNumber\",\"phone\",\"Fax\",\"mail\",\"website\",\"date_of_corp\",
			\"initial_capital\",\"authority\",\"current_capital\",\"asset_avg\",\"revenue_avg\",\"debt_avg\",\"net_profit\",\"date_of_last_data\",\"trends_profit\",\"BusinessType\",\"IndustypeID\",
			\"explanation\",\"Proportion_in_country\",\"Proportion_out_country\",\"Proportion_Cash\",\"Proportion_Credit\",\"Amount_Employee\",\"CountryCode\")
			values ($corpType,$corpName_THA,$corpName_ENG,$trade_name,'$corp_regis',$TaxNumber,$phone,$Fax,$mail,$website,$date_of_corp,
					$initial_capital,$authority,$current_capital,$asset_avg,$revenue_avg,$debt_avg,$net_profit,$date_of_last_data,$trends_profit,$BusinessType,$IndustypeID,
					$explanation,$Proportion_in_country,$Proportion_out_country,$Proportion_Cash,$Proportion_Credit,$Amount_Employee,$CountryCode)";
		if($result=pg_query($sql_add_corp))
		{}
		else
		{
			$status++;
		}
	}
	
	$query_maxcorp = pg_query("select max(\"corpID\") as \"corpID\" from public.\"th_corp\" where \"corp_regis\" = '$corp_regis' ");
	$row_corpID = pg_num_rows($query_maxcorp);
	while($result_maxcorp = pg_fetch_array($query_maxcorp))
	{
		$corpID = $result_maxcorp["corpID"];
	}
	
	//-------- หาที่อยู่ลูกค้านิติบุคคล
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_corp = pg_fetch_array($query_adds))
	{
		$addsType = $result_corp["addsType"]; // ประเภทที่อยู่
		$addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
		$floor = $result_corp["floor"]; // จำนวนชั้น
		$HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
		$room = $result_corp["room"]; // หมายเลขห้อง
		$LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
		$Moo = $result_corp["Moo"]; // หมู่ที่
		$Building = $result_corp["Building"]; // อาคาร/สถานที่
		$Village = $result_corp["Village"]; // หมู่บ้าน
		$Lane = $result_corp["Lane"]; // ซอย
		$Road = $result_corp["Road"]; // ถนน
		$District = $result_corp["District"]; // แขวง/ตำบล
		$State = $result_corp["State"]; // เขต/อำเภอ
		$Province = $result_corp["ProvinceID"]; // จังหวัด
		$Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
		$Country = $result_corp["Country"]; // ประเทศ
		$phone = $result_corp["phone"]; // โทรศัพท์
		$Fax = $result_corp["Fax"]; // โทรสาร
		$Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
		$Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
		$Acquired = $result_corp["Acquired"]; // ได้มาโดย
		$purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ

		if($addsType ==""){$addsType = "NULL";}else{$addsType = "'$addsType'";}
		if($addsStyle ==""){$addsStyle = "NULL";}else{$addsStyle = "'$addsStyle'";}
		if($HomeNumber ==""){$HomeNumber = "NULL";}else{$HomeNumber = "'$HomeNumber'";}
		if($room ==""){$room = "NULL";}else{$room = "'$room'";}
		if($LiveFloor ==""){$LiveFloor = "NULL";}else{$LiveFloor = "'$LiveFloor'";}
		if($Moo ==""){$Moo = "NULL";}else{$Moo = "'$Moo'";}
		if($Building ==""){$Building = "NULL";}else{$Building = "'$Building'";}
		if($Village ==""){$Village = "NULL";}else{$Village = "'$Village'";}
		if($Lane ==""){$Lane = "NULL";}else{$Lane = "'$Lane'";}
		if($Road ==""){$Road = "NULL";}else{$Road = "'$Road'";}
		if($District ==""){$District = "NULL";}else{$District = "'$District'";}
		if($State ==""){$State = "NULL";}else{$State = "'$State'";}
		if($Province ==""){$Province = "NULL";}else{$Province = "'$Province'";}
		if($Postal_code ==""){$Postal_code = "NULL";}else{$Postal_code = "'$Postal_code'";}
		if($Country ==""){$Country = "NULL";}else{$Country = "'$Country'";}
		if($phone ==""){$phone = "NULL";}else{$phone = "'$phone'";}
		if($Fax ==""){$Fax = "NULL";}else{$Fax = "'$Fax'";}
		if($Live_it ==""){$Live_it = "NULL";}else{$Live_it = "'$Live_it'";}
		if($Completion ==""){$Completion = "NULL";}else{$Completion = "'$Completion'";}
		if($Acquired ==""){$Acquired = "NULL";}else{$Acquired = "'$Acquired'";}
		if($purchase_price ==""){$purchase_price = "NULL";}else{$purchase_price = "'$purchase_price'";}
		if($floor ==""){$floor = "NULL";}else{$floor = "'$floor'";}
		
		// เพิ่มที่อยู่ลูกค้านิติบุคคล
		$sql_add_address = "insert into public.\"th_corp_adds\" (\"corpID\",\"corp_regis\",\"addsType\",\"addsStyle\",\"HomeNumber\",\"room\",\"LiveFloor\",\"Moo\",\"Building\",\"Village\",\"Lane\",
													\"Road\",\"District\",\"State\",\"ProvinceID\",\"Postal_code\",\"Country\",\"phone\",\"Fax\",\"Live_it\",\"Completion\",\"Acquired\",
													\"purchase_price\",\"floor\")
										values ('$corpID','$corp_regis',$addsType,$addsStyle,$HomeNumber,$room,$LiveFloor,$Moo,$Building,$Village,$Lane,
												$Road,$District,$State,$Province,$Postal_code,$Country,$phone,$Fax,$Live_it,$Completion,$Acquired,
												$purchase_price,$floor)";
		if($result_adds = pg_query($sql_add_address))
		{}
		else
		{
			$status++;
		}
	}
	
	//----- บัญชีธนาคาร
	$query_bank = pg_query("select * from public.\"th_corp_acc_temp\" where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_babk = pg_fetch_array($query_bank))
	{
		$acc_Number = $result_babk["acc_Number"]; // เลขที่บัญชี
		$bankID = $result_babk["bankID"]; // รหัสธนาคาร
		$acc_Name = $result_babk["acc_Name"]; // ชื่อบัญชี
		$branch = $result_babk["branch"]; // สาขา
		$acc_type = $result_babk["acc_type"]; // ประเภทบัญชี
		
		if($acc_Number ==""){$acc_Number = "NULL";}else{$acc_Number = "'$acc_Number'";}
		if($bankID ==""){$bankID = "NULL";}else{$bankID = "'$bankID'";}
		if($acc_Name ==""){$acc_Name = "NULL";}else{$acc_Name = "'$acc_Name'";}
		if($branch ==""){$branch = "NULL";}else{$branch = "'$branch'";}
		if($acc_type ==""){$acc_type = "NULL";}else{$acc_type = "'$acc_type'";}
		
		// เพิ่มบัญชีธนาคาร
		$sql_add_bank = "insert into public.\"th_corp_acc\" (\"corpID\",\"corp_regis\",\"acc_Number\",\"bankID\",\"acc_Name\",\"branch\",\"acc_type\")
										values ('$corpID','$corp_regis',$acc_Number,$bankID,$acc_Name,$branch,$acc_type)";
		if($result_bank = pg_query($sql_add_bank))
		{}
		else
		{
			$status++;
		}
	}
	
	//----- ผู้รับมอบอำนาจ
	$query_attorney = pg_query("select * from public.\"th_corp_attorney_temp\" where \"corp_regis\" = '$corp_regis' and \"attorneyEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_attorney = pg_fetch_array($query_attorney))
	{
		$CusID = $result_attorney["CusID"]; // รหัสลูกค้า หรือ ชื่อลูกค้า
		$path_receipt_authority = $result_attorney["path_receipt_authority"]; // ที่เก็บไฟล์ใบรับมอบอำนาจ
		
		if($CusID ==""){$CusID = "NULL";}else{$CusID = "'$CusID'";}
		if($path_receipt_authority ==""){$path_receipt_authority = "NULL";}else{$path_receipt_authority = "'$path_receipt_authority'";}
		
		// เพิ่มผู้รับมอบอำนาจ
		$sql_add_attorney = "insert into public.\"th_corp_attorney\" (\"corpID\",\"corp_regis\",\"CusID\",\"path_receipt_authority\")
										values ('$corpID','$corp_regis',$CusID,$path_receipt_authority)";
		if($result_attorney = pg_query($sql_add_attorney))
		{}
		else
		{
			$status++;
		}
	}
	
	//----- กรรมการ
	$query_board = pg_query("select * from public.\"th_corp_board_temp\" where \"corp_regis\" = '$corp_regis' and \"boardEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_board = pg_fetch_array($query_board))
	{
		$CusID = $result_board["CusID"]; // รหัสลูกค้า หรือ ชื่อลูกค้า
		$path_signature = $result_board["path_signature"]; // ที่เก็บไฟล์ใบรับมอบอำนาจ
		
		if($CusID ==""){$CusID = "NULL";}else{$CusID = "'$CusID'";}
		if($path_signature ==""){$path_signature = "NULL";}else{$path_signature = "'$path_signature'";}
		
		// เพิ่มกรรมการ
		$sql_add_board = "insert into public.\"th_corp_board\" (\"corpID\",\"corp_regis\",\"CusID\",\"path_signature\")
										values ('$corpID','$corp_regis',$CusID,$path_signature)";
		if($result_board = pg_query($sql_add_board))
		{}
		else
		{
			$status++;
		}
	}
	
	//----- ผู้ติดต่อ
	$query_communicant = pg_query("select * from public.\"th_corp_communicant_temp\" where \"corp_regis\" = '$corp_regis' and \"communicantEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_communicant = pg_fetch_array($query_communicant))
	{
		$CommunicantName = $result_communicant["CommunicantName"]; // ชื่อผู้ติดต่อ
		$position = $result_communicant["position"]; // ตำแหน่ง
		$subject = $result_communicant["position"]; // ประสานงานเรื่อง
		$phone = $result_communicant["phone"]; // เบอร์โทรศัพท์
		$mobile = $result_communicant["mobile"]; // เบอร์มือถือ
		$email = $result_communicant["email"]; // ประสานงานเรื่อง
		
		if($CommunicantName ==""){$CommunicantName = "NULL";}else{$CommunicantName = "'$CommunicantName'";}
		if($position ==""){$position = "NULL";}else{$position = "'$position'";}
		if($subject ==""){$subject = "NULL";}else{$subject = "'$subject'";}
		if($phone ==""){$phone = "NULL";}else{$phone = "'$phone'";}
		if($mobile ==""){$mobile = "NULL";}else{$mobile = "'$mobile'";}
		if($email ==""){$email = "NULL";}else{$email = "'$email'";}
		
		// เพิ่มกรรมการ
		$sql_add_communicant = "insert into public.\"th_corp_communicant\" (\"corpID\",\"corp_regis\",\"CommunicantName\",\"position\",\"subject\",\"phone\",\"mobile\",\"email\")
										values ('$corpID','$corp_regis',$CommunicantName,$position,$subject,$phone,$mobile,$email)";
		if($result_communicant = pg_query($sql_add_communicant))
		{}
		else
		{
			$status++;
		}
	}
	
	//----- ผู้ถือหุ้น
	$query_share = pg_query("select * from public.\"th_corp_share_temp\" where \"corp_regis\" = '$corp_regis' and \"shareEdit\" = '$maxedit' and \"Approved\" = 'true' and \"hidden\" = 'false' ");
	while($result_share = pg_fetch_array($query_share))
	{
		$CusID = $result_share["CusID"]; // รหัสลูกค้า หรือ ชื่อลูกค้า
		$share_amount = $result_share["share_amount"]; // จำนวนหุ้น
		$share_value = $result_share["share_value"]; // มูลค่าหุ้น
		$path_signature = $result_share["path_signature"]; // ที่เก็บไฟล์ตัวอย่างลายเซ็นต์
		
		if($CusID ==""){$CusID = "NULL";}else{$CusID = "'$CusID'";}
		if($share_amount ==""){$share_amount = "NULL";}else{$share_amount = "'$share_amount'";}
		if($share_value ==""){$share_value = "NULL";}else{$share_value = "'$share_value'";}
		if($path_signature ==""){$path_signature = "NULL";}else{$path_signature = "'$path_signature'";}
		
		// เพิ่มผู้ถือหุ้น
		$sql_add_share = "insert into public.\"th_corp_share\" (\"corpID\",\"corp_regis\",\"CusID\",\"share_amount\",\"share_value\",\"path_signature\")
										values ('$corpID','$corp_regis',$CusID,$share_amount,$share_value,$path_signature)";
		if($result_share = pg_query($sql_add_share))
		{}
		else
		{
			$status++;
		}
	}
	
	
	// เพิ่มรหัสนิติบุคคลให้กับ ข้อมูลนิติบุคคล
	$sql_no_appv_no_corp = "update public.\"th_corp_temp\" set \"corpID\" = '$corpID'  
							where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_corp = pg_query($sql_no_appv_no_corp))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ บัญชีธนาคาร
	$sql_no_appv_no_acc = "update public.\"th_corp_acc_temp\" set \"corpID\" = '$corpID' 
							where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_acc = pg_query($sql_no_appv_no_acc))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ ที่อยู่ลูกค้านิติบุคคล
	$sql_no_appv_no_adds = "update public.\"th_corp_adds_temp\" set \"corpID\" = '$corpID'
							where \"corp_regis\" = '$corp_regis' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_adds = pg_query($sql_no_appv_no_adds))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ ผู้รับมอบอำนาจ
	$sql_no_appv_no_attorney = "update public.\"th_corp_attorney_temp\" set \"corpID\" = '$corpID' 
							where \"corp_regis\" = '$corp_regis' and \"attorneyEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_attorney = pg_query($sql_no_appv_no_attorney))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ กรรมการ
	$sql_no_appv_no_board = "update public.\"th_corp_board_temp\" set \"corpID\" = '$corpID' 
							where \"corp_regis\" = '$corp_regis' and \"boardEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_board = pg_query($sql_no_appv_no_board))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ ผู้ติดต่อ
	$sql_no_appv_no_communicant = "update public.\"th_corp_communicant_temp\" set \"corpID\" = '$corpID' 
							where \"corp_regis\" = '$corp_regis' and \"communicantEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_communicant = pg_query($sql_no_appv_no_communicant))
	{}
	else
	{
		$status++;
	}
	
	// เพิ่มรหัสนิติบุคคลให้กับ ผู้ถือหุ้น
	$sql_no_appv_no_share = "update public.\"th_corp_share_temp\" set \"corpID\" = '$corpID' 
							where \"corp_regis\" = '$corp_regis' and \"shareEdit\" = '$maxedit' and \"Approved\" = 'true' and \"corpID\" = '0' and \"hidden\" = 'false' ";
	if($resultNO_share = pg_query($sql_no_appv_no_share))
	{}
	else
	{
		$status++;
	}
}


if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) อนุมัติลูกค้านิติบุคคล', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<center><h2><font color=\"#FF0000\">$error</font></h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_detail_appvCorp.php?corp_regis=$corp_regis\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
//--------------- จบการบันทึกข้อมูล
}else{ //กรณีมีการอนุมัติไปแล้วก่อนหน้านี้
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ได้รับการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:RefreshMe();\"></div>";
}
?>