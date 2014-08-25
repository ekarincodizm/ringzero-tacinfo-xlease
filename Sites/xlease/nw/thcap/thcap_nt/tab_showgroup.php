<?php
include("../../../config/config.php");
$val=pg_escape_string($_REQUEST["val"]);

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";
				
				// นับจำนวนรายการทั้งหมด
				if($val=='1'){
					$qry_selcol = pg_query("select \"contractID\",\"backDueDate\",\"overdue\",\"conNumNTDays\",\"backAmt\",\"LeftPrinciple\",
						\"nextDueDate\",\"nextDueAmt\",\"periods\",\"is_overdue\",\"NT_1_Status\"   from \"thcap_nt1_waitforappv\"  
						where \"overdue\" > \"conNumNTDays\"  and \"overdue\" is not null and \"backAmt\" > 0");
				}
				else{
					$qry_selcol = pg_query("select \"contractID\",\"backDueDate\",\"overdue\",\"conNumNTDays\",\"backAmt\",\"LeftPrinciple\",
						\"nextDueDate\",\"nextDueAmt\",\"periods\",\"is_overdue\",\"NT_1_Status\"   from \"thcap_nt1_waitforappv\"  ");
				
				}
				$row_Selcol = pg_num_rows($qry_selcol);
				
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด <font color=\"red\"> ($row_Selcol)</font></a></div>";			
				$qry_year=pg_query("
					SELECT distinct(\"conType\")
					FROM thcap_contract
				");
				while($restype=pg_fetch_array($qry_year)){
					list($contracttype)=$restype;
					
					// นับจำนวนของสัญญานั้นๆ
					if($val=='1'){
						$qry_selcol = pg_query("select \"contractID\" from \"thcap_nt1_waitforappv\"  
						where \"overdue\" > \"conNumNTDays\"  and \"overdue\" is not null  and (\"thcap_get_contractType\"(\"contractID\") = '$contracttype' OR subStr(\"contractID\" ,0,3)='$contracttype') and \"backAmt\" > 0");
					}
					else{
						$qry_selcol = pg_query("select \"contractID\" from \"thcap_nt1_waitforappv\" where  (\"thcap_get_contractType\"(\"contractID\") = '$contracttype' OR subStr(\"contractID\" ,0,3)='$contracttype') ");
				
					}					
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