<?php
include("../../config/config.php");

$term = pg_escape_string($_GET['term']);

//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("
				SELECT
					distinct
					a.\"contractID\",
					c.\"full_name\",
					CASE WHEN d.\"astypeID\" = '10' THEN e.\"motorcycle_no\" ELSE f.\"frame_no\" END AS \"chassis\",
					CASE WHEN d.\"astypeID\" = '10' THEN e.\"regiser_no\" ELSE f.\"regiser_no\" END AS \"regiser_no\"
				FROM
					\"thcap_contract_asset\" a
				LEFT JOIN
					\"thcap_ContactCus\" b ON a.\"contractID\" = b.\"contractID\" AND b.\"CusState\" = '0'
				LEFT JOIN
					\"VSearchCusCorp\" c ON b.\"CusID\" = c.\"CusID\"
				LEFT JOIN
					\"thcap_asset_biz_detail\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_10\" e ON d.\"assetDetailID\" = e.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_car\" f ON d.\"assetDetailID\" = f.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_astype\" g ON d.\"astypeID\" = g.\"astypeID\"
				WHERE
					g.\"astypeName\" LIKE 'รถ%' AND
					(
						a.\"contractID\" LIKE '%$term%' OR
						c.\"full_name\" LIKE '%$term%' OR
						CASE WHEN d.\"astypeID\" = '10' THEN e.\"motorcycle_no\" LIKE '%$term%' ELSE f.\"frame_no\" LIKE '%$term%' END OR
						CASE WHEN d.\"astypeID\" = '10' THEN e.\"regiser_no\" LIKE '%$term%' ELSE f.\"regiser_no\" LIKE '%$term%' END
					)
				ORDER BY
					\"contractID\"
			");
$numrows = pg_num_rows($sql);
if($numrows > 0)
{
	while($res = pg_fetch_array($sql))
	{
		$contractID = $res["contractID"]; // เลขที่สัญญา
		$full_name = $res["full_name"]; // ชื่อลูกค้า
		$chassis = $res["chassis"]; // เลขตัวถัง
		$regiser_no = $res["regiser_no"]; // ทะเบียนรถ
		
		$sData = "$contractID#$chassis#$full_name#$regiser_no";
		
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$sData");		
		
		$dt['value'] = $contractID;
		$dt['label'] = $display_name;
		$matches[] = $dt;
	}				
}

if($matches=="")
{
	$matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>