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
			
			var contractID = document.frm1.contractID.value; // เลขที่สัญญา
			
			if (document.frm1.CusFullName.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ ชื่อ-นามสกุลลูกค้า";
			}
			
			if (document.frm1.contractID.value=="") // ถ้าไม่ได้ระบุเลขที่สัญญา
			{
				theMessage = theMessage + "\n ->  กรุณาระบุ เลขที่สัญญา";
			}
			else // ถ้าระบุเลขที่สัญญาแล้ว ให้ตรวจสอบ format
			{
				//ตรวจสอบจำนวนตัวอักษรของเลขที่สัญญาใหม่  15 ตัวอักษร
				var stringnum = contractID.length;
				
				if(stringnum != 15) // ถ้าจำนวนตัวอักษรไม่ถูก
				{
					theMessage = theMessage + "\n ->  เลขที่สัญญาควรจะจำนวน 15 ตัวอักษรเท่านั้น !";
				}
				else // ถ้าจำนวนตัวอักษรถูกต้อง ให้เช็ค format ด้วย XX-XX00-00000
				{
					if(!((contractID.substring(0,1)  >= "A" && contractID.substring(0,1) <= "Z"))){ // ตัวที่ 1 ต้องเป็นตัวอักษรอังกฤษพิมพ์ใหญ่เท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(1,2)  >= "A" && contractID.substring(1,2) <= "Z"))){ // ตัวที่ 2 ต้องเป็นตัวอักษรอังกฤษพิมพ์ใหญ่เท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(contractID.substring(2,3) != "-"){ // ตัวที่ 3 ต้องเป็นเครื่องหมายขีดเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(3,4)  >= "A" && contractID.substring(3,4) <= "Z"))){ // ตัวที่ 4 ต้องเป็นตัวอักษรอังกฤษพิมพ์ใหญ่เท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(4,5)  >= "A" && contractID.substring(4,5) <= "Z"))){ // ตัวที่ 5 ต้องเป็นตัวอักษรอังกฤษพิมพ์ใหญ่เท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(5,6)  >= "0" && contractID.substring(5,6) <= "9"))){ // ตัวที่ 6 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(6,7)  >= "0" && contractID.substring(6,7) <= "9"))){ // ตัวที่ 7 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(contractID.substring(7,8) != "-"){ // ตัวที่ 8 ต้องเป็นเครื่องหมายขีดเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(8,9)  >= "0" && contractID.substring(8,9) <= "9"))){ // ตัวที่ 9 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(9,10)  >= "0" && contractID.substring(9,10) <= "9"))){ // ตัวที่ 10 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(10,11)  >= "0" && contractID.substring(10,11) <= "9"))){ // ตัวที่ 11 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(11,12)  >= "0" && contractID.substring(11,12) <= "9"))){ // ตัวที่ 12 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(12,13)  >= "0" && contractID.substring(12,13) <= "9"))){ // ตัวที่ 13 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(13,14)  >= "0" && contractID.substring(13,14) <= "9"))){ // ตัวที่ 14 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
					else if(!((contractID.substring(14,15)  >= "0" && contractID.substring(14,15) <= "9"))){ // ตัวที่ 15 ต้องเป็นตัวเลขเท่านั้น
						theMessage = theMessage + "\n ->  format เลขที่สัญญา ไม่ถูกต้อง";
					}
				}
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
			
			if (document.frm1.doerNote.value=="") {
				theMessage = theMessage + "\n ->  กรุณาระบุ หมายเหตุรายละเอียดของสัญญา";
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
			<td align="right">ชื่อ-นามสกุลลูกค้า <font color="#FF0000">*</font> : </td>
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
			<td align="left"><input type="text" name="firstDueDate" id="firstDueDate" size="15" value="" readOnly></td>
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
			<td align="right" valign="top">หมายเหตุ (แสดงใน Card Bill Payment) : </td>
			<td align="left"><textarea cols="40" name="note"></textarea></td>
		</tr>
		<tr>
			<td align="right" valign="top"> หมายเหตุรายละเอียดของสัญญา <font color="#FF0000">*</font> : </td>
			<td align="left">
				<textarea name="doerNote" cols="40" rows="5"></textarea>
				<br/>
				<font color="red">*ระบุรายละเอียดของสัญญาหรือทรัพย์สินเพื่อใช้ยืนยันเลขที่สัญญา<br/>เช่น แจ้งทรัพย์สิน ขนาด ที่ตั้ง</font>
			</td>
		</tr>
	</table>
	<br/>
	<input type="submit" name="add" value="ตกลง" style="cursor:pointer;" onClick="return validate();"> &nbsp;&nbsp;&nbsp;
	<input type="button" value="เริ่มใหม่" style="cursor:pointer;" onClick="window.location='frm_Index.php';"> &nbsp;&nbsp;&nbsp;
	<input type="button" value="ปิด" style="cursor:pointer;" onClick="javascript:window.close();">
</form>

<br/><br/>
<table width="90%">
	<tr>
		<td>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr bgcolor="#FFFFFF">
					<td colspan="8" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
				</tr>
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<th>ชื่อ-นามสกุลลูกค้า</th>
					<th>ยอดผ่อนขั้นต่ำ</th>
					<th>วันที่ครบกำหนดชำระงวดแรก</th>
					<th>จ่ายทุกวันที่</th>
					<th>ผู้ทำรายการ</th>
					<th>วันเวลาที่ทำรายการ</th>
					<th>รายละเอียด</th>
				</tr>
				<?php
				$qry_wait = pg_query("
										SELECT
											a.\"autoID\",
											a.\"CusFullName\",
											a.\"contractID\",
											a.\"minPayment\",
											a.\"firstDueDate\",
											a.\"payDay\",
											b.\"fullname\",
											a.\"doerStamp\"
										FROM
											\"thcap_print_card_bill_payment\" a
										LEFT JOIN
											\"Vfuser\" b ON b.\"id_user\" = a.\"doerID\"
										WHERE
											a.\"appvStatus\" = '9'
										ORDER BY
											a.\"doerStamp\"
									");
				$i = 0;
				while($res_wait = pg_fetch_array($qry_wait))
				{
					$i++;
					$autoID = $res_wait["autoID"]; // ลำดับรายการ
					$CusFullName = $res_wait["CusFullName"]; // ชื่อลูกค้า
					$contractID = $res_wait["contractID"]; // เลขที่สัญญา
					$minPayment = $res_wait["minPayment"]; // ยอดผ่อนขั้นต่ำ
					$firstDueDate = $res_wait["firstDueDate"]; // วันที่ครบกำหนดชำระงวดแรก
					$payDay = $res_wait["payDay"]; // จ่ายทุกวันที่
					$fullname = $res_wait["fullname"]; // รหัสพนักงานที่ทำรายการ
					$doerStamp = $res_wait["doerStamp"]; // วันเวลาที่ทำรายการ
					
					if($i%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
					
					echo "<td align=\"left\">$CusFullName</td>";
					echo "<td align=\"right\">".number_format($minPayment,2)."</td>";
					echo "<td align=\"center\">$firstDueDate</td>";
					echo "<td align=\"center\">$payDay</td>";
					echo "<td align=\"left\">$fullname</td>";
					echo "<td align=\"center\">$doerStamp</td>";
					echo "<td align=\"center\"><img src=\"../thcap/images/detail.gif\" height=\"19\" width=\"19\" style=\"cursor:pointer;\" onClick=\"javascript:popU('popup_view_detail.php?id=$autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\"></td>";
					
					echo "</tr>";
				}
				
				if($i == 0)
				{
					echo "<tr><td colspan=\"7\" align=\"center\">--ไม่พบข้อมูล--</td></tr>";
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<br/><br/>
		<?php include("frm_history_limit.php"); ?>
		</td>
	</tr>
</table>

</center>
</body>
</html>