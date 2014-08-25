<?php
require_once("../../config/config.php");

$datepicker = $_GET['datepicker']; //วันที่สนใจ
$contype = $_GET['contype']; //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

$contypeyear="";
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=''){
		if($contypeyear == ""){
			$contypeyear = "\"conType\"='$contypechk[$con]'";
		}else{
			$contypeyear = $contypeyear." OR \"conType\"='$contypechk[$con]'";
		}	
	}
}
if($contypeyear!=""){
	$contypeyear="and ($contypeyear)";
}

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";	
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด</a></div>";							
				$qry_year=pg_query("SELECT DISTINCT(EXTRACT(YEAR FROM \"conDate\")) as \"conyear\" FROM thcap_contract 
				where \"conDate\"<='$datepicker' and \"thcap_get_all_isSold\"(\"contractID\", '$datepicker') IS NULL AND \"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL $contypeyear 
				ORDER BY \"conyear\" ASC");
				while($resyear=pg_fetch_array($qry_year)){
					list($contractyear)=$resyear;
					echo "<div class=\"tab active\"><a id=\"$contractyear\" href=\"javascript:list_tab_menu('$contractyear');\">$contractyear</a></div>";
				}
			echo "
				</div>
			</div>
		</div>
	</div>
	<div class=\"list_tab_menu\"></div>
";
?>