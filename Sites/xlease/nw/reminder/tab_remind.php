<?php
require_once("../../config/config.php");
require_once("function_reminder.php");
require_once("function_getremindernum.php");

$focusdate = pg_escape_string($_GET['focusdate']); 
$get_userid = $_SESSION["av_iduser"];
	
	echo "
		<div class=\"tab_menu_contrainer\">
			<div class=\"menu_box\">
				<div class=\"tab_box\">
					<div class=\"slide_tab\">";
					// ค้นหารายการที่ตรงกับเงื่อนไขที่จะต้องแสดง ก่อนวัน ที่ที่ต้องการให้แสดงงาน
					$date_yesterday= date ("Y-m-d", strtotime("-1 day", strtotime($focusdate)));	
					$qry_fuc_yesterday = getreminderquery($date_yesterday, $get_userid,'1'); 				
					$numrow1 = getreminder_num($focusdate,$qry_fuc_yesterday,$date_yesterday,'1'); 
					echo "<div class=\"tab active\"><a id=\"1\" href=\"javascript:list_tab_menu('1','$date_yesterday','$get_userid');\">
					<font color=blue>งานที่ยังไม่ได้ดำเนินการก่อนหน้านี้</font><font color=red>($numrow1)</font></a></div>";	
					
					// ค้นหารายการที่ตรงกับเงื่อนไขที่จะต้องแสดง วันที่ที่ต้องการให้แสดงงาน					
					$qry_fuc = getreminderquery($focusdate, $get_userid,'2'); 
					$numrow2=getreminder_num($focusdate,$qry_fuc,$focusdate,'2'); 
					echo "<div class=\"tab active\"><a id=\"2\" href=\"javascript:list_tab_menu('2','$focusdate','$get_userid');\">
					<font color=blue>งานที่ต้องทำในวันนี้</font><font color=red>($numrow2)</font></a></div>";
					
					// ค้นหารายการที่ตรงกับเงื่อนไขที่จะต้องแสดง วันที่ พรุ่งนี้ (+1 วัน )
					$date_tomorrow= date ("Y-m-d", strtotime("+1 day", strtotime($focusdate)));					
					$qry_fuc_tomorrow = getreminderquery($date_tomorrow, $get_userid,'3'); 
					$numrow3=getreminder_num($focusdate,$qry_fuc_tomorrow,$date_tomorrow,'3'); 
					echo "<div class=\"tab active\"><a id=\"3\" href=\"javascript:list_tab_menu('3','$date_tomorrow','$get_userid');\">
					<font color=blue>งานที่ต้องทำในวันพรุ่งนี้</font><font color=red>($numrow3)</font></a></div>";
					
					// ค้นหารายการที่ตรงกับเงื่อนไขที่จะต้องแสดง วันที่ มะรืน (+2 วัน )		
					$date_aftertomorrow= date ("Y-m-d", strtotime("+2 day", strtotime($focusdate)));	
					$qry_fuc_aftertomorrow = getreminderquery($date_aftertomorrow, $get_userid,'4'); 
					$numrow4=$numrow3=getreminder_num($focusdate,$qry_fuc_aftertomorrow,$date_aftertomorrow,'3'); 
					echo "<div class=\"tab active\"><a id=\"4\" href=\"javascript:list_tab_menu('4','$date_aftertomorrow','$get_userid');\">
					<font color=blue>งานที่ต้องทำในวันมะรืนนี้</font><font color=red>($numrow4)</font></a></div>";
		
				echo "
					</div>
				</div>
			</div>
		</div>
		<div class=\"list_tab_menu\">
		$n
		</div>
		
	";
?>
