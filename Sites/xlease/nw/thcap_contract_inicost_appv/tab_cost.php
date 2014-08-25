<?php
require_once("../../config/config.php");
echo "<fieldset style=\"width:70%\" align=\"center\" ><legend><b>รายการรออนุมัติต้นทุนสัญญา</b></legend>";
	$qry_con=pg_query("select a.\"contractID\"  from \"thcap_contract_inicost\" a
			left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
			where a.\"ini_appv_status\"='2' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\"  order by a.\"ini_add_stamp\" ASC ");
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
									
									
									$qrnum=pg_query("select a.\"contractID\"  from \"thcap_contract_inicost\" a
									left join \"thcap_contract\" b on a.\"contractID\"= b.\"contractID\"
									where a.\"ini_appv_status\"='2' and b.\"conType\"='$tabID' group by a.\"contractID\",a.\"ini_add_stamp\",a.\"ini_add_user\",b.\"conType\",b.\"conDate\"  order by a.\"ini_add_stamp\" ASC ");	
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