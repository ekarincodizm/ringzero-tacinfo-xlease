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
    <title>อนุมัติปลดล็อกสัญญาเช่าซื้อ</title>
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
		<div class="header"><h1>อนุมัติปลดล็อกสัญญาเช่าซื้อ</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติปลดล็อกสัญญาเช่าซื้อ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<th>รายการ</th>
				<th>เลขที่สัญญา</th>
				<th>ผู้ขอปลดล๊อก</th>
				<th>วันเวลาขอปลดล็อก</th>
				<th>รายละเอียด</th>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"Fp_unlock\" where \"appvStatus\" = '9' order by \"doerStamp\" ");
			$nub=pg_num_rows($qry_fr);
			$i = 0;
			while($res_fr=pg_fetch_array($qry_fr))
			{
				$autoID = $res_fr["autoID"];
				$IDNO = $res_fr["IDNO"];
				$doerID = $res_fr["doerID"];
				$doerStamp = $res_fr["doerStamp"];
				
				// หาชื่อพนักงาน
				$qry_fullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
				$fullname = pg_result($qry_fullname,0);
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $i; ?></td>
				<td><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>
				<td>
					<span onclick="javascript:popU('popup_approve_unlock.php?autoID=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=5 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<!--ประวัติการอนุมัติ-->
<div style="padding-top:20px;">
	<?php
	$limit="limit 30";
	$txthead="ประวัติการอนุมัติปลดล็อกสัญญาเช่าซื้อ 30 รายการล่าสุด";
	include("frm_history.php");
	?>
</div>
</body>
</html>