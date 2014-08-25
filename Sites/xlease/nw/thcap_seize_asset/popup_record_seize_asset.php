<?php
include("../../config/config.php");

$seizeID = pg_escape_string($_GET['seizeID']);

// หาข้อมูลการยึด
$qry_data = pg_query("select \"createID\", \"assetDetailID\", \"seizeStatus\" from \"thcap_seize_asset\" where \"seizeID\" = '$seizeID' ");
$createID = pg_fetch_result($qry_data,0); // รหัสสร้างงานยึด
$assetDetailID = pg_fetch_result($qry_data,1); // รหัสรายละเอียดสินค้า
$seizeStatus = pg_fetch_result($qry_data,2); // สถานะการยึด

// หาเลขที่สัญญา
$qry_contract = pg_query("select \"contractID\" from \"thcap_create_seize_asset\" where \"createID\" = '$createID' ");
$contractID = pg_fetch_result($qry_contract,0);

// หาข้อมูลสินทรัพย์
$qry_asset_detail = pg_query("select * from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
$res_asset_detail = pg_fetch_array($qry_asset_detail);
$productCode = $res_asset_detail["productCode"]; // รหัสสินค้า
$secondaryID = $res_asset_detail["secondaryID"]; // รหัสสินค้ารอง
$brand = $res_asset_detail["brand"]; // ยี่ห้อ
$model = $res_asset_detail["model"]; // รุ่น

// หาชื่อยี่ห้อ
$qry_brand = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brandID\" = '$brand' ");
$brand_name = pg_fetch_result($qry_brand,0);

// หาชื่อรุ่น
$qry_model = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"modelID\" = '$model' ");
$model_name = pg_fetch_result($qry_model,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) บันทึกรับทรัพย์สินรับคืน-ยึดคืน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
	$(document).ready(function(){
		$("#dateRecord").datepicker({
			showOn: 'button',
			buttonImage: '../thcap/images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
	
	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}
	
	function chkData()
	{
		if(document.getElementById("dateRecord").value == '')
		{
			alert('กรุณาระบุ วันที่ยึดสินทรัพย์เข้าบริษัท ');
			return false;
		}
		else if(document.getElementById("Remark").value == '')
		{
			alert('กรุณาระบุ หมายเหตุ');
			return false;
		}
		else
		{
			return true;
		}
	}
</script>

<style type="text/css">
#warppage
{
width:800px;
margin-left:auto;
margin-right:auto;

min-height: 5em;
background: rgb(255, 255, 255);
padding: 5px;
border: rgb(128, 128, 128) solid 0.5px;
border-radius: .625em;
-moz-border-radius: .625em;
-webkit-border-radius: .625em;
}
/*
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
*/
</style>
</head>
<body>

<center>
	<h2>(THCAP) บันทึกรับทรัพย์สินรับคืน-ยึดคืน</h2>
	<form method="post" name='myfrm' action="process_record_seize_asset.php">
	
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) บันทึกรับทรัพย์สินรับคืน-ยึดคืน</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<th>เลขที่สัญญา</th>
				<th>ID สินทรัพย์</th>
				<th>รหัสสินค้าหลัก</th>
				<th>รหัสสินค้ารอง</th>
				<th>ยี่ห้อ</th>
				<th>รุ่น</th>
			</tr>
			<tr class="even" align=center>
				<td align="center"><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="center"><?php echo $assetDetailID; ?></td>
				<td align="center"><?php echo $productCode; ?></td>
				<td align="center"><?php echo $secondaryID; ?></td>
				<td align="center"><?php echo $brand_name; ?></td>
				<td align="center"><?php echo $model_name; ?></td>
			</tr>
		</table>
		
		<br><br>
		
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
			<tr>
				<td width="50%" align="right"><b>วันที่ยึดสินทรัพย์เข้าบริษัท : </b></td>
				<td width="50%" align="left"><input type="text" id="dateRecord" name="dateRecord" size="15" ></td>
			</tr>
			<tr>
				<td width="50%" align="right"><b>หมายเหตุ : </b></td>
				<td width="50%" align="left"><textarea name="Remark" id="Remark" cols="45" rows="4"></textarea></td>
			</tr>
			<tr>
				<td width="50%" align="right"><input name="appv" type="submit" value="บันทึก" onClick="return chkData();" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;</td>
				<td width="50%" align="left">&nbsp;&nbsp;&nbsp;<input name="unAppv" type="button" value="ออก" onClick="window.close();" style="cursor:pointer;"></td>
			</tr>
		</table>
		
		<input type="hidden" name="seizeID" value="<?php echo $seizeID; ?>">
	</form>
</center>

</body>
</html>