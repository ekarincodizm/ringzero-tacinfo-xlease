<?php
include("../../../config/config.php");
set_time_limit(0);
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php

pg_query("BEGIN WORK");
$status = 0;

// หารายการที่ เลขตัวถัง ไม่ซ้ำ
$qry_fidNull = pg_query("select \"receiptNumber\", \"doerStamp\" from \"thcap_asset_biz_detail_temp\"
						where \"tempAssetID\" is null and \"receiptNumber\" is not null
						group by \"receiptNumber\", \"doerStamp\" ");
while($res_fidNull = pg_fetch_array($qry_fidNull))
{
	$receiptNumber = $res_fidNull["receiptNumber"]; // เลขที่ใบเสร็จ
	$doerStamp = $res_fidNull["doerStamp"];
	
	$qry_sid = pg_query("select \"tempID\" from \"thcap_asset_biz_temp\" where \"receiptNumber\" = '$receiptNumber' and \"doerStamp\" = '$doerStamp' limit 1 ");
	list($tempID) = pg_fetch_array($qry_sid); // รหัส temp
	
	$qry_up = "update \"thcap_asset_biz_detail_temp\" set \"tempAssetID\" = '$tempID' where \"receiptNumber\" = '$receiptNumber' and \"doerStamp\" = '$doerStamp' ";
	if($result = pg_query($qry_up))
	{}
	else
	{
		$status++;
		echo "select \"tempID\" from \"thcap_asset_biz_temp\" where \"receiptNumber\" = '$receiptNumber' and \"doerStamp\" = '$doerStamp' limit 1";
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2>SUCCESS</h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>ERROR</h2></center>";
}
?>