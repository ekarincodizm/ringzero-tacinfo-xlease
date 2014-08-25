<?php include('../../config/config.php');
$cancelID=$_GET["cancelID"];

$query_reason = pg_query("select * from \"thcap_asset_cancel\" where \"cancelID\" = '$cancelID'");
$result = pg_fetch_array($query_reason);
$numrows = pg_num_rows($query_reason);
if($numrows >0){
	$reason= $result["reason"]; //หมายเหตุ
	$assetID = $result["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
	$appvID= $result["appvID"];
	$appvStamp= $result["appvStamp"];
	$qry_asset = pg_query("select * from \"thcap_asset_biz\" where \"assetID\" = '$assetID' ");
	while($result_receipt = pg_fetch_array($qry_asset))
		{
			$receipt = $result_receipt["receiptNumber"]; // เลขที่ใบเสร็จ
			$purchaseorder = $result_receipt["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
		}
	$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$appvID'");
	$rs_appv_name = pg_fetch_array($qry_appv_name);
	$numrows = pg_num_rows($qry_appv_name);
	if($numrows >0){
	$appvname = $rs_appv_name["fullname"]; //ชื่อู้อนุมัติการตรวจสอบ
	}	
}
?>
<div style="text-align:center"><h2>เหตุผลในการขอยกเลิก</h2></div>
<div><b>เลขที่สัญญา :</b> <?php echo $receipt;?></div>
<div><b>เลขที่ใบเสร็จ :</b> <?php echo $purchaseorder;?></div>
<div><b>ผู้ที่อนุมัติ:</b> <?php echo $appvname;?></div>
<div><b>วันเวลาที่อนุมัติ:</b> <?php echo $appvStamp;?></div>
<div style="padding-top:10px;width:400px;">
<fieldset><legend><b>เหตุผลในการขอยกเลิก</b></legend>
<textarea cols="60" rows="4" readonly><?php echo $reason;?></textarea>
</fieldset>
</div>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>