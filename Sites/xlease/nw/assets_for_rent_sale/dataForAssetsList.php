<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
$selectType = $_GET["selectType"];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<option value="">-เลือกประเภทสินทรัพย์-</option>
<?php
$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
while($res_assetsType=pg_fetch_array($qry_assetsType))
{
	$astypeID = trim($res_assetsType["astypeID"]);
	$astypeName = trim($res_assetsType["astypeName"]);
?>
	<option value="<?php echo $astypeID; ?>" <?php if($selectType == $astypeID){echo "selected";} ?>><?php echo $astypeName; ?></option>
<?php
}
?>