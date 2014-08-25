<?php
require_once("../../config/config.php");
echo "<fieldset><legend><b>รายการที่ยังไม่ได้ใส่ต้นทุนสัญญา</b></legend>";
	$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(\"contractID\") as investment,\"thcap_get_conEndDate\"(\"contractID\") as \"FconEndDate\" from \"thcap_contract\"
			where \"conCredit\" is null 
			and 
			(\"contractID\" not in(select distinct \"contractID\" from \"thcap_contract_inicost\") 
			or
			\"contractID\"  not in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('2','1'))
			and		
			\"contractID\"  in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('0') )) 
			");
	$row2 = pg_num_rows($qry_con);
	echo " <div style='overflow-x:hidden;overflow-y:hidden;width:250px;>";
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">
						<div class=\"tab\"><a href=\"javascript:list_tab_menu('0');\"></a></div>";
						echo"<div class=\"tab\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด<font color=red>($row2)</font></a></div>";
						$qr = pg_query("select \"conType\" as \"conType\" from thcap_contract_type ");
						if($qr)
						{
							$row = pg_num_rows($qr);
							if($row!=0)
							{
								while($rs=pg_fetch_array($qr))
								{
									$tabID = $rs['conType'];
									$tab_name = $rs['conType'];
									
									
									$qrnum=pg_query("select * from \"thcap_contract\"
											where \"conCredit\" is null and \"conType\"='$tabID'
											and 
											(\"contractID\" not in(select distinct \"contractID\" from \"thcap_contract_inicost\") 
											or
											\"contractID\"  not in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('2','1'))
											and		
											\"contractID\"  in (select \"contractID\" from thcap_contract_inicost where ini_appv_status in ('0') ))");	
									$row2 = pg_num_rows($qrnum);
	
									echo "
										<div class=\"tab\"><a id=\"$tabID\" href=\"javascript:list_tab_menu('$tabID');\">$tab_name <font color=red>($row2)</font></a></div>
									";
								}
							}
						}
				echo "
					</div>
				</div>
			</div>
		</div></div>
		<div class=\"list_tab_menu\"></div>
	";
?>
</fieldset>