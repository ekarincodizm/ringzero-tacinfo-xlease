<?php
	include("../../config/config.php");
	include("document_function.php");
	
	$Docs_Id = pg_escape_string($_GET['Docs_ID']); 
	$Contract_ID = pg_escape_string($_GET['Contract_ID']);
	// รับค่า ข้อมูล ผู้ทำรายการ จาก database 
	$Str_Query = "
					SELECT 
							\"Value\" 
					FROM 
							\"thcap_audit_docs_detail\"
					WHERE 
							(\"Docs_ID\" = '".$Docs_Id."') And 
							(\"Element_Name\" = 'Checker')
					
				 ";
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Check_Name_i = explode('#',$Data[0]);
	$Check_Name = $Check_Name_i[1];
	
	// รับค่าข้อมูล "วันเวลาที่ตรวจสอบ" จาก  database
	$Str_Query = "
					SELECT 
							thcap_audit_docs_main.\"AppvStamp\"
					FROM 
							thcap_audit_docs_main,
							thcap_audit_docs_detail
					WHERE 	
							(thcap_audit_docs_main.\"auto_ID\" = thcap_audit_docs_detail.\"main_autoID\") AND
							(thcap_audit_docs_detail.\"Docs_ID\" = '".$Docs_Id."')	
					Group By 
							thcap_audit_docs_main.\"AppvStamp\"	
					
				 ";			 
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Time_Stamp = $Data[0];
	
	// รัหค่าข้อมูล "หมายเหตุ ในหน้า 1 ด้านบน" จาก database
	$Str_Query = "
					SELECT 
							\"Value\"
					FROM 
							thcap_audit_docs_detail
					WHERE 
							(\"Element_Type\" = 'textarea') And 
							(\"Element_Name\" = 'Note_P1-0') And 
      						(\"Docs_ID\" = '".$Docs_Id."')
				 ";
	
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Notice_Page_1_Top = $Data[0];
	
	// รับค่าข้อมูล "หมายเหตุ ในหน้า 1 ด้านล่าง" จาก database
	$Str_Query = "
					SELECT 
							\"Value\"
					FROM 
							thcap_audit_docs_detail
					WHERE 
							(\"Element_Type\" = 'textarea') And 
							(\"Element_Name\" = 'Note_P1-1') And 
      						(\"Docs_ID\" = '".$Docs_Id."')
				 ";
	
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Notice_Page_1_Down = $Data[0];
	
	// รับค่าข้อมูล "หมายเหตุ ในหน้า 2 ด้านบน " จาก  database 
	$Str_Query = "
					SELECT 
							\"Value\"
					FROM 
							thcap_audit_docs_detail
					WHERE 
							(\"Element_Type\" = 'text') And 
							(\"Element_Name\" = 'Note_P2-0') And 
      						(\"Docs_ID\" = '".$Docs_Id."')
				 ";
	
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Notice_Page_2_Top = $Data[0];
	
	
	// รับค่าข้อมูล "หมายเหตุ  ในหน้า2 ด้านล่าง" จาก database
	$Str_Query = "
					SELECT 
							\"Value\"
					FROM 
							thcap_audit_docs_detail
					WHERE 
							(\"Element_Type\" = 'textarea') And 
							(\"Element_Name\" = 'Checker_Note') And 
      						(\"Docs_ID\" = '".$Docs_Id."')
				 ";
	
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	$Notice_Page_2_Down = $Data[0];
	
	
	
	
	
	$Str_Query = "
					
				 ";
	show_doc_msg("หมายเหตุ", 2);
	echo "<B>เลขที่สัญญา :</B>".$Contract_ID.'<BR>';
	echo "<B>ผู้ทำรายการตรวจสอบ :</B>".$Check_Name.'<BR>';
	echo "<B>วันเวลาที่ตรวจสอบ :</B>".$Time_Stamp.'<BR>';
?>
	<fieldset>
		<legend>หมายเหตุ</legend>
			<left>
				<textarea   rows="3" cols="65"><?php  echo $Notice_Page_2_Down;?></textarea>
			</left>
	</fieldset>
	<BR>
	<div align="center">
		<input type = "button" value="ปิด" onClick="window.close()">
	</div>	