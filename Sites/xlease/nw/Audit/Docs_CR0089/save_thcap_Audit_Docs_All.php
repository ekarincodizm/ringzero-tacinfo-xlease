<?php 
	$Data_Arr = $_POST["data"]; // ข้อมูลเป็น Array จึงไม่ไช้ pg_Escape_string เอาไว้ใช้ตอนวน Loop
	$Type_Doc = pg_escape_string($_POST["doctype"]); 
	$Main_Array = array(); // เตรียมเพื่อเก็บข้อมูล สำหรับการบันทึก ลงในตาราง  thcap_audit_docs_main
	$Detail_Array = array(); // เตรียมเพื่อเก็บข้อมูล สำหรับการบันทึก ลงในตาราง thcap_audit_docs_detail
	switch($Type_Doc){
	 // นำค่าตัวแปรที่ส่งมาในตัวแปร $_POST["data"] แยกใส่ไว้ในตัวแปร $Main_Array และ $Detail_Array  เพื่อเตรียมสำหรับการบันทึกข้อมูล
		
		case "CR0089":
			// echo "cr0089";	
			// แยกข้อมูลลง ตัวแปร $Main_Array และ $Detail_Array 
			include("Separate_Data_for_cr0089.php");	
			break;
	 }
	 // echo "After Input Array ";
	 // เริ่มบันทึกข้อมูลลงใน Data Base 
	 
	 // บันทึกข้อมูลลงในตาราง  thcap_audit_docs_main
	 switch($Type_Doc){
	 	
		case "CR0089":
				include("Save_Docs_cr0089.php");
			break;	
	 }
	 
	 
	 
?>	
