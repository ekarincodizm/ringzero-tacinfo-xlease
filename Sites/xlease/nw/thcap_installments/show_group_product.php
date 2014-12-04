<?php
$q = "select \"brand_name\",\"model_name\",count(*) as sum_itm from (select * from \"thcap_contract_asset\" as a 
		inner join \"thcap_asset_biz_detail\" as b on a.\"assetDetailID\"=b.\"assetDetailID\"
		left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
		left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
		where a.\"contractID\"='$contractID') as all_data
		group by \"brand_name\",\"model_name\"";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
?>
<div style="width:944px; display:block;">
    <fieldset style="padding:15px 0px; width:944px;">
        <legend><b>รายการสินค้า</b></legend>
        <table border="0" cellspacing="1" cellpadding="5" width="942">
            <tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold;">
                <td align="center" style="width:10%;">ลำดับ</td>
                <td align="center" style="width:25%;">ยี่ห้อ</td>
                <td align="center" style="width:25%;">รุ่น</td>
                <td align="center" style="width:25%;">จำนวน</td>
                <td align="center" style="width:14%;">รายละเอียด</td>
            </tr>
            <?php
			if($row==0)
			{
				echo "
					<tr style=\"background-color:#d2eaf1; height:25px; color:#333;\">
						<td colspan=\"5\" align=\"center\">*** ไม่มีรายการสินค้า ***</td>
					</tr>
				";
			}
			else
			{
				$i = 0;
				$all_itm = 0;
				while($rs = pg_fetch_array($qr))
				{
					$i++;
					$sum_itm = $rs['sum_itm'];
					$all_itm = $all_itm+$sum_itm;
					if($i%2==0)
					{
						echo "<tr style=\"background-color:#D5EFFD; height:25px; color:#333;\">";
					}
					else
					{
						echo "<tr style=\"background-color:#EDF8FE; height:25px; color:#333;\">";
					}
					echo "
						<td align=\"center\">".$i."</td>
						<td align=\"center\">".$rs['brand_name']."</td>
						<td align=\"center\">".$rs['model_name']."</td>
						<td align=\"center\">".number_format($sum_itm,0,'.',',')."</td>
						<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap_installments/show_list_product.php?contractID=$contractID&brand=".$rs['brand_name']."&model=".$rs['model_name']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')\" style=\"cursor:pointer;\" /></td>
					</tr>
					";
				}
				if($row!=0)
				{
					echo "
						<tr style=\"background-color:#4bacc6; height:25px; color:#333; font-weight:bold;\">
							<td colspan=\"3\" align=\"center\">ยอดรวม</td>
							<td align=\"center\">".number_format($all_itm,0,'.',',')."</td>
							<td></td>
						</tr>
					";
				}
			}
			?>
        </table>
    </fieldset>
</div>
<?php
}
?>