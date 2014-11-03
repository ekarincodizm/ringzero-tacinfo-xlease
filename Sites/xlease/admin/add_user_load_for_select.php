<?php
	// Purpose Load ค่าตัวแปรตัวเลือกต่าง ๆ เก็บไว้ใน Array เพื่อที่จะได้ไม่ต้องเรียกใหม่ทุกครั้ง เมื่อ เพิ่่ม Row ใหม่
	
	// Load ค่าตัวเลือกสำหรับ "กลุ่มผู้ใช้" ผลลัพธ์เก็บไว้ที่  $Group_Select
    $i=0;
	$Group_Select[$i]['Name'] = "---เลือก---";
	$Group_Select[$i]['Value'] = "";
	$i++;
	$qry_gpuser=pg_query("select dep_name,dep_id from department order by dep_id");
	while($resg=pg_fetch_array($qry_gpuser))
    {
 		$Group_Select[$i]['Name'] = $resg["dep_name"];
		$Group_Select[$i]['Value'] = $resg["dep_id"];
		$i++;           
    }
	
	
	// Load ค่าตัวเลือกสำหรับ "ฝ่าย" ผลลัพธ์เก็บไว้ที่  $fdep_Select 
	$i=0;
	$fdep_Select[$i]['Name'] = "---เลือก---";
	$fdep_Select[$i]['Value'] = "";
	$i++;
    $qry_dep=pg_query("select fdep_id,fdep_name from f_department where fstatus='TRUE' order by fdep_id");
	while($resd=pg_fetch_array($qry_dep))
    {
    	$fdep_Select[$i]['Name'] = $resd['fdep_name'];
		$fdep_Select[$i]['Value'] = $resd['fdep_id'];
		$i++;
	}
	
	// กำหนดค่าสำหรับ  "Office" ผลลัพธ์เก็บไว้ที่  $Office_Select 
	$i = 0;
	$Office_Select[$i]['Name'] = "เลือก";
	$Office_Select[$i]['Value'] = "";
	$i = 1;
	$Office_Select[$i]['Name'] = "NV";
	$Office_Select[$i]['Value'] = $_SESSION["session_company_nv"];
	$i = 2;
	$Office_Select[$i]['Name'] = "JR";
	$Office_Select[$i]['Value'] = $_SESSION["session_company_jr"];
	$i = 3;
	$Office_Select[$i]['Name'] = "TV";
	$Office_Select[$i]['Value'] = $_SESSION["session_company_tv"];
	
	//กำหนดค่าสำหรับ "status" ผลลัพธ์เก็บไว้ที่ $Status_Select
	$i = 0; 
	$Status_Select[$i]['Name'] = "---เลือก---";
	$Status_Select[$i]['Value'] = "";
	$i = 1;
	$Status_Select[$i]['Name'] = "ใช้งาน";
	$Status_Select[$i]['Value'] = "1";
	$i = 2;
	$Status_Select[$i]['Name'] = "ระงับการใช้งาน";
	$Status_Select[$i]['Value'] = "0";
               
   //กำหนดค่าสำหรับ " 	ใช้งานระบบ"  ผลลัพธ์เก็บไว้ใน $System_Select            
	$i = 0; 
	$System_Select[$i]['Name'] = "---เลือก---";
	$System_Select[$i]['Value'] = "";
	$i = 1;
	$System_Select[$i]['Name'] = "XLEASE เท่านั้น";
	$System_Select[$i]['Value'] = "0";
	$i = 2;
	$System_Select[$i]['Name'] = "XLEASE และ TA";
	$System_Select[$i]['Value'] = "1";
	
	// กำหนดค่าสำหรับ  "IsAdmin" ผลลัพธ์เก็บไว้ใน $IsAdmin_Select
	$i = 0; 
	$IsAdmin_Select[$i]['Name'] = "ไม่เป็น Admin";
	$IsAdmin_Select[$i]['Value'] = "0";
	$i = 1; 
	$IsAdmin_Select[$i]['Name'] = "เป็น Admin";
	$IsAdmin_Select[$i]['Value'] = "1";
	
	function Chk_Login_Is_Admin(){
		$Str_Get_Status = "SELECT \"thcap_get_is_admin_status\"('".$_SESSION['uid']."')";
		$Result = pg_query($Str_Get_Status);
		$Login_Is_Admin = pg_fetch_result($Result,0,0);
		if($Login_Is_Admin == 0){
			return false;
		}elseif($Login_Is_Admin == 1){
			return true;
		}
	}
	
  	function Part_List_Selection($Para_Select)
  	{
  		foreach ($Para_Select as $Mi_Select)
		{ 
			echo "<option value=\"".$Mi_Select['Value']."\">".$Mi_Select['Name']."</option>"; 
		}
	} // End Of function Part_List_Selection
	
	function Part_Test()
	{
			
		echo "Part Test";
	}
	
	function Part_List_Selection_2($Para_Select)
  	{
  			var_dump($Para_Select);
  	} // End Of function Part_List_Selection
	
	
	function Part_Test_2($Var_1 = 'xxx')
	{
	 	echo $Var_1;	
		
	}
?>

