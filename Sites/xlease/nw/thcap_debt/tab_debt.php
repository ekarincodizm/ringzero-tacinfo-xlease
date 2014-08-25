<?php
session_start();
require_once("../../config/config.php");
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ

//ตรวจสอบว่าพนักงานมีระดับใด
$qrylevel=pg_query("select \"ta_get_user_emplevel\"('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">
					<div class=\"tab active\"><a id=\"1\" href=\"javascript:list_tab_menu(1);\">รายการหนี้ทั้งหมดที่ถึงกำหนด</a></div>
					";
					if($emplevel<=15){
						echo "<div class=\"tab active\"><a id=\"2\" href=\"javascript:list_tab_menu(2);\">รายการหนี้ทั้งหมด</a></div>";
					}
					echo "
				</div>
			</div>
		</div>
	</div>
	<div class=\"list_tab_menu\"></div>
";

?>