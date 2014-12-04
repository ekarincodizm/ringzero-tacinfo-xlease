<?php
	include("../../../config/config.php");
	
	pg_query("BEGIN WORK");
	$Status = 0;
	
	include("Save_Docs_cr0089_to_main.php");
	include("Save_Docs_Detail.php");
	
	if($Status == 0)
	{
		pg_query("COMMIT");
		echo "บันทึกสำเร็จ เลขที่เอกสารคือ ".$Docs_No;
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้";
	}
?>