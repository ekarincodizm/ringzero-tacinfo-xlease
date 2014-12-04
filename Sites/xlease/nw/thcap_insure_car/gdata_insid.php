<?php
include("../../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$term = pg_escape_string($_GET["term"]);

//ประกันคุ้มภาคสมัครใจ
$qry_dc=pg_query("
					SELECT
						a.\"UnforceID\",
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" ELSE d.\"frame_no\" END AS \"C_CARNUM\",
						CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" ELSE d.\"regiser_no\" END AS \"C_REGIS\"
					FROM
						insure.\"thcap_InsureUnforce\" a
					LEFT JOIN
						\"thcap_asset_biz_detail\" b ON a.\"assetDetailID\" = b.\"assetDetailID\"
					LEFT JOIN
						\"thcap_asset_biz_detail_10\" c ON a.\"assetDetailID\" = c.\"assetDetailID\"
					LEFT JOIN
						\"thcap_asset_biz_detail_car\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
					WHERE
						a.\"InsID\" IS NULL AND
						a.\"NetPremium\" = '0.00' AND
						a.\"InsDate\" IS NULL AND
						(
							CASE WHEN b.\"astypeID\" = '10' THEN c.\"motorcycle_no\" LIKE '%$term%' ELSE d.\"frame_no\" LIKE '%$term%' END OR
							CASE WHEN b.\"astypeID\" = '10' THEN c.\"regiser_no\" LIKE '%$term%' ELSE d.\"regiser_no\" LIKE '%$term%' END
						)
					ORDER BY
						a.\"UnforceID\" DESC
				"); 
                   
$nrows=pg_num_rows($qry_dc);               
while($res_dc=pg_fetch_array($qry_dc)){
    $UnforceID = $res_dc["UnforceID"];
    $C_CARNUM = $res_dc["C_CARNUM"];
    $C_REGIS = $res_dc["C_REGIS"];

	$name = str_replace("'", "\'"," ".$UnforceID.""." / ".$C_CARNUM.""." / ".$C_REGIS);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
	
	$dt['value'] = $UnforceID."#".$C_CARNUM."#".$C_REGIS;
	$dt['label'] = $display_name;
    $matches[] = $dt;
} 

if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>