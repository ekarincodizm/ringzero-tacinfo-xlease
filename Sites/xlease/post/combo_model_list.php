<?php
include("../config/config.php");

$brandID = $_GET['brandID'];
$model = $_GET['model'];

if($brandID==""){
	echo " - เลือกยี่ห้อก่อน - <font color=\"red\">*</font>";
    echo "<input type=\"hidden\" name=\"f_model\" id=\"f_model\" value=\"\" >";	
}else{
	$objQuery = pg_query("select \"modelID\",\"model_name\" from \"thcap_asset_biz_model\" WHERE \"brandID\" = '$brandID' AND \"status\" = '1'
	order by \"model_name\"");
	
	echo "<select name=\"f_model\" id=\"f_model\" onchange=\"passrq(this);\" >";
	echo "<option value=\"\">- เลือกรุ่น -</option>";
	while($objResuut = pg_fetch_array($objQuery)){
		$modelID = trim($objResuut["modelID"]);
		$model_name = trim($objResuut["model_name"]);
		if($modelID == $model){ $selected = "selected"; }else{ $selected = ""; }
		echo "<option value=\"$modelID\" $selected>$model_name</option>";
	}
	echo "</select><font color=\"red\">*</font>";		

	
} ?>
		
	
