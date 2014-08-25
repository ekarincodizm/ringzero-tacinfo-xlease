<?php
include("../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจรับเงินสดประจำวัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>

    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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
		<div class="header"><h1>(THCAP) ตรวจรับเงินสดประจำวัน</h1></div>
		<div class="wrapper">
			<div><span style="background-color:#FFCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;</span><b> คือ รายการที่มีการเปลี่ยนแปลง</b></div>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#CDCDB4">
			<tr style="color:#FFF;" valign="middle" bgcolor="#8B8B7A" align="center">
				<th>รหัสผู้ใช้งาน</th>
				<th>คำนำหน้า-ชื่อ-นามสกุล</th>
				<th>วันที่รับชำระ</th>
				<th>จำนวนเงินที่รับชำระ</th>
				<th>ดูรายละเอียดรายการนี้</th>
			</tr>
			<?php
			$qry_app=pg_query("SELECT receiveuserid,fullname,auditdate,cashsum,status FROM \"thcap_audit_cashday\" a
			LEFT JOIN \"Vfuser\" b on a.\"receiveuserid\"=b.\"id_user\"
			WHERE \"status\" IN ('0','2') ORDER BY auditdate,receiveuserid");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$receiveuserid=$res_app["receiveuserid"]; //รหัสผู้ใช้งาน
				$fullname=$res_app["fullname"]; //คำนำหน้า-ชื่อ-นามสกุล
				$auditdate=$res_app["auditdate"];//วันที่รับชำระ
				$cashsum=number_format($res_app["cashsum"],2);//จำนวนเงินที่รับชำระ
				$status=$res_app["status"];//สถานะการตรวจสอบ 0=รอตรวจสอบ 2=มีการเปลี่ยนแปลง
			
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=\"#EEEED1\" align=center>";
				}else{
					echo "<tr bgcolor=\"#FFFFE0\" align=center>";
				}
				if($status==2){
					echo "<tr bgcolor=\"#FFCCCC\" align=center>";
				}
				
			?>
				<td><?php echo $receiveuserid;?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $auditdate; ?></td>
				<td align="right"><?php echo $cashsum; ?></td>
				<td>
				<?php
					echo "<img src=\"../images/open.png\" width=\"16\" height=\"16\" onclick=\"javascript:popU('show_Approve.php?receiveuserid=$receiveuserid&auditdate=$auditdate','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=400')\" style=\"cursor: pointer;\">";
				?>
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
	$txthead="ประวัติการตรวจรับเงินสด 30 รายการล่าสุด";
	include("frm_history.php");
	?>
</div>
</body>
</html>