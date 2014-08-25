<?php
session_start();
include("../../config/config.php");		
$securID=$_GET["securID"]; 
$numid=$_GET["numid"];
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
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h2>-คืนหลักทรัพย์ค้ำประกัน-</h2>
	</div>

	<form name="form1" method="post" action="process_securities.php">
		<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;" align="center">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">วันที่คืน : </td>
			<td bgcolor="#FFFFFF"><input type="text" id="returnDate" name="returnDate" value="<?php echo nowDate(); ?>" size="15" readonly="true" style="text-align:center;"></td>
		</tr>
		<tr bgcolor="#E8E8E8">
			<td align="right" width="200">ผู้รับคืน(ค้นจากชื่อ,นามสกุล): </td>
			<td bgcolor="#FFFFFF">
				<input type="text" name="CusID" id="CusID" size="40"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="40" bgcolor="#FFFFFF" align="center">
				<input type="hidden" name="cmd" value="return">
				<input type="hidden" name="securID" value="<?php echo $securID;?>">
				<input type="hidden" name="numid" value="<?php echo $numid;?>">
				<input type="submit" value="บันทึกข้อมูล" onclick="return checkdata();">
				<input type="button" value="ปิดหน้าต่าง" onclick="window.close();"></td>
		</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function(){	
	$("#CusID").autocomplete({
			source: "s_cusid.php",
			minLength:1
	});
	$("#returnDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
	});
});
function checkdata(){
	if(document.form1.returnDate.value==""){
		alert("กรุณาระบุวันที่รับคืน");
		document.form1.returnDate.focus();
		return false;
	}else if(document.form1.CusID.value==""){
		alert("กรุณาระบุชื่อผู้รับคืน");
		document.form1.CusID.focus();
		return false;
	}else{
		return true;
	}
}
</script>
</body>
</html>
