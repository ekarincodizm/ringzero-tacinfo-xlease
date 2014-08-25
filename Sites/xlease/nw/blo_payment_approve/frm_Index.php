<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(BLO) อนุมัติรับชำระเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>(BLO) อนุมัติรับชำระเงิน</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr bgcolor="#FFFFFF">
					<td colspan="7" align="left" style="font-weight:bold;">(BLO) อนุมัติรับชำระเงิน</td>
				</tr>
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td>ลำดับ</td>
					<td>สัญญาเลขที่</td>
					<td>ผู้ชำระเงิน</td>
					<td>วันที่ชำระเงิน</td>
					<td>ผู้ทำรายการ</td>
					<td>วันเวลาที่ทำรายการ</td>
					<td>ทำรายการอนุมัติ</td>
				</tr>
				<?php
				$qry_blo = pg_query("select \"receiptTempID\", \"receiptStamp\"::date, \"contractID\", \"CusID\", \"doerID\", \"doerStamp\"
									from \"blo_receipt_temp\" where \"appvStatus\" = '9' order by \"doerStamp\" ");
				$nub = pg_num_rows($qry_blo);
				$i = 0;
				while($res_blo = pg_fetch_array($qry_blo))
				{
					$receiptTempID = $res_blo["receiptTempID"];
					$receiptStamp = $res_blo["receiptStamp"];
					$contractID = $res_blo["contractID"];
					$CusID = $res_blo["CusID"];
					$doerID = $res_blo["doerID"];
					$doerStamp = $res_blo["doerStamp"];
					
					// หาชื่อเต็มลูกค้า
					$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$CusID' ");
					$CusName = pg_result($qry_cus,0);
					
					// หาชื่อเต็มพนักงานที่ทำรายการ
					$qry_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
					$doerName = pg_result($qry_doer,0);
					
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
				?>
					<td><?php echo $i; ?></td>
					<td><?php echo $contractID; ?></td>
					<td align="left"><?php echo $CusName; ?></td>
					<td><?php echo $receiptStamp; ?></td>
					<td align="left"><?php echo $doerName; ?></td>
					<td><?php echo $doerStamp; ?></td>
					<td><span onclick="javascript:popU('payment_detail.php?TempID=<?php echo $receiptTempID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700')" style="cursor: pointer;"><img src="../thcap/images/detail.gif" height="19" width="19" border="0"></span></td>
				</tr>
				<?php
				} //end while
				if($nub == 0){
					echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}
				?>
			</table>
		</div>
	</td>
</tr>
</td>
</tr>	
</table>

<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>
			<?php
			include("history_appv_limit.php");
			?>
		</td>
	</tr>
</table>

</body>
</html>