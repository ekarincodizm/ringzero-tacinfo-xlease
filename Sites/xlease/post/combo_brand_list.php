<?php
include("../config/config.php");

$type = $_GET['type'];
$brand = $_GET['brand'];

if($type==""){
	echo " -เลือกประเภทรถก่อน- <font color=\"red\">*</font>";
	echo "<input type=\"hidden\" name=\"f_brand\" id=\"f_brand\" value=\"\" >";	
}else{
	$objQuery = pg_query("select \"brandID\",\"brand_name\" from \"thcap_asset_biz_brand\" where \"astypeID\" = '$type' AND \"status\" = '1'
	order by \"brand_name\"
	");
	
	echo "<select name=\"f_brand\" id=\"f_brand\"  onchange=\"show_model_func();passrq(this);\" >";
		echo "<option value=\"\" >- เลือกยี่ห้อ -</option>";
	while($objResuut = pg_fetch_array($objQuery)){
		$brandID = trim($objResuut["brandID"]);
		$brand_name = trim($objResuut["brand_name"]);
		if($brandID == $brand){ $selected = "selected"; }else{ $selected = ""; }
		echo "<option value=\"$brandID\" $selected>$brand_name</option>";
	}
	echo "</select><font color=\"red\">*</font>";		

	
} ?>
		
	
