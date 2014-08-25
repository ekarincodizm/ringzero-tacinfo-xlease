<?php
include("../../config/config.php");

$cancelTaxID=$_GET['cancelTaxID'];

	//หาเหตุผลโดยการนำเลขที่ใบกำกับภาษีไปค้นในตาราง  thcap_temp_receipt_cancel
	$qryresult=pg_query("SELECT \"taxinvoiceID\",result FROM thcap_temp_taxinvoice_cancel where \"cancelTaxID\"='$cancelTaxID' ");
	$resresult=pg_fetch_array($qryresult);
	list($taxinvoiceID,$result)=$resresult;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title>รายละเอียดการยกเลิก</title>
</head>
<body>

<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#EAF9FF" align="center">
<tr>
    <td align="center" colspan="3"><h2>- เหตุผลยกเลิกใบกำกับภาษี -</h2></td>
</tr>
<tr><td align="right"><span onclick="window.close();" style="cursor:pointer;"><u>X ปิดหน้านี้</u></span></td></tr>
<tr>
    <td height="25"><b>ใบกำกับภาษีที่ขอยกเลิก: <font color="red"><?php echo $taxinvoiceID; ?></font></b></td>
</tr>

<tr bgcolor="#F5F5F5">
	<td colspan="5"><b>::เหตุผลที่ยกเลิก::</b><br><textarea name="resultcancel" id="resultcancel" cols="50" rows="5" readonly="true"><?php echo $result;?></textarea></td>
</tr>
</table>
</form>
</body>
</html>