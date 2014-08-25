<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = $nowDateTime;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) Approve Create งานยึด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}


</script>

</head>
<body>
<form name="frm">
<table width="990" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>(THCAP) Approve Create งานยึด</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) Approve Create งานยึด</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<th>ลำดับ</th>
				<th>เลขที่สัญญา</th>
				<th>ผู้ Create งานยึด</th>
				<th>วันเวลาที่ Create งานยึด</th>
				<th>หมายเหตุ Create งานยึด</th>
				<th>ทำรายการอนุมัติ</th>
			</tr>
			<?php
			$qry_create = pg_query("select * from \"thcap_create_seize_asset\" where \"createStatus\" = '9' order by \"doerStamp\" ");
			$nub = pg_num_rows($qry_create);
			$i = 0;
			while($res_create = pg_fetch_array($qry_create))
			{
				$i++;
				$createID = $res_create["createID"]; // รหัส Create งานยึด
				$contractID = $res_create["contractID"]; // เลขที่สัญญา
				$doerID = $res_create["doerID"]; // รหัสผู้ทำรายการ
				$doerStamp = $res_create["doerStamp"]; // วันเวลาที่ทำรายการ
				$doerNote = $res_create["doerNote"]; // หมายเหตุการทำรายการ
				
				// หาชื่อ ผู้ทำรายการ
				$sqlNameUser = pg_query("SELECT \"fullname\"  FROM \"Vfuser\" where \"id_user\" = '$doerID'");
				$fullnameUser = pg_fetch_result($sqlNameUser,0);
				
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
					<td align="center"><?php echo $i; ?></td>
					<td align="center"><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
					<td align="left"><?php echo $fullnameUser; ?></td>
					<td align="center"><?php echo $doerStamp; ?></td>
					<td align="left"><?php echo $doerNote; ?></td>
					<td align="center"><span onclick="javascript:popU('popup_appv_create_seize_asset.php?createID=<?php echo $createID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')" style="cursor:pointer;"><u>ทำรายการ</u></span></td>
			<?php
				echo "</tr>";
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</td>
</tr>	
</table>
<?php
// todo รอเพิ่มประวัติการอนุมัติ
//include("frm_history_limit.php");
?>
</form>
</body>
</html>