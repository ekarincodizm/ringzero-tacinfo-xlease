<?php
	session_start();
	include("../../../config/config.php");
	include("../../function/checknull.php");
?>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
	$ascenID = pg_escape_string($_POST["ascenID"]); //รหัสการใส่รายละเอียดสินทรัพย์
	$ascenID_Real = pg_escape_string($_POST["ascenID_Real"]);
	$note = checknull(pg_escape_string($_POST["note"])); //เหตุผลการปฎิเสธการอนุมัติ
	 
	$status = 0;  
	 
	// ดึงค่าเวลาปัจจุบัน
	$Result = pg_query("SELECT \"nowDateTime\"()");
	$Data = pg_fetch_array($Result);
	$App_Date = $Data[0];
	// ดึงรหัสผู้ใช้งาน
	$User_Id =  $_SESSION["av_iduser"];
	 
	$Err_Msg = ""; 

	pg_query("BEGIN");

	$cmd = "app"; // สำหรับการอนุมัติ 
		
	IF($cmd == 'app'){ //หากอนุมัติ
		// เตรียมข้อมูลสำหรับการอนุมัติ Update สถานะของข้อมูล
		 
	
		$Str_Update = "
						UPDATE 
								\"thcap_asset_biz_detail_central\"
						SET 
								\"statusapp\" = '1',
								\"appID\" = '".$User_Id."',
								\"appDate\" = '".$App_Date."'
								
						WHERE 
								\"ascenID\" =	".$ascenID_Real." and
								\"statusapp\" = '0'
				  		";
	
		echo '<BR>'.$Str_Update; //exit();
		$Result = pg_query($Str_Update);	
		if($Result){ // Update ข้อมูลได้
			
		}else{ // Update ข้อมูลไม่ได้
			$status++;
			$Err_Msg = $Err_Msg + "ไม่สามารถ UPDATE ข้อมูลในตาราง thcap_asset_biz_detail_central"+"\n\r";
		}
			
		// ดึงข้อมูลเพื่อเตรียมการบันทึก
		$Str_Get_Value_For_Insert = "	
										SELECT 
												\"thcap_asset_biz_detail_central\".\"assetDetailID\",
												\"thcap_asset_biz_detail_car_temp\".\"engine_no\",
												\"thcap_asset_biz_detail_car_temp\".\"frame_no\",
												\"thcap_asset_biz_detail_car_temp\".\"EngineCC\",
												\"thcap_asset_biz_detail_car_temp\".\"year_regis\",
												\"thcap_asset_biz_detail_car_temp\".\"regiser_no\",
												\"thcap_asset_biz_detail_car_temp\".\"register_date\",
												\"thcap_asset_biz_detail_car_temp\".\"register_province\",
												\"thcap_asset_biz_detail_car_temp\".\"car_type\",
												\"thcap_asset_biz_detail_car_temp\".\"car_mileage\",
												\"thcap_asset_biz_detail_car_temp\".\"car_color\"
	
										FROM 
												\"thcap_asset_biz_detail_car_temp\",\"thcap_asset_biz_detail_central\"
										where  
												(\"thcap_asset_biz_detail_car_temp\".\"ascenID\"  = \"thcap_asset_biz_detail_central\".\"ascenID\")
												AND
												(\"thcap_asset_biz_detail_central\".\"assetDetailID\" = ".$ascenID.")
									
									";
		 
		$Result = pg_query($Str_Get_Value_For_Insert);	
		if($Result){ // กรณีที่ดึงข้อมูลจากฐานข้อมูลมาได้
			// เตรียมค่าตัวแปรเพื่อการบันทึกข้อมูล
			$Err_Msg = "สามารถดึงข้อมูลได้";
			$Data = pg_fetch_array($Result);
			$In_assetDetailID = $Data['assetDetailID'] ; 
			$In_engine_no = $Data['engine_no']; 
			$In_frame_no = $Data['frame_no']; 
			$In_EngineCC = $Data['EngineCC']; 
			$In_year_regis = $Data['year_regis']; 
			$In_regiser_no = $Data['regiser_no']; 
			$In_register_date = $Data['register_date']; 
			$In_register_province = $Data['register_province']; 
			$In_car_type = $Data['car_type']; 
			$In_car_mileage = $Data['car_mileage']; 
			$In_car_color = $Data['car_color'];
 
		}else{ 	
			$status++;
			$Err_Msg = "ไม่สามารถดึงข้อมูลจากฐานข้อมูลมาได้ ";
			echo $Err_Msg;
    	}
		

		
		// บันทึกข้อมูลลงในตาราง  
		$Str_Ins =  "
						INSERT INTO
										thcap_asset_biz_detail_car(
																	\"assetDetailID\",
																	\"engine_no\",
																	\"frame_no\",
																	\"EngineCC\", 
																	\"year_regis\",
																	\"regiser_no\",
																	\"register_date\",
																	\"register_province\",
																	\"car_type\", 
																	\"car_mileage\",
																	\"car_color\")
														VALUES (
																	".$In_assetDetailID.",
																	'".$In_engine_no."',
																	'".$In_frame_no."',
																	".$In_EngineCC.",
																	".$In_year_regis.",  
																	'".$In_regiser_no."',
																	'".$In_register_date."',
																	'".$In_register_province."',
																	".$In_car_type.", 
																	".$In_car_mileage.",
																	".$In_car_color."
																)	
				";
	
		  
	
		$Result = pg_query($Str_Ins); 
		
		if($Result){
			
		}else{
			$status++;
			$Err_Msg = "ไม่สามารถนำเข้าตาราง ในตารางได้"+"\n\r";
		}
							
	
	

   
	
}	
	
//echo "Value Of status Is ".$status;
if($status == 0)
{
	pg_query("COMMIT");
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}elseif($frompage =="appvdetail"){
		
			$script= '<script language=javascript>';
			$script.= " alert('อนุมัติเรียบร้อยแล้ว');
					opener.location.reload(true);
					self.close();";
			$script.= '</script>';
			echo $script;
	}else{
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";	
	}
		
		
	
}
else
{
	
	pg_query("ROLLBACK"); 
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">ไม่สามารถอนุมัติข้อมูล โดยอัตโนมัติได้ จำเป็นต้องอนุมัติด้วยตัวบุคคล</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		if($frompage =="appvdetail"){
			$script= '<script language=javascript>';
			$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้');
					opener.location.reload(true);
					self.close();";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo "1";
		}
	}	
}
?>
<?php
if($autoapp == 't'){
?>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<?php
}
?>
