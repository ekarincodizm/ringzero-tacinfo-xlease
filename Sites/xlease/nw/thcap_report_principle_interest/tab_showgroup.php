<?php
require_once("../../config/config.php");
$checkoption = $_GET["op1"];

IF($checkoption == 'my'){
	$checked1 = "checked";
	$selectMonth = $_GET["month"]; // เดือนที่เลือก
	$selectYear = $_GET["year"]; // ปีที่เลือก
	$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$selectMonth' and EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";

}else if($checkoption == 'y'){
	$checked2 = "checked";
	$selectYear = $_GET["year"]; // ปีที่เลือก
	$where = " EXTRACT(YEAR FROM \"receiveDate\") = '$selectYear'";
}

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";	
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด</a></div>";							
				$qry_year=pg_query("SELECT distinct(b.\"contractYear\") FROM thcap_temp_int_201201 a 
				left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
				where $where and \"isReceiveReal\" = '1' 
				ORDER BY b.\"contractYear\"");
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