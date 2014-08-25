<?php
//session_start();
include("../../config/config.php");
$account_debt=$_GET["account_debt"];

$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice_action\".\"doerID\" , \"thcap_invoice_action\".\"doerStamp\" , \"thcap_invoice_action\".\"appvXID\" , \"thcap_invoice_action\".\"appvXStamp\"
				, \"thcap_invoice_action\".\"appvYID\" , \"thcap_invoice_action\".\"appvYStamp\" , \"thcap_typePay\".\"tpDesc\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
						and \"thcap_invoice\".\"invoiceID\" = '$account_debt'");
$result=pg_fetch_array($query);

$invoiceDate = $result["invoiceDate"];
$doerStamp = $result["doerStamp"];
$appvXStamp = $result["appvXStamp"];
$appvYStamp = $result["appvYStamp"];

//หาชื่อผู้ทำรายการ
$doerID = $result["doerID"];
$query_doer = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID'");
$result_doer = pg_fetch_array($query_doer);
$doerName = $result_doer["fullname"];

$appvXID = $result["appvXID"];
$query_appvXID = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvXID'");
$result_appvXID = pg_fetch_array($query_appvXID);
$appvXID = $result_appvXID["fullname"];

$appvYID = $result["appvYID"];
$query_appvYID = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvYID'");
$result_appvYID = pg_fetch_array($query_appvYID);
$appvYID = $result_appvYID["fullname"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คำอธิบายรายละเอียด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <!-- <link type="text/css" rel="stylesheet" href="act.css"></link> -->
</head>
<body>


<table width="550" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="wrapper">
			<fieldset><legend><B>รายละเอียด</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right" valign="top"><b>เลขที่ใบแจ้งหนี้</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["invoiceID"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>เลขที่สัญญา</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["contractID"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>วันเวลาที่ออกใบแจ้งหนี้</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $invoiceDate;?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>รายการค่าใช้จ่าย</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["tpDesc"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>จำนวนเงินที่ต้องชำระทั้งสิ้น</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["invoiceAmt"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>อัตราภาษีมูลค่าเพิ่ม เฉพาะรายการนี้</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["invoiceVATRate"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>จำนวน VAT เฉพาะรายการนี้</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $result["invoiceAmtVAT"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ผู้ทำรายการ</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $doerName;?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>วันเวลาที่ทำรายการ</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php echo $doerStamp; ?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ผู้อนุมัติรายการคนที่หนึ่ง</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php if($appvXStamp < $appvYStamp || $appvYStamp == ""){echo $appvXID;}else{echo $appvYID;} ?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>วันเวลาที่อนุมัติรายการครั้งที่หนึ่ง</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php if($appvXStamp < $appvYStamp || $appvYStamp == ""){echo $appvXStamp;}else{echo $appvYStamp;} ?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ผู้อนุมัติรายการคนที่สอง</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php if($appvXStamp != "" && $appvYStamp != ""){ if($appvXStamp < $appvYStamp){echo $appvYID;}else{echo $appvXID;} } ?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>วันเวลาที่อนุมัติรายการครั้งที่สอง</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td align="left"><?php if($appvXStamp != "" && $appvYStamp != ""){ if($appvXStamp < $appvYStamp){echo $appvYStamp;}else{echo $appvXStamp;} } ?></td>
				</tr>
				<tr align="center">
				  <td colspan=4 height="50"><input name="button" type="button" onclick="javascript:window.close();" value=" Close " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>

      
</body>
</html>
