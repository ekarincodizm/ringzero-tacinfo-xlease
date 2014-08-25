<?php
include("../../config/config.php");
$term = $_GET['term'];

$q = "select a.*, b.\"full_name\" from \"thcap_asset_biz\" a left join \"VSearchCusCorp\" b on a.\"corpID\" = b.\"CusID\" where a.\"assetID\" in(select distinct \"assetID\" from \"vthcap_asset_biz_detail\")
		and (b.\"full_name\" like '%$term%' or a.\"receiptNumber\" like '%$term%' or a.\"PurchaseOrder\" like '%$term%' or cast(\"afterVat\" as character varying) like '%$term%')";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
	
	$data = array();
	$buffer = array();
	
	if($row==0)
	{
		$data[] = "ไม่พบข้อมูล";
	}
	else
	{
		while($rs = pg_fetch_array($qr))
		{
			$id = $rs['assetID'];
			$saler = $rs['corpName_THA'];
			$receiptNumber = $rs['receiptNumber'];
			$PurchaseOrder = $rs['PurchaseOrder'];
			$afterVat = $rs['afterVat'];
			
			$value = $id;
			if($receiptNumber!="")
			{
				$value.="#เลขที่ใบเสร็จ ".$receiptNumber;
			}
			else if($PurchaseOrder!="")
			{
				$value.="#เลขที่ใบสั่งซื้อ ".$PurchaseOrder;
			}
			$buffer['value'] = $value;
			$label = "ไอดี : ".$id;
			if($saler!="")
			{
				$label.=" :: ผู้ขาย ".$saler;
			}
			if($receiptNumber!="")
			{
				$label.=" :: เลขที่ใบเสร็จ ".$receiptNumber;
			}
			if($PurchaseOrder!="")
			{
				$label.=" :: เลขที่ใบสั่งซื้อ ".$PurchaseOrder;
			}
			if($afterVat!="")
			{
				$label.=" :: ราคารวม vat ".number_format($afterVat,2,".",",")." บาท";
			}
			$buffer['label'] = $label;
			
			$data[] = $buffer;
		}
	}
	$data = array_slice($data,0,100);
	print json_encode($data);
}
?>