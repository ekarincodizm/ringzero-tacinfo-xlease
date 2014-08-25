<?php
include("../../config/config.php");

$assetDetailID = $_GET['assetDetailID'];

$q = "select * from \"thcap_asset_biz\" as a 
	inner join \"thcap_asset_biz_detail\" as b on a.\"assetID\"=b.\"assetID\" 
	left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
	left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
	where b.\"assetDetailID\"='$assetDetailID'";
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

function print_asset_report(assetid,realdata){
	if(assetid=='')
	{
		alert('ผิดพลาด : ไม่พบรหัสสินทรัพย์');
	}
	else
	{
		popU('../view_assets_for_rent_sale/print_asset_report_pdf.php?assetid='+assetid+'&realdata='+realdata,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=800');
	}
}
</script>
</head>

<body style="padding:0px; margin:0px; font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#444;">
<div align="center">
	<div style="width:900px; display:block;">
    	<div style="border-bottom:solid 1px #cbcbcb; font-size:12px; font-weight:bold; height:30px; margin:5px 2px 15px 0px;"><br>รายการสินค้า</div>
    	<table border="0" cellpadding="5" cellspacing="1" width="900" style="font-size:11px;">
        	<tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold; font-size:12px;">
            	<td align="center" style="width:10%">ลำดับ</td>
                <td align="center" style="width:13%">เลขที่ใบแจ้งหนี้</td>
                <td align="center" style="width:12%">เลขที่ใบเสร็จ</td>
                <td align="center" style="width:13%">ยี่ห้อ</td>
                <td align="center" style="width:13%">รุ่น</td>
                <td align="center" style="width:13%">ราคา/หน่วย</td>
                <td align="center" style="width:13%">รหัสสินค้า(serial)</td>
                <td align="center" style="width:12%">VAT (บาท)</td>
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
				while($rs=pg_fetch_array($qr))
				{
					$receiptNumber = $rs['receiptNumber'];
					$assetID = $rs['assetID'];
					
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
					$number = $i+1;
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
							<td align=\"center\"><span style=\"color:#0387ad; text-decoration:underline; cursor:pointer; font-weight:bold;\" onclick=\"javascript:popU('show_receipt_temp.php?assetID=".$assetID."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=910,height=700')\">".$PurchaseOrder."</span></td>
							<td align=\"center\"><span style=\"color:#0387ad; text-decoration:underline; cursor:pointer; font-weight:bold;\" onclick=\"javascript:popU('show_receipt_temp.php?assetID=".$assetID."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=910,height=700')\">".$receiptNumber."</span></td>
							<td align=\"center\">".$rs['brand_name']."</td>
							<td align=\"center\">".$rs['model_name']."</td>
							<td align=\"right\">".number_format($rs['pricePerUnit'],2,'.',',')."</td>
							<td align=\"center\">".$rs['productCode']."</td>
							<td align=\"right\">".number_format($rs['VAT_value'],2,'.',',')."</td>
						</tr>
					";
				}
			}
			?>
        </table>
    </div>
	<div>
		<?php
			$fromApproveContract=1; //กำหนดค่าให้ทราบว่าจะแสดงรายละเอียดจากหน้านี้
			include("../view_assets_for_rent_sale/show_appvDetail.php");
		?>
	</div>
</div>
</body>
</html>
<?php
}
?>