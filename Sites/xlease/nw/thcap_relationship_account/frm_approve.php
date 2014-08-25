<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = nowDateTime();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติความสัมพันธ์ทางบัญชี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
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
		<div class="header"><h1>(THCAP) อนุมัติความสัมพันธ์ทางบัญชี</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<th>ประเภทการทำรายการ</th>
				<th>รหัสประเภทค่าใช้จ่าย</th>
				<th>ชื่อประเภทค่าใช้จ่าย</th>
				<th>ชื่อสมุดบัญชีพื้นฐาน</th>
				<th>ชื่อสมุดบัญชีคงค้าง</th>
				<th>ชื่อสมุดบัญชีทยอยรับรู้</th>
				<th>ผู้ทำรายการ</th>
				<th>วันเวลาที่ทำรายการ</th>
				<th>ผู้อนุมัติคนที่ 1</th>
				<th>วันเวลาที่อนุมัติครั้งที่ 1</th>
				<th>ทำรายการอนุมัติ</th>
			</tr>
			<?php
			$qry_fr=pg_query("select * from account.\"thcap_typePay_acc_temp\" where (\"appvStatus1\" = '9' or \"appvStatus2\" = '9') and \"appvStatus1\" <> '0' and \"appvStatus2\" <> '0' order by \"autoID\" ");
			$nub=pg_num_rows($qry_fr);
			
			while($res_fr=pg_fetch_array($qry_fr))
			{
				$autoID=$res_fr["autoID"];
				$tpID=$res_fr["tpID"];
				$tpBasis=$res_fr["tpBasis"]; // บัญชีพื้นฐาน
				$tpAccrual=$res_fr["tpAccrual"]; // บัญชีคงค้าง
				$tpAmortize=$res_fr["tpAmortize"]; // บัญชีทยอยรับรู้
				$doerID=$res_fr["doerID"];
				$doerStamp=$res_fr["doerStamp"];
				$appvID1=$res_fr["appvID1"];
				$appvStamp1=$res_fr["appvStamp1"];
				
				// หาว่าเป็นการแก้ไขหรือเพิ่มใหม่
				$qry_chk_row = pg_query("select * from account.\"thcap_typePay_acc\" where \"tpID\" = '$tpID' ");
				$chk_row = pg_num_rows($qry_chk_row);
				if($chk_row > 0)
				{
					$typeText = "แก้ไข";
				}
				else
				{
					$typeText = "เพิ่มใหม่";
				}
				
				// หา ชื่อประเภทค่าใช้จ่าย
				$qry_tpDesc = pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$tpID' ");
				$tpDesc = pg_result($qry_tpDesc,0);
				
				// หา ชื่อสมุดบัญชีพื้นฐาน
				if($tpBasis != "")
				{
					$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpBasis' ");
					$tpBasisName = pg_result($qry_accBookName,0);
				}
				else
				{
					$tpBasisName = "";
				}
				
				// หา ชื่อสมุดบัญชีคงค้าง
				if($tpAccrual != "")
				{
					$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAccrual' ");
					$tpAccrualName = pg_result($qry_accBookName,0);
				}
				else
				{
					$tpAccrualName = "";
				}
				
				// หา ชื่อสมุดบัญชีทยอยรับรู้
				if($tpAmortize != "")
				{
					$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAmortize' ");
					$tpAmortizeName = pg_result($qry_accBookName,0);
				}
				else
				{
					$tpAmortizeName = "";
				}
				
				// หาชื่อพนักงานที่ทำรายการ
				$qry_doerName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
				$doerName = pg_result($qry_doerName,0);
				
				// หาชื่อผู้อนุมัติคนที่ 1
				if($appvID1 != "")
				{
					$qry_appvName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID1' ");
					$appvName = pg_result($qry_appvName,0);
				}
				else
				{
					$appvName = "";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $typeText; ?></td>
				<td><?php echo $tpID; ?></td>
				<td align="left"><?php echo $tpDesc; ?></td>
				<td align="left"><?php echo $tpBasisName; ?></td>
				<td align="left"><?php echo $tpAccrualName; ?></td>
				<td align="left"><?php echo $tpAmortizeName; ?></td>
				<td align="left"><?php echo $doerName; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<td align="left"><?php echo $appvName; ?></td>
				<td><?php echo $appvStamp1; ?></td>
				<td><span onclick="javascript:popU('frm_detail_approve.php?id=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=750')" style="cursor: pointer;"><img src="../thcap/images/detail.gif" height="19" width="19" border="0"></span></td>
			<?php
				echo "</tr>";
			} //end while
			if($nub == 0)
			{
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>	
</table>

<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>
			<?php include("frm_historyapp_limit.php"); ?>
		</td>
	</tr>
</table>

</body>
</html>