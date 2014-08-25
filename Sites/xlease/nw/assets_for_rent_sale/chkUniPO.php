<?php
include("../../config/config.php");

$buyer = $_POST["buyer"];
$PurchaseOrder = $_POST["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
$qrychkPO = pg_query("select \"PurchaseOrder\" from \"thcap_asset_biz\" where \"compID\" = '$buyer' and \"PurchaseOrder\" = '$PurchaseOrder' and \"ActiveStatus\" = '1'
					union
					select \"PurchaseOrder\" from \"thcap_asset_biz_temp\" where \"compID\" = '$buyer' and \"PurchaseOrder\" = '$PurchaseOrder' and \"Approved\" is null");
$numrows = pg_num_rows($qrychkPO);

if($numrows > 0)
{
	echo "duplicatePO";
}
else
{
	echo "NOduplicate";
}
?>