<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql = "select distinct \"assetID\",\"receiptNumber\",\"PurchaseOrder\" from  \"vthcap_asset_biz_detail_active\"
		WHERE \"assetID\" not in(select distinct \"assetID\" from \"thcap_asset_cancel\" where \"Approved\" is null) 
		and \"receiptNumber\" like '%$term%' or \"PurchaseOrder\" like '%$term%'";

$results=pg_query($sql);
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results ))
{
	$assetID = trim($row["assetID"]);
	$receiptNumber = $row["receiptNumber"];
	$PurchaseOrder = $row['PurchaseOrder'];
	
	$dt_val = $assetID;
	if($PurchaseOrder!="")
	{
		$dt_val.="#".$PurchaseOrder;
	}
    if($receiptNumber!="")
	{
		$dt_val.="#".$receiptNumber;
	}
	
	$dt['value'] = $dt_val;
	$label = "$assetID";
	if($receiptNumber!="")
	{
		$label.=" :: เลขที่ใบเสร็จ ".$receiptNumber;
	}
	if($PurchaseOrder!="")
	{
		$label.=" :: เลขที่ใบสั่งซื้อ ".$PurchaseOrder;
	}
	$dt['label'] = $label;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>