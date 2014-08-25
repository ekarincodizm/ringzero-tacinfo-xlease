<?php
include("../../config/config.php");

$contractID = $_GET['contractID'];
$showall = $_GET['showall'];

IF($showall == 'true'){ //หากต้องการแสดงสินค้าทั้งหมดของเลขที่สัญญานั้นๆ
	$q = "	select * from \"thcap_contract_asset\" as a 
			inner join \"thcap_asset_biz_detail\" as b on a.\"assetDetailID\"=b.\"assetDetailID\"
			left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
			left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
			where a.\"contractID\"='$contractID' 
			order by a.\"assetDetailID\" ";
}else{ //แสดงสินค้าเฉพาะ brand และ model ที่ต้องการ

$brand = $_GET['brand'];
$model = $_GET['model'];

if($brand != "" && $model != "")
{
	$whereBrandModel = "and d.\"brand_name\"='$brand' and c.\"model_name\"='$model'";
}
elseif($brand != "" && $model == "")
{
	$whereBrandModel = "and d.\"brand_name\"='$brand' and c.\"model_name\" is null";
}
elseif($brand == "" && $model != "")
{
	$whereBrandModel = "and d.\"brand_name\" is null and c.\"model_name\"='$model'";
}
else
{
	$whereBrandModel = "and d.\"brand_name\" is null and c.\"model_name\" is null";
}

$q = "select * from \"thcap_contract_asset\" as a 
inner join \"thcap_asset_biz_detail\" as b on a.\"assetDetailID\"=b.\"assetDetailID\"
left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
where a.\"contractID\"='$contractID' $whereBrandModel";
}
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการสินค้า - เลขที่สัญญา <?php echo $contractID; ?></title>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body style="padding:0px; margin:0px; font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#444;">
<div align="center">
	<div style="width:900px; display:block;">
    	<div style="border-bottom:solid 1px #cbcbcb; font-size:12px; font-weight:bold; height:30px; margin:5px 2px 15px 0px;">รายการสินค้า :: เลขที่สัญญา <?php echo $contractID; ?></div>
    	<table border="0" cellpadding="5" cellspacing="1" width="900" style="font-size:11px;">
        	<tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold; font-size:12px;">
            	<td align="center" style="width:10%">ลำดับ</td>
                <td align="center" style="width:13%">เลขที่ใบแจ้งหนี้</td>
                <td align="center" style="width:12%">เลขที่ใบเสร็จ</td>
                <td align="center" style="width:13%">ยี่ห้อ</td>
                <td align="center" style="width:13%">รุ่น</td>
                <td align="center" style="width:13%">รหัสสินค้า(serial)</td>
                <td align="center" style="width:13%">ราคา/หน่วย</td>
                <td align="center" style="width:12%">VAT (บาท)</td>
            </tr>
            <?php
			if($row==0)
			{
				echo "
					<tr style=\"background-color:#d2eaf1; height:25px; color:#333;\">
						<td colspan=\"8\" align=\"center\">*** ไม่มีรายการสินค้า ***</td>
					</tr>
				";
			}
			else
			{
				$i = 0;
				$number = 1;
				$all_ppu = 0;
				$all_vat = 0;
				while($rs=pg_fetch_array($qr))
				{
					//$receiptNumber = $rs['receiptNumber'];
					$assetID = $rs['assetID'];
					$ppu = $rs['pricePerUnit'];
					$vat = $rs['VAT_value'];
					
					$all_ppu = $all_ppu+$ppu;
					$all_vat = $all_vat+$vat;
					
					
					$q1 = "select \"assetID\",\"PurchaseOrder\" from \"thcap_asset_biz\" where \"assetID\"='$assetID'";
					$qr1 = pg_query($q1);
					if($qr1)
					{
						$row1 = pg_num_rows($qr1);
						if($row1!=0)
						{
							$rs1 = pg_fetch_array($qr1);
							$PurchaseOrder = $rs1['PurchaseOrder'];
							//$assetID = $rs1['assetID'];
						}
					}
					if($i%2==0)
					{
						echo "<tr style=\"background-color:#d2eaf1; height:25px; color:#333;\">";
					}
					else
					{
						echo "<tr style=\"background-color:#fff; height:25px; color:#333;\">";
					}
					echo "
							<td align=\"center\">$number</td>
							<td align=\"center\"><span style=\"color:#0387ad; text-decoration:underline; cursor:pointer; font-weight:bold;\" onclick=\"javascript:popU('show_receipt.php?assetID=".$assetID."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')\">".$PurchaseOrder."</span></td>
							<td align=\"center\"><span style=\"color:#0387ad; text-decoration:underline; cursor:pointer; font-weight:bold;\" onclick=\"javascript:popU('show_receipt.php?assetID=".$assetID."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')\">".$rs['receiptNumber']."</span></td>
							<td align=\"center\">".$rs['brand_name']."</td>
							<td align=\"center\">".$rs['model_name']."</td>
							<td align=\"center\">".$rs['productCode']."</td>
							<td align=\"right\">".number_format($ppu,2,'.',',')."</td>
							<td align=\"right\">".number_format($vat,2,'.',',')."</td>
						</tr>
					";
					$number++;
					$i++;
				}
				if($row!=0)
				{
					echo "
						<tr style=\"background-color:#4bacc6; height:25px; color:#333; font-weight:bold; font-size:12px;\">
							<td colspan=\"6\" align=\"center\">ยอดรวม</td>
							<td align=\"right\">".number_format($all_ppu,2,'.',',')."</td>
							<td align=\"right\">".number_format($all_vat,2,'.',',')."</td>
						</tr>
					";
				}
			}
			?>
        </table>
    </div>
</div>
</body>
</html>
<?php
}
?>