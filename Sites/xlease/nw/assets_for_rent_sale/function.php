<?php
function SetFavoriteAssetType($brandID,$astypeID){
	$data = $astypeID;
	//ตรวจสอบว่ามี User คนนี้อยู่ในตาราง Favorite หรือยัง
	$Qry_brand = pg_query("select \"brandID\" from thcap_asset_biz_default_add where \"brandID\" = '$brandID'");
	$check_brand = pg_num_rows($Qry_brand);
	if($check_brand>0){
		$qrytype = "update"; //ถ้ามีให้ update
	} else {
		$qrytype = "insert"; //ไม่มีให้ insert
	}
	//process การ insert
	if (trim($qrytype)=="insert"){
	
		$dataSet = "{".$data."}";
		$qry = "insert into thcap_asset_biz_default_add(\"brandID\",\"astypeID\") values ('$brandID','$dataSet')";
		pg_query($qry);
	}
	//process การ update
	if(trim($qrytype)=="update"){
	
		$select_data = pg_query("select \"astypeID\" from thcap_asset_biz_default_add where \"brandID\" = '$brandID'");
		$res_favor = pg_fetch_result($select_data,0);
		
			$count_array = pg_query("select ta_array1d_count('$res_favor')");
			$res_count = pg_fetch_result($count_array,0);
		
			$get_array = pg_query("select ta_array1d_get('$res_favor',0,$res_count) as favorite");
		
			if($res_count<30){
				$i=0;
				while($res_fav = pg_fetch_array($get_array)){
					$i++;
					$favorite = $res_fav["favorite"];
					if($i==1){
						$favoriteSet = $favorite;
					} else {
						$favoriteSet = $favoriteSet.",".$favorite;
					}
				}
				$dataSet = "{".$favoriteSet.",".$data."}";
			} else {
				$i=0;
				while($res_fav = pg_fetch_array($get_array)){
					$i++;
					$favorite = $res_fav["favorite"];
					
					if($i==1){
						$favoriteSet = "";
					} else if ($i==2) {
						$favoriteSet = $favorite;
					} else {
						$favoriteSet = $favoriteSet.",".$favorite;
					}
				}
				$dataSet = "{".$favoriteSet.",".$data."}";
			}
		
		$qry = "update thcap_asset_biz_default_add set \"astypeID\" = '$dataSet' where \"brandID\" = '$brandID'";
		pg_query($qry);
	}
}
?>