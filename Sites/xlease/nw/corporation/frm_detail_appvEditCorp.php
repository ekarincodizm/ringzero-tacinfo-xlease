<?php
session_start();
include("../../config/config.php");

$corp_regis = $_GET["corp_regis"];
$corpID = $_GET["corpID"];

//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
$querychk = pg_query("select * from public.\"th_corp_temp\" where \"Approved\" is null and \"hidden\" = 'false' and \"corpID\"='$corpID'");
$numchk = pg_num_rows($querychk);

if($numchk>0){ //แสดงว่ายังไม่อนุมัติ
// Query ข้อมูลของพนักงาน
$Uid = $_SESSION["av_iduser"];
$qry_user=pg_query("select * from \"Vfuser\" WHERE id_user='$Uid' ");
$res_user=pg_fetch_array($qry_user);
$emplevel=$res_user["emplevel"];
$Uid=$res_user["username"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดลูกค้านิติบุคคล</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function NoAppv() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm1.RemarkAll.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่สาเหตุที่ไม่อนุมัติ ในกรณีที่ไม่อนุมัติ";
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
		//window.location='process_appvCorp.php?appv=2&corp=<?php echo $corp_regis; ?>';
	} 
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>




<!---- หน้าต่าง Popup รูปภาพ ---->

<!-- Add jQuery library -->
	<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

<script type="text/javascript">
		$(document).ready(function() {
		
			$('.fancyboxa').fancybox({
				minWidth: 450,
				maxWidth: 450
						
			});
			$('.fancyboxb').fancybox({	
				minWidth: 450,
				maxWidth: 450
			  });
			
			$(".pdforpic").fancybox({
			   minWidth: 500,
			   maxWidth: 800,
			   'height' : '600',
			   'autoScale' : true,
			   'transitionIn' : 'none',
			   'transitionOut' : 'none',
			   'type' : 'iframe'
			});

		});
</script>



<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<?php
//----- ข้อมูลนิติบุคคล
$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corpID\" = '$corpID' and \"Approved\" is null and \"hidden\" = 'false' ");
while($result_corp = pg_fetch_array($query_corp))
{
	$corpID = $result_corp["corpID"]; // รหัสนิติบุคคล
	$corpType = $result_corp["corpType"]; // ประเภทนิติบุคคล
	$corpName_THA = $result_corp["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
	$corpName_ENG = $result_corp["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
	$trade_name = $result_corp["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
	$corp_regis = $result_corp["corp_regis"]; // เลขทะเบียนนิติบุคคล(13 หลัก)
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
	$Proportion_in_country = $result_corp["Proportion_in_country"]; // สัดส่วนภายในประเทศ
	$Proportion_out_country = $result_corp["Proportion_out_country"]; // สัดสว่นภายนอกประเทศ
	$Proportion_Cash = $result_corp["Proportion_Cash"]; // สัดส่วนการขายเงินสด
	$Proportion_Credit = $result_corp["Proportion_Credit"]; // สัดส่วนการขายสินเชื่อ
	$Amount_Employee = $result_corp["Amount_Employee"]; // จำนวนพนักงาน
	$iduser = $result_corp["doerUser"]; // user ที่ทำรายการ
	$CountryCode = $result_corp["CountryCode"]; // สัญชาตินิติบุคคล
	
	$phone = str_replace("#"," ต่อ ",$phone);
	
	if($IndustypeID == 0)
	{
		$IndustypeName = "ไม่ระบุ";
	}
	else
	{
		$query_Industype = pg_query("select * from public.\"th_corp_industype\" where \"IndustypeID\" = '$IndustypeID' ");
		while($result_Industype = pg_fetch_array($query_Industype))
		{
			$IndustypeName = $result_Industype["IndustypeName"];
		}
	}
	
	if($CountryCode != "")
	{
		$qry_country = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode' ");
		$CountryName_THAI = pg_fetch_result($qry_country,0);
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ตามหนังสือรับรอง
$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corpID\" = '$corpID' and \"addsType\" = '1' and \"Approved\" is null and \"hidden\" = 'false' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$C_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$C_floor = $result_corp["floor"]; // จำนวนชั้น
	$C_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$C_room = $result_corp["room"]; // หมายเลขห้อง
	$C_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$C_Moo = $result_corp["Moo"]; // หมู่ที่
	$C_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$C_Village = $result_corp["Village"]; // หมู่บ้าน
	$C_Lane = $result_corp["Lane"]; // ซอย
	$C_Road = $result_corp["Road"]; // ถนน
	$C_District = $result_corp["District"]; // แขวง/ตำบล
	$C_State = $result_corp["State"]; // เขต/อำเภอ
	$C_Province = $result_corp["ProvinceID"]; // จังหวัด
	$C_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$C_Country = $result_corp["Country"]; // ประเทศ
	$C_phone = $result_corp["phone"]; // โทรศัพท์
	$C_Fax = $result_corp["Fax"]; // โทรสาร
	$C_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$C_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$C_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$C_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$C_phone = str_replace("#"," ต่อ ",$C_phone);
	
	if($C_floor != "")
	{
		$C_addsStyle = "$C_addsStyle $C_floor ชั้น";
	}
	
	if($C_Province != "")
	{
		$query_C_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$C_Province'");
		while($res_C_Province_name = pg_fetch_array($query_C_Province_name))
		{
			$C_Province = $res_C_Province_name["proName"];
		}
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่สำนักงานใหญ่
$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corpID\" = '$corpID' and \"addsType\" = '2' and \"Approved\" is null and \"hidden\" = 'false' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$H_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$H_floor = $result_corp["floor"]; // จำนวนชั้น
	$H_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$H_room = $result_corp["room"]; // หมายเลขห้อง
	$H_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$H_Moo = $result_corp["Moo"]; // หมู่ที่
	$H_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$H_Village = $result_corp["Village"]; // หมู่บ้าน
	$H_Lane = $result_corp["Lane"]; // ซอย
	$H_Road = $result_corp["Road"]; // ถนน
	$H_District = $result_corp["District"]; // แขวง/ตำบล
	$H_State = $result_corp["State"]; // เขต/อำเภอ
	$H_Province = $result_corp["ProvinceID"]; // จังหวัด
	$H_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$H_Country = $result_corp["Country"]; // ประเทศ
	$H_phone = $result_corp["phone"]; // โทรศัพท์
	$H_Fax = $result_corp["Fax"]; // โทรสาร
	$H_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$H_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$H_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$H_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$H_phone = str_replace("#"," ต่อ ",$H_phone);
	
	if($H_floor != "")
	{
		$H_addsStyle = "$H_addsStyle $H_floor ชั้น";
	}
	
	if($H_Province != "")
	{
		$query_H_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$H_Province'");
		while($res_H_Province_name = pg_fetch_array($query_H_Province_name))
		{
			$H_Province = $res_H_Province_name["proName"];
		}
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corpID\" = '$corpID' and \"addsType\" = '3' and \"Approved\" is null and \"hidden\" = 'false' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$M_addsStyle = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$M_floor = $result_corp["floor"]; // จำนวนชั้น
	$M_HomeNumber = $result_corp["HomeNumber"]; // บ้านเลขที่
	$M_room = $result_corp["room"]; // หมายเลขห้อง
	$M_LiveFloor = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$M_Moo = $result_corp["Moo"]; // หมู่ที่
	$M_Building = $result_corp["Building"]; // อาคาร/สถานที่
	$M_Village = $result_corp["Village"]; // หมู่บ้าน
	$M_Lane = $result_corp["Lane"]; // ซอย
	$M_Road = $result_corp["Road"]; // ถนน
	$M_District = $result_corp["District"]; // แขวง/ตำบล
	$M_State = $result_corp["State"]; // เขต/อำเภอ
	$M_Province = $result_corp["ProvinceID"]; // จังหวัด
	$M_Postal_code = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$M_Country = $result_corp["Country"]; // ประเทศ
	$M_phone = $result_corp["phone"]; // โทรศัพท์
	$M_Fax = $result_corp["Fax"]; // โทรสาร
	$M_Live_it = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$M_Completion = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$M_Acquired = $result_corp["Acquired"]; // ได้มาโดย
	$M_purchase_price = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$M_phone = str_replace("#"," ต่อ ",$M_phone);
	
	if($M_floor != "")
	{
		$M_addsStyle = "$M_addsStyle $M_floor ชั้น";
	}
	
	if($M_Province != "")
	{
		$query_M_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$M_Province'");
		while($res_M_Province_name = pg_fetch_array($query_M_Province_name))
		{
			$M_Province = $res_M_Province_name["proName"];
		}
	}
}


//------------------------- เริ่มเปรียบเทียบข้อมูล
//----- ข้อมูลนิติบุคคล
$query_corp = pg_query("select * from public.\"th_corp\" where \"corpID\" = '$corpID' ");
while($result_corp = pg_fetch_array($query_corp))
{
	$corpID_old = $result_corp["corpID"]; // รหัสนิติบุคคล
	$corpType_old = $result_corp["corpType"]; // ประเภทนิติบุคคล
	$corpName_THA_old = $result_corp["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
	$corpName_ENG_old = $result_corp["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
	$trade_name_old = $result_corp["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
	$corp_regis_old = $result_corp["corp_regis"]; // เลขทะเบียนนิติบุคคล(13 หลัก)
	$TaxNumber_old = $result_corp["TaxNumber"]; // เลขที่ประจำตัวผู้เสียภาษี
	$phone_old = $result_corp["phone"]; // โทรศัพท์
	$Fax_old = $result_corp["Fax"]; // Fax
	$mail_old = $result_corp["mail"];
	$website_old = $result_corp["website"];
	$date_of_corp_old = $result_corp["date_of_corp"]; // วันที่จดทะเบียนบริษัท
	$initial_capital_old = $result_corp["initial_capital"]; // ทุนจดทะเบียนเริ่มแรก
	$authority_old = $result_corp["authority"]; // ผู้มีอำนาจการทำรายการของบริษัท
	$current_capital_old = $result_corp["current_capital"]; // ทุนจดทะเบียนปัจจุบัน
	$asset_avg_old = $result_corp["asset_avg"]; // สินทรัพย์เฉลี่ย
	$revenue_avg_old = $result_corp["revenue_avg"]; // รายได้เฉลี่ย
	$debt_avg_old = $result_corp["debt_avg"]; // หนี้สินเฉลี่ย
	$net_profit_old = $result_corp["net_profit"]; // กำไรสุทธิ
	$date_of_last_data_old = $result_corp["date_of_last_data"]; // วันที่ของข้อมูลล่าสุด
	$trends_profit_old = $result_corp["trends_profit"]; // แนวโน้มกำไร
	$BusinessType_old = $result_corp["BusinessType"]; // ประเภทธุรกิจ
	$IndustypeID_old = $result_corp["IndustypeID"]; // รหัสประเภทอุตสาหกรรม
	$explanation_old = $result_corp["explanation"]; // คำอธิบายกิจการ
	$CountryCode_old = $result_corp["CountryCode"]; // ชื่อย่อสัาชาติ หรือประเทศ
	
	$phone_old = str_replace("#"," ต่อ ",$phone_old);
	
	if($IndustypeID_old == 0)
	{
		$IndustypeName_old = "ไม่ระบุ";
	}
	else
	{
		$query_Industype = pg_query("select * from public.\"th_corp_industype\" where \"IndustypeID\" = '$IndustypeID_old' ");
		while($result_Industype = pg_fetch_array($query_Industype))
		{
			$IndustypeName_old = $result_Industype["IndustypeName"];
		}
	}
	
	if($CountryCode_old != "")
	{
		$qry_country_old = pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$CountryCode_old' ");
		$CountryName_THAI_old = pg_fetch_result($qry_country_old,0);
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ตามหนังสือรับรอง
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '1' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$C_addsStyle_old = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$C_floor_old = $result_corp["floor"]; // จำนวนชั้น
	$C_HomeNumber_old = $result_corp["HomeNumber"]; // บ้านเลขที่
	$C_room_old = $result_corp["room"]; // หมายเลขห้อง
	$C_LiveFloor_old = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$C_Moo_old = $result_corp["Moo"]; // หมู่ที่
	$C_Building_old = $result_corp["Building"]; // อาคาร/สถานที่
	$C_Village_old = $result_corp["Village"]; // หมู่บ้าน
	$C_Lane_old = $result_corp["Lane"]; // ซอย
	$C_Road_old = $result_corp["Road"]; // ถนน
	$C_District_old = $result_corp["District"]; // แขวง/ตำบล
	$C_State_old = $result_corp["State"]; // เขต/อำเภอ
	$C_Province_old = $result_corp["ProvinceID"]; // จังหวัด
	$C_Postal_code_old = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$C_Country_old = $result_corp["Country"]; // ประเทศ
	$C_phone_old = $result_corp["phone"]; // โทรศัพท์
	$C_Fax_old = $result_corp["Fax"]; // โทรสาร
	$C_Live_it_old = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$C_Completion_old = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$C_Acquired_old = $result_corp["Acquired"]; // ได้มาโดย
	$C_purchase_price_old = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$C_phone_old = str_replace("#"," ต่อ ",$C_phone_old);
	
	if($C_floor_old != "")
	{
		$C_addsStyle_old = "$C_addsStyle_old $C_floor_old ชั้น";
	}
	
	if($C_Province_old != "")
	{
		$query_C_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$C_Province_old'");
		while($res_C_Province_name = pg_fetch_array($query_C_Province_name))
		{
			$C_Province_old = $res_C_Province_name["proName"];
		}
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่สำนักงานใหญ่
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '2' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$H_addsStyle_old = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$H_floor_old = $result_corp["floor"]; // จำนวนชั้น
	$H_HomeNumber_old = $result_corp["HomeNumber"]; // บ้านเลขที่
	$H_room_old = $result_corp["room"]; // หมายเลขห้อง
	$H_LiveFloor_old = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$H_Moo_old = $result_corp["Moo"]; // หมู่ที่
	$H_Building_old = $result_corp["Building"]; // อาคาร/สถานที่
	$H_Village_old = $result_corp["Village"]; // หมู่บ้าน
	$H_Lane_old = $result_corp["Lane"]; // ซอย
	$H_Road_old = $result_corp["Road"]; // ถนน
	$H_District_old = $result_corp["District"]; // แขวง/ตำบล
	$H_State_old = $result_corp["State"]; // เขต/อำเภอ
	$H_Province_old = $result_corp["ProvinceID"]; // จังหวัด
	$H_Postal_code_old = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$H_Country_old = $result_corp["Country"]; // ประเทศ
	$H_phone_old = $result_corp["phone"]; // โทรศัพท์
	$H_Fax_old = $result_corp["Fax"]; // โทรสาร
	$H_Live_it_old = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$H_Completion_old = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$H_Acquired_old = $result_corp["Acquired"]; // ได้มาโดย
	$H_purchase_price_old = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$H_phone_old = str_replace("#"," ต่อ ",$H_phone_old);
	
	if($H_floor_old != "")
	{
		$H_addsStyle_old = "$H_addsStyle $H_floor_old ชั้น";
	}
	
	if($H_Province_old != "")
	{
		$query_H_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$H_Province_old'");
		while($res_H_Province_name = pg_fetch_array($query_H_Province_name))
		{
			$H_Province_old = $res_H_Province_name["proName"];
		}
	}
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)
$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" ='$corpID' and \"addsType\" = '3' ");
while($result_corp = pg_fetch_array($query_adds))
{
	$M_addsStyle_old = $result_corp["addsStyle"]; // ลักษณะของที่อยู่
	$M_floor_old = $result_corp["floor"]; // จำนวนชั้น
	$M_HomeNumber_old = $result_corp["HomeNumber"]; // บ้านเลขที่
	$M_room_old = $result_corp["room"]; // หมายเลขห้อง
	$M_LiveFloor_old = $result_corp["LiveFloor"]; // อาศัยอยู่ชั้นที่
	$M_Moo_old = $result_corp["Moo"]; // หมู่ที่
	$M_Building_old = $result_corp["Building"]; // อาคาร/สถานที่
	$M_Village_old = $result_corp["Village"]; // หมู่บ้าน
	$M_Lane_old = $result_corp["Lane"]; // ซอย
	$M_Road_old = $result_corp["Road"]; // ถนน
	$M_District_old = $result_corp["District"]; // แขวง/ตำบล
	$M_State_old = $result_corp["State"]; // เขต/อำเภอ
	$M_Province_old = $result_corp["ProvinceID"]; // จังหวัด
	$M_Postal_code_old = $result_corp["Postal_code"]; // รหัสไปรษณีย์
	$M_Country_old = $result_corp["Country"]; // ประเทศ
	$M_phone_old = $result_corp["phone"]; // โทรศัพท์
	$M_Fax_old = $result_corp["Fax"]; // โทรสาร
	$M_Live_it_old = $result_corp["Live_it"]; // อาศัยมาแล้ว(ปี)
	$M_Completion_old = $result_corp["Completion"]; // ปีที่สร้างเสร็จ
	$M_Acquired_old = $result_corp["Acquired"]; // ได้มาโดย
	$M_purchase_price_old = $result_corp["purchase_price"]; // มูลค่า/ราคาที่ซื้อ
	
	$M_phone_old = str_replace("#"," ต่อ ",$M_phone_old);
	
	if($M_floor_old != "")
	{
		$M_addsStyle_old = "$M_addsStyle_old $M_floor_old ชั้น";
	}
	
	if($M_Province_old != "")
	{
		$query_M_Province_name = pg_query("select * from public.\"nw_province\" where \"proID\" = '$M_Province_old'");
		while($res_M_Province_name = pg_fetch_array($query_M_Province_name))
		{
			$M_Province_old = $res_M_Province_name["proName"];
		}
	}
}


// ตรวจสอบว่ามีการเปลี่ยนแปลงบัญชีธนาคารหรือไม่
$query_banktemp = pg_query("select * from public.\"th_corp_acc_temp\" where \"corpID\" = '$corpID' and \"Approved\" is null and \"hidden\" = 'false' ");
$numrows_banktemp = pg_num_rows($query_banktemp);

$query_bankmain = pg_query("select * from public.\"th_corp_acc\" where \"corpID\" = '$corpID' ");
$numrows_bankmain = pg_num_rows($query_bankmain);

if($numrows_banktemp != $numrows_bankmain)
{
	$convertBank = "t";
}
else
{
	while($resultBCKH = pg_fetch_array($query_banktemp))
	{
		$acc_Number_chk = $resultBCKH["acc_Number"]; // เลขที่บัญชี
		$bankID_chk = $resultBCKH["bankID"]; // รหัสธนาคาร
		$acc_Name_chk = $resultBCKH["acc_Name"]; // ชื่อบัญชี
		$branch_chk = $resultBCKH["branch"]; // สาขา
		$acc_type_chk = $resultBCKH["acc_type"]; // ประเภทบัญชี
		
		$query_bankchk = pg_query("select * from public.\"th_corp_acc\" where \"corpID\" = '$corpID' and \"acc_Number\" = '$acc_Number_chk' and \"bankID\" = '$bankID_chk' and \"acc_Name\" = '$acc_Name_chk'
									and \"branch\" = '$branch_chk' and \"acc_type\" = '$acc_type_chk' ");
		$numrows_bankchk = pg_num_rows($query_bankchk);
		if($numrows_bankchk == 0)
		{
			$convertBank = "t";
		}
	}
}





// ตรวจสอบกรรมการ
$edit_th_corp_board = 0;
$sqlcheck1 = pg_query("SELECT * FROM th_corp_board where \"corpID\" = '$corpID'");
$sqlcheckrows = pg_num_rows($sqlcheck1); 

$sqlcheck2 = pg_query("SELECT * FROM \"th_corp_board_temp\" where \"corpID\" = '$corpID' AND \"Approved\" is null AND \"hidden\" = 'FALSE' ");
$rowcheck2 = pg_num_rows($sqlcheck2); 
if($sqlcheckrows != $rowcheck2)
{
	$edit_th_corp_board++;
}
else
{
	while($recheck1 = pg_fetch_array($sqlcheck2))
	{

		$chk1_corp_regis = $recheck1['corp_regis'];
		$chk1_CusID = $recheck1['CusID'];
		$chk1_path_signature = $recheck1['path_signature'];
		if($chk1_path_signature == ""){
			$chk1_path_signature = "is null";
		}else{
			$chk1_path_signature = "="."'".$chk1_path_signature."'";
		}
		

		$sqlcheck10 = "SELECT * FROM \"th_corp_board\" where \"corpID\" = '$corpID' AND \"corp_regis\" = '$chk1_corp_regis' AND \"CusID\" = '$chk1_CusID' AND \"path_signature\" $chk1_path_signature";
		$sqlcheckquery2 = pg_query($sqlcheck10);
		$rowcheck10 = pg_num_rows($sqlcheckquery2); 
		if($rowcheck10 == 0){
			$edit_th_corp_board++;
			
		}
	
	}
}
// ตรวจสอบผู้ติดต่อ
$edit_th_corp_communicant = 0;
$sqlcheck1 = pg_query("SELECT * FROM th_corp_communicant where \"corpID\" = '$corpID'");
$sqlcheckrows = pg_num_rows($sqlcheck1); 

$sqlcheck2 = "SELECT  * FROM \"th_corp_communicant_temp\" where \"corpID\" = '$corpID' AND \"Approved\" is null AND \"hidden\" = 'FALSE' ";
$sqlcheckquery2 = pg_query($sqlcheck2);
$rowcheck2 = pg_num_rows($sqlcheckquery2); 
if($sqlcheckrows != $rowcheck2)
{
	$edit_th_corp_communicant++;
}
else
{
	
	while($recheck1 = pg_fetch_array($sqlcheckquery2))
	{

		$chk1_corp_regis = $recheck1['corp_regis'];
		$chk1_CommunicantName = $recheck1['CommunicantName'];
		$chk1_position = $recheck1['position'];
		$chk1_subject = $recheck1['subject'];
		$chk1_phone = $recheck1['phone'];
		$chk1_mobile = $recheck1['mobile'];
		$chk1_email = $recheck1['email'];

		$sqlcheck2 = "SELECT  * FROM \"th_corp_communicant\" where \"corpID\" = '$corpID' AND \"corp_regis\" = '$chk1_corp_regis' 
		AND \"CommunicantName\" = '$chk1_CommunicantName' AND \"position\" = '$chk1_position' AND \"subject\" = '$chk1_subject'
		AND \"phone\" = '$chk1_phone' AND \"mobile\" = '$chk1_mobile' AND \"email\" = '$chk1_email'";
		$sqlcheckquery20 = pg_query($sqlcheck2);
		$rowcheck2 = pg_num_rows($sqlcheckquery20); 
		if($rowcheck2 == 0){
			$edit_th_corp_communicant++;			
		}
	}
}

// ตรวจสอบผู้รับมอบอำนาจ
$edit_th_corp_attorney = 0;
$sqlcheck1 = pg_query("SELECT * FROM th_corp_attorney where \"corpID\" = '$corpID'");
$rowcheck2 = pg_num_rows($sqlcheck1);

$sqlcheck2 = "SELECT * FROM th_corp_attorney_temp where \"corpID\" = '$corpID' AND \"Approved\" is null AND \"hidden\" = 'FALSE' ";
$sqlcheckquery2 = pg_query($sqlcheck2);
$rowcheck3 = pg_num_rows($sqlcheckquery2);
if($rowcheck3 != $rowcheck2)
{
	$edit_th_corp_attorney++;
}
else
{
	
	while($recheck1 = pg_fetch_array($sqlcheckquery2))
	{

		$chk1_corp_regis = $recheck1['corp_regis'];
		$chk1_CusID = $recheck1['CusID'];
		$chk1_path_receipt_authority = $recheck1['path_receipt_authority'];
		if($chk1_path_receipt_authority == ""){
			$chk1_path_receipt_authority = "is null";
		}else{
			$chk1_path_receipt_authority = "="."'".$chk1_path_receipt_authority."'";
		}
		

		$sqlcheck24 = "SELECT * FROM th_corp_attorney where \"corpID\" = '$corpID' and corp_regis = '$chk1_corp_regis' and \"CusID\" = '$chk1_CusID' and path_receipt_authority $chk1_path_receipt_authority";
		$sqlcheckquery24 = pg_query($sqlcheck24);
		$rowcheck24 = pg_num_rows($sqlcheckquery24); 

		if($rowcheck24 == 0){
			$edit_th_corp_attorney++;			
		}
	}
}
// ผู้ถือหุ้น
$edit_th_corp_share = 0;
$sqlcheck1 = pg_query("SELECT * FROM th_corp_share where \"corpID\" = '$corpID'");
$rowcheck2 = pg_num_rows($sqlcheck1); 

$sqlcheck70 = "SELECT * FROM th_corp_share_temp where \"corpID\" = '$corpID' AND \"Approved\" is null AND \"hidden\" = 'FALSE' ";
$sqlcheckquery70 = pg_query($sqlcheck70);
$rowcheck70 = pg_num_rows($sqlcheckquery70); 
if($rowcheck2 != $rowcheck70)
{
	//$edit_th_corp_attorney++;
	$edit_th_corp_share++;
}
else
{
	while($recheck1 = pg_fetch_array($sqlcheckquery70))
	{

		$chk1_corp_regis = $recheck1['corp_regis'];
		$chk1_CusID = $recheck1['CusID'];
		$chk1_share_amount = $recheck1['share_amount'];
		$chk1_share_value = $recheck1['share_value'];
		$chk1_path_signature = $recheck1['path_signature'];
		if($chk1_path_signature == ""){
			$chk1_path_signature = "is null";
		}else{
			$chk1_path_signature = "="."'".$chk1_path_signature."'";
		}
		
		$sqlcheck2 = "SELECT * FROM th_corp_share where \"corpID\" = '$corpID' and corp_regis = '$chk1_corp_regis' and \"CusID\" = '$chk1_CusID' and share_amount = '$chk1_share_amount'
		and \"share_value\" =  '$chk1_share_value' and \"path_signature\" $chk1_path_signature";
		$sqlcheckquery2 = pg_query($sqlcheck2);
		$rowcheck88 = pg_num_rows($sqlcheckquery2); 

		if($rowcheck88 == 0){
			$edit_th_corp_share++;			
		}
	}
}
// จบการตรวจสอบบัญชีธนาคาร
//------------------------- จบการเปรียบเทียบข้อมูล
?>
	
<br>
<center>
<form name="frm1" method="post" action="process_appvEditCorp.php?appv=2&corpID=<?php echo $corpID; ?>"> <!-- URL ใน form นี้ใช้ในกรณีไม่อนุมัติเท่านั้น -->
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td align="left">
			<font color="#FF0000">* ท่านสามารถดูข้อมูลก่อนการแก้ไขได้ โดยการนำเม้าส์ไปวางแช่ไว้ช่องที่เป็นสีแดง (ที่แสดงว่าถูกแก้ไข) เพื่อตรวจสอบการเปลี่ยนแปลง</font>
		</td>
	</tr>
	<tr>
        <td align="center">
			<fieldset><legend><B>ลูกค้านิติบุคคล</B></legend>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ชื่อนิติบุคคลภาษาไทย :</td><td><input type="text" name="corpName_THA" size="25" <?php if($corpName_THA != $corpName_THA_old){echo "style=\"background-color:#FFCCCC\" title = \"$corpName_THA_old\" ";} ?> value="<?php echo $corpName_THA; ?>" disabled></td>
						<td align="right">ชื่อนิติบุคคลภาษาอังกฤษ :</td><td><input type="text" name="corpName_ENG" size="25" <?php if($corpName_ENG != $corpName_ENG_old){echo "style=\"background-color:#FFCCCC\" title = \"$corpName_ENG_old\" ";} ?> value="<?php echo $corpName_ENG; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">ชื่อย่อ/เครื่องหมายทางการค้า :</td><td><input type="text" name="trade_name" size="25" <?php if($trade_name != $trade_name_old){echo "style=\"background-color:#FFCCCC\" title = \"$trade_name_old\" ";} ?> value="<?php echo $trade_name; ?>" disabled></td>
						<td align="right">ประเภทนิติบุคคล :</td><td><input type="text" size="25" <?php if($corpType != $corpType_old){echo "style=\"background-color:#FFCCCC\" title = \"$corpType_old\" ";} ?> value="<?php echo $corpType; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">เลขทะเบียนนิติบุคคล(13 หลัก) :</td><td><input type="text" name="corp_regis" size="25" value="<?php echo $corp_regis; ?>" <?php if($corp_regis != $corp_regis_old){echo "style=\"background-color:#FFCCCC\" title = \"$corp_regis_old\" ";} ?> disabled></td>
						<td align="right">เลขที่ประจำตัวผู้เสียภาษี(10 หลัก) :</td><td><input type="text" name="TaxNumber" size="25" value="<?php echo $TaxNumber; ?>" <?php if($TaxNumber != $TaxNumber_old){echo "style=\"background-color:#FFCCCC\" title = \"$TaxNumber_old\" ";} ?> disabled></td>
					</tr>
					<tr>
						<td align="right">สัญชาตินิติบุคคล :</td><td><input type="text" name="corpNationality" size="25" value="<?php echo $CountryName_THAI; ?>" <?php if($CountryName_THAI != $CountryName_THAI_old){echo "style=\"background-color:#FFCCCC\" title = \"$CountryName_THAI_old\" ";} ?> disabled></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td align="right">โทรศัพท์ :</td>
						<td>
							<input type="text" name="phone" size="25" value="<?php echo $phone; ?>" <?php if($phone != $phone_old){echo "style=\"background-color:#FFCCCC\" title = \"$phone_old\" ";} ?> disabled>
						</td>
						<td align="right">โทรสาร :</td><td><input type="text" name="Fax" size="25" value="<?php echo $Fax; ?>" <?php if($Fax != $Fax_old){echo "style=\"background-color:#FFCCCC\" title = \"$Fax_old\" ";} ?> disabled></td>
					</tr>
					<tr>
						<td align="right">E-mail :</td><td><input type="text" name="mail" size="25" value="<?php echo $mail; ?>" <?php if($mail != $mail_old){echo "style=\"background-color:#FFCCCC\" title = \"$mail_old\" ";} ?> disabled></td>
						<td align="right">Website :</td><td><input type="text" name="website" size="25" value="<?php echo $website; ?>" <?php if($website != $website_old){echo "style=\"background-color:#FFCCCC\" title = \"$website_old\" ";} ?> disabled></td>
					</tr>
					<tr>
						<td align="right">วันที่จดทะเบียนบริษัท :</td><td><input type="text" name="datepicker_regis" id="datepicker_regis" <?php if($date_of_corp != $date_of_corp_old){echo "style=\"background-color:#FFCCCC\" title = \"$date_of_corp_old\" ";} ?> value="<?php echo $date_of_corp; ?>" disabled style="text-align:center" size="15"></td>
						<td align="right">ทุนจดทะเบียนเริ่มแรก :</td><td><input type="text" name="initial_capital" size="25" <?php if($initial_capital != $initial_capital_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($initial_capital_old,2)."\" ";} ?> value="<?php if($initial_capital != ""){echo number_format($initial_capital,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td valign="top" align="right">ผู้มีอำนาจการทำรายการของบริษัท :</td><td colspan="3"><textarea name="authority" <?php if($authority != $authority_old){echo "style=\"background-color:#FFCCCC\" title = \"$authority_old\" ";} ?> cols="70" rows="2" readonly><?php echo $authority; ?></textarea></td>
					</tr>
					<tr>
						<td align="right">วันที่ของข้อมูลล่าสุด :</td><td><input type="text" name="datepicker_last" id="datepicker_last" <?php if($date_of_last_data != $date_of_last_data_old){echo "style=\"background-color:#FFCCCC\" title = \"$date_of_last_data_old\" ";} ?> value="<?php echo $date_of_last_data; ?>" disabled style="text-align:center" size="15"></td>
						<td align="right">ทุนจดทะเบียนปัจจุบัน :</td><td><input type="text" name="current_capital" <?php if($current_capital != $current_capital_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($current_capital_old,2)."\" ";} ?> size="25" value="<?php if($current_capital != ""){echo number_format($current_capital,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">สินทรัพย์เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="asset_avg" size="25" <?php if($asset_avg != $asset_avg_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($asset_avg_old,2)."\" ";} ?> value="<?php if($asset_avg != ""){echo number_format($asset_avg,2);} ?>" disabled></td>
						<td align="right">รายได้เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="revenue_avg" size="25" <?php if($revenue_avg != $revenue_avg_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($revenue_avg_old,2)."\" ";} ?> value="<?php if($revenue_avg != ""){echo number_format($revenue_avg,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">หนี้สินเฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="debt_avg" size="25" <?php if($debt_avg != $debt_avg_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($debt_avg_old,2)."\" ";} ?> value="<?php if($debt_avg != ""){echo number_format($debt_avg,2);} ?>" disabled></td>
						<td align="right">กำไรสุทธิ(3 ปีล่าสุด) :</td><td><input type="text" name="net_profit" size="25" <?php if($net_profit != $net_profit_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($net_profit_old,2)."\" ";} ?> value="<?php if($net_profit != ""){echo number_format($net_profit,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">แนวโน้มกำไร :</td><td><input type="text" name="trends_profit" size="25" <?php if($trends_profit != $trends_profit_old){echo "style=\"background-color:#FFCCCC\" title = \"$trends_profit_old\" ";} ?> value="<?php echo $trends_profit; ?>" disabled></td>
						<td align="right">ประเภทธุรกิจ :</td><td><input type="text" name="BusinessType" size="25" <?php if($BusinessType != $BusinessType_old){echo "style=\"background-color:#FFCCCC\" title = \"$BusinessType_old\" ";} ?> value="<?php echo $BusinessType; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">ประเภทอุตสาหกรรม :</td><td><input type="text" name="IndustypeID" size="25" <?php if($IndustypeName != $IndustypeName_old){echo "style=\"background-color:#FFCCCC\" title = \"$IndustypeName_old\" ";} ?> value="<?php echo $IndustypeName; ?>" disabled></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td valign="top" align="right">คำอธิบายกิจการ :</td><td colspan="3"><textarea name="explanation" <?php if($explanation != $explanation_old){echo "style=\"background-color:#FFCCCC\" title = \"$explanation_old\" ";} ?> cols="70" rows="2" readonly><?php echo $explanation; ?></textarea></td>
					</tr>
				</table>
			</center>
			</fieldset>
			
			
			<br>
			
			
			<br>
			
			<fieldset><legend><B>กรรมการ</B></legend>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
					<?php
					if($edit_th_corp_board > 0)
					{
					?>
						<tr><td align="center" colspan="10"><a onclick="javascript:popU('popup_detail_th_corp_board.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=200')" style="cursor:pointer;"><font color="#FF0000">มีการเปลี่ยนแปลง</font> <img id="test" src="images/detail.gif" /></a>
						</td></tr>
					<?php
					}
					?>	
						
				<?php 			
				
				$sql1 = pg_query("SELECT * FROM th_corp_board_temp where \"corpID\" = '$corpID'  AND \"Approved\" is null  AND hidden = FALSE");
				$row1 = pg_num_rows($sql1);		
					if($row1 == 0){ ?>
						<tr><td align="center" colspan="10"><center> ไม่มีรายชื่อกรรมการ </center></td></tr>
				<?php	}else{
						$num = 1;
						
						while($re1 = pg_fetch_array($sql1)){
							$CusID = $re1['CusID'];
							
							$sqlold1 = pg_query("SELECT * FROM th_corp_board where \"corpID\" = '$corpID' and \"CusID\" = '$CusID' ");
							$rowold1 = pg_num_rows($sqlold1);
													
							if($rowold1 == 0){
						?>	
								<tr align="right" width="25%" bgcolor="#FFCCCC">
							
					<?php	}else{ ?>
								<tr align="right" width="25%" >
					<?php	}  ?>
										
											<td>ชื่อกรรมการคนที่ <?php echo $num; ?> : </td>
											
										<?php $sql2 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
											  $re2 = pg_fetch_array($sql2);
											   $row2 = pg_num_rows($sql2);
											  
													if($row2==0){
													$fullname =  $CusID;
													}else{
														$fullname = $re2['full_name'];
													}
											  
								?>
					
											<td align="left" width="30%"><b><?php echo $fullname;?></b></td>
											<td align="right" width="15%">ตัวอย่างลายเซ็นต์ :</td>
											<td align="left" width="30%">
								<?php 	if($re1['path_signature'] == ""){
											
								
										}else{	?>			
											<a class="fancyboxa" href="upload/<?php echo $re1['path_signature']; ?>" data-fancybox-group="gallery" title=" ลายเซ็นต์กรรมการ "><u> แสดงลายเซ็นต์ </u></a></td>
								<?php 	} ?>		
										</tr>
						
					<?php 
							$num++;
							}
						
					}
					?>	
				</table>
			</center>
			</fieldset>
			
			<br>			
			
			
			<fieldset><legend><B>ผู้ติดต่อ</B></legend>
			<center>
				<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" >
				<?php
					if($edit_th_corp_communicant > 0)
					{
					?>
						<tr><td align="center" colspan="10"><a onclick="javascript:popU('popup_detail_th_corp_communicant.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=200')" style="cursor:pointer;"><font color="#FF0000">มีการเปลี่ยนแปลง</font> <img id="test" src="images/detail.gif" /></a>
						</td></tr>
					<?php
					}
					?>	
						<tr align="right" bgcolor="#79BCFF">
							<th align="center">ลำดับที่</th>
							<th align="center">ชื่อผู้ติดต่อ</th>
							<th align="center">ตำแหน่ง</th>
							<th align="center">ประสานงานเรื่อง</th>
							<th align="center">เบอร์โทรศัพท์</th>
							<th align="center">เบอร์มือถือ</th>
							<th align="center">email</th>
							
						</tr>
						
			<?php $sql3 = pg_query("SELECT * FROM th_corp_communicant_temp where \"corpID\" = '$corpID'  AND \"Approved\" is null  AND hidden = FALSE");
				$row3 = pg_num_rows($sql3);		
				if($row3 == 0){ ?>
						<tr><td align="center" colspan="7">ไม่มีรายชื่อผู้ติดต่อ</td></tr>
			<?php	}else{	  
						$num = 1;
						
						while($re3 = pg_fetch_array($sql3)){
				
			?>					
						
						<tr align="right" width="25%" bgcolor="#FFCCCC">
						<tr align="right" width="25%" >
							<td align="center" width="5%"><?php echo $num;?></td>												
							<td align="center" width="20%" bgcolor=<?php echo $color ?> ><?php echo $re3['CommunicantName'];?></td>				
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['position'];?></td>						
							<td align="center" width="20%" bgcolor=<?php echo $color ?> ><?php echo $re3['subject'];?></td>						
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['phone'];?></td>						
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['mobile'];?></td>						
							<td align="center" width="15%" bgcolor=<?php echo $color ?> ><?php echo $re3['email'];?></td>
						</tr>
						
					<?php 
						$num++;
						}
					}
					?>	
				</table>
				
			
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ผู้รับมอบอำนาจ</B></legend>
			<center>
			<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
			<?php
					if($edit_th_corp_attorney > 0)
					{
					?>
						<tr><td align="center" colspan="10"><a onclick="javascript:popU('popup_detail_th_corp_attorney.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=200')" style="cursor:pointer;"><font color="#FF0000">มีการเปลี่ยนแปลง</font> <img id="test" src="images/detail.gif" /></a>
						</td></tr>
					<?php
					}
					?>	
				
						
						
			<?php $sql4 = pg_query("SELECT * FROM th_corp_attorney_temp where \"corpID\" = '$corpID' AND \"Approved\" is null  AND hidden = FALSE");
				$row4 = pg_num_rows($sql4);		
					if($row4 == 0){ ?>
						<tr><td align="center" colspan="10"><center> ไม่มีรายชื่อผู้รับมอบอำนาจ </center></td></tr>
			<?php	}else{  
						$num = 1;
						
						while($re4 = pg_fetch_array($sql4)){
							$CusID = $re4['CusID'];
							
							$sqlold1 = pg_query("SELECT * FROM th_corp_attorney where \"corpID\" = '$corpID' and \"CusID\" = '$CusID' ");
							$rowold1 = pg_num_rows($sqlold1);	
							if($rowold1 == 0){
						?>	
								<tr align="right" width="25%" bgcolor="#FFCCCC" >
							
					<?php	}else{ ?>
								<tr align="right" width="25%" >
					<?php	}  ?>											
							<td>ผู้รับมอบอำนาจคนที่ <?php echo $num; ?> : </td>
							
						<?php $sql5 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re5 = pg_fetch_array($sql5);
							  $row5 = pg_num_rows($sql5);
							  
									if($row5==0){
									$fullname =  $CusID;
									}else{
										$fullname = $re5['full_name'];
									}
	  
							  
						?>	
							<td align="left" width="30%"><b><?php echo $fullname;?></b></td>
							<td align="right" width="15%">ใบรับมอบอำนาจ : </td>
							<td align="left" width="30%">
						<?php if($re4['path_receipt_authority'] == ""){
							
				
						}else{	?>	
							
							<a class="pdforpic" href="upload/<?php echo $re4['path_receipt_authority']; ?>" data-fancybox-group="gallery" title=" ใบรับมอบอำนาจ "><u> แสดงใบรับมอบอำนาจ </u></a></td>
							
						<?php } ?>	
						</tr>
						
					<?php 
						$num++;
						}
					}	
					?>	
				</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ผู้ถือหุ้น</B></legend>
			<center>
			<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" >
			<?php
					if($edit_th_corp_share > 0)
					{
					?>
						<tr><td align="center" colspan="10"><a onclick="javascript:popU('popup_detail_th_corp_share.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=200')" style="cursor:pointer;"><font color="#FF0000">มีการเปลี่ยนแปลง</font> <img id="test" src="images/detail.gif" /></a>
						</td></tr>
					<?php
					}
					?>	
			
				
						<tr align="right" bgcolor="#79BCFF">
							<th align="center">ลำดับที่</th>
							<th align="center">ชื่อผู้ถือหุ้น</th>
							<th align="center">จำนวนหุ้น</th>
							<th align="center">มูลค่าหุ้น</th>
							<th align="center">มุลค่าหุ้นที่ถือ</th>
							<th align="center">เปอร์เซ็นต์หุ้น</th>
							<th align="center">ตัอวย่างลายเซ็นต์</th>
							
						</tr>
						
			<?php $sql6 = pg_query("SELECT * FROM th_corp_share_temp where \"corpID\" = '$corpID' AND \"Approved\" is null  AND hidden = FALSE");
				  $row6 = pg_num_rows($sql6);		
				if($row6 == 0){ ?>
						<tr><td align="center" colspan="7">ไม่มีรายชื่อผู้ถือหุ้น</td></tr>
			<?php	}else{  
						$num = 1;
						
						while($re6 = pg_fetch_array($sql6)){
							$CusID = $re6['CusID'];
							$sql7 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re7 = pg_fetch_array($sql7);
							  $row7 = pg_num_rows($sql7);
						
						if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}

						if($row7==0){
							$fullname =  $CusID;
						}else{
							$fullname = $re7['full_name'];
						}
							
						$color = "";	
						?>						
							<td align="center" width="5%"><?php echo $num;?></td>	
							<td align="center" width="20%" color=<?php echo $color;?> ><?php echo $fullname;?></td>				
							<td align="center" width="10%" color=<?php echo $color;?> ><?php echo $re6['share_amount'];?></td>							
							<td align="center" width="20%" color=<?php echo $color;?> ><?php echo $re6['share_value'];?></td>
						<?php	
						
						if($re6['share_amount']=="" || $re6['share_value']==""){
						$sumshare = "";
						}else{
						$sumshare = $re6['share_amount']*$re6['share_value'];
						}
						
						?>
							
							<td align="center" width="10%"><?php echo number_format($sumshare,2);?></td>
							
							<?php if($current_capital == "" || $sumshare == ""){
									$percent = "";
									}else{
									$percent = ($sumshare/$current_capital)*100;
									$percent = number_format($percent,2)."%";
									}
							?>		
							<td align="center" width="10%"><?php echo $percent;?></td>
							<td align="center" width="15%">
						<?php if($re6['path_signature'] == ""){
							
				
						}else{	?>	
							
							<a class="fancyboxb" href="upload/<?php echo $re6['path_signature']; ?>" data-fancybox-group="gallery" title=" ตัวอย่างลายเซ็นต์ "><u>ตัวอย่างลายเซ็นต์</u></a></td>
						<?php } ?>	
						</tr>
						
					<?php 
						$num++;
						} 
					
					 }	
					?>	
				</table>
			
			</center>
			</fieldset>
			
			<br>

			<br>
			
			<fieldset><legend><B>ที่อยู่ตามหนังสือรับรอง</B></legend>
			<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="C_HomeNumber" size="25" <?php if($C_HomeNumber != $C_HomeNumber_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_HomeNumber_old\" ";} ?> value="<?php echo $C_HomeNumber; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="C_room" size="25" <?php if($C_room != $C_room_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_room_old\" ";} ?> value="<?php echo $C_room; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="C_LiveFloor" size="25" <?php if($C_LiveFloor != $C_LiveFloor_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_LiveFloor_old\" ";} ?> value="<?php echo $C_LiveFloor; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="C_Moo" size="25" <?php if($C_Moo != $C_Moo_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Moo_old\" ";} ?> value="<?php echo $C_Moo; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="C_Building" size="25" <?php if($C_Building != $C_Building_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Building_old\" ";} ?> value="<?php echo $C_Building; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="C_Village" size="25" <?php if($C_Village != $C_Village_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Village_old\" ";} ?> value="<?php echo $C_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="C_Lane" size="25" <?php if($C_Lane != $C_Lane_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Lane_old\" ";} ?> value="<?php echo $C_Lane; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="C_Road" size="25" <?php if($C_Road != $C_Road_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Road_old\" ";} ?> value="<?php echo $C_Road; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="C_District" size="25" <?php if($C_District != $C_District_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_District_old\" ";} ?> value="<?php echo $C_District; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="C_State" size="25" <?php if($C_State != $C_State_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_State_old\" ";} ?> value="<?php echo $C_State; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td><td><input type="text" name="C_Province" size="25" <?php if($C_Province != $C_Province_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Province_old\" ";} ?> value="<?php echo $C_Province; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="C_Postal_code" size="25" <?php if($C_Postal_code != $C_Postal_code_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Postal_code_old\" ";} ?> value="<?php echo $C_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ :</td><td><input type="text" name="C_Country" size="25" <?php if($C_Country != $C_Country_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Country_old\" ";} ?> value="<?php echo $C_Country; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="C_phone" size="25" <?php if($C_phone != $C_phone_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_phone_old\" ";} ?> value="<?php echo $C_phone; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="C_Fax" size="25" <?php if($C_Fax != $C_Fax_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Fax_old\" ";} ?> value="<?php echo $C_Fax; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="C_Live_it" size="23" <?php if($C_Live_it != $C_Live_it_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Live_it_old\" ";} ?> value="<?php echo $C_Live_it; ?>" disabled> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="C_Completion" size="25" <?php if($C_Completion != $C_Completion_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Completion_old\" ";} ?> value="<?php echo $C_Completion; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="C_Acquired" size="25" <?php if($C_Acquired != $C_Acquired_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_Acquired_old\" ";} ?> value="<?php echo $C_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="C_purchase_price" size="20" <?php if($C_purchase_price != $C_purchase_price_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($C_purchase_price_old,2)."\" ";} ?> value="<?php if($C_purchase_price != ""){echo number_format($C_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="50"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $C_addsStyle; ?>" <?php if($C_addsStyle != $C_addsStyle_old){echo "style=\"background-color:#FFCCCC\" title = \"$C_addsStyle_old\" ";} ?> disabled></td>
						</tr>
					</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ที่อยู่สำนักงานใหญ่</B></legend>
			<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="H_HomeNumber" size="25" <?php if($H_HomeNumber != $H_HomeNumber_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_HomeNumber_old\" ";} ?> value="<?php echo $H_HomeNumber; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="H_room" size="25" <?php if($H_room != $H_room_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_room_old\" ";} ?> value="<?php echo $H_room; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="H_LiveFloor" size="25" <?php if($H_LiveFloor != $H_LiveFloor_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_LiveFloor_old\" ";} ?> value="<?php echo $H_LiveFloor; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="H_Moo" size="25" <?php if($H_Moo != $H_Moo_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Moo_old\" ";} ?> value="<?php echo $H_Moo; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="H_Building" size="25" <?php if($H_Building != $H_Building_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Building_old\" ";} ?> value="<?php echo $H_Building; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="H_Village" size="25" <?php if($H_Village != $H_Village_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Village_old\" ";} ?> value="<?php echo $H_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="H_Lane" size="25" <?php if($H_Lane != $H_Lane_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Lane_old\" ";} ?> value="<?php echo $H_Lane; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="H_Road" size="25" <?php if($H_Road != $H_Road_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Road_old\" ";} ?> value="<?php echo $H_Road; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="H_District" size="25" <?php if($H_District != $H_District_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_District_old\" ";} ?> value="<?php echo $H_District; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="H_State" size="25" <?php if($H_State != $H_State_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_State_old\" ";} ?> value="<?php echo $H_State; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td><td><input type="text" name="H_Province" size="25" <?php if($H_Province != $H_Province_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Province_old\" ";} ?> value="<?php echo $H_Province; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="H_Postal_code" size="25" <?php if($H_Postal_code != $H_Postal_code_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Postal_code_old\" ";} ?> value="<?php echo $H_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="H_Country" size="25" <?php if($H_Country != $H_Country_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Country_old\" ";} ?> value="<?php echo $H_Country; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="H_phone" size="25" <?php if($H_phone != $H_phone_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_phone_old\" ";} ?> value="<?php echo $H_phone; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="H_Fax" size="25" <?php if($H_Fax != $H_Fax_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Fax_old\" ";} ?> value="<?php echo $H_Fax; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="H_Live_it" size="23" <?php if($H_Live_it != $H_Live_it_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Live_it_old\" ";} ?> value="<?php echo $H_Live_it; ?>" disabled> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="H_Completion" size="25" <?php if($H_Completion != $H_Completion_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Completion_old\" ";} ?> value="<?php echo $H_Completion; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="H_Acquired" size="25" <?php if($H_Acquired != $H_Acquired_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_Acquired_old\" ";} ?> value="<?php echo $H_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="H_purchase_price" size="20" <?php if($H_purchase_price != $H_purchase_price_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($C_purchase_price_old,2)."\" ";} ?> value="<?php if($H_purchase_price != ""){echo number_format($H_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="50"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" <?php if($H_addsStyle != $H_addsStyle_old){echo "style=\"background-color:#FFCCCC\" title = \"$H_addsStyle_old\" ";} ?> value="<?php echo $H_addsStyle; ?>" disabled></td>
						</tr>
					</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)</B></legend>
			<center>
					<table>
						<tr>
							<td align="right">บ้านเลขที่ :</td><td><input type="text" name="M_HomeNumber" size="25" <?php if($M_HomeNumber != $M_HomeNumber_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_HomeNumber_old\" ";} ?> value="<?php echo $M_HomeNumber; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ห้อง :</td><td><input type="text" name="M_room" size="25" <?php if($M_room != $M_room_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_room_old\" ";} ?> value="<?php echo $M_room; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ชั้น :</td><td><input type="text" name="M_LiveFloor" size="25" <?php if($M_LiveFloor != $M_LiveFloor_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_LiveFloor_old\" ";} ?> value="<?php echo $M_LiveFloor; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="M_Moo" size="25" <?php if($M_Moo != $M_Moo_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Moo_old\" ";} ?> value="<?php echo $M_Moo; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="M_Building" size="25" <?php if($M_Building != $M_Building_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Building_old\" ";} ?> value="<?php echo $M_Building; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="M_Village" size="25" <?php if($M_Village != $M_Village_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Village_old\" ";} ?> value="<?php echo $M_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="M_Lane" size="25" <?php if($M_Lane != $M_Lane_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Lane_old\" ";} ?> value="<?php echo $M_Lane; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ถนน :</td><td><input type="text" name="M_Road" size="25" <?php if($M_Road != $M_Road_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Road_old\" ";} ?> value="<?php echo $M_Road; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="M_District" size="25" <?php if($M_District != $M_District_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_District_old\" ";} ?> value="<?php echo $M_District; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="M_State" size="25" <?php if($M_State != $M_State_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_State_old\" ";} ?> value="<?php echo $M_State; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">จังหวัด :</td><td><input type="text" name="M_Province" size="25" <?php if($M_Province != $M_Province_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Province_old\" ";} ?> value="<?php echo $M_Province; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="M_Postal_code" size="25" <?php if($M_Postal_code != $M_Postal_code_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Postal_code_old\" ";} ?> value="<?php echo $M_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="M_Country" size="25" <?php if($M_Country != $M_Country_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Country_old\" ";} ?> value="<?php echo $M_Country; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="M_phone" size="25" <?php if($M_phone != $M_phone_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_phone_old\" ";} ?> value="<?php echo $M_phone; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="M_Fax" size="25" <?php if($M_Fax != $M_Fax_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Fax_old\" ";} ?> value="<?php echo $M_Fax; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="M_Live_it" size="23" <?php if($M_Live_it != $M_Live_it_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Live_it_old\" ";} ?> value="<?php echo $M_Live_it; ?>" disabled> ปี</td>
						</tr>
						<tr>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="M_Completion" size="25" <?php if($M_Completion != $M_Completion_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Completion_old\" ";} ?> value="<?php echo $M_Completion; ?>" disabled></td>
							<td width="50"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="M_Acquired" size="25" <?php if($M_Acquired != $M_Acquired_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_Acquired_old\" ";} ?> value="<?php echo $M_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="M_purchase_price" size="20" <?php if($M_purchase_price != $M_purchase_price_old){echo "style=\"background-color:#FFCCCC\" title = \" ".number_format($M_purchase_price_old,2)."\" ";} ?> value="<?php if($M_purchase_price != ""){echo number_format($M_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="50"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" <?php if($M_addsStyle != $M_addsStyle_old){echo "style=\"background-color:#FFCCCC\" title = \"$M_addsStyle_old\" ";} ?> value="<?php echo $M_addsStyle; ?>" disabled></td>
						</tr>
					</table>
			</center>
			</fieldset>
			
			<br>
			
			<fieldset><legend><B>บัญชีธนาคารของลูกค้านิติบุคคล</B></legend>
			<center>
					<?php
					if($convertBank == "t")
					{
					?>
						<a onclick="javascript:popU('popup_detail_now_bank.php?corpID=<?php echo $corpID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=200')" style="cursor:pointer;"><font color="#FF0000">มีการเปลี่ยนแปลงบัญชีธนาคาร!!</font> <img id="test" src="images/detail.gif" /></a>
					<?php
					}
					?>
			
					<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th width="30"></th>
							<th>เลขที่บัญชี</th>
							<th>ชื่อบัญชี</th>
							<th>ธนาคาร</th>
							<th>สาขา</th>
							<th>ประเภทบัญชี</th>
						</tr>
						<?php
						$query = pg_query("select * from public.\"th_corp_acc_temp\" where \"corpID\" = '$corpID' and \"Approved\" is null and \"hidden\" = 'false' ");
						$numrows = pg_num_rows($query);
						$i=0;
						while($result = pg_fetch_array($query))
						{
							$i++;
							$acc_Number = $result["acc_Number"]; // เลขที่บัญชี
							$bankID = $result["bankID"]; // รหัสธนาคาร
							$acc_Name = $result["acc_Name"]; // ชื่อบัญชี
							$branch = $result["branch"]; // สาขา
							$acc_type = $result["acc_type"]; // ประเภทบัญชี
							
							$query_bank = pg_query("select * from public.\"BankProfile\" where \"bankID\" = '$bankID' ");
							while($resultBank = pg_fetch_array($query_bank))
							{
								$bankName = $resultBank["bankName"]; // ชื่อธนาคาร
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
							
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"center\">$acc_Number</td>";
							echo "<td>$acc_Name</td>";
							echo "<td>$bankName</td>";
							echo "<td>$branch</td>";
							echo "<td align=\"center\">$acc_type</td>";
							echo "</tr>";
						}
						
						if($numrows==0){
							echo "<tr bgcolor=#FFFFFF><td colspan=6 align=center><b>ไม่พบบัญชีธนาคาร</b></td><tr>";
						}
						?>
					</table>
			</center>
			</fieldset>
			<br>
			
			<fieldset><legend><B>ข้อมูลอื่นๆ</B></legend>
			<center>
				<div id="panel">
				<?php
					include("corp_other.php");
				?>
				</div>
			</center>
			</fieldset>
			
		
			<br><br>
			
			<table>
				<tr>
					<td>สาเหตุที่ไม่อนุมัติ : </td>
					<td><textarea name="RemarkAll" cols="70" rows="2"></textarea></td>
				</tr>
			</table>
			
			<br>
			<?php
				if(($iduser!=$Uid) || $emplevel<=3){
			?>
			<input type="button" value="อนุมัติ" title="อนุมัติตนเองได้เมื่อระดับ <= 3" onclick="window.location='process_appvEditCorp.php?appv=1&corpID=<?php echo $corpID; ?>'"> &nbsp;&nbsp;&nbsp; <input type="submit" value="ไม่อนุมัติ" title="อนุมัติตนเองได้เมื่อระดับ <= 3" onclick="return NoAppv();"><?php } ?> &nbsp;&nbsp;&nbsp; <input type="button" value="ออก" title="อนุมัติตนเองได้เมื่อระดับ <= 3" onclick="javascript:window.close();">
			<br><br>
		</td>
	</tr>
</table>
</form>
</center>
</body>

<script type="text/javascript">
 addother();
function addother(){
 
	//--- กำหนดข้อมูลอื่นๆ
	
		var other1 = '<?php echo "$Proportion_in_country"; ?>';
		var other2 = '<?php echo "$Proportion_out_country"; ?>';
		var other3 = '<?php echo "$Proportion_Cash"; ?>';
		var other4 = '<?php echo "$Proportion_Credit"; ?>';
		var other5 = '<?php echo "$Amount_Employee"; ?>';
	
		document.frm1.Proportion_in_country.value = other1;
		document.frm1.Proportion_out_country.value = other2;
		document.frm1.Proportion_Cash.value = other3;
		document.frm1.Proportion_Credit.value = other4;
		document.frm1.Amount_Employee.value = other5;
		document.frm1.Proportion_in_country.readOnly = true;
		document.frm1.Proportion_out_country.readOnly = true;
		document.frm1.Proportion_Cash.readOnly = true;
		document.frm1.Proportion_Credit.readOnly = true;
		document.frm1.Amount_Employee.readOnly = true;
		
	//--- จบการกำหนดข้อมูลอื่นๆ
}
</script>
</html>
<?php
}else{
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ได้รับการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:opener.location.reload(true);self.close();\"></div>";
}
?>