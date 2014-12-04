<?php
$q = "
		SELECT
			a.\"assetDetailID\",
			b.\"astypeName\",
			c.\"brand_name\",
			d.\"model_name\",
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"motorcycle_no\" ELSE f.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN a.\"astypeID\" = '10' THEN a.\"productCode\" ELSE f.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN a.\"astypeID\" = '10' THEN e.\"regiser_no\" ELSE f.\"regiser_no\" END AS \"regiser_no\"
		FROM
			\"thcap_asset_biz_detail\" a
		LEFT JOIN
			\"thcap_asset_biz_astype\" b ON a.\"astypeID\" = b.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" d ON a.\"model\" = d.\"modelID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" e ON a.\"assetDetailID\" = e.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" f ON a.\"assetDetailID\" = f.\"assetDetailID\"
		WHERE
			b.\"astypeName\" LIKE 'รถ%' AND
			a.\"assetDetailID\" IN(select \"assetDetailID\" from \"thcap_contract_asset\" where \"contractID\" = '$contractID')
	";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
?>
<div style="width:100%; display:block;">
    <fieldset style="padding:15px 0px; width:100%;">
        <legend><b>เลือกรายการรถ</b></legend>
        <table border="0" cellspacing="1" cellpadding="5" width="100%">
            <tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold;">
                <th>รายการ</th>
				<th>ประเภทสินค้า</th>
                <th>ยี่ห้อ</th>
                <th>รุ่น</th>
				<th>เลขตัวถัง</th>
				<th>เลขเครื่อง</th>
				<th>ทะเบียนรถ</th>
                <th>ทำรายการ</th>
            </tr>
            <?php
			if($row==0)
			{
				echo "
					<tr style=\"background-color:#d2eaf1; height:25px; color:#333;\">
						<td colspan=\"8\" align=\"center\">*** ไม่มีรายการรถ ***</td>
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
						<td align=\"center\">".$rs['astypeName']."</td>
						<td align=\"center\">".$rs['brand_name']."</td>
						<td align=\"center\">".$rs['model_name']."</td>
						<td align=\"center\">".$rs['chassis']."</td>
						<td align=\"center\">".$rs['engine']."</td>
						<td align=\"center\">".$rs['regiser_no']."</td>
						<td align=\"center\"><img src=\"../thcap/images/edit.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('popup_detail_car_select_insure.php?contractID=$contractID&assetDetailID=".$rs['assetDetailID']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" style=\"cursor:pointer;\" /></td>
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