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
							\"doerNote\",
							\"appvStatus\",
							(select \"fullname\" from \"Vfuser\" where \"id_user\" = \"appvID\") AS \"appvName\",
							\"appvStamp\",
							\"appvNote\"
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
$appvStatus = pg_fetch_result($qry_detail,9); // ผลการอนุมัติ
$appvName = pg_fetch_result($qry_detail,10); // ชื่อพนักงานที่อนุมัติ
$appvStamp = pg_fetch_result($qry_detail,11); // วันเวลาที่อนุมัติ
$appvNote = pg_fetch_result($qry_detail,12); // หมายเหตุการอนุมัติ

// ผลการอนุมัติ
if($appvStatus == "0")
{
	$appvStatusText = "<font color=\"red\">ไม่อนุมัติ</font>";
}
elseif($appvStatus == "1")
{
	$appvStatusText = "<font color=\"green\">อนุมัติ</font>";
}
elseif($appvStatus == "9")
{
	$appvStatusText = "รอการอนุมัติ";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>รายละเอียด Card Bill Payment</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
	</script>
</head>

<body>
<center>
	<h2>รายละเอียด Card Bill Payment</h2>
	<table>
		<tr>
			<td align="right"><b>ชื่อ-นามสุกลลูกค้า  : </b></td>
			<td align="left"><?php echo $CusFullName; ?></td>
		</tr>
		<?php
		if($appvStatus != "9") // ถ้ารออนุมัติอยู่ ให้ซ้อนเลขที่สัญญาไว้ก่อน ยังไม่ต้องแสดงให้เห็น แต่ถ้าทำรายการอนุมัติ/ไม่อนุมัติไปแล้ว ให้แสดงให้เห็นได้
		{
		?>
			<tr>
				<td align="right"><b>เลขที่สัญญา  : </b></td>
				<td align="left"><?php echo $contractID; ?></td>
			</tr>
		<?php
		}
		?>
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
			<td align="right"><b>ผลการอนุมัติ : </b></td>
			<td align="left"><?php echo $appvStatusText; ?></td>
		</tr>
		<tr>
			<td align="right"><b>ผู้อนุมัติ : </b></td>
			<td align="left"><?php echo $appvName; ?></td>
		</tr>
		<tr>
			<td align="right"><b>วันเวลาที่อนุมัติ : </b></td>
			<td align="left"><?php echo $appvStamp; ?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>หมายเหตุการอนุมัติ : </b></td>
			<td align="left"><textarea name="doerNote" cols="40" rows="4" disabled><?php echo $appvNote; ?></textarea></td>
		</tr>
	</table>
	<br/>
	<?php
	if($appvStatus == "1") // ถ้าอนุมัติแล้ว ให้พิมพ์รายการได้
	{
	?>
		<input type="button" value="พิมพ์" onclick="javascript:popU('print_card_bill_payment_pdf.php?autoID=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700');" style="cursor:pointer;" />
		&nbsp;&nbsp;&nbsp;
	<?php
	}
	?>
	<input type="button" value="ปิด" onclick="javascript:window.close();" style="cursor:pointer;" />

</center>
</body>
</html>