<?php
	include ("../../config/config.php");
	include ("../function/checknull.php");
	$Data_For_Save = $_POST["dat"]; // ข้อมูลเป็น Array
	$name = pg_escape_string($_POST['name']);
	$i = 1;
	$Main_Array = array();
	$Detail_Array = array();
	$maintable = array();
	foreach ($Data_For_Save as $key => $val) {
		$element = Separate_Data($val);
	
		if ($element[1] == "Contract_ID" || $element[1] == "Type_Contract_Select_Input"){
			$maintable[$element[1]] = $element[3];
		
		}
	}

	pg_query("BEGIN WORK");
	$Status = 0;

	$maintable["Appv_Time"] =Get_Approve_Time_1($maintable["Contract_ID"]);
 
	$Str_Chk_Prv_Data = "
							SELECT 
									\"auto_ID\"
							FROM
									\"thcap_audit_docs_main\" 	
							WHERE
									(\"Contract_ID\" = '".$maintable["Contract_ID"]."') AND
									(\"AppvTime\" = ".$maintable["Appv_Time"]." )   			
						";
	$Result = pg_query($Str_Chk_Prv_Data);
	$Num_Row = pg_num_rows($Result);
	if($Num_Row > 0)
	{
		$Status++;
	}					
	//insert maintable
	$Result = Insert_Data_To_thcap_audits_docs_main($maintable);
	$Data = pg_fetch_array($Result);
	$Main_Auto_ID = $Data[0];
	$Document_No = Get_Document_No('CR0046');
	
	$detailtable = array();
	foreach ($Data_For_Save as $key => $val){
		$element = Separate_Data($val);
	
		if ($element[1] == "Contract_ID" || $element[1] == "Type_Contract_Select_Input")
		{
		 	// No thing to do
		}elseif($element[0] == "submit"|| $element[0] == "button")
		{
		 	// No thing to do	
		}else{	
			$detailtable[0] = $element[0];	
			$detailtable[1] = $element[1];
			$detailtable[2] = $element[2];
			$detailtable[3] = $element[3];
			$detailtable[4] = $element[4];
			// insert val($element[1],$element[2],$element[3],$element[4])
			// var_dump($detailtable);	
			$must_save = Decision_For_Save_Data($detailtable);
			if($must_save){
				$Status_Of_Ins = Insert_Data_To_thcap_audit_docs_detail($detailtable,$Document_No,$Main_Auto_ID);
				if($Status_Of_Ins)// Check หา  กรณีที่ไม่สามารถ Insert ข้อมูลได้
				{
					// No thing to do
				}else{ // ทำเมื่อ Insert ไม่ได้
					$Status++;
				}
			}
		}
	}
	
	if($Status == 0)
	{   
		pg_query("COMMIT");
		echo "บันทึกสำเร็จ  เลขที่เอกสาร คือ ".$Document_No;
	}else
	{	
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได่้";
	}
	
function Input_In_Tab_main($element, $Main_Array) {
	$Word_Chk = array("Contract_ID", "Type_Contract_Select_Input");
	for ($i = 0; $i < sizeof($Detail_Array); $i++) {
		if ($Detail_Array == "Contract_ID" || $Detail_Array == "Type_Contract_Select_Input") {
		$Main_Array[$element[1]] = $element[3];
		}  
	}
	return $Main_Array;
}

function Input_In_Tab_Detail($Detail_Array) {
	$Word_Chk = array("Contract_ID", "Type_Contract_Select_Input");
	for ($i = 0; $i < sizeof($Detail_Array); $i++) {
		if ($Detail_Array == "Contract_ID" || $Detail_Array == "Type_Contract_Select_Input") {

		} else {
			// $Detail_Array[][1] = $element[1];
			// $Detail_Array[][2] = $element[2];
			// $Detail_Array[][3] = $element[3];
			// $Detail_Array[][4] = $element[4];
		}
	}

	return $Detail_Array;
}

function Separate_Data($Data_In) {

	$Sepate = explode("|", $Data_In);
	return $Sepate;
	//echo "Data Separate Is ".$Sepate."<BR>";
}
function Insert_Data_To_thcap_audits_docs_main($Data_In)
{		$Current_Time_Stamp = Get_Current_Date_And_Time();
		$Str_Ins = "
						INSERT INTO 
									thcap_audit_docs_main(
															\"Contract_ID\",
															\"Con_Type\", 
															\"AppvTime\",
															\"AppvStamp\"
														  )
									values(
											'".$Data_In["Contract_ID"]."',
											'".$Data_In["Type_Contract_Select_Input"]."',
											".$Data_In["Appv_Time"].",
											'".$Current_Time_Stamp."'
											) 
									RETURNING
												\"auto_ID\"						
					";
					
		// pg_query($Str_Ins);	
		$Auto_ID =pg_query($Str_Ins);
		return($Auto_ID);	
}
	
function Insert_Data_To_thcap_audit_docs_detail($Data_In,$Docs_ID,$Main_Auto_ID)
{   $Value_Save = checknull(trim($Data_In[3])); // กำหนดให้ข้อมูล ที่เป็น Blank มีค่า เป็น Null
	$Str_Ins = " INSERT INTO 
								thcap_audit_docs_detail(
            												\"Docs_ID\", 
            												\"Doc_Type\", 
            												\"Element_Name\",
            												\"Element_ID\", 
            												\"Element_Type\", 
            												\"Value\", 
            												\"main_autoID\")
    							VALUES(
    										'".$Docs_ID."',
    										 'cr0046',
    										 '".$Data_In[1]."',
    										 '".$Data_In[2]."',
    										 '".$Data_In[0]."',
    										 ".$Value_Save.", 
            								 ".$Main_Auto_ID.")
            	";
	$Status_Insert = pg_query($Str_Ins);
	return($Status_Insert);										 
	
}
function Get_Approve_Time_1($Contract_ID)
	{
		$SQL_Max_Chk_Time_Contract = "
										SELECT 
												MAX(\"AppvTime\")
  										FROM 
  											thcap_audit_docs_main
  										WHERE 
  											\"Contract_ID\" = '$Contract_ID'	
									 ";
										 
		$Result = pg_query($SQL_Max_Chk_Time_Contract);
		$Data = pg_fetch_array($Result);
		if(empty($Data[0]))
		{
			return 1;
		}else{
			return $Data[0]+1;
		}
						
	} 
	
function Get_Current_Date_And_Time()
{
	$Str_Get_Current_Date_And_Time = "	
										SELECT 
												\"nowDateTime\"()
									 ";
	$Result = pg_query($Str_Get_Current_Date_And_Time);
	$Data = pg_fetch_result($Result, 0, 0);
	return($Data);								 
}	
	
	
function Get_Document_No($DocVar)
{
		$Sql_Get_Doc_No = " 
							SELECT 
									\"thcap_gen_AuditdocumentID\"('".$DocVar."')
						  ";
		$Result = pg_query($Sql_Get_Doc_No);
		$Data = pg_fetch_result($Result,0);
		return($Data);
}	

function Decision_For_Save_Data($Data_Chk){
	$Type_Element = $Data_Chk[0];
	$must_save = false;
	switch ($Type_Element) {
  		case "radio":   
    					if($Data_Chk[4] == "true"){
		    				$must_save = true;
		    			}else{
		    				$must_save = false;
		    			}		
    					
   		    break;
  		case "textarea":
    				$must_save = true;
    		break;
		case "text":
					$must_save = true;
			break;	
		case "select-one":
					$Data_Chk[4] = $Data_Chk[3];
					$must_save = true;
			break;
		case "hidden":
					$must_save = true;
			break;	
  		default:
   	}// End Of Switch
   	
	return($must_save);
}	
?>	
