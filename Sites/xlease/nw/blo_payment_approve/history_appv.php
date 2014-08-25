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
    <title>(BLO) ประวัติการอนุมัติรับชำระเงิน ทั้งหมด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<center><h1>(BLO) ประวัติการอนุมัติรับชำระเงิน ทั้งหมด</h1></center>

<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>ลำดับ</td>
				<td>สัญญาเลขที่</td>
				<td>ผู้ชำระเงิน</td>
				<td>วันที่ชำระเงิน</td>
				<td>ผู้ทำรายการรับชำระ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>ผู้ทำรายการอนุมัติ</td>
				<td>วันเวลาที่ทำรายการอนุมัติ</td>
				<td>ผลการอนุมัติ</td>
			</tr>
			<?php
				$qry_blo = pg_query("select *, \"receiptStamp\"::date as \"receiptDate\"
									from \"blo_receipt_temp\" where \"appvStatus\" in('0','1') order by \"appvStamp\" DESC ");
				$nub = pg_num_rows($qry_blo);
				$i = 0;
				while($res_blo = pg_fetch_array($qry_blo))
				{
					$receiptTempID = $res_blo["receiptTempID"];
					$receiptDate = $res_blo["receiptDate"];
					$contractID = $res_blo["contractID"];
					$CusID = $res_blo["CusID"];
					$doerID = $res_blo["doerID"];
					$doerStamp = $res_blo["doerStamp"];
					$appvID = $res_blo["doerID"];
					$appvStamp = $res_blo["doerStamp"];
					$appvStatus = $res_blo["appvStatus"];
					
					// หาชื่อเต็มลูกค้า
					$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = '$CusID' ");
					$CusName = pg_result($qry_cus,0);
					
					// หาชื่อเต็มพนักงานที่ทำรายการ
					$qry_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
					$doerName = pg_result($qry_doer,0);
					
					// หาชื่อเต็มพนักงานที่ทำรายการ
					$qry_appv = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
					$appvName = pg_result($qry_appv,0);
					
					if($appvStatus == "1")
					{
						$appvStatusText = "อนุมัติ";
					}
					elseif($appvStatus == "0")
					{
						$appvStatusText = "ไม่อนุมัติ";
					}
					else
					{
						$appvStatusText = "";
					}
					
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
					<td><?php echo $receiptDate; ?></td>
					<td align="left"><?php echo $doerName; ?></td>
					<td><?php echo $doerStamp; ?></td>
					<td align="left"><?php echo $appvName; ?></td>
					<td><?php echo $appvStamp; ?></td>
					<td><?php echo $appvStatusText; ?></td>
				</tr>
				<?php
				} //end while
				if($nub == 0){
					echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
				}
				?>
		</table><br>
		</div>
	</td>
</tr>	
</table>

</body>
</html>