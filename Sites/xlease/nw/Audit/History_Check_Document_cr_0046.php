<?php
	
?>
<fieldset>
	<legend>
		ประวัติการตรวจสอบ 30 รายการล่าสุด
		<?php 
			show_Line_Link_To_Check_Document("(ทั้งหมด)","All_Check_Doc_cr0046.php"); 
		?>
	</legend>
	<!-- ตารางสำหรับแสดง ประวัติการตรวจสอบ -->
	
	<table border="0" width="99%">
		<?php
			Head_Table_Of_Check_Doc();
			// Row_Table_Of_cr_0046('30');
		?>
	</table>
	
</fieldset>