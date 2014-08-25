<?php
include("../../config/config.php");

$BAccount2 = $_POST["BAccount2"];
$BName2 = $_POST["BName2"];
$BBranch2 = $_POST["BBranch2"];
$BCompany2 = $_POST["BCompany2"];
$BType2 = $_POST["BType2"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เพิ่มบัญชีธนาคารบริษัท</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script>
$(document).ready(function(){   
	$("#BCompany").autocomplete({
        source: "s_cusid.php",
        minLength:1
    });
});
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.BAccount.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกเลขที่บัญชี";
}
if (document.form1.BName2.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกชื่อธนาคาร";
}
if (document.form1.BBranch2.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกสาขา";
}
if (document.form1.BCompany2.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกชื่อเจ้าของบัญชี";
}
if (document.form1.BType2.value=="") {
    theMessage = theMessage + "\n -->  กรุณาเลือก Ratio";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}

</script>
</head>
<body>
<form name="form1" method="post" action="process_AddAccount.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="header"><h2>เพิ่มบัญชีธนาคารบริษัท</h2></div>
		<div class="wrapper">
			<fieldset><legend><B>กรอกรายละเอียด</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right"><b>รหัสช่องทาง</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"BChannel\" value=\"\" size=\"30\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>รายละเอียดช่องทาง</b></td>
					<td width="10" align="center" valign="top">:</td>
					<?php echo "<td class=\"text_gray\"><textarea cols=\"50\" rows=\"3\" name=\"desc\"></textarea></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>เลขที่บัญชี</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"BAccount\" value=\"$BAccount2\" size=\"30\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ชื่อธนาคาร</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"BName\" value=\"$BName2\" size=\"60\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>สาขา</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"BBranch\" value=\"$BBranch2\" size=\"60\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ชื่อเจ้าของบัญชี</b></td>
					<td width="10" align="center"valign="top">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"BCompany\" id=\"BCompany\" value=\"$BCompany2\" size=\"60\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ประเภทบัญชี</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<select name="BType">
							<?php echo "<option value=\"1\" ";?> <?php if($BType2=="1"){echo "selected";} ?> <?php echo ">กระแสรายวัน</option>"; ?>
							<?php echo "<option value=\"2\" ";?> <?php if($BType2=="2"){echo "selected";} ?> <?php echo ">ออมทรัพย์</option>"; ?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>ช่องทางการรับชำระ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<select name="isChannel">
							<?php echo "<option value=\"0\" ";?> <?php if($isChannel=="0"){echo "selected";} ?> <?php echo ">ไม่เป็นช่องทางการรับชำระ</option>"; ?>
							<?php echo "<option value=\"1\" ";?> <?php if($isChannel=="1"){echo "selected";} ?> <?php echo ">เป็นช่องทางการรับชำระ</option>"; ?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>ใช่บัญชีเงินโอนหรือไ่ม่</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<select name="isTranPay">
							<?php echo "<option value=\"1\" ";?> <?php if($isTranPay=="0"){echo "selected";} ?> <?php echo ">ไม่ใช่</option>"; ?>
							<?php echo "<option value=\"0\" ";?> <?php if($isTranPay=="1"){echo "selected";} ?> <?php echo ">ใช่</option>"; ?>
						</select>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>สถานะ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BActive" value="1" disabled="disabled" size="20"></td>
				</tr>
				<tr align="center">
				  <td colspan=3 height="50"><input type="hidden" name="method" value="add"><input name="submit" type="submit" value="บันทึก" onclick="return validate()"><input name="button" type="button" onclick="window.location='frm_Index.php'" value=" ย้อนกลับ " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>         
</form>
</body>
</html>