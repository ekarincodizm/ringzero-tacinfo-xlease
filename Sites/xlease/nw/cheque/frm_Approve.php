<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติสั่งจ่ายเช็ค</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td width="100">เลขที่สัญญา</td>
				<td width="100">ประเภทการสั่งจ่าย</td>
				<td width="180">สั่งจ่าย</td>
				<td width="80">จำนวนเงิน</td>
				<td width="60">วันที่สั่งจ่าย</td>
				<td width="80">ทำรายการอนุมัติ</td>
			</tr>
			<?php
			$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\" from cheque_pay a
				left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
				where \"appStatus\"='2' order by \"keyStamp\"");
			$nub=pg_num_rows($qrychq);
			while($reschq=pg_fetch_array($qrychq)){
				list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay)=$reschq;
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $IDNO; ?></td>
				<td><?php echo $typeName; ?></td>
				<td align="left"><?php echo $cusPay; ?></td>
				<td align="right"><?php echo number_format($moneyPay,2); ?></td>
				<td><?php echo $datePay; ?></td>
				<td>
					<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('showdetail.php?chqpayID=<?php echo $chqpayID; ?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')" style="cursor: pointer;">
				</td>
				<!--td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve.php?chqpayID=<?php echo $chqpayID; ?>&stsapp=1';}"><u>อนุมัติ</u></span></td>
				<td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการไม่อนุมัติ!!')){location.href='process_approve.php?chqpayID=<?php echo $chqpayID; ?>&stsapp=0'}"><u>ไม่อนุมัติ</u></span></td-->
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=6 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table><br><br>


<!--อนุมัติห้องชุด-->
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติยกเลิกเช็ค</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#F3CC43" align="center">
				<td width="100">เลขที่สัญญา</td>
				<td width="100">ประเภทการสั่งจ่าย</td>
				<td width="180">สั่งจ่าย</td>
				<td width="80">จำนวนเงิน</td>
				<td width="60">วันที่สั่งจ่าย</td>
				<td width="80">ทำรายการอนุมัติ</td>
			</tr>
			<?php
			$qrycancel=pg_query("select a.\"cancelID\",a.\"chqpayID\",c.\"typeName\",b.\"IDNO\",b.\"cusPay\",b.\"moneyPay\",b.\"datePay\" from \"cheque_cancel\" a
				left join \"cheque_pay\" b on a.\"chqpayID\"=b.\"chqpayID\"
				left join cheque_typepay c on b.\"typePay\"=c.\"typePay\"
				where \"cancelStatus\" = '2' order by a.\"cancelID\"");
			$nub=pg_num_rows($qrycancel);
			while($rescancel=pg_fetch_array($qrycancel)){
				list($cancelID,$chqpayID2,$typeName,$IDNO,$cusPay,$moneyPay,$datePay)=$rescancel;
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#FEFBF1 align=center>";
				}else{
					echo "<tr bgcolor=#FCF1CD align=center>";
				}
			?>
				<td><?php echo $IDNO; ?></td>
				<td><?php echo $typeName; ?></td>
				<td align="left"><?php echo $cusPay; ?></td>
				<td align="right"><?php echo number_format($moneyPay,2); ?></td>
				<td><?php echo $datePay; ?></td>
				<td>
					<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('showdetailcancel.php?cancelID=<?php echo $cancelID; ?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=700')" style="cursor: pointer;">
				</td>
				<!--td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve.php?chqpayID=<?php echo $cancelID; ?>&stsapp=11';}"><u>อนุมัติ</u></span></td>
				<td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการไม่อนุมัติ!!')){location.href='process_approve.php?chqpayID=<?php echo $cancelID; ?>&stsapp=22'}"><u>ไม่อนุมัติ</u></span></td-->
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=6 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>