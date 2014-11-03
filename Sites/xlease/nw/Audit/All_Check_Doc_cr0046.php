<?php
	include("../../config/config.php");
	include("document_function.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<?php
?>
<fieldset>
	<legend>
		ประวัติการตรวจสอบ ทุกรายการ
	</legend>
	<!-- ตารางสำหรับแสดง ประวัติการตรวจสอบ -->
	<table border="0" width="99%">
		<?php
			Head_Table_Of_Check_Doc();
			Row_Table_Of_cr_0046('ALL');
		?>
	</table>
	
</fieldset>