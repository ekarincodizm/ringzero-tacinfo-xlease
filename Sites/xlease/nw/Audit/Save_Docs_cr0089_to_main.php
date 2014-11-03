<?php
	// Purpose : บันทึกข้อมูลเอกสาร cr0089 ลงในตาราง thcap_audit_docs_main
	// รับค่าตัวแปร สำหรับบันทึกข้อมูล
	//echo "Array long Is ";
	//print_r($Main_Array);
	foreach ($Main_Array as $key => $Val){
		print_r($Val);
		if($Val[0] == "Cutomer_Name"){
			$Customer_Name = $Val[1];
		}
	}
	echo '+++'.$Customer_Name;
	
	//รับค่า ลำดับครั้งในการตรวจสอบเอกสาร ชองลูกค้าในแต่ละราย
	$Str_Max_Time = "
						SELECT
								MAX(\"AppvTime\")
						FROM
								thcap_audit_docs_main
						WHERE 	
								(\"Contract_ID\" = '".$Customer_Name."' )				
					
					";
 	echo $Str_Max_Time;	
	
	$Result = pg_query($Str_Max_Time);
	$Data = pg_fetch_array($Result);
	if(empty($Data[0]))
	{
		return 1;
	}else{
		return $Data[0]+1;
	}					
?>