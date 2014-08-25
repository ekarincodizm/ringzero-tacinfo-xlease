<?php
require_once("../../config/config.php");
$s = pg_escape_string($_GET['s']); //ตัวแปรสำหรับบอกว่าแสดงข้อมูลอะไร
$current_date=nowDateTime(); 
if($s==1){ //ใบแจ้งหนี้ที่ถึงกำหนดส่ง
	
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">";
					$qrnum=pg_query("select a.\"bankOutID\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
							left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
							WHERE \"revChqStatus\" in('2','8') and \"bankChqDate\" <= '$current_date' 
							and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
							and \"bankRevResult\" is null
							order by a.\"bankChqDate\"");
					$row2 = pg_num_rows($qrnum);
					echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu('0',1);\">ทั้งหมด <font color=red>($row2)</font></a></div>";
					
					$qrnum=pg_query("select a.\"bankOutID\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
							left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
							WHERE \"revChqStatus\" ='8' and \"bankChqDate\" <= '$current_date' and \"isInsurChq\"<>1 
							and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
							and \"bankRevResult\" is null
							order by a.\"bankChqDate\"");
					$row2 = pg_num_rows($qrnum);	
					echo "<div class=\"tab active\"><a id=\"1\" href=\"javascript:list_tab_menu('1',1);\">เช็คปกติ <font color=red>($row2)</font></a></div>";
					
					$qrnum=pg_query("select a.\"bankOutID\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
							left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
							WHERE \"revChqStatus\" ='8' and \"bankChqDate\" <= '$current_date' and \"isInsurChq\"=1 
							and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
							and \"bankRevResult\" is null
							order by a.\"bankChqDate\"");
					$row2 = pg_num_rows($qrnum);
					echo "<div class=\"tab active\"><a id=\"2\" href=\"javascript:list_tab_menu('2',1);\">เช็คค้ำสัญญา <font color=red>($row2)</font></a></div>";
					
					$qrnum=pg_query("select a.\"bankOutID\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
							left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
							WHERE \"revChqStatus\" ='2' and \"bankChqDate\" <= '$current_date' 
							and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
							and \"bankRevResult\" is null
							order by a.\"bankChqDate\"");
					$row2 = pg_num_rows($qrnum);	
					echo "<div class=\"tab active\"><a id=\"3\" href=\"javascript:list_tab_menu('3',1);\">เช็คคืน <font color=red>($row2)</font></a></div>";
		
				echo "
					</div>
				</div>
			</div>
		</div>
		<div class=\"list_tab_menu\"></div>
	";
}
?>