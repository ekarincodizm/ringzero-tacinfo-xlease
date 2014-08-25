<?php
include("../../config/config.php");
$brand_name = $_POST['brand_name'];
$selectModel = $_POST['selectModel'];

$qr1 = pg_query("select \"brandID\" from \"thcap_asset_biz_brand\" where \"brandID\"='$brand_name'");
if($qr1)
{
	$row1 = pg_num_rows($qr1);
	if($row1!=0)
	{
		$rs1 = pg_fetch_array($qr1);
		$brandid = $rs1['brandID'];
	}
}
$qr = pg_query("select * from \"thcap_asset_biz_model\" where \"brandID\"='$brandid' order by \"model_name\" asc");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		echo "<option value=\"\">--------- เลือกรุ่น ---------</option>";
		while($rs = pg_fetch_array($qr))
		{
			$modelID = $rs['modelID'];
			$model_name = $rs['model_name'];
			
			if($modelID == $selectModel)
			{
				echo "<option value=\"$modelID\" selected>$model_name</option>";
			}
			else
			{
				echo "<option value=\"$modelID\">$model_name</option>";
			}
		}
	}
}
?>