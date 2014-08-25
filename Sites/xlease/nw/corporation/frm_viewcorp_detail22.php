<?php
include("../../config/config.php");

$corp_regis = $_GET["corp_regis"];
$view = $_GET["view"]; // ถ้าเป็น 2 แสดงว่าเปิดจากเมนู เพิ่มลูกค้านิติบุคคล
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
    
    <script language="javascript" type="text/javascript" src="js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.coolfieldset.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.coolfieldset.css" />

</head>
<body>

<?php
//----- ข้อมูลนิติบุคคล
if($view == 2) // ถ้าดูข้อมูลที่ไม่อนุมัติ
{
	// หาจำนวนที่แก้ไขครั้งล่าสุด
	$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" is null and \"hidden\" = 'false' ");
	while($res_maxedit = pg_fetch_array($query_maxedit))
	{
		$maxedit = $res_maxedit["maxedit"];
	}

	$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" is null ");
}
elseif($view == 3) // ถ้าดูข้อมูลที่รออนุมัติ
{
	// หาจำนวนที่แก้ไขครั้งล่าสุด
	$query_maxedit = pg_query("select max(\"corpEdit\") as \"maxedit\" from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"Approved\" = 'false' and \"hidden\" = 'false' ");
	while($res_maxedit = pg_fetch_array($query_maxedit))
	{
		$maxedit = $res_maxedit["maxedit"];
	}
	
	$query_corp = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'false' ");
}
else // ถ้าเป็นการหาจากข้่อมูลปกติ (ลูกค้านิติบุคคลที่อนุมัติแล้วจากตาราง th_corp)
{
	$query_corp = pg_query("select * from public.\"th_corp\" where \"corp_regis\" = '$corp_regis' ");
}

$row1=pg_num_rows($query_corp);

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
}

//-------- หาที่อยู่ลูกค้านิติบุคคล - ที่อยู่ตามหนังสือรับรอง
if($view == 2)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
}
elseif($view == 3)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
}
else
{
	$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '1' ");
}

$row2=pg_num_rows($query_adds);

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
if($view == 2)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
}
elseif($view == 3)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
}
else // ถ้าเป็นการดูแบบลูกค้าที่อนุมัติแล้ว
{
	$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '2' ");
}

$row3=pg_num_rows($query_adds);

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
if($view == 2)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' and \"addsEdit\" = '$maxedit' and \"Approved\" is null ");
}
elseif($view == 3)
{
	$query_adds = pg_query("select * from public.\"th_corp_adds_temp\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' and \"addsEdit\" = '$maxedit' and \"Approved\" = 'false' ");
}
else // ถ้าเป็นการดูแบบลูกค้าที่อนุมัคิแล้ว
{
	$query_adds = pg_query("select * from public.\"th_corp_adds\" where \"corp_regis\" = '$corp_regis' and \"addsType\" = '3' ");
}

$row4=pg_num_rows($query_adds);

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

?>
	
<br>
<center>
<form name="frm1" method="post" action="frm_EditCorpAll.php?corp_regis=<?php echo $corp_regis; ?>">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
			<fieldset class="coolfieldset" id="fs1"><legend><B>ลูกค้านิติบุคคล</B></legend>
            <div>
			<center>
				<table width="auto" border="0" cellSpacing="1" cellPadding="3" bgcolor="#FFFFFF">
					<tr>
						<td align="right">ชื่อนิติบุคคลภาษาไทย :</td><td><input type="text" name="corpName_THA" size="25" value="<?php echo $corpName_THA; ?>" disabled></td>
						<td align="right">ชื่อนิติบุคคลภาษาอังกฤษ :</td><td><input type="text" name="corpName_ENG" size="25" value="<?php echo $corpName_ENG; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">ชื่อย่อ/เครื่องหมายทางการค้า :</td><td><input type="text" name="trade_name" size="25" value="<?php echo $trade_name; ?>" disabled></td>
						<td align="right">ประเภทนิติบุคคล :</td><td><input type="text" size="25" value="<?php echo $corpType; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">เลขทะเบียนนิติบุคคล(13 หลัก) :</td><td><input type="text" name="corp_regis" size="25" value="<?php echo $corp_regis; ?>" disabled></td>
						<td align="right">เลขที่ประจำตัวผู้เสียภาษี(10 หลัก) :</td><td><input type="text" name="TaxNumber" size="25" value="<?php echo $TaxNumber; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">โทรศัพท์ :</td>
						<td>
							<input type="text" name="phone" size="25" value="<?php echo $phone; ?>" disabled>
						</td>
						<td align="right">โทรสาร :</td><td><input type="text" name="Fax" size="25" value="<?php echo $Fax; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">E-mail :</td><td><input type="text" name="mail" size="25" value="<?php echo $mail; ?>" disabled></td>
						<td align="right">Website :</td><td><input type="text" name="website" size="25" value="<?php echo $website; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">วันที่จดทะเบียนบริษัท :</td><td><input type="text" name="datepicker_regis" id="datepicker_regis" value="<?php echo $date_of_corp; ?>" disabled style="text-align:center" size="15"></td>
						<td align="right">ทุนจดทะเบียนเริ่มแรก :</td><td><input type="text" name="initial_capital" size="25" value="<?php if($initial_capital != ""){echo number_format($initial_capital,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td valign="top" align="right">ผู้มีอำนาจการทำรายการของบริษัท :</td><td colspan="3"><textarea name="authority" cols="70" rows="2" readonly><?php echo $authority; ?></textarea></td>
					</tr>
					<tr>
						<td align="right">วันที่ของข้อมูลล่าสุด :</td><td><input type="text" name="datepicker_last" id="datepicker_last" value="<?php echo $date_of_last_data; ?>" disabled style="text-align:center" size="15"></td>
						<td align="right">ทุนจดทะเบียนปัจจุบัน :</td><td><input type="text" name="current_capital" size="25" value="<?php if($current_capital != ""){echo number_format($current_capital,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">สินทรัพย์เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="asset_avg" size="25" value="<?php if($asset_avg != ""){echo number_format($asset_avg,2);} ?>" disabled></td>
						<td align="right">รายได้เฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="revenue_avg" size="25" value="<?php if($revenue_avg != ""){echo number_format($revenue_avg,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">หนี้สินเฉลี่ย(3 ปีล่าสุด) :</td><td><input type="text" name="debt_avg" size="25" value="<?php if($debt_avg != ""){echo number_format($debt_avg,2);} ?>" disabled></td>
						<td align="right">กำไรสุทธิ(3 ปีล่าสุด) :</td><td><input type="text" name="net_profit" size="25" value="<?php if($net_profit != ""){echo number_format($net_profit,2);} ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">แนวโน้มกำไร :</td><td><input type="text" name="trends_profit" size="25" value="<?php echo $trends_profit; ?>" disabled></td>
						<td align="right">ประเภทธุรกิจ :</td><td><input type="text" name="BusinessType" size="25" value="<?php echo $BusinessType; ?>" disabled></td>
					</tr>
					<tr>
						<td align="right">ประเภทอุตสาหกรรม :</td><td><input type="text" name="IndustypeID" size="25" value="<?php echo $IndustypeName; ?>" disabled></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td valign="top" align="right">คำอธิบายกิจการ :</td><td colspan="3"><textarea name="explanation" cols="70" rows="2" readonly><?php echo $explanation; ?></textarea></td>
					</tr>
				</table>
			</center>
            </div>
			</fieldset>
            
			<br>
			
			<fieldset class="coolfieldset" id="fs2"><legend><B>ที่อยู่ตามหนังสือรับรอง</B></legend>
            <div>
			<center>
					<table>
						<tr>
							<td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="C_HomeNumber" size="25" value="<?php echo $C_HomeNumber; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ห้อง :</td><td><input type="text" name="C_room" size="25" value="<?php echo $C_room; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ชั้น :</td><td><input type="text" name="C_LiveFloor" size="25" value="<?php echo $C_LiveFloor; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="C_Moo" size="25" value="<?php echo $C_Moo; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="C_Building" size="25" value="<?php echo $C_Building; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="C_Village" size="25" value="<?php echo $C_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="C_Lane" size="25" value="<?php echo $C_Lane; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ถนน :</td><td><input type="text" name="C_Road" size="25" value="<?php echo $C_Road; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="C_District" size="25" value="<?php echo $C_District; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="C_State" size="25" value="<?php echo $C_State; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">จังหวัด :</td><td><input type="text" name="C_Province" size="25" value="<?php echo $C_Province; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="C_Postal_code" size="25" value="<?php echo $C_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ :</td><td><input type="text" name="C_Country" size="25" value="<?php echo $C_Country; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="C_phone" size="25" value="<?php echo $C_phone; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="C_Fax" size="25" value="<?php echo $C_Fax; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="C_Live_it" size="23" value="<?php echo $C_Live_it; ?>" disabled> ปี</td>
							<td width="30"></td>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="C_Completion" size="25" value="<?php echo $C_Completion; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="C_Acquired" size="25" value="<?php echo $C_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="C_purchase_price" size="20" value="<?php if($C_purchase_price != ""){echo number_format($C_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="30"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $C_addsStyle; ?>" disabled></td>
							<td width="30"></td>
							<td></td>
						</tr>
					</table>
			</center>
            </div>
			</fieldset>
			
			<br>
			
			<fieldset class="coolfieldset" id="fs3"><legend><B>ที่อยู่สำนักงานใหญ่</B></legend>
            <div>
			<center>
					<table>
						<tr>
							<td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="H_HomeNumber" size="25" value="<?php echo $H_HomeNumber; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ห้อง :</td><td><input type="text" name="H_room" size="25" value="<?php echo $H_room; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ชั้น :</td><td><input type="text" name="H_LiveFloor" size="25" value="<?php echo $H_LiveFloor; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="H_Moo" size="25" value="<?php echo $H_Moo; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="H_Building" size="25" value="<?php echo $H_Building; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="H_Village" size="25" value="<?php echo $H_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="H_Lane" size="25" value="<?php echo $H_Lane; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ถนน :</td><td><input type="text" name="H_Road" size="25" value="<?php echo $H_Road; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="H_District" size="25" value="<?php echo $H_District; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="H_State" size="25" value="<?php echo $H_State; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">จังหวัด :</td><td><input type="text" name="H_Province" size="25" value="<?php echo $H_Province; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="H_Postal_code" size="25" value="<?php echo $H_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="H_Country" size="25" value="<?php echo $H_Country; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="H_phone" size="25" value="<?php echo $H_phone; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="H_Fax" size="25" value="<?php echo $H_Fax; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="H_Live_it" size="23" value="<?php echo $H_Live_it; ?>" disabled> ปี</td>
							<td width="30"></td>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="H_Completion" size="25" value="<?php echo $H_Completion; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="H_Acquired" size="25" value="<?php echo $H_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="H_purchase_price" size="20" value="<?php if($H_purchase_price != ""){echo number_format($H_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="30"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $H_addsStyle; ?>" disabled></td>
							<td width="30"></td>
							<td></td>
						</tr>
					</table>
			</center>
            </div>
			</fieldset>
			
			<br>
			
			<fieldset class="coolfieldset" id="fs4"><legend><B>ที่อยู่ที่ติดต่อ(ที่อยู่ส่งเอกสาร)</B></legend>
            <div>
			<center>
					<table>
						<tr>
							<td align="right" width="100">บ้านเลขที่ :</td><td><input type="text" name="M_HomeNumber" size="25" value="<?php echo $M_HomeNumber; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ห้อง :</td><td><input type="text" name="M_room" size="25" value="<?php echo $M_room; ?>" disabled></td>
							<td width="30"></td>
							<td align="right" width="100">ชั้น :</td><td><input type="text" name="M_LiveFloor" size="25" value="<?php echo $M_LiveFloor; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">หมู่ที่ :</td><td><input type="text" name="M_Moo" size="25" value="<?php echo $M_Moo; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">อาคาร/สถานที่ :</td><td><input type="text" name="M_Building" size="25" value="<?php echo $M_Building; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">หมู่บ้าน :</td><td><input type="text" name="M_Village" size="25" value="<?php echo $M_Village; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ซอย :</td><td><input type="text" name="M_Lane" size="25" value="<?php echo $M_Lane; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ถนน :</td><td><input type="text" name="M_Road" size="25" value="<?php echo $M_Road; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">แขวง/ตำบล :</td><td><input type="text" name="M_District" size="25" value="<?php echo $M_District; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">เขต/อำเภอ :</td><td><input type="text" name="M_State" size="25" value="<?php echo $M_State; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">จังหวัด :</td><td><input type="text" name="M_Province" size="25" value="<?php echo $M_Province; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">รหัสไปรษณีย์ :</td><td><input type="text" name="M_Postal_code" size="25" value="<?php echo $M_Postal_code; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">ประเทศ:</td><td><input type="text" name="M_Country" size="25" value="<?php echo $M_Country; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">โทรศัพท์ :</td><td><input type="text" name="M_phone" size="25" value="<?php echo $M_phone; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">เบอร์ FAX :</td><td><input type="text" name="M_Fax" size="25" value="<?php echo $M_Fax; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">อาศัยมาแล้ว :</td><td><input type="text" name="M_Live_it" size="23" value="<?php echo $M_Live_it; ?>" disabled> ปี</td>
							<td width="30"></td>
							<td align="right">ปีที่สร้างเสร็จ :</td><td><input type="text" name="M_Completion" size="25" value="<?php echo $M_Completion; ?>" disabled></td>
							<td width="30"></td>
							<td align="right">ได้มาโดย :</td><td><input type="text" name="M_Acquired" size="25" value="<?php echo $M_Acquired; ?>" disabled></td>
						</tr>
						<tr>
							<td align="right">มูลค่า/ราคาที่ซื้อ :</td><td><input type="text" name="M_purchase_price" size="20" value="<?php if($M_purchase_price != ""){echo number_format($M_purchase_price,2);} ?>" disabled> บาท</td>
							<td width="30"></td>
							<td align="right">ลักษณะของที่อยู่ :</td><td><input type="text" size="25" value="<?php echo $M_addsStyle; ?>" disabled></td>
							<td width="30"></td>
							<td></td>
						</tr>
					</table>
			</center>
            </div>
			</fieldset>
			
			<br>
			
			<fieldset class="coolfieldset" id="fs5"><legend><B>บัญชีธนาคารของลูกค้านิติบุคคล</B></legend>
            <div>
			<center>
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
						if($view == 2)
						{
							$query = pg_query("select * from public.\"th_corp_acc_temp\" where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" is null ");
						}
						elseif($view == 3)
						{ // กรณีที่ไม่อนุมัติ
							$query = pg_query("select * from public.\"th_corp_acc_temp\" where \"corp_regis\" = '$corp_regis' and \"accEdit\" = '$maxedit' and \"Approved\" = 'false' ");
						}
						else
						{
							$query = pg_query("select * from public.\"th_corp_acc\" where \"corp_regis\" = '$corp_regis' ");
						}
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
            </div>
			</fieldset>
			
			<?php
			if($view == 3)
			{
				$query_noAppv = pg_query("select * from public.\"th_corp_temp\" where \"corp_regis\" = '$corp_regis' and \"corpEdit\" = '$maxedit' and \"Approved\" = 'false' ");
				while($res_noAppv = pg_fetch_array($query_noAppv))
				{
					$RemarkAll = $res_noAppv["RemarkAll"];
				}
			?>
				<br>
				<table>
					<tr>
						<td align="right">สาเหตุที่ไม่อนุมัติ :</td><td><textarea name="explanation" cols="70" rows="3" readonly><?php echo $RemarkAll; ?></textarea><td>
					</tr>
				</table>
			<?php
			}
			?>
			
			<br><br>
			<?php
			if($view == 3)
			{
			?>
				<input type="submit" value="แก้ไข"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
			}
			?>
			<input type="button" value="CLOSE" onclick="javascript:window.close();">
			<br><br>
		</td>
	</tr>
</table>
</form>
</center>
<?php
echo "<script type=\"text/javascript\">";
if($row1==0)
{
	echo "$('#fs1').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs1').coolfieldset({speed:\"fast\"});";
}
if($row2==0)
{
	echo "$('#fs2').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs2').coolfieldset({speed:\"fast\"});";
}
if($row3==0)
{
	echo "$('#fs3').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs3').coolfieldset({speed:\"fast\"});";
}
if($row4==0)
{
	echo "$('#fs4').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs4').coolfieldset({speed:\"fast\"});";
}
if($numrows==0)
{
	echo "$('#fs5').coolfieldset({collapsed:true});";
}
else
{
	echo "$('#fs5').coolfieldset({speed:\"fast\"});";
}
echo "</script>";
?>
</body>
</html>