<?php
	include ("../../function/checknull.php");
	
	$i = 0;
	
	// รับค่าเลขที่เอกสาร จาก database
	$Str_Get_Doc_No = "
						SELECT 
								\"thcap_gen_AuditdocumentID\"('".$Type_Doc."')
					  ";	
	//var_dump($Str_Get_Doc_No);		
	$Result = pg_query($Str_Get_Doc_No);
	$Docs_No = pg_fetch_result($Result, 0, 0); // รับค่าข้อมูลเลขที่เอกสาร
	// echo "Doc No Is ".$Docs_No;	
	// echo "Main Auto ID Is ".$Main_Auto_ID;	  		
	foreach($Detail_Array As $Value){
		
		$Element_Type = $Value[0]; // รับค่า  Tag Element : Type 
		$Element_Name = $Value[1]; // รับค่า  Tag Element : Name
		$Element_ID = $Value[2];   // รับค่า  Tag Element : ID
		$Element_Value = checknull(trim($Value[3]));// รับค่า  Tag : Value
		$Element_Check = $Value[4];// รับค่า สถานะ การ Check ใช้กับ Element Type ที่เป็น Radio 		
		
		$Str_Ins = 	"
						INSERT INTO
									thcap_audit_docs_detail(
																\"Docs_ID\",											
																\"Doc_Type\",
																\"Element_Name\",	
																\"Element_ID\",		
																\"Element_Type\",
																\"Value\",
																\"main_autoID\"
															)
									VALUES(
											'".$Docs_No."',
											'".$Type_Doc."',
											'".$Element_Name."',
											'".$Element_ID."',
											'".$Element_Type."',
											".$Element_Value.",
											$Main_Auto_ID
										  )											
					"; 
		// echo $Str_Ins;
		$Status_Ins = pg_query($Str_Ins); 	
		
		if($Status_Ins)// Check หา  กรณีที่ไม่สามารถ Insert ข้อมูลได้
		{
			// No thing to do
		}else{ // ทำเมื่อ Insert ไม่ได้
					$Status++;
		}	
	} // End foreach($Detail_Array As $Value)
	
	
	
?>