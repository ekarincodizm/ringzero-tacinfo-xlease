<?php
$q = "
		SELECT
			a.\"assetDetailID\",
			e.\"astypeName\",
			f.\"brand_name\",
			g.\"model_name\",
			CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN b.\"astypeID\" = '10' THEN b.\"productCode\" ELSE d.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"regiser_no\"
		FROM
			insure.\"thcap_InsureForce\" a
		LEFT JOIN
			\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_astype\" e ON b.\"astypeID\" = e.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" f ON b.\"brand\" = f.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" g ON b.\"model\" = g.\"modelID\"
		WHERE
			a.\"Cancel\" = FALSE AND
			a.\"contractID\" = '$contractID'

		UNION

		SELECT
			a.\"assetDetailID\",
			e.\"astypeName\",
			f.\"brand_name\",
			g.\"model_name\",
			CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"chassis\", -- เลขตัวถัง
			CASE WHEN b.\"astypeID\" = '10' THEN b.\"productCode\" ELSE d.\"engine_no\" END AS \"engine\", -- เลขตัวเครื่อง
			CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"regiser_no\"
		FROM
			insure.\"thcap_InsureUnforce\" a
		LEFT JOIN
			\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
		LEFT JOIN
			\"thcap_asset_biz_astype\" e ON b.\"astypeID\" = e.\"astypeID\"
		LEFT JOIN
			\"thcap_asset_biz_brand\" f ON b.\"brand\" = f.\"brandID\"
		LEFT JOIN
			\"thcap_asset_biz_model\" g ON b.\"model\" = g.\"modelID\"
		WHERE
			a.\"Cancel\" = FALSE AND
			a.\"contractID\" = '$contractID'
	";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
?>
<div style="width:100%; display:block;">
    <fieldset style="padding:15px 0px; width:100%;">
        <legend><b>เลือกรายการรถที่จะแก้ไข</b></legend>
        <table border="0" cellspacing="1" cellpadding="5" width="100%">
            <tr style="background-color:#4bacc6; height:25px; color:#333; font-weight:bold;">
                <th>รายการ</th>
				<th>ประเภทรถ</th>
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
						<td align=\"center\"><img src=\"../thcap/images/edit.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('popup_detail_car_select_insure_edit.php?contractID=$contractID&assetDetailID=".$rs['assetDetailID']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" style=\"cursor:pointer;\" /></td>
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