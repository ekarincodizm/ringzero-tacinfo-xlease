<?php
include('../../config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ผูกสัญญาวงเงินชั่วคราว</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
$(document).ready(function(){
	CreateNewRow();
	guarantorNewRow(); // ผู้ค้ำประกัน
 
 
	$("#conDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	
	$("#conStartDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	
	$("#conEndDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	
	$("#conFirstDue").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });	
	$("#conFreeDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	
	 $("#main").autocomplete({
        source: "listcus_main.php",
        minLength:1
    });
	
	 $("#cusadd").autocomplete({
        source: "listcus_main.php",
        minLength:1
    });
	


});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm.conid.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่สัญญา";
	}
	
	//ตรวจสอบจำนวนตัวอักษรของเลขที่สัญญาใหม่  15 - 20 ตัวอักษร
	var stringnum = document.frm.conid.value.length;
	if(stringnum != 15 && stringnum != 20){
		theMessage = theMessage + "\n -->  เลขที่สัญญาควรจะจำนวน 15 หรือ 20 ตัวอักษรเท่านั้น !";
	}
	
	if (document.frm.contype.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก ประเภทสินเชื่อ";
	}
	
	if (document.frm.conCredit.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ วงเงินที่ปล่อย";
	}
	
	if (document.frm.conLoanIniRate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ % ดอกเบี้ยคุม";
	}
	
	if (document.frm.conDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก วันที่ทำสัญญาวงเงิน";
	}
	
	if (document.frm.main.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ผู้กู้หลัก";
	}
	
	if (document.frm.address.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ รายละเอียดที่อยู่";
	}
	
	if (document.frm.address.value=="..........") {
	theMessage = theMessage + "\n -->  กรุณากดปุ่ม บันทึก อีกครั้ง";
	}
	

	// If no errors, submit the form
	if (theMessage == noErrors){
		//return true;
		//chklist();
		if(document.getElementById("valuechk").value=="1")
		{
			alert(' เลขที่สัญญานี้มีในระบบแล้ว กรุณาเปลี่ยนด้วยครับ ');
			return false;	
		}
		else if(document.getElementById("valuechk").value=="2")
		{
			alert(' เลขที่สัญญานี้กำลังรออนุมัติ กรุณาเปลี่ยนด้วยครับ ');
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}


var intLine = 0;
var guarantorLine = 0;


function CreateNewRow()
	{
		intLine++;
		
		var theTable = document.getElementById("tableadd");
		var newRow = theTable.insertRow(theTable.rows.length)
		newRow.id = newRow.intLine
		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.intLine;
		newCell.align="right";
		newCell.width="35%";
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "ผู้กู้ร่วม :";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.intLine;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<input NAME=\"join[]\" ID=\"join"+intLine+"\" onkeypess=\"javascript : autocom("+intLine+");\" type=\"text\" size=\"50\">";
		
		$("#join"+intLine).autocomplete({
			source: "listcus_main.php",
			minLength:1
		});
		
	}	

function RemoveRow()
	{
		if(parseInt(intLine) > 1)
		{
				document.getElementById("join"+intLine+"").value=="";
				theTable = document.getElementById("tableadd");				
				theTableBody = theTable.tBodies[0];
				theTableBody.deleteRow(intLine);
				intLine--;
		
		}		
	}

function guarantorNewRow() // เพิ่มผู้ค้ำประกัน
{

		guarantorLine++;
		
		var theTable = document.getElementById("tableGuarantor");
		var newRow = theTable.insertRow(theTable.rows.length)
		newRow.id = newRow.guarantorLine
		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.guarantorLine;
		newCell.align="right";
		newCell.width="35%";
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "ผู้ค้ำประกัน :";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.guarantorLine;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<input NAME=\"guarantor[]\" ID=\"guarantor"+guarantorLine+"\" onkeypess=\"javascript : autocom("+guarantorLine+");\" type=\"text\" size=\"50\">";
		
		$("#guarantor"+guarantorLine).autocomplete({
			source: "listcus_main.php",
			minLength:1
		});
	
}	

function guarantorRemoveRow() // ลดผู้ค้ำประกัน
{
	if(parseInt(guarantorLine) > 1)
	{
			document.getElementById("guarantor"+guarantorLine+"").value=="";
			theTable = document.getElementById("tableGuarantor");				
			theTableBody = theTable.tBodies[0];
			theTableBody.deleteRow(guarantorLine);
			guarantorLine--;
	}		
}

function checkcon(){

	$.post("checkid.php",{
			id : document.frm.conid.value
			
		},
		function(data){		
			
				if(data=='No'){
						//alert(' รหัสซ้ำครับกรุณาเปลี่ยนด้วย ');
						document.getElementById("conid").style.backgroundColor ="#FFE1E1";
						var textalert = ' เลขที่สัญญานี้ มีอยู่ในระบบแล้ว ';
						$("#checkconid").text(textalert);
						document.getElementById("valuechk").value='1';
				}else if(data == 'YES'){
						document.getElementById("conid").style.backgroundColor = "#F2F2F2";
						$("#checkconid").text("");
						document.getElementById("valuechk").value='0';
				}else if(data=='Dup'){
						document.getElementById("conid").style.backgroundColor ="#FFE1E1";
						var textalert = ' เลขที่สัญญานี้ กำลังรออนุมัติ ';
						$("#checkconid").text(textalert);
						document.getElementById("valuechk").value='2';
				}
		});
};

function chkadd(){
	$.post("address.php",{
			id : document.frm.cusadd.value			
		},
		function(data){		
			$("#address").text(data);
		}
	);
};		

function chklist(){
	if(document.getElementById("valuechk").value=="1"){
		alert(' เลขที่สัญญานี้มีในระบบแล้ว กรุณาเปลี่ยนด้วยครับ ');
		return false;	
	}else if(document.getElementById("valuechk").value=="2"){
		alert(' เลขที่สัญญานี้กำลังรออนุมัติ กรุณาเปลี่ยนด้วยครับ ');
		return false;
	}else{
		if(ducument.frm.conid.value==""){
			alert("กรุณาระบุเลขที่สัญญา");
			ducument.frm.conid.focus();
			return false;
		}else if(ducument.frm.conCredit.value==""){
			alert("กรุณาระบุวงเงินที่ปล่อย");
			ducument.frm.conCredit.focus();
			return false;
		}
				return true;
	}		
}
</script>
</head>
<center><h2>(THCAP) ผูกสัญญาวงเงินชั่วคราว</h2></center>
<body >
<table width="900" frame="border" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#FFE1E1">
<tr>
	<td><br></td>
</tr>
<tr>
	<th align="center">
		สัญญาล่าสุดหมวด MG
	</th>
	<th align="center">
		สัญญาล่าสุดหมวด LI
	</th>
	<th align="center">
		สัญญาล่าสุดหมวด SM
	</th>
	<th align="center">
		สัญญาล่าสุดหมวด FA
	</th>
	<th align="center">
		สัญญาล่าสุดหมวด PN
	</th>
	<th align="center">
		สัญญาล่าสุดหมวด CG
	</th>
</tr>
<tr>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'MG'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'LI'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'SM'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'FA'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'PN'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
	<td align="center">
		<?php 
			$sql1 = "SELECT MAX(\"contractID\") as max FROM thcap_contract where \"conType\" = 'CG'";
			$query1 = pg_query($sql1);
			$result1 = pg_fetch_array($query1);
			echo $result1['max'];
		?>
	</td>
</tr>
<tr>
	<td><br></td>
</tr>		
</table>
<form name="frm" action="query.php" method="POST">
<table width="900" border="0" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#ECFAFF" id="tble">
<tr>
	<td width="25%"><br></td>
	<td width="45%"><br></td>
	<input type="hidden" name="valuechk" id="valuechk">
</tr>
<tr>
	<td align="right">เลขที่สัญญา <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conid" id="conid" size="50" onkeyup="javascript : checkcon();" onblur="javascript : checkcon();"><span id="checkconid" name="checkconid"></span></td>
</tr>
<tr>
	<td align="right">ประเภทสินเชื่อ <font color="#FF0000"><b> * </b></font> : </td>
	<td>
		<select name="contype">
			<option value="">เลือกประเภทสินเชื่อ</option>
			<option value="CG">CG</option>
			<option value="FA">FA</option>
			<option value="FI">FI</option>
			<option value="LI">LI</option>
			<option value="MG">MG</option>
			<option value="PN">PN</option>
			<option value="SM">SM</option>
		</select>
	</td>	
</tr>
<tr>
	<td align="right">เลือกบริษัท : </td>
	<td>
		<select name="conCompany">
			<option value="THCAP">---- THCAP  ----</option>
		</select>
	</td>
</tr>
<tr>
	<td align="right">วงเงินที่ปล่อย <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conCredit"></td>
</tr>
<tr>
	<td align="right">% ดอกเบี้ยคุม <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conLoanIniRate"></td>
</tr>
<tr>
	<td align="right">วันที่ทำสัญญาวงเงิน <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="conDate" id="conDate"></td>
</tr>
<tr>
	<td align="right">ผู้กู้หลัก <font color="#FF0000"><b> * </b></font> : </td>
	<td><input type="textbox" name="main" id="main" size="50"></td>
</tr>
<tr>
	<td align="center" colspan="2">
	ปุ่มเพิ่ม /ลบ ผู้กู้ร่วม <input type="button" value=" เพิ่ม " onclick="javascript : CreateNewRow();"><input type="button" value=" ลบ " onClick="javascript : RemoveRow();"></td>
</tr>
<tr>
	<td colspan="2">
		<table width="850"  cellspacing="2" cellpadding="2" style="margin-top:1px" align="center" id="tableadd">
			<tr></tr>
			
		</table>
	</td>
</tr>

<tr>
	<td align="center" colspan="2">
	ปุ่มเพิ่ม /ลบ ผู้ค้ำประกัน <input type="button" value=" เพิ่ม " onclick="javascript : guarantorNewRow();"><input type="button" value=" ลบ " onClick="javascript : guarantorRemoveRow();"></td>
</tr>
<tr>
	<td colspan="2">
		<table width="850"  cellspacing="2" cellpadding="2" style="margin-top:1px" align="center" id="tableGuarantor">
			<tr></tr>
		</table>
	</td>
</tr>

<tr>
	
	<td align="right">เลือกที่อยู่จากชื่อ :</td>
	<td>
		<input type="textbox" name="cusadd" id="cusadd" size="50" onkeyup="javascript : chkadd()" onblur="javascript : chkadd()">
	</td>
</tr>
<tr>
	<td align="right">รายละเอียดที่อยู่ <font color="#FF0000"><b> * </b></font> : </td>
	<td><textarea cols="50" name="address"  id="address" rows="5" readonly></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="hidden" name="method" value="addcredit"><input type="submit" value="บันทึก" onclick="return validate();"></td>
</tr>
<tr>
	<td><br></td>
</tr>	
</table>
<div style="margin-top:5px;"></div>
	<?php 
	$where = "\"conRepeatDueDay\" is null ";
	$credit = 'yes';
	include("table_waitapp.php"); 
	
	?>
</form>
</body>