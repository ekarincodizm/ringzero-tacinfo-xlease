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
    <title>(THCAP) ตรวจสอบรับทรัพย์สินรับคืน-ยึดคืน</title>
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
		<div class="header"><h1>(THCAP) ตรวจสอบรับทรัพย์สินรับคืน-ยึดคืน</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr bgcolor="#FFFFFF">
					<td colspan="11" align="left" style="font-weight:bold;">(THCAP) ตรวจสอบรับทรัพย์สินรับคืน-ยึดคืน</td>
				</tr>
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<th>ลำดับ</th>
					<th>เลขที่สัญญา</th>
					<th>ID สินทรัพย์</th>
					<th>รหัสสินค้าหลัก</th>
					<th>รหัสสินค้ารอง</th>
					<th>ยี่ห้อ</th>
					<th>รุ่น</th>
					<th>วันที่ยึดสินทรัพย์เข้าบริษัท</th>
					<th>ผู้บันทึกยึด</th>
					<th>วันเวลาที่บันทึกยึด</th>
					<th>ทำรายการ</th>
				</tr>
				<?php
				$qry_create = pg_query("select a.\"seizeID\", a.\"createID\", a.\"assetDetailID\", a.\"seizeStatus\", b.\"contractID\", a.\"seizeDate\", a.\"doerID\", a.\"doerStamp\"
										from \"thcap_seize_asset\" a, \"thcap_create_seize_asset\" b
										where a.\"createID\" = b.\"createID\"
										and a.\"seizeStatus\" = '8'
										order by a.\"doerStamp\" ");
				$nub = pg_num_rows($qry_create);
				$i = 0;
				while($res_create = pg_fetch_array($qry_create))
				{
					$i++;
					$seizeID = $res_create["seizeID"];
					$createID = $res_create["createID"];
					$assetDetailID = $res_create["assetDetailID"];
					$seizeStatus = $res_create["seizeStatus"];
					$contractID = $res_create["contractID"];
					$seizeDate = $res_create["seizeDate"];
					$doerID = $res_create["doerID"];
					$doerStamp = $res_create["doerStamp"];
					
					// หาข้อมูลสินทรัพย์
					$qry_asset_detail = pg_query("select * from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
					$res_asset_detail = pg_fetch_array($qry_asset_detail);
					$productCode = $res_asset_detail["productCode"]; // รหัสสินค้า
					$secondaryID = $res_asset_detail["secondaryID"]; // รหัสสินค้ารอง
					$brand = $res_asset_detail["brand"]; // ยี่ห้อ
					$model = $res_asset_detail["model"]; // รุ่น
					
					// หาชื่อยี่ห้อ
					$qry_brand = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brandID\" = '$brand' ");
					$brand_name = pg_fetch_result($qry_brand,0);
					
					// หาชื่อรุ่น
					$qry_model = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"modelID\" = '$model' ");
					$model_name = pg_fetch_result($qry_model,0);
					
					// หาชื่อผู้บันทึกยึด
					$qry_fullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
					$fullname = pg_fetch_result($qry_fullname,0);
					
					if($i%2==0){
						echo "<tr class=\"odd\" align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
				?>
						<td align="center"><?php echo $i; ?></td>
						<td align="center"><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
						<td align="center"><?php echo $assetDetailID; ?></td>
						<td align="center"><?php echo $productCode; ?></td>
						<td align="center"><?php echo $secondaryID; ?></td>
						<td align="center"><?php echo $brand_name; ?></td>
						<td align="center"><?php echo $model_name; ?></td>
						<td align="center"><?php echo $seizeDate; ?></td>
						<td align="left"><?php echo $fullname; ?></td>
						<td align="center"><?php echo $doerStamp; ?></td>
						<td align="center"><img src="../thcap/images/detail.gif" onClick="popU('popup_appv_record_seize_asset.php?seizeID=<?php echo $seizeID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700');" style="cursor:pointer;"></td>
				<?php
					echo "</tr>";
				} //end while
				if($nub == 0){
					echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
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