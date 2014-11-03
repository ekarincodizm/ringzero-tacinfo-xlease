<?php
	$Data_Arr = pg_escape_string($_POST["data"]);
	$Type_Doc = pg_escape_string($_POST["doctype"]);
	$Main_Array = array(); // เตรียมเพื่อเก็บข้อมูล สำหรับการบันทึก ลงในตาราง  thcap_audit_docs_main
	$Detail_Array = array(); // เตรียมเพื่อเก็บข้อมูล สำหรับการบันทึก ลงในตาราง thcap_audit_docs_detail
	switch($Type_Doc){
	 // นำค่าตัวแปรที่ส่งมาในตัวแปร $_POST["data"] แยกใส่ไว้ในตัวแปร $Main_Array และ $Detail_Array  เพื่อเตรียมสำหรับการบันทึกข้อมูล
		case "cr0046" :
			include("Separate_Data_for_cr0046.php"); 
			break;
		case "cr0089":
			include("Separate_Data_for_cr0089.php");	
			break;
	 }
	 echo "After Input Array ";
	 // เริ่มบันทึกข้อมูลลงใน Data Base 
	 
	 // บันทึกข้อมูลลงในตาราง  thcap_audit_docs_main
	 switch($Type_Doc){
	 	case "cr0046" :
			include("Save_Docs_cr0046_to_main.php");
			break;
		case "cr0089":
			include("Save_Docs_cr0089_to_main.php");
			break;	
	 }
?>	
