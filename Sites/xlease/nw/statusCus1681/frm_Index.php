<?php
//session_start();
//include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสถานะบัญชีลูกค้า 1681</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function checkdata() {
	if(document.form1.shownum.value =="") {
		alert("กรุณากรอกจำนวนรายการที่ต้องการให้แสดง");
		document.form1.shownum.focus();
		return false;
	}else{
		return true;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.form1.shownum.focus();
		return false;
	}
	return true;
}
</script>    
</head>
<body>
<form method="post" name="form1" action="frm_Report.php">
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr><td align="center"><h1>รายงานสถานะบัญชีลูกค้า 1681</h1></td></tr>
	<tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div align="right"></div> 
				<fieldset><legend><B>เงื่อนไขการรายงาน</B></legend>
					<table width="100%" border="0" align="center">
						<tr>
							<td colspan="2" align="center" height="35"><b>จำนวนรายการที่ต้องการให้แสดง : <input type="text" name="shownum" value="23810" style="text-align:center" onkeypress="return check_number(event);"> รายการ (สูงสุด 23810)</b></td>
						</tr>
						<tr>
							<td width="35%">&nbsp;</td>
							<td><input type="checkbox" name="condition1" value="1">แสดงเฉพาะที่ยังไม่ได้เช็คศูนย์</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="checkbox" name="condition2" value="1">แสดงเฉพาะสัญญาที่ยังไม่ยกเลิก</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="checkbox" name="condition3" value="1">แสดงเฉพาะสัญญาที่มียอดค้างชำระ</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="checkbox" name="condition4" value="1">แสดงเฉพาะสัญญาที่เข้าข่ายออก NT</td>
						</tr>
						<tr>
							<td colspan="2" height="80" align="center"><input type="submit" value="ตกลง" onclick="return checkdata();"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
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