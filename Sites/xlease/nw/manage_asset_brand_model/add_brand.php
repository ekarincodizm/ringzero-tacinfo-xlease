<?php
include("../../config/config.php");
include("../function/general_function.php");
include("../function/checknull.php");

$brand = htmlspecialchars($_POST['brand'],ENT_QUOTES);
$astype = htmlspecialchars($_POST['astype'],ENT_QUOTES);
if($astype==""){
	$condition="";
}else{
	$condition="and \"astypeID\"='$astype'";
}

$time = date("Y-m-d H:i:s");
$doer_id = $_SESSION['av_iduser'];

$qr_chk = pg_query("select \"brand_name\" from \"thcap_asset_biz_brand\" where \"brand_name\"='$brand' $condition");
$row_chk = pg_num_rows($qr_chk);
if($row_chk!=0)
{
	echo 2;
}
else
{
	$astype=checknull($astype);
	$qr = pg_query("insert into \"thcap_asset_biz_brand\"(\"brand_name\",\"doerStamp\",\"doerID\",\"astypeID\") values('$brand','$time','$doer_id',$astype)");
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