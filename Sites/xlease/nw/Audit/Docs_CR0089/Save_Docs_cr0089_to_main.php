<?php
	// Purpose : บันทึกข้อมูลเอกสาร cr0089 ลงในตาราง thcap_audit_docs_main
	
	
	
	// รับค่าตัวแปร สำหรับบันทึกข้อมูล
	foreach ($Main_Array as $key => $Val){
		// print_r($Val);
		if($Val[0] == "Cutomer_Name"){
			$Customer_Name = $Val[1];
		}
	}
	//echo '+++'.$Customer_Name;
	
	//รับค่า ลำดับครั้งในการตรวจสอบเอกสาร ชองลูกค้าในแต่ละราย
	$Str_Max_Time = "
						SELECT
								MAX(\"AppvTime\")
						FROM
								thcap_audit_docs_main
						WHERE 	
								(\"Contract_ID\" = '".$Customer_Name."' )				
					
					";
 	// echo $Str_Max_Time;	
	
	$Result = pg_query($Str_Max_Time);
	$Data = pg_fetch_array($Result);
	if(empty($Data[0]))
	{
		$New_Appv_Time =  1;
	}else{
		$New_Appv_Time = $Data[0]+1;
		
	}					
	
	// echo 'Check Time '.$New_Appv_Time;
	
	// ทำการตรวจสอบว่า ข้อมูลในตาราง thcap_audit_docs_main 
	//  	ที่  Col "Contract_ID" = $Customer_Name
	//		และ Col "AppvTime" = $New_Appv_Time
	
	$Str_Chk_Prv_Data = "
							SELECT 
									\"auto_ID\"
							FROM
									\"thcap_audit_docs_main\" 	
							WHERE
									(\"Contract_ID\" = '".$Customer_Name."') AND
									(\"AppvTime\" = ".$New_Appv_Time." )   			
						";
	$Result = pg_query($Str_Chk_Prv_Data);
	$Num_Row = pg_num_rows($Result);
	if($Num_Row > 0)
	{// กรณีที่มีข้อมูล	
		$Status++;
	}					
	// echo "Value Of Status Before Insert To Main Is ".$Status;
	
	
	// รับค่าเวลาในการบันทึกข้อมูล
	$Current_Time = nowDateTime(); 
	// echo "App Stamp Time Is ".$Current_Time; 
	
	$Str_Ins_To_Main = "
							INSERT INTO 
										thcap_audit_docs_main(
            													\"Contract_ID\", 
            													\"Con_Type\", 
            													\"AppvTime\", 
            													\"AppvStamp\"
            												  )
    						VALUES ( 
    								'".$Customer_Name."',
    								 NULL,
    								 ".$New_Appv_Time.",
    								 '".$Current_Time."')
    						RETURNING \"auto_ID\"		 
						";
	// echo '<BR>'.$Str_Ins_To_Main.'>>>';	
	$Result = pg_query($Str_Ins_To_Main);
	$Data = pg_fetch_array($Result);	
	// echo " Run Main Auto ID Is ".$Data[0];
	$Main_Auto_ID = $Data[0]; // ค่าข้อมูล ใน Column "auto_ID" จากตาราง  thcap_audit_docs_main 
				
?>