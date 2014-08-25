<?php
session_start();
include("../../config/config.php");
		
$lawMaxInterest2 = $_POST["lawMaxInterest2"];
$lawMaxMonthTerm2 = $_POST["lawMaxMonthTerm2"];
$lawVATRate2 = $_POST["lawVATRate2"];
$lawSBTRate2 = $_POST["lawSBTRate2"];
$lawLTRate2 = $_POST["lawLTRate2"];
$comPenaltyC2 = $_POST["comPenaltyC2"];
$comMaxInterest2 = $_POST["comMaxInterest2"];
$comMaxMonthTerm2 = $_POST["comMaxMonthTerm2"];
$comPenaltyD2 = $_POST["comPenaltyD2"];
$comLawyerFee2 = $_POST["comLawyerFee2"];
$comCloseAccFee2 = $_POST["comCloseAccFee2"];
$comPenaltyF2 = $_POST["comPenaltyF2"];
$datepicker2 = $_POST["datepicker2"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เพิ่ม</title>
    <!-- <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" /> -->
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script language=javascript>

$(document).ready(function(){    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		//dateFormat: 'dd-mm-yy'
    });
});


function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.lawMaxMonthTerm.value=="" || document.form1.lawMaxMonthTerm.value=="" || document.form1.lawVATRate.value=="" || document.form1.lawSBTRate.value==""
	|| document.form1.lawLTRate.value=="" || document.form1.comPenaltyC.value=="" || document.form1.comMaxInterest.value=="" || document.form1.comMaxMonthTerm.value==""
	|| document.form1.comPenaltyD.value=="" || document.form1.comLawyerFee.value=="" || document.form1.comCloseAccFee.value=="" || document.form1.comPenaltyF.value==""
	|| document.form1.datepicker.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกข้อมูลให้ครบถ้วน";
}
/*else if (document.form1.lawMaxMonthTerm.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกประเภทสินเชื่ีอ";
}*/

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
<form name="form1" method="post" action="process_Add.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="header"><h2>เพิ่ม</h2></div>
		<div class="wrapper">
			<fieldset><legend><B>กรอกรายละเอียด</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right"><b>วันที่เริ่มบังคับใช้</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" id=\"datepicker\" name=\"datepicker\" value=\"$datepicker2\" readonly size=\"20\"></td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>อัตราดอกเบี้ยสูงสุดที่กฎหมายกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"lawMaxInterest\" value=\"$lawMaxInterest2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>จำนวนเดือนที่กู้ได้สูงสุดตามที่กฎหมายกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"lawMaxMonthTerm\" value=\"$lawMaxMonthTerm2\" size=\"20\"> เดือน</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>อัตราภาษีมูลค่าเพิ่มที่กฎหมายกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"lawVATRate\" value=\"$lawVATRate2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>อัตราภาษีธุรกิจเฉพาะที่กฎหมายกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"lawSBTRate\" value=\"$lawSBTRate2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>อัตราภาษีท้องถิ่นที่กฎหมายกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"lawLTRate\" value=\"$lawLTRate2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ค่าติดตามทวงถามประจำเดือน</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comPenaltyC\" value=\"$comPenaltyC2\" size=\"20\"> บาท</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>อัตราดอกเบี้ยสูงสุดที่บริษัทกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comMaxInterest\" value=\"$comMaxInterest2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ระยะเวลาการผ่อนสูงสุดที่บริษัทกำหนด</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comMaxMonthTerm\" value=\"$comMaxMonthTerm2\" size=\"20\"> เดือน</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ค่าติดตามกรณีค้างชำระ</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comPenaltyD\" value=\"$comPenaltyD2\" size=\"20\"> บาท</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ค่าเตือนโดยทนาย</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comLawyerFee\" value=\"$comLawyerFee2\" size=\"20\"> บาท</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ค่าปรับปิดบัญชีก่อนกำหนด (คิดจากยอดกู้เริ่มต้น)</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comCloseAccFee\" value=\"$comCloseAccFee2\" size=\"20\"> %</td>"; ?>
				</tr>
				<tr align="left">
					<td align="right"><b>ค่าติดตามทวงถามกรณีมีการฟ้องร้อง</b></td>
					<td width="10" align="center">:</td>
					<?php echo "<td class=\"text_gray\"><input type=\"text\" name=\"comPenaltyF\" value=\"$comPenaltyF2\" size=\"20\"> บาท</td>"; ?>
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