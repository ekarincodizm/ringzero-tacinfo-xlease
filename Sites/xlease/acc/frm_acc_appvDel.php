<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติยกเลิกเงินโอนนอกระบบ</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h2>อนุมัติยกเลิกเงินโอนนอกระบบ</h2></center>
<br>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th align="center">รายการที่</th>
		<th align="center">ธนาคาร</th>
        <th align="center">รหัสสาขา</th>
        <th align="center">วันเวลาที่โอน</th>
        <th align="center">จำนวนเงิน</th>
		<th align="center">ผู้ทำรายการ</th>
        <th align="center">วันเวลาที่ทำรายการ</th>
        <th align="center">ทำรายการ</th>
	</tr>
	<?php
	$query = pg_query("select b.*, a.\"doerID\", a.\"doerStamp\", a.\"autoID\" from \"TranPay_Request_Cancel\" a left join \"TranPay\" b on a.id_tranpay = b.id_tranpay where a.\"Approved\" = '9' order by a.\"doerStamp\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$amt = $result['amt'];
		$pay_bank_branch = $result['pay_bank_branch'];
		$tr_date = $result['tr_date'];
		$tr_time = $result['tr_time'];
		$bank_no = $result['bank_no'];
		$PostID = $result['PostID'];
		$id_tranpay = $result['id_tranpay'];
		$doerID = $result['doerID'];
		$doerStamp = $result['doerStamp'];
		$autoID = $result['autoID'];
		
		$BankName = 0;
		$qry_bank=pg_query("select \"BankName\" from \"BankCheque\" WHERE \"BankNo\"='$bank_no' ");
		if($res_bank=pg_fetch_array($qry_bank))
		{
			$BankName = $res_bank["BankName"];
		}
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
?>
		<td align="center"><?php echo $i; ?></td>
		<td align="center"><?php echo $BankName; ?></td>
        <td align="center"><?php echo $pay_bank_branch; ?></td>
        <td align="center"><?php echo "$tr_date $tr_time"; ?></td>
        <td align="right"><?php echo number_format($amt,2); ?></td>
		<td align="left"><?php echo $fullname; ?></td>
		<td align="center"><?php echo $doerStamp; ?></td>
        <td align="center"><span onclick="javascript:popU('frm_acc_appvDelDetail.php?aID=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=450')" style="cursor: pointer;"><img src="image/detail.gif"></span></td>
		</tr>
<?php
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td></tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td></tr>";
	}
	?>
</table>
<div style="margin-top:50px;"></div>
<center>
<div align="center" style="width:80%;">
<?php include("frm_historyAccDel_limit.php"); ?>
</div>
</center>
</body>
</html>