<?php
include("../../config/config.php");

$requestID = pg_escape_string($_GET["requestID"]);

// หารายละเอียดรถ
$q = "
		SELECT
			d.\"astypeName\",
			e.\"brand_name\",
			f.\"model_name\",
			CASE WHEN c.\"astypeID\" = '10' THEN g.\"motorcycle_no\" ELSE h.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN c.\"astypeID\" = '10' THEN c.\"productCode\" ELSE h.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN c.\"astypeID\" = '10' THEN g.\"regiser_no\" ELSE h.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN c.\"astypeID\" = '10' THEN i.\"car_color\" ELSE j.\"car_color\" END AS \"car_color\",
			k.\"InsFullName\",
			a.\"Code\",
			a.\"StartDate\",
			a.\"EndDate\",
			a.\"Capacity\",
			a.\"Discount\",
			a.\"Premium\",
			a.\"CollectCus\",
			b.\"fullname\" AS \"doerName\",
			a.\"doerStamp\",
			a.\"editTime\"
		FROM
			insure.\"thcap_InsureForce_request\" a
		LEFT JOIN
			\"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
		LEFT JOIN
			\"thcap_asset_biz_detail\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_astype\" d ON c.\"astypeID\" = d.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" e ON c.\"brand\" = e.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" f ON c.\"model\" = f.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" g ON c.\"assetDetailID\" = g.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" h ON c.\"assetDetailID\" = h.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10_color\" i ON g.\"car_color\" = i.\"auto_id\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car_color\" j ON h.\"car_color\" = j.\"auto_id\"
		LEFT JOIN
			\"insure\".\"InsureInfo\" k ON a.\"Company\" = k.\"InsCompany\"
		WHERE
			a.\"requestForceID\" = '$requestID'
	";
$qr = pg_query($q);
$astypeName = pg_fetch_result($qr,0); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,1); // ยี่ห้อ
$model_name = pg_fetch_result($qr,2); // รุ่น
$chassis = pg_fetch_result($qr,3); // เลขตัวถัง
$engine = pg_fetch_result($qr,4); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,5); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,6); // สีรถ
$InsFullName = pg_fetch_result($qr,7); // บริษัทประกัน
$Code = pg_fetch_result($qr,8); // ประเภท
$StartDate = pg_fetch_result($qr,9); // วันที่เริ่ม
$EndDate = pg_fetch_result($qr,10); // วันที่หมดอายุ
$Capacity = pg_fetch_result($qr,11); // ขนาดเครื่องยนต์ / น้ำหนักรวม (กก.) / จำนวนที่นั่ง
$Discount = pg_fetch_result($qr,12); // ส่วนลด
$Premium = pg_fetch_result($qr,13); // ค่าเบิ้ยประกัน
$CollectCus = pg_fetch_result($qr,14); // เบี้ยที่เก็บกับลูกค้า
$doerName = pg_fetch_result($qr,15); // ชื่อผู้ทำรายการ
$doerStamp = pg_fetch_result($qr,16); // วันเวลาที่ทำรายการ
$editTime = pg_fetch_result($qr,17); // เบี้ยที่เก็บกับลูกค้า

if($Code == "1.400" || $Code == "1.401" || $Code == "1.402" || $Code == "1.403" || $Code == "1.420" || $Code == "1.421")
{
	$CodeName = "น้ำหนักรวม (กก.)";
}
elseif($Code == "1.200" || $Code == "1.201" || $Code == "1.202" || $Code == "1.203")
{
	$CodeName = "จำนวนที่นั่ง";
}
else
{
	$CodeName = "ขนาดเครื่องยนต์";
}

if($editTime == "0")
{
	$editText = "ขอเพิ่มข้อมูล";
}
else
{
	$editText = "ขอแก้ไขครั้ง $editTime";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติ ประกันภัย ภาคบังคับ (พรบ.)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		
		function validate()
		{
			var theMessage = "";
			var noErrors = theMessage;

			if (document.insureforce.appvNote.value == "") {
				theMessage = theMessage + "\n - กรุณาระบุ หมายเหตุการอนุมัติ";
			}

			// If no errors, submit the form
			if (theMessage == noErrors){
				return true;
			}else{
				// If errors were found, show alert message
				alert(theMessage);
				return false;
			}
		}
	</script>
 
</head>
<body>
	<center>
		<div style="text-align:center;"><h1>อนุมัติ ประกันภัย ภาคบังคับ (พรบ.)</h1></div>
		
		<form id="insureforce" name="insureforce" method="post" action="process_force_approve.php" onsubmit="return validate(this)">
			<table>
				<tr align="left">
					<td><b>ประเภทรถ</b></td>
					<td class="text_gray"><?php echo $astypeName; ?></td>
				</tr>
				<tr align="left">
					<td><b>ยี่ห้อ</b></td>
					<td class="text_gray"><?php echo $brand_name; ?></td>
				</tr>
				<tr align="left">
					<td><b>รุ่น</b></td>
					<td class="text_gray"><?php echo $model_name; ?></td>
				</tr>
				<tr align="left">
					<td><b>เลขถัง</b></td>
					<td class="text_gray"><?php echo $chassis; ?></td>
				</tr>
				<tr align="left">
					<td><b>เลขเครื่อง</b></td>
					<td class="text_gray"><?php echo $engine; ?></td>
				</tr>
				<tr align="left">
					<td><b>ทะเบียนรถ</b></td>
					<td class="text_gray"><?php echo $regiser_no; ?></td>
				</tr>
				<tr align="left">
					<td><b>สีรถ</b></td>
					<td class="text_gray"><?php echo $car_color; ?></td>
				</tr>
				<tr align="left">
					<td><b>บริษัทประกัน</b></td>
					<td><?php echo $InsFullName; ?></td>
				</tr>
				<tr align="left">
					<td><b>ประเภท</b></td>
					<td><?php echo $Code; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันที่เริ่ม</b></td>
					<td><?php echo $StartDate; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันที่หมดอายุ</b></td>
					<td><?php echo $EndDate; ?></td>
				</tr>
				<tr align="left">
					<td><b><?php echo $CodeName; ?></b></td>
					<td><?php echo $Capacity; ?></td>
				</tr>
				<tr align="left">
					<td><b>ส่วนลด</b></td>
					<td><input type="text" readonly="true" size="15" value="<?php echo number_format($Discount,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
				</tr>
				<tr align="left">
					<td><b>ค่าเบิ้ยประกัน</b></td>
					<td class="text_gray"><input type="text" readonly="true" size="15" value="<?php echo number_format($Premium,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท.</td>
				</tr>
				<tr align="left">
					<td><b>เบี้ยที่เก็บกับลูกค้า</b></td>
					<td class="text_gray"><input type="text" readonly="true" size="15" value="<?php echo number_format($CollectCus,2); ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท.</td>
				</tr>
				<tr align="left">
					<td><b>ผู้ทำรายการ</b></td>
					<td><?php echo $doerName; ?></td>
				</tr>
				<tr align="left">
					<td><b>วันเวลาที่ทำรายการ</b></td>
					<td><?php echo $doerStamp; ?></td>
				</tr>
				<tr align="left">
					<td><b>ประเภทการทำรายการ</b></td>
					<td><?php echo $editText; ?></td>
				</tr>
				<tr align="left">
					<td valign="top"><b>หมายเหตุการอนุมัติ <font color="red">*</font></b></td>
					<td><textarea id="appvNote" name="appvNote"></textarea></td>
				</tr>
			</table>
			
			<table>
				<tr align="center">
					<td align="center">
						<br/>
						<input type="hidden" name="requestID" value="<?php echo $requestID; ?>" />
						<input type="submit" name="appv" value="อนุมัติ" style="cursor:pointer;" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="unAppv" value="ไม่อนุมัติ" style="cursor:pointer;" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="ปิด" style="cursor:pointer;" onClick="window.close();" />
					</td>
				</tr>
			</table>
		</form>
	</center>
</body>
</html>