<?php
require_once("../../config/config.php");
$type = $_GET["type"]; // ประเภท
$Sdate = $_GET["Sdate"]; // วันที่เริ่ม
$Edate = $_GET["Edate"]; // วันที่สิ้นสุด
$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก
$whereContract = $_GET["whereContract"]; // เลขที่สัญญา
$selectStyle = $_GET["selectStyle"]; // รูปแบบการแสดง

if($whereContract != ""){
	$where_other = "and a.\"contractID\" = '$whereContract' ";
}else{
	$where_other = "";
}

if($selectStyle == "receiptStyle")
{
	$where_other .= "and \"isReceiveReal\" > '0' ";
}
elseif($selectStyle == "autoStyle")
{
	$where_other .= "and \"isReceiveReal\" = '0' ";
}

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";	
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด</a></div>";			
				if($type == "between")
				{
					$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
								left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
								where \"newInterest\" > '0'
								and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
								and \"genDate\" >= '$Sdate'
								and \"genDate\" <= '$Edate'
								$where_other
								order by b.\"contractYear\" ");
				}
				elseif($type == "month")
				{
					$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
							left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
							where \"newInterest\" > '0'
							and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
							and substr(\"genDate\"::character varying,6,2) = '$month'
							and substr(\"genDate\"::character varying,1,4) = '$year'
							$where_other
							order by b.\"contractYear\" ");
				}
				elseif($type == "year")
				{
					$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
								left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
								where \"newInterest\" > '0'
								and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
								and substr(\"genDate\"::character varying,1,4) = '$year'
								$where_other
								order by b.\"contractYear\" ");
				}
				
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