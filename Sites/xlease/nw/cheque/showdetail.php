<?php
include("../../config/config.php");		
$chqpayID=pg_escape_string($_GET["chqpayID"]);
$show=pg_escape_string($_GET["show"]);

$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\",
c.\"BAccount\",c.\"BName\",c.\"BBranch\",c.\"BCompany\",a.\"typeChq\",a.\"note\",d.\"fullname\",\"keyStamp\" from cheque_pay a
		left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
		left join \"BankInt\" c on a.\"BAccount\"=c.\"BAccount\"
		left join \"Vfuser\" d on a.\"keyUser\"=d.\"id_user\"
		where \"chqpayID\"='$chqpayID'");
$reschq=pg_fetch_array($qrychq);
list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay,$BAccount,$BName,$BBranch,$BCompany,$typeChq,$note,$keyuser,$keyStamp)=$reschq;
if($BName=="") $BName="-";
if($BBranch=="") $BBranch="-";
if($BCompany=="")$BCompany="-";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>แสดงรายละเอียดการสั่งจ่าย</title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h2>- รายละเอียดการสั่งจ่าย -</h2>
	</div>

	<div id="warppage"  style="width:570px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<table width="550" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right" width="150" valign="top">เลขที่บัญชี : </td>
			<td bgcolor="#FFFFFF"><?php echo $BAccount;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right" valign="top">ชื่อธนาคาร : </td>
			<td bgcolor="#FFFFFF"><?php echo $BName;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right"  valign="top">สาขา : </td>
			<td bgcolor="#FFFFFF"><?php echo $BBranch;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right"  valign="top">เจ้าของบัญชี : </td>
			<td bgcolor="#FFFFFF"><?php echo $BCompany;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">ประเภทการสั่งจ่าย : </td>
			<td bgcolor="#FFFFFF"><?php echo $typeName;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">เลขที่สัญญา : </td><td bgcolor="#FFFFFF"><?php echo $IDNO;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">สั่งจ่าย : </td>
			<td bgcolor="#FFFFFF"><?php echo $cusPay;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">ประเภทเช็ค : </td>
			<td bgcolor="#FFFFFF">
				<?php
					if($typeChq=="1"){
						echo "ปกติ";
					}else if($typeChq=="2"){
						echo "A/C PAYEE ONLY";
					}else{
						echo "&Co.";	
					}
				?>
			</td>
		</tr>
		<tr  bgcolor="#D6FEEA" height="25">
			<td align="right">จำนวนเงินที่สั่งจ่าย (บาท) : </td>
			<td bgcolor="#FFFFFF"><?php echo $moneyPay;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA"height="25">
			<td align="right">วันที่สั่งจ่าย : </td>
			<td bgcolor="#FFFFFF"><?php echo $datePay;?></td>
		</tr>
		<tr  bgcolor="#D6FEEA">
			<td align="right" valign="top">หมา่ยเหตุ : </td>
			<td bgcolor="#FFFFFF"><textarea name="note" cols="40" rows="5" readonly="true"><?php echo $note;?></textarea></td>
		</tr>
		<tr  bgcolor="#FFCCCC" height="25">
			<td align="right" valign="top">ผู้ทำรายการ : </td>
			<td bgcolor="#FFECEC"><?php echo $keyuser;?></td>
		</tr>
		<tr  bgcolor="#FFCCCC" height="25">
			<td align="right">วันเวลาที่ทำรายการ : </td>
			<td bgcolor="#FFECEC"><?php echo $keyStamp;?></td>
		</tr>
		<tr>
			<td colspan="4" height="80" bgcolor="#FFFFFF" align="center">
			<?php if($show=='1') {?>
			<form method="post" action="process_approve.php">
				<input type="hidden" name="chqpayID" id="chqpayID" value="<?php echo $chqpayID; ?>">
				<input name="appv" type="submit" value="อนุมัติ" />
				<input name="unappv" type="submit" value="ไม่อนุมัติ" />	
			</form>
			<?php }?>
			</td>
		</tr>		
		<tr>
			<td colspan="4" height="80" bgcolor="#FFFFFF" align="center">
			<input type="button" value="ปิดหน้านี้" onclick="window.close();">
			</td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
</body>
</html>
