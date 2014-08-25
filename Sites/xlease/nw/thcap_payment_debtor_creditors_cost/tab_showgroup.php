<?php
require_once("../../config/config.php");

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";
				
				// นับจำนวนรายการทั้งหมด
				$qry_selcol = pg_query("SELECT  \"contractID\" from  \"thcap_contract\"  a WHERE \"conStartDate\" >='2014-01-01' 
								AND ((SELECT \"thcap_get_iniinvestmentamt\"(\"contractID\",'3')) != (SELECT \"thcap_get_all_payment_paid_for_contract\"(\"contractID\")))");
				$row_Selcol = pg_num_rows($qry_selcol);
				
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด <font color=\"red\"> ($row_Selcol)</font></a></div>";			
				$qry_year=pg_query("
					SELECT distinct(\"conType\")
					FROM thcap_contract
				");
				while($restype=pg_fetch_array($qry_year)){
					list($contracttype)=$restype;
					
					// นับจำนวนของสัญญานั้นๆ
					$qry_selcol = pg_query("
								SELECT  \"contractID\" from  \"thcap_contract\"  a WHERE \"conStartDate\" >='2014-01-01' 
								AND ((SELECT \"thcap_get_iniinvestmentamt\"(\"contractID\",'3')) != (SELECT \"thcap_get_all_payment_paid_for_contract\"(\"contractID\")))
								AND \"thcap_get_contractType\"(\"contractID\") = '$contracttype'
								order by \"contractID\" asc
					");
					$row_Selcol = pg_num_rows($qry_selcol);
					echo "<div class=\"tab active\"><a id=\"$contracttype\" href=\"javascript:list_tab_menu('$contracttype');\">$contracttype <font color=\"red\"> ($row_Selcol) </font></a></div>";
				}
			echo "
				</div>
			</div>
		</div>
	</div>
	<div class=\"list_tab_menu\"></div>
";
?>