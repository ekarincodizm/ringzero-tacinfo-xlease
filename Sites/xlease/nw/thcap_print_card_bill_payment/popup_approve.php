<?php
include("../../config/config.php");

$autoID = pg_escape_string($_GET["id"]);

// หารายละเอียด
$qry_detail = pg_query("
						SELECT
							\"CusFullName\",
							\"contractID\",
							\"minPayment\",
							\"firstDueDate\",
							\"payDay\",
							\"note\",
							(select \"fullname\" from \"Vfuser\" where \"id_user\" = \"doerID\") AS \"doerName\",
							\"doerStamp\",
							\"doerNote\"
						FROM
							\"thcap_print_card_bill_payment\"
						WHERE
							\"autoID\" = '$autoID'
					");
$CusFullName = pg_fetch_result($qry_detail,0); // ชื่อ-นามสุกลลูกค้า
$contractID = pg_fetch_result($qry_detail,1); // เลขที่สัญญา
$minPayment = pg_fetch_result($qry_detail,2); // ยอดผ่อนขั้นต่ำ
$firstDueDate = pg_fetch_result($qry_detail,3); // วันที่ครบกำหนดชำระงวดแรก
$payDay = pg_fetch_result($qry_detail,4); // จ่ายทุกวันที่
$note = pg_fetch_result($qry_detail,5); // หมายเหตุ (แสดงใน Card Bill Payment)
$doerName = pg_fetch_result($qry_detail,6); // ชื่อพนักงานที่ทำรายการ
$doerStamp = pg_fetch_result($qry_detail,7); // วันเวลาที่ทำรายการ
$doerNote = pg_fetch_result($qry_detail,8); // หมายเหตุการทำรายการ / หมายเหตุรายละเอียดของสัญญา
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) อนุมัติพิมพ์ Card Bill Payment</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function validate()
		{
			var theMessage = "Please complete the following: \n-----------------------------------\n";
			var noErrors = theMessage
			
			if (document.frm1.appvNote.value==""){
				theMessage = theMessage + "\n ->  กรุณาระบุ หมายเหตุการอนุมัติ";
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
		
		function checkContrctID()
		{
			var contractID = '<?php echo $contractID; ?>'; // เลขที่สัญญา
			
			if(document.frm1.contractID.value == contractID)
			{
				document.getElementById("contractID").style.backgroundColor = "#00FF00";
				document.getElementById("appv").disabled = false;
			}
			else
			{
				document.getElementById("contractID").style.backgroundColor = "#FF4444";
				document.getElementById("appv").disabled = true;
			}
		}
	</script>
</head>

<body>
<center>
	<h2>(THCAP) อนุมัติพิมพ์ Card Bill Payment</h2>
	<form name="frm1" method="post" action="process_approve.php">
		<table>
			<tr>
				<td align="right"><b>ชื่อ-นามสุกลลูกค้า  : </b></td>
				<td align="left"><?php echo $CusFullName; ?></td>
			</tr>
			<tr>
				<td align="right"><b>เลขที่สัญญา <font color="red">*</font> : </b></td>
				<td align="left"><input type="textbox" size="30" name="contractID" id="contractID" autocomplete="off" onkeyup="checkContrctID();" /></td>
			</tr>
			<tr>
				<td align="right"><b>ยอดผ่อนขั้นต่ำ : </b></td>
				<td align="left"><?php echo number_format($minPayment,2); ?></td>
			</tr>
			<tr>
				<td align="right"><b>วันที่ครบกำหนดชำระงวดแรก : </b></td>
				<td align="left"><?php echo $firstDueDate; ?></td>
			</tr>
			<tr>
				<td align="right"><b>จ่ายทุกวันที่ : </b></td>
				<td align="left"><?php echo $payDay; ?></td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>หมายเหตุ (แสดงใน Card Bill Payment) : </b></td>
				<td align="left"><textarea name="note" cols="40" disabled><?php echo $note; ?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>หมายเหตุรายละเอียดของสัญญา : </b></td>
				<td align="left"><textarea name="doerNote" cols="40" rows="5" disabled><?php echo $doerNote; ?></textarea></td>
			</tr>
			<tr>
				<td align="right"><b>ผู้ทำรายการ : </b></td>
				<td align="left"><?php echo $doerName; ?></td>
			</tr>
			<tr>
				<td align="right"><b>วันเวลาที่ทำรายการ : </b></td>
				<td align="left"><?php echo $doerStamp; ?></td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>หมายเหตุการอนุมัติ <font color="red">*</font> : </b></td>
				<td align="left"><textarea name="appvNote" cols="40" rows="4"></textarea></td>
			</tr>
		</table>
		
		<br/>
		
		<input type="hidden" name="autoID" value="<?php echo $autoID; ?>" />
		
		<input type="submit" value="อนุมัติ" id="appv" name="appv" onClick="return validate();" style="cursor:pointer;" disabled />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="ไม่อนุมัติ" id="unAppv" name="unAppv" onClick="return validate();" style="cursor:pointer;" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="ปิด" onClick="javascript:window.close();" style="cursor:pointer;" />
	</form>

</center>
</body>
</html>