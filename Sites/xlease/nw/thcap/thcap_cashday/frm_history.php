<?php
if($limit==""){
	include("../../../config/config.php");
	$txthead="ประวัติการตรวจรับเงินสดทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติการตรวจรับเงินสดทั้งหมด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>

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
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#BBBBBB">
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CCCCCC" align="center">
				<th>รหัสผู้ใช้งาน</th>
				<th>คำนำหน้า-ชื่อ-นามสกุล</th>
				<th>วันที่รับชำระ</th>
				<th>จำนวนเงินที่รับชำระ</th>
				<th>รายละเอียดรายการนี้</th>
				<th>ผู้ตรวจรับ</th>
				<th>วันเวลาที่ตรวจรับ</th>
			</tr>
			<?php
			$qry_app=pg_query("SELECT receiveuserid,b.fullname,auditdate,cashsum,
			c.fullname as \"auditname\",auditstamp FROM \"thcap_audit_cashday\" a
			LEFT JOIN \"Vfuser\" b on a.\"receiveuserid\"=b.\"id_user\"
			LEFT JOIN \"Vfuser\" c on a.\"audituserid\"=c.\"id_user\"
			WHERE \"status\"='1' ORDER BY auditstamp $limit");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$receiveuserid=$res_app["receiveuserid"]; //รหัสผู้ใช้งาน
				$fullname=$res_app["fullname"]; //คำนำหน้า-ชื่อ-นามสกุล
				$auditdate=$res_app["auditdate"];//วันที่รับชำระ
				$cashsum=number_format($res_app["cashsum"],2);//จำนวนเงินที่รับชำระ
				$auditname=$res_app["auditname"];//ชื่อผู้ตรวจสอรับ
				$auditstamp=$res_app["auditstamp"];//วันเวลาที่ตรวจรับ
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#DDDDDD align=center>";
				}else{
					echo "<tr bgcolor=#EEEEEE align=center>";
				}
				
			?>
				<td><?php echo $receiveuserid;?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $auditdate; ?></td>
				<td align="right"><?php echo $cashsum; ?></td>
				<td>
				<?php
					echo "<img src=\"../images/full_page.png\" width=\"16\" height=\"16\" onclick=\"javascript:popU('show_apphistory.php?receiveuserid=$receiveuserid&auditdate=$auditdate','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=400')\" style=\"cursor: pointer;\">";
				?>
				</td>
				<td align="left"><?php echo $auditname; ?></td>
				<td><?php echo $auditstamp; ?></td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบรายการตรวจรับเงินสด -</b></td></tr>";
			}else{
				echo "<tr><td colspan=7 bgcolor=#CCCCCC><b>มีทั้งหมด $nub รายการ</b></td></tr>";			
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>