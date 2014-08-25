<?php
include('../config/config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการอนุมัติยกเลิกเงินโอนนอกระบบ</title>

	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<style type="text/css">
.sortable {
	color: #000000;
	cursor:pointer;
	text-decoration:underline;
}
</style>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<center>

<h2>ประวัติการอนุมัติยกเลิกเงินโอนนอกระบบ</h2>

<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th align="center">รายการที่</th>
		<th align="center">ธนาคาร</th>
		<th align="center">รหัสสาขา</th>
		<th align="center">วันเวลาที่โอน</th>
		<th align="center">จำนวนเงิน</th>
		<th align="center">ผู้ทำรายการ</th>
		<th align="center">วันเวลาที่ทำรายการ</th>
		<th align="center">ผู้อนุมัติ</th>
		<th align="center">วันเวลาที่อนุมัติ</th>
		<th align="center">เหตุผล</th>
		<th align="center">ผลการอนุมัติ</th>
	</tr>
	<?php
	$query = pg_query("select CASE WHEN b.\"amt\" is null THEN c.\"amt\" ELSE b.\"amt\" END,
						CASE WHEN b.\"pay_bank_branch\" is null THEN c.\"pay_bank_branch\" ELSE b.\"pay_bank_branch\" END,
						CASE WHEN b.\"tr_date\" is null THEN c.\"tr_date\" ELSE b.\"tr_date\" END,
						CASE WHEN b.\"tr_time\" is null THEN c.\"tr_time\" ELSE b.\"tr_time\" END,
						CASE WHEN b.\"bank_no\" is null THEN c.\"bank_no\" ELSE b.\"bank_no\" END,
						CASE WHEN b.\"PostID\" is null THEN c.\"PostID\" ELSE b.\"PostID\" END,
						CASE WHEN b.\"id_tranpay\" is null THEN c.\"id_tranpay_deleted\" ELSE b.\"id_tranpay\" END as \"id_tranpay\",
						a.\"doerID\", a.\"doerStamp\", a.\"autoID\", a.\"appvID\", a.\"appvStamp\", a.\"Approved\", a.\"reason\"
						from \"TranPay_Request_Cancel\" a
						left join \"TranPay\" b on a.id_tranpay = b.id_tranpay
						left join \"TranPay_deleted\" c on a.id_tranpay = c.id_tranpay_deleted
						where a.\"Approved\" in('0','1')
						order by \"appvStamp\" DESC ");
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
		$appvID = $result['appvID'];
		$appvStamp = $result['appvStamp'];
		$Approved = $result['Approved'];
		
		$BankName = 0;
		$qry_bank=pg_query("select \"BankName\" from \"BankCheque\" WHERE \"BankNo\"='$bank_no' ");
		if($res_bank=pg_fetch_array($qry_bank))
		{
			$BankName = $res_bank["BankName"];
		}
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$doer_fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$appv_fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($Approved == 1)
		{
			$ApprovedTXT = "อนุมัติ";
		}
		elseif($Approved == 0)
		{
			$ApprovedTXT = "ไม่อนุมัติ";
		}
		else
		{
			$ApprovedTXT = "";
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
		<td align="left"><?php echo $doer_fullname; ?></td>
		<td align="center"><?php echo $doerStamp; ?></td>
		<td align="left"><?php echo $appv_fullname; ?></td>
		<td align="center"><?php echo $appvStamp; ?></td>
		<td align="center"><span onclick="javascript:popU('frm_acc_appvDelDetail.php?aID=<?php echo $autoID; ?>&view=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=450')" style="cursor: pointer;"><img src="image/detail.gif"></span></td>
		<td align="center"><?php echo $ApprovedTXT; ?></td>
		</tr>
<?php
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td></tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td></tr>";
	}
	?>
</table>

</center>

</body>
</html>