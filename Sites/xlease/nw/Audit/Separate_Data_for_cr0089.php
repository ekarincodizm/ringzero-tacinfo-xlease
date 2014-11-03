<?php
	// แยกค่าข้อมูล จาก ตัวแปร $Data_Arr เก็บไว้ในตัวแปร $Main_Array,$Detail_Array สำหรับเตรียมบันทึก
	//echo "cr0089+-+-"; print_r($Data_Arr);
	$Type_No_Save_Element = array("button","submit"); // ประเภทของ Element ที่ไม่ต้องการบันทึกข้อมูล 
	$Name_Element_In_Main = array("Cutomer_Name");  // ชื่อของ Element ที่ต้องการนำข้อมูลไปบันทึกลง ตาราง  thcap_audit_docs_main
	$Idx_Main = 0; $Idx_Detail = 0;
	foreach ($Data_Arr as $key => $Val){
		//print_r($Val);
		$Element = explode("|", $Val);
		echo $Element[0].'\n';
		if(in_array($Element[0],$Type_No_Save_Element))
		{
			// no thing	
		}elseif(in_array($Element[1],$Name_Element_In_Main)){
			// เตรียมข้อมูลงในตาราง thcap_audit_docs_main
			//$Main_Array["$Idx_Main"]["$Element[1]"] = $Element[3];
			$Main_Array["$Idx_Main"][0] = $Element[1];
			$Main_Array["$Idx_Main"][1] = $Element[3];
			$Idx_Main++;
		}else{ // เตรียมบันท  thcap_audit_docs_main 
			
			if(($Element[0] == "radio")and($Element[4] == "true"))
			{   // กรณีที่ Element Type เป็น radio และ ถูก Click
				$Detail_Array["$Idx_Detail"][0] = $Element[0];	
				$Detail_Array["$Idx_Detail"][1] = $Element[1];
				$Detail_Array["$Idx_Detail"][2] = $Element[2];
				$Detail_Array["$Idx_Detail"][3] = $Element[3];
				$Detail_Array["$Idx_Detail"][4] = $Element[4];
				
				$Idx_Detail++;
			}elseif($Element[0] != "radio")
			{   // กรณีที่ Element Type ไม่เป็น Radio
				$Detail_Array["$Idx_Detail"][0] = $Element[0];	
				$Detail_Array["$Idx_Detail"][1] = $Element[1];
				$Detail_Array["$Idx_Detail"][2] = $Element[2];
				$Detail_Array["$Idx_Detail"][3] = $Element[3];
				$Detail_Array["$Idx_Detail"][4] = $Element[4];
				$Idx_Detail++;
			}
		}
	} // End For Each	
?>