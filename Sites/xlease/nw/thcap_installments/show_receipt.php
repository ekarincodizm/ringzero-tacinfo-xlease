<?php
include("../../config/config.php");

$assetID = $_GET['assetID'];

$q1 = "select \"receiptNumber\",\"PurchaseOrder\" from \"thcap_asset_biz\" where \"assetID\"='$assetID'";
$qr1 = pg_query($q1);
if($qr1)
{
	$rs1 = pg_fetch_array($qr1);
	$receiptNumber = $rs1['receiptNumber'];
	$PurchaseOrder = $rs1['PurchaseOrder'];
}

$q = "select * from \"thcap_asset_biz_detail\" a
left join \"thcap_asset_biz_model\" c ON a.\"model\" = c.\"modelID\"
left join \"thcap_asset_biz_brand\" d ON a.\"brand\" = d.\"brandID\"
 where a.\"assetID\"='$assetID'";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการสินค้า</title>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body style="padding:0px; margin:0px; font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#444;">
<div align="center">
	<div style="width:900px; display:block;">
    	<div style="border-bottom:solid 1px #cbcbcb; font-size:12px; font-weight:bold; height:30px; margin:5px 2px 15px 0px;">รายการสินค้า<?php if($receiptNumber!=""){ echo " :: เลขที่ใบเสร็จ $receiptNumber"; } if($PurchaseOrder!=""){ echo " :: เลขที่ใบสั่งซื้อ $PurchaseOrder"; } ?></div>
    	<table border="0" cellpadding="5" cellspacing="1" width="900" style="font-size:11px;">
        	<tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold; font-size:12px;">
            	<td align="center" style="width:10%">ลำดับ</td>
                <td align="center" style="width:15%">ยี่ห้อ</td>
                <td align="center" style="width:15%">รุ่น</td>
                <td align="center" style="width:15%">รหัสสินค้า(serial)</td>
                <td align="center" style="width:14%">สถานะ</td>
                <td align="center" style="width:15%">ราคา/หน่วย</td>
                <td align="center" style="width:15%">VAT(บาท)</td>
            </tr>
            <?php
			if($row==0)
			{
				echo "
					<tr style=\"background-color:#d2eaf1; height:25px; color:#333;\">
						<td colspan=\"7\" align=\"center\">*** ไม่มีรายการสินค้า ***</td>
					</tr>
				";
			}
			else
			{
				$i = 0;
				$all_ppu = 0;
				$all_vat = 0;
				while($rs=pg_fetch_array($qr))
				{
					$ppu = $rs['pricePerUnit'];
					$vat = $rs['VAT_value'];
					
					$all_ppu = $all_ppu+$ppu;
					$all_vat = $all_vat+$vat;
					
					if($rs['materialisticStatus']=="0")
					{
						$rs['materialisticStatus'] = "ไม่มีสินค้าแล้ว";
					}
					else if($rs['materialisticStatus']=="1")
					{
						$rs['materialisticStatus'] = "มีสินค้าพร้อมใช้";
					}
					else if($rs['materialisticStatus']=="2")
					{
						$rs['materialisticStatus'] = "สินค้าถูกนำไปใช้อยู่";
					}
					//$number = $i+1;
					$i++;
					if($i%2==0)
					{
						echo "<tr style=\"background-color:#D5EFFD; height:25px; color:#333;\">";
					}
					else
					{
						echo "<tr style=\"background-color:#EDF8FE; height:25px; color:#333;\">";
					}
					echo "
							<td align=\"center\">$i</td>
							<td align=\"center\">".$rs['brand_name']."</td>
							<td align=\"center\">".$rs['model_name']."</td>
							<td align=\"center\">".$rs['productCode']."</td>
							<td align=\"center\">".$rs['materialisticStatus']."</td>
							<td align=\"right\">".number_format($ppu,2,'.',',')."</td>
							<td align=\"right\">".number_format($vat,2,'.',',')."</td>
						</tr>
					";
				}
				if($row!=0)
				{
					echo "
						<tr style=\"background-color:#4bacc6; height:25px; color:#333; font-weight:bold; font-size:12px;\">
							<td colspan=\"5\" align=\"center\">ยอดรวม</td>
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