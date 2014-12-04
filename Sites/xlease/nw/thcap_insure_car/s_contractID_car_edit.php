<?php
include("../../config/config.php");

$term = pg_escape_string($_GET['term']);

//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("
				SELECT
					a.\"contractID\",
					CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"chassis\",
					CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"regiser_no\"
				FROM
					insure.\"thcap_InsureForce\" a
				LEFT JOIN
					\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
				WHERE
					a.\"Cancel\" = FALSE AND
					(
						a.\"contractID\" LIKE '%$term%' OR
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" LIKE '%$term%' ELSE d.\"frame_no\" LIKE '%$term%' END OR
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" LIKE '%$term%' ELSE d.\"regiser_no\" LIKE '%$term%' END
					)

				UNION

				SELECT
					a.\"contractID\",
					CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"chassis\",
					CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"regiser_no\"
				FROM
					insure.\"thcap_InsureUnforce\" a
				LEFT JOIN
					\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
				LEFT JOIN
					\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
				WHERE
					a.\"Cancel\" = FALSE AND
					(
						a.\"contractID\" LIKE '%$term%' OR
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" LIKE '%$term%' ELSE d.\"frame_no\" LIKE '%$term%' END OR
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" LIKE '%$term%' ELSE d.\"regiser_no\" LIKE '%$term%' END
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
		$chassis = $res["chassis"]; // เลขตัวถัง
		$regiser_no = $res["regiser_no"]; // ทะเบียนรถ
		
		$sData = "$contractID/$chassis/$regiser_no";
		
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