<?php
session_start();
include("../../config/config.php");		 
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
<title>เช็ค-ซื้อเช็คเข้า</title>
<script type="text/javascript">
$(document).ready(function(){
	$("#account").autocomplete({
		source: "s_account.php",
		minLength:1
	});
	
	$("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
		if($("#account").val()=="" || $("#account").val()=="ไม่พบข้อมูล"){
			alert('กรุณาระบุเลขที่บัญชีธนาคาร');
			$('#account').select();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#chequebook").val()==""){
			alert('กรุณาระบุเช็คเล่มที่');
			$('#chequebook').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#start").val()==""){
			alert('เลขที่เริ่มต้นเช็ค');
			$('#start').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#end").val()==""){
			alert('เลขที่สิ้นสุดเช็ค');
			$('#end').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
	});
	$("#checknum").click(function(){
		if($("#account").val()==""){
			alert('กรุณาระบุเลขที่บัญชีธนาคาร');
			$('#account').focus();
			$("#checknum").attr('disabled', false);
			return false;
		}else if($("#chequebook").val()==""){
			alert('กรุณาระบุเล่มที่ต้องการตรวจสอบ');
			$('#chequebook').focus();
			$("#checknum").attr('disabled', false);
			return false;
		}
		$.post("process_cheque.php",{
			method : "checknum",
			chequebook : $("#chequebook").val(),
			account : $("#account").val()
		},
		function(data){
			if(data == "1"){
				alert("สามารถใช้เล่มนี้ได้ค่ะ");
				$("#checknum").attr('disabled', false);
			}else{
				//alert(data);
				alert("เช็คเล่มนี้ได้มีการบันทึกก่อนหน้านี้แล้วค่ะ");
				$('#chequebook').select();
				$("#checknum").attr('disabled', false);
			}
		});
		
	});
});

function check_num(evt) {
	//ให้ใส่จุดได้  ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<form name="form1"  method="post" action="process_cheque.php"  enctype="multipart/form-data">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ ซื้อเช็คเข้า +</h1>
	</div>

	<div id="warppage"  style="width:700px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>x ปิดหน้านี้</u></span></div>
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="700" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right" width="220">เลขที่บัญชี<br>(ค้นจากเลขที่บัญชี, ธนาคาร, สาขา) : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="account" id="account" size="40"/><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">เช็คเล่มที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="chequebook" id="chequebook" onkeypress="return check_num(event);"/><input type="button" value="ตรวจสอบเลขซ้ำ" id="checknum"><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">เลขที่เริ่มต้นเช็ค : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="start" id="start"/><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">เลขที่สิ้นสุดเช็ค : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="end" id="end"/><font color="red">*</font></td>
		</tr>

		<tr>
			<td colspan="4" height="80" bgcolor="#FFFFFF" align="center">
			* ในการบันทึกข้อมูลแต่ละครั้ง จะ<font color="red">ไม่สามารถกลับมาแก้ไขได้</font> ดังนั้น<font color="red">กรุณาตรวจสอบข้อมูลให้ถูกต้อง</font>ก่อนการบันทึกทุกครั้ง<br><br>
			<input type="hidden" name="method" value="add">
			<input type="submit" value="บันทึกข้อมูล" id="submitButton">
			<input type="reset" value="ยกเิลิก">
			</td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
</body>
</html>
