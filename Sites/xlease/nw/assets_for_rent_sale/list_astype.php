<?php
include("../../config/config.php");
$brand_name = $_POST['brand_name'];

$qry_array = pg_query("select \"astypeID\" from thcap_asset_biz_default_add where \"brandID\" = '$brand_name'");
	$already_fav = pg_num_rows($qry_array);
	
	$qry_assetsType = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeStatus\" = '1' order by \"astypeName\" ");
	
		if($already_fav>0){
			$res_array = pg_fetch_result($qry_array ,0);
			$qyr_Fav = pg_query("select ta_array1d_popularity('$res_array','2')");
			$res_Fav = pg_fetch_result($qyr_Fav,0);
		
				while($res_assetsType=pg_fetch_array($qry_assetsType))
				{
					$astypeID = trim($res_assetsType["astypeID"]);
					$astypeName = trim($res_assetsType["astypeName"]);
					
					if($astypeID == $res_Fav){
						echo "<option value=\"$astypeID\" selected>$astypeName</option>";
					}else{
						echo "<option value=\"$astypeID\">$astypeName</option>";
					}		
				}
								
		}else{
			
			echo "<option value=\"\">-เลือกประเภทสินทรัพย์-</option>";
			while($res_assetsType=pg_fetch_array($qry_assetsType))
				{
					$astypeID = trim($res_assetsType["astypeID"]);
					$astypeName = trim($res_assetsType["astypeName"]);
					
					echo "<option value=\"$astypeID\">$astypeName</option>";
		
				}
		}