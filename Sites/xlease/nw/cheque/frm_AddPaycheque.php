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
<title>จ่ายเช็ค</title>
<script type="text/javascript">
$(document).ready(function(){
	$("#a1").hide();
	$("#a2").show();
	$("#a3").hide();
	$("#a4").show();
	
	$("#account").autocomplete({
		source: "s_accountchq.php",
		minLength:1
	});
	
	$("#IDNO").autocomplete({
		source: "s_idno.php",
		minLength:1
	});
	
	$("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
		if($("#account").val()=="" || $("#account").val()=="ไม่พบข้อมูล"){
			alert('กรุณาระบุเลขที่บัญชีธนาคาร');
			$('#account').select();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#typePay").val()==""){
			alert('กรุณาเลือกประเภทการสั่งจ่าย');
			$('#typePay').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($('#typePay option:selected').attr('value')!=3){
			if($("#IDNO").val()==""){
				alert('กรุณาระบุเลขที่สัญญา');
				$('#IDNO').focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}else{
				if($('#typePay option:selected').attr('value')==1){
					if($("#cusname").val()==""){
						alert('กรุณาระบุชื่อสั่งจ่าย');
						$('#cusname').focus();
						$("#submitButton").attr('disabled', false);
						return false;
					}
					
				}
			}
		}else if($('#typePay option:selected').attr('value')==3){
			if($("#cusPay").val()==""){
				alert('กรุณาระบุชื่อสั่งจ่าย');
				$('#cusPay').focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
		}
		if($("#typeChq").val()==""){
			alert('กรุณาระบุประเภทเช็ค');
			$('#typeChq').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#moneyPay").val()==""){
			alert('กรุณาระบุจำนวนเงินที่สั่งจ่าย');
			$('#moneyPay').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
	});
	
	$("#checkname").click(function(){
		$.post("process_cheque.php",{
			method : "searchname",
			IDNO : $("#IDNO").val()
		},
		function(data){
			if(data=="999999999"){
				alert("เลขที่สัญญาไม่ถูกต้อง กรุณาระบุใหม่อีกครั้ง");
				$("#IDNO").val('');
			}else{
				
				popU($("#IDNO").val());
			}
		});
	});
	

	$("#checkguide").click(function(){
		$.post("process_cheque.php",{
			method : "searchguide",
			IDNO : $("#IDNO").val()
		},
		function(data){
			if(data=="999999999"){
				alert("เลขที่สัญญานี้ไม่มีค่าแนะนำ กรุณาตรวจสอบอีกครั้ง");
				$("#IDNO").val('');
			}else{
				$("#cusguide").val(data);
			}
		});
	});
	
	$("#IDNO").change(function(){
		$("#cusname").val('');
		$("#cusguide").val('---อ้างอิงผู้แนะนำของสัญญาเช่าซื้อ---');
	});
	
	$("#typePay").change(function(){
		var src = $('#typePay option:selected').attr('value');
        if ( src == "1" ){
			$("#IDNO").show();
			$("#cusPay").attr('readonly','readonly');
			$("#cusPay").val('');
			$("#a1").show();
			$("#a2").hide();
			$("#a3").hide();
			$("#a4").show();
		}else if(src == "2"){
			$("#IDNO").show();
			$("#cusPay").attr('readonly','');
			$("#cusPay").val('');
			$("#a1").hide();
			$("#a2").hide();
			$("#a3").show();
			$("#a4").show();
		}else if(src == "3"){
			$("#IDNO").hide();
			$("#cusPay").attr('readonly','');
			$("#cusPay").val('');
			$("#a1").hide();
			$("#a2").show();
			$("#a3").hide();
			$("#a4").hide();
		}
	});
	$("#datePay").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
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
function checkvalue() {
    var val=$("#IDNO").val();
	alert(val);
}
function popU(id) {
	//alert(id);
	var U="selectcustomer.php?IDNO="+id;
	var N="";
	var T="toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300";
    newWindow = window.open(U, N, T);
}
function showdetail(){
    $('body').append('<div id="dialogdetail"></div>');
    $('#dialogdetail').load('selectcustomer.php?IDNO='+$("#IDNO").val());
    $('#dialogdetail').dialog({
        title: 'เลือกสั่งจ่าย ' +$("#IDNO").val(),
        resizable: false,
        modal: true,  
        width: 700,
        height: 500,
        close: function(ev, ui){
            $('#dialogdetail').remove();
        }
    });
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<form name="form1" id="form1" method="post" action="process_cheque.php"  enctype="multipart/form-data">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">- จ่ายเช็ค -</h1>
	</div>

	<div id="warppage"  style="width:700px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>x ปิดหน้านี้</u></span></div>
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="700" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right" width="220">เลขที่บัญชี<br>(ค้นเลขบัญชี, บริษัท, ธนาคาร, สาขา) : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="account" id="account"/><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">ประเภทการสั่งจ่าย : </td>
			<td bgcolor="#FFFFFF">
				<select name="typePay" id="typePay">
					<option value="">--เลือก--</option>
					<?php
					//ดึงข้อมูลจากตาราง cheque_typepay
					$qrytype=pg_query("SELECT \"typePay\", \"typeName\" FROM cheque_typepay order by \"typePay\"");
					while($restype=pg_fetch_array($qrytype)){
						list($typePay,$typeName)=$restype;
						echo "<option value=$typePay>$typeName</option>";
					}
					?>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA" id="a4">
			<td align="right">เลขที่สัญญา : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="IDNO" id="IDNO"/><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">สั่งจ่าย : </td>
			<td bgcolor="#FFFFFF">

				<div id="a1"><input type="text" name="cusname" id="cusname" size="50" value="" readonly="true" /><input type="button" name="checkname" id="checkname" value="เลือก"></div>
				<div id="a2"><input type="text" name="cusPay" id="cusPay" size="50"/><font color="red">*</font></div>
				<div id="a3"><input type="text" name="cusguide" id="cusguide" size="50" value="---อ้างอิงผู้แนะนำของสัญญาเช่าซื้อ---" readonly="true" style="text-align:center;"/><input type="button" name="checkguide" id="checkguide" value="ตรวจสอบ"></div>
			</td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">ประเภทเช็ค : </td>
			<td bgcolor="#FFFFFF">
				<select name="typeChq" id="typeChq">
					<option value="">--เลือก--</option>
					<option value="1">ปกติ</option>
					<option value="2" selected>A/C PAYEE ONLY</option>
					<option value="3"><?php echo "&Co.";?></option>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">จำนวนเงินที่สั่งจ่าย (บาท) : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="moneyPay" id="moneyPay" size="30" onkeypress="return check_num(event);"/><font color="red">*</font></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right">วันที่สั่งจ่าย : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="datePay" id="datePay" style="text-align:center;" value="<?php echo nowDate();?>" readonly="true"/></td>
		</tr>
		<tr height="30" bgcolor="#D6FEEA">
			<td align="right" valign="top">หมา่ยเหตุ : </td>
			<td bgcolor="#FFFFFF"><textarea name="note" cols="40" rows="5"></textarea></td>
		</tr>
		<tr>
			<td colspan="4" height="80" bgcolor="#FFFFFF" align="center">
			* ในการบันทึกข้อมูลแต่ละครั้ง จะ<font color="red">ไม่สามารถกลับมาแก้ไขได้</font> ดังนั้น<font color="red">กรุณาตรวจสอบข้อมูลให้ถูกต้อง</font>ก่อนการบันทึกทุกครั้ง<br><br>
			<input type="hidden" name="method" value="addpay">
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
