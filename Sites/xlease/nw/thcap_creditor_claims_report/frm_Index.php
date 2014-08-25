<?php 
include("../../config/config.php");

$nowDate = nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานเจ้าหนี้สิทธิเรียกร้อง</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) รายงานเจ้าหนี้สิทธิเรียกร้อง</h1></div>
	<div style="margin-top:10px; width:80%;margin-left:auto;margin-right:auto;">
	<fieldset><legend>เงือนไขการค้นหา</legend>
		<table align="center" cellspacing="10px">			
			<tr>
				<td align="right"><b>วันที่ : </b></td>
				<td align="left"><input type="text" id="datepicker" name="datepicker" value="<?php echo $nowDate; ?>" size="15" style="text-align:center"/>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><b>ประเภทสัญญา : </b></td>
				<td align="left">
					<input type="radio" name="contractType" id="typeFA" value="FA" /> FA
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="contractType" id="typeFI" value="FI" /> FI
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
				<input type="hidden" name="val" value="1"/>
				<input type="button" id="Search"  value="ค้นหา" />
				</td>				
			</tr>
		</table>
	</fieldset>
	</div>
	<!--แสดงผลการค้นหา-->	
	<div id="list_creditor_claims" style="margin-top:10px; width:80%;margin-left:auto;margin-right:auto;"></div>
</bodY>
</html>
<script>
$(document).ready(function(){
	$("#datepicker").datepicker({
		showOn: 'button',
		buttonImage: '../thcap/images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});

$("#Search").click(function(){
	var chk = 0;
	var contractType;
	var errorMessage = "Error Message! \n";
	
	if($("#datepicker").val() == ""){
		errorMessage += "-> กรุณาระบุ  วันที่ \n";
		chk++;
	}
	
	if(document.getElementById("typeFA").checked == false && document.getElementById("typeFI").checked == false){
		errorMessage += "-> กรุณาเลือก ประเภทสัญญา \n";
		chk++;
	}else if(document.getElementById("typeFA").checked == true){
		contractType = document.getElementById("typeFA").value;
	}else if(document.getElementById("typeFI").checked == true){
		contractType = document.getElementById("typeFI").value;
	}
	
	if(chk == 0){ //ถ้าระบุข้อมูลครบ
		$("#list_creditor_claims").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#list_creditor_claims").load("list_creditor_claims.php",{
			s_date : $("#datepicker").val(),
			s_contractType : contractType
		});
	}else{
		alert(errorMessage);
		return false;
	}
});
</script>