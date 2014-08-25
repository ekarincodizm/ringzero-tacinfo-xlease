<?php
require_once("config/config.php");
echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"prev\"></div>
			<div class=\"tab_box\">
				<div class=\"slide_tab\">
					<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0');\">ทั้งหมด</a></div>
	";
					$qr = pg_query("select * from \"f_menu_tab\" where \"tab_status\"='1' order by \"tabID\"");
					if($qr)
					{
						$row = pg_num_rows($qr);
						if($row!=0)
						{
							while($rs=pg_fetch_array($qr))
							{
								$tabID = $rs['tabID'];
								$tab_name = $rs['tab_name'];
								echo "
									<div class=\"tab\"><a id=\"$tabID\" href=\"javascript:list_tab_menu('$tabID');\">$tab_name</a></div>
								";
							}
						}
					}
echo "
				</div>
				<input type=\"hidden\" name=\"cur_margin_left\" id=\"cur_margin_left\" value=\"0\" />
			</div>
			<div class=\"next\"></div>
		</div>
	</div>
	<div class=\"list_tab_menu\"></div>
";
?>