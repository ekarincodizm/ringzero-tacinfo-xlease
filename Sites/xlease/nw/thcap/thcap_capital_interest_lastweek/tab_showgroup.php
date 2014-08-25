<?php
require_once("../../../config/config.php");
set_time_limit(0);

$month = pg_escape_string($_GET["month"]); //รับเดือน
$year = pg_escape_string($_GET["year"]); //รับปี
$contype = pg_escape_string($_GET['contype']); //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

//นำค่า array ของประเภทสัญญามาต่อกันเป็นเงื่อนไข เพื่อนำไปค้นหาปีที่ต้องนำมาแสดงในรายงาน
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

//หาวันที่สุดท้ายของเดือน	
$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$focusdate=$year.'-'.$month.'-'.$day;

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";	
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด</a></div>";			
				$qry_year=pg_query("SELECT distinct(EXTRACT(YEAR FROM \"conDate\")) FROM thcap_contract 
				WHERE \"conDate\" <='$focusdate' $contypeyear
				ORDER BY EXTRACT(YEAR FROM \"conDate\")");
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