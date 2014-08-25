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
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">
<form>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เพิ่มการเชื่อมโยงหลักทรัพย์ค้ำประกัน+</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.location='frm_IndexLink.php'"><u><--ย้อนกลับ</u></span></div>
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">รหัสเชื่อมโยง: </td>
			<td bgcolor="#FFFFFF"><input type="text" name="number_running" id="number_running" onkeypress="return check_num(event);"><input type="button" value="ตรวจสอบเลขซ้ำ" id="checknum"></td>
		</tr>
		</table>
		<div id='TextBoxesGroup1'>
		<div id="TextBoxDiv1">
			<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
				<tr bgcolor="#E8E8E8">
					<td align="right" width="210">หลักทรัพย์ (ค้นจากเลขที่โฉนด) : </td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="text" name="securID1" id="securID1" size="30"/><input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton">
					</td>
				</tr>
			</table>
		</div>
		</div>
		<div id='TextGroup1'>
		<div id="TextDiv1">
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">เลขที่สัญญา (ค้นจากเลขที่สัญญา) : </td>
			<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="IDNO1" id="IDNO1" size="30"> 
				วันที่ค้ำประกัน: <input type="text" id="guaranteeDate1" name="guaranteeDate1" value="" size="15" readonly="true" style="text-align:center;">&nbsp;&nbsp;
				<input type="button" value="+ เพิ่ม" id="addButton2"><input type="button" value="- ลบ" id="removeButton2">
			</td>
		</tr>
		</table>
		</div>
		</div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5"></textarea></td>
		</tr>
		<tr>
			<td colspan="4" height="40" bgcolor="#FFFFFF" align="center"><input type="button" value="บันทึกข้อมูล" id="submitButton"><input type="reset" value="ยกเลิก"></td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
<script type="text/javascript">
var counter = 1;

$(document).ready(function(){
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr bgcolor="#E8E8E8">'
	+ '		<td align="right" width="206">'+ counter +'</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF">'
	+ '			<input type="text" name="securID'+ counter +'" id="securID'+ counter +'" size="30"/>'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup1");

		
		$("#securID"+counter).autocomplete({
			source: "s_secur2.php",
			minLength:1
		});

    });

	$("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
    });
	
	var counter2=1;
	$('#addButton2').click(function(){
    counter2++;
    console.log(counter2);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextDiv' + counter2);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr height="30" bgcolor="#E8E8E8">'
	+ '		<td align="right" width="206">'+ counter2 +'</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="IDNO'+ counter2 +'" id="IDNO'+ counter2 +'" size="30">'
	+ '		<b>วันที่ค้ำประกัน:</b> <input type="text" id="guaranteeDate'+ counter2 +'" name="guaranteeDate'+ counter2 +'" value="" size="15" readonly="true" style="text-align:center;"></td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextGroup1");

		$("#guaranteeDate"+counter2).datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		});
		
		$("#IDNO"+counter2).autocomplete({
			source: "s_idno.php",
			minLength:1
		});
	
    });

	$("#removeButton2").click(function(){
        if(counter2==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextDiv" + counter2).remove();
        counter2--;
        console.log(counter2);
        updateSummary();
    });
	
	$("#securID1").autocomplete({
			source: "s_secur2.php",
			minLength:1
	});
	
	$("#IDNO1").autocomplete({
			source: "s_idno.php",
			minLength:1
	});
		
	$("#guaranteeDate1").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
	});
    
	$("#checknum").click(function(){
		if($("#number_running").val()==""){
			alert('กรุณาระบุลำดับที่ก่อนตรวจสอบ');
			$('#number_running').focus();
			$("#checknum").attr('disabled', false);
			return false;
		}
		$.post("process_linksecur.php",{
			cmd : "checknum",
			number_running : $("#number_running").val()
		},
		function(data){
			if(data == "1"){
				alert("สามารถใช้ลำดับที่นี้ได้ค่ะ");
				$("#checknum").attr('disabled', false);
			}else if(data=="2"){
				alert("เลขลำดับนี้กำลังรออนุมัติ");
				$('#number_running').select();
				$("#checknum").attr('disabled', false);
			}else{
				alert("เลขลำดับนี้มีอยู่ในระบบแล้วค่ะ");
				$('#number_running').select();
				$("#checknum").attr('disabled', false);
			}
		});
		
	});
	$("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
		var payment = [];
		var payment2 = [];
		if($("#number_running").val()==""){
			alert('กรุณาระบุลำดับที่');
			$('#number_running').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}
		
		for( i=1; i<=counter; i++ ){
			if ( $("#securID"+i).val() == ""){
				alert('กรุณาระบุหลักทรัพย์ '+i);
				$('#securID'+ i).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			payment[i] = {securID : $("#securID"+ i).val()};
		}
		
		for( j=1; j<=counter2; j++ ){
			if ( $("#IDNO"+j).val() == ""){
				alert('กรุณาระบุเลขที่สัญญา '+j);
				$('#IDNO'+ j).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			payment2[j] = {IDNO : $("#IDNO"+ j).val(),guaranteeDate : $("#guaranteeDate"+ j).val()};
		}
        
		$.post("process_linksecur.php",{
			cmd : "add",
			number_running : $("#number_running").val(), 
			note :$("#note").val(),
			payment : JSON.stringify(payment),
			payment2 : JSON.stringify(payment2)
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location.href = "frm_IndexLinkAdd.php";
				$("#submitButton").attr('disabled', false);
			}else if(data == "2"){
				//alert(data);
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#submitButton").attr('disabled', false);
			}else if(data=="3"){
				//alert(data);
				alert("ลำดับที่นี้กำลังรออนุมัติหรือมีอยู่ในระบบแล้วค่ะ");
				$('#number_running').select();
				$("#submitButton").attr('disabled', false);
			}else if(data=="4"){
				//alert(data);
				alert("หลักทรัพย์บางตัวผิดพลาด !! เพื่อป้องกันการผิดพลาดกรุณาเลือกหลักทรัพย์ที่ระบบกำหนดให้ค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="5"){
				//alert(data);
				alert("หลักทรัพย์บางตัวซ้ำกัน กรุณาเลือกใหม่อีกครั้งค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="6"){
				alert("เลขที่สัญญาบางตัวไม่พบในระบบ!! เพื่อป้องกันการผิดพลาดกรุณาเลือกเลขที่สัญญาที่ระบบกำหนดให้ค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="7"){
				alert("เลขที่สัญญาบางตัวซ้ำกัน กรุณาเลือกใหม่อีกครั้งค่ะ");
				$("#submitButton").attr('disabled', false);
			}else{
				alert(data);
				$("#submitButton").attr('disabled', false);
			}
		});
    });
});
function check_num(evt) {
	//ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode < 48 || charCode >57) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		$('#number_running').focus();
		return false;
	}
	return true;
}

</script>
</body>
</html>
