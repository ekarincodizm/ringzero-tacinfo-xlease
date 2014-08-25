<?php
include("../../config/config.php");
include("../function/general_function.php");

$brandid = $_POST['brandid'];
$model = htmlspecialchars($_POST['model'],ENT_QUOTES);

$time = date("Y-m-d H:i:s");
$doer_id = $_SESSION['av_iduser'];

$qr_chk = pg_query("select \"model_name\" from \"thcap_asset_biz_model\" where \"brandID\"='$brandid' and \"model_name\"='$model'");
$row_chk = pg_num_rows($qr_chk);
if($row_chk!=0)
{
	echo 2;
}
else
{
	$qr = pg_query("insert into \"thcap_asset_biz_model\"(\"brandID\",\"model_name\",\"doerStamp\",\"doerID\") values('$brandid','$model','$time','$doer_id')");
	if($qr)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
?>