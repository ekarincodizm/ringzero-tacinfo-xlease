<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) พิมพ์ Card Bill Payment</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#firstDueDate").datepicker({
				showOn: 'button',
				buttonImage: '../thcap/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
		});
	
		function validate() 
		{
			var theMessage = "Please complete the following: \n-----------------------------------\n";
			var noErrors = theMessage
			
			if (document.frm1.CusFullName.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ ชื่อ-นามสุกลลูกค้า";
			}
			
			if (document.frm1.contractID.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ เลขที่สัญญา";
			}
			
			//ตรวจสอบจำนวนตัวอักษรของเลขที่สัญญาใหม่  15 - 20 ตัวอักษร
			var stringnum = document.frm1.contractID.value.length;
			if(stringnum != 15 && stringnum != 20){
				theMessage = theMessage + "\n ->  เลขที่สัญญาควรจะจำนวน 15 หรือ 20 ตัวอักษรเท่านั้น !";
			}
			
			if (document.frm1.minPayment.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ ยอดผ่อนขั้นต่ำ";
			}
			
			if (document.frm1.firstDueDate.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ วันที่ครบกำหนดชำระงวดแรก";
			}
			
			if (document.frm1.payDay.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ จ่ายทุกวันที่";
			}
			
			// If no errors, submit the form
			if (theMessage == noErrors) {
				return true;
			}
			else
			{
				// If errors were found, show alert message
				alert(theMessage);
				return false;
			}
		}
		
		function check_num(e)
		{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
			var key;
			if(window.event)
			{
				key = window.event.keyCode; // IE
				if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
					&& key != 43 && key != 44 && key != 45 && key != 47)
				{
					// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
				}
				else
				{
					window.event.returnValue = false;
				}
			}
			else
			{
				key = e.which; // Firefox       
				if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
					&& key != 43 && key != 44 && key != 45 && key != 47)
				{
					// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
				}
				else
				{
					key = e.preventDefault();
				}
			}
		}
	</script>
	
	<script type="text/javascript">
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
	</script>
</head>

<body>
<center>
<form name="frm1" method="post" action="process_save.php">
	<h1>(THCAP) พิมพ์ Card Bill Payment</h1>
	<table>
		<tr>
			<td align="right">ชื่อ-นามสุกลลูกค้า <font color="#FF0000">*</font> : </td>
			<td align="left"><input type="text" name="CusFullName" size="40" value=""></td>
		</tr>
		<tr>
			<td align="right">เลขที่สัญญา <font color="#FF0000">*</font> : </td>
			<td align="left"><input type="text" name="contractID" size="40" value=""></td>
		</tr>
		<tr>
			<td align="right">ยอดผ่อนขั้นต่ำ <font color="#FF0000">*</font> : </td>
			<td align="left"><input type="text" name="minPayment" size="15" value="" onkeypress="check_num(event);"></td>
		</tr>
		<tr>
			<td align="right">วันที่ครบกำหนดชำระงวดแรก <font color="#FF0000">*</font> : </td>
			<td align="left"><input type="text" name="firstDueDate" id="firstDueDate" size="15" value=""></td>
		</tr>
		<tr>
			<td align="right">จ่ายทุกวันที่ <font color="#FF0000">*</font> : </td>
			<td align="left">
				<select name="payDay">
					<option value="">- กรุณาเลือกวันที่จ่าย -</option>
					<?php
					for($i=1; $i<=28; $i++)
					{
						if(strlen($i) == 1){$i = "0".$i;}
						echo "<option value=\"$i\">".$i."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">หมายเหตุ : </td>
			<td align="left"><textarea cols="30" name="note"></textarea></td>
		</tr>
	</table>
	<br><br>
	<input type="submit" name="add" value="ตกลง" onclick="return validate();"> &nbsp;&nbsp;&nbsp;
	<input type="button" value="เริ่มใหม่" onclick="window.location='frm_Index.php';"> &nbsp;&nbsp;&nbsp;
	<input type="button" value="ปิด" onclick="javascript:window.close();">
</form>
</center>
</body>
</html>