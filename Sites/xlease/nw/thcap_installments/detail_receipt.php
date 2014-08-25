<?php
include("../../config/config.php");

$receiptID = $_GET["receiptID"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<style type="text/css">
	.odd{
		background-color:#FFFFCF;
		font-size:12px
	}
	.even{
		background-color:#D5EFFD;
		font-size:12px
	}
	.sum{
		background-color:#FFC0C0;
		font-size:12px
	}
	</style>
</head>

<body>
<center>
<h2>
<br>
รายละเอียดใบเสร็จ : <?php echo $receiptID; ?>
<br>
</h2>
<?php
	$qry = pg_query("select * from public.\"thcap_temp_int_201201\" where \"receiptID\" = '$receiptID'");
	while($result = pg_fetch_array($qry))
	{
		$receiveDate = $result["receiveDate"]; // วันที่รับชำระ
		$receiveAmount = $result["receiveAmount"]; // จำนวนเงินที่ชำระ
		$byChannel  = $result["byChannel"]; // ช่องทางการจ่าย
		
		if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$byChannel="ไม่ระบุ";}
		else
		{
			//นำไปค้นหาในตาราง BankInt
			$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
			$ressearch=pg_fetch_array($qrysearch);
			list($BAccount,$BName)=$ressearch;
			$byChannel="$BAccount-$BName";
		}
	}
	
	$qry_detail = pg_query("select * from public.\"thcap_v_receipt_details\" where \"receiptID\" = '$receiptID'");
	while($result_detail = pg_fetch_array($qry_detail))
	{
		$doerID = $result_detail["doerID"]; // ผู้รับชำระ
		$doerStamp = $result_detail["doerStamp"]; // วันเวลาที่ทำรายการ
	}
	
	$qry_user = pg_query("select * from public.\"Vfuser\" where \"username\" = '$doerID'");
	while($result_user= pg_fetch_array($qry_user))
	{
		$fullname = $result_user["fullname"];
	}
?>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
	<tr align="center" bgcolor="#79BCFF">
		<th>ผู้ทำรายการ</th>
		<th>ช่องทางการชำระ</th>
		<th>วันที่รับชำระ</th>
		<th>จำนวนเงินที่รับชำระ</th>
		<th>วันเวลาที่ทำรายการ</th>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo $fullname; ?></td>
		<td align="center"><?php echo $byChannel; ?></td>
		<td align="center"><?php echo $receiveDate; ?></td>
		<td align="right"><?php echo number_format($receiveAmount,2); ?></td>
		<td align="center"><?php echo $doerStamp; ?></td>
	<tr>
</table>
</center>
</body>
</html>