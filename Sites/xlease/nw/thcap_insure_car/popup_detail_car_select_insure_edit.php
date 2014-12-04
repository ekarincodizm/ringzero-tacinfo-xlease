<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา
$assetDetailID = pg_escape_string($_GET["assetDetailID"]); // รหัส PK ของสินทรัพย์

// หารายละเอียดรถ
$q = "
		SELECT
			b.\"astypeName\",
			c.\"brand_name\",
			d.\"model_name\",
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"motorcycle_no\" ELSE f.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN a.\"astypeID\" = '10' THEN a.\"productCode\" ELSE f.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"regiser_no\" ELSE f.\"regiser_no\" END AS \"regiser_no\",
			CASE WHEN a.\"astypeID\" = '10' THEN g.\"car_color\" ELSE h.\"car_color\" END AS \"car_color\"
		FROM
			\"thcap_asset_biz_detail\" a
		LEFT JOIN
			\"thcap_asset_biz_astype\" b ON a.\"astypeID\" = b.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" d ON a.\"model\" = d.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" e ON a.\"assetDetailID\" = e.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" f ON a.\"assetDetailID\" = f.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10_color\" g ON e.\"car_color\" = g.\"auto_id\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car_color\" h ON f.\"car_color\" = h.\"auto_id\"
		WHERE
			a.\"assetDetailID\" = '$assetDetailID'
	";
$qr = pg_query($q);
$astypeName = pg_fetch_result($qr,0); // ประเภทสินค้า
$brand_name = pg_fetch_result($qr,1); // ยี่ห้อ
$model_name = pg_fetch_result($qr,2); // รุ่น
$chassis = pg_fetch_result($qr,3); // เลขตัวถัง
$engine = pg_fetch_result($qr,4); // เลขตัวเครื่อง
$regiser_no = pg_fetch_result($qr,5); // ทะเบียนรถ
$car_color = pg_fetch_result($qr,6); // สีรถ

// ตรวจสอบก่อนว่า สามารถ บันทึก ข้อมูล ประกันภัย ภาคบังคับ (พรบ.) ได้หรือไม่
$qry_check_force = pg_query("
						SELECT
							\"ForceID\"
						FROM
							insure.\"thcap_InsureForce\"
						WHERE
							\"contractID\" = '$contractID' AND
							\"assetDetailID\" = '$assetDetailID' AND
							\"Cancel\" = FALSE AND
							\"ForceID\" NOT IN(select \"ForceID\" from insure.\"thcap_InsureForce_request\" where \"editTime\" > '0' and \"appvStatus\" = '9')
					");
$row_check_force = pg_num_rows($qry_check_force);
if($row_check_force == 0)
{
	$can_edit_force = "disabled";
}

// ตรวจสอบก่อนว่า สามารถ บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ ได้หรือไม่
$qry_check_unforce = pg_query("
						SELECT
							\"UnforceID\"
						FROM
							insure.\"thcap_InsureUnforce\"
						WHERE
							\"contractID\" = '$contractID' AND
							\"assetDetailID\" = '$assetDetailID' AND
							\"Cancel\" = FALSE AND
							\"UnforceID\" NOT IN(select \"UnforceID\" from insure.\"thcap_InsureUnforce_request\" where \"editTime\" > '0' and \"appvStatus\" = '9')
					");
$row_check_unforce = pg_num_rows($qry_check_unforce);
if($row_check_unforce == 0)
{
	$can_edit_unforce = "disabled";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
	</script>
 
</head>
<body>
	<center>
		<br/>
		<div style="text-align:center;"><h2>รายละเอียด</h2></div>
		
		<table>
			<tr>
				<td align="right"><b>ประเภทสินค้า :</b></td>
				<td align="left"><?php echo $astypeName; ?></td>
			</tr>
			<tr>
				<td align="right"><b>ยี่ห้อ :</b></td>
				<td align="left"><?php echo $brand_name; ?></td>
			</tr>
			<tr>
				<td align="right"><b>รุ่น :</b></td>
				<td align="left"><?php echo $model_name; ?></td>
			</tr>
			<tr>
				<td align="right"><b>เลขตัวถัง :</b></td>
				<td align="left"><?php echo $chassis; ?></td>
			</tr>
			<tr>
				<td align="right"><b>เลขตัวเครื่อง :</b></td>
				<td align="left"><?php echo $engine; ?></td>
			</tr>
			<tr>
				<td align="right"><b>ทะเบียนรถ :</b></td>
				<td align="left"><?php echo $regiser_no; ?></td>
			</tr>
			<tr>
				<td align="right"><b>สีรถ :</b></td>
				<td align="left"><?php echo $car_color; ?></td>
			</tr>
		</table>
		
		<br/><br/>
		<input type="button" value="แก้ไข ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)" style="cursor:pointer;" <?php echo $can_edit_force; ?> onClick="window.location='frm_force_edit.php?contractID=<?php echo $contractID; ?>&assetDetailID=<?php echo $assetDetailID; ?>';" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ" style="cursor:pointer;" <?php echo $can_edit_unforce; ?> onClick="window.location='frm_unforce_edit.php?contractID=<?php echo $contractID; ?>&assetDetailID=<?php echo $assetDetailID; ?>';" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="ปิด" style="cursor:pointer;" onClick="window.close();" />
	</center>
</body>
</html>