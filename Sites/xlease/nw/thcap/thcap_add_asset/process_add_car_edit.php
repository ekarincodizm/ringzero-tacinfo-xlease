<?php  echo "process_add_car_edit.php";  echo "<BR>"; print_r($_POST); exit();
include("../../../config/config.php");

?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
include("../../function/checknull.php");  
?>

<?php
$id_user = $_SESSION["av_iduser"];

//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);  //++ echo "ln 10"; print_r($emplevel);

$nowdate=nowDateTime();

// echo 'ln19'; print_r($_POST); exit();// เตรียมตัวแปรสำหรับการบันทึกข้อมูล

$method = pg_escape_string($_POST["method"]); // เตรียมไว้เพื่อเป็น ตัวแปรรับค่า เพื่อทำงาน กรณีที่ ต้องการแก้ไข จ้อมูล
$Asset_ID = pg_escape_string($_POST['hdassetDetailID']); // รหัสสินทรัพย์
$Engine_No = pg_escape_string($_POST['engineno']); // เลขตัวถัง
$frame_No = pg_escape_string($_POST['bodyno']);  // เลขที่ตัวเครื่อง
$Engine_CC = pg_escape_string($_POST['cceg']); // ขนาด CC
$Year_Regis = pg_escape_string($_POST['yearregis']); // ปีที่จดทะเบียน
$Regis_No = pg_escape_string($_POST['regis']); // ทะเบียนรถ
$Regis_Date = pg_escape_string($_POST['dateregis']); // วันที่จดทะเบียน
$Regis_Province = pg_escape_string($_POST['regis_province']); // จังหวัดที่จดทะเบียน
$Car_Type = pg_escape_string($_POST['car_type']); // ชนิดรถ 
$Car_Code = pg_escape_string($_POST['Car_Code']); // รหัสประเภทรถ
$Car_Mile = pg_escape_string($_POST['car_mileage']); // ระยะทางเป็นไมล์
$Car_Color = pg_escape_string($_POST['car_color']); // รหัสสีรถ
$status = 0; // ใช้งาน Transaction
echo '<BR>'; print_r($_POST); echo $emplevel; //exit(); 

pg_query("BEGIN");

	//หาก user มีเลเวลน้อยกว่าหรือเท่ากับ 1 สามารถแก้ไขเลขตัวถัง
	if($emplevel <= 1)
	{
		// Update ข้อมูล รายละเอียดสินทรัพย์ แต่ละตัว ในส่วนของ	เลขตัวถัง	
		$productcode_new = checknull(pg_escape_string($_POST["bodyno"]));
		$Str_Update = "
						UPDATE 
								thcap_asset_biz_detail
						SET 
								\"productCode\" = $productcode_new
						WHERE 
								\"assetDetailID\" = '$Asset_ID';
				
						";
		echo '<BR> 1'.$Str_Update; 				
		$qry_up = pg_query($Str_Update);
		if($qry_up){}else{ $status++ ;}					  
	}// End Of การแก้ไขเลขตัวถัง
		
		// ดึงลำดับครั้งล่าสุดที่ทำรายการสินทรัพย์ แต่ละรายการ
		/*$Sql_Get_Add_Or_Edit_Value = "	
										SELECT 
												CASE 
														WHEN MAX(\"add_or_edit\") is null 
															THEN '0' 
															ELSE MAX(\"add_or_edit\")+1 
														END
										FROM 
												\"thcap_asset_biz_detail_central\" 
										where 
												\"assetDetailID\" = '$Asset_ID'
									  ";
		  echo 	'<BR> 2'.$Sql_Get_Add_Or_Edit_Value;									  
		  $qry_sel1 = pg_query($Sql_Get_Add_Or_Edit_Value);
		  if($qry_sel1){}else{$status++;}
		  list($maxedit) = pg_fetch_array($qry_sel1); */
	      $maxedit = 0;
			//เก็บรายละเอียดผู้ทำรายการ 
			$Sql_Ins_doer = "
								INSERT INTO 
										thcap_asset_biz_detail_central( 
																		\"assetDetailID\", 
																		\"doerID\", 
																		\"doerDate\", 
																		statusapp,
																		add_or_edit
																	  )
																VALUES (
																		'$Asset_ID',
																		'$id_user',
																		'$nowdate',
																		'0',
																		'$maxedit' 
																		)
									     			             RETURNING         
                                                    		            \"ascenID\";        
														
					";
			 echo '<BR> 3'.$Sql_Ins_doer;		
			 $Result = pg_query($Sql_Ins_doer);
             if($Result)
             {   // เตรียมค่า  จาก Col ascenID ในตาราง หลัง Insert ไว้ใช้งาน
                 $Data = pg_fetch_array($Result);        
                 $New_ascenID = $Data[0];         
             }else{
                 $status++; // กรณีที่ Insert ไม่สำเร็จ
             }
			
				
			// เก็บรายละเอียดทรัพย์สิน ในส่วนตารางการเก็บข้อมูล รถยนต์
			$Str_Ins_Asset =  " 
                          		INSERT INTO 
                                		       \"thcap_asset_biz_detail_car_temp\"
                                        		                                   (
                                                                                 		\"ascenID\", 
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
                            		                                                    ".$New_ascenID.",
                                    		                                            '".$Engine_No."',
                                            		                                    '".$frame_No."',
                                                    		                             ".$Engine_CC.",
                                                            		                     ".$Year_Regis.",
                                                                    		            '".$Regis_No."', 
                                                                            		    '".$Regis_Date."',
    		                                                                            '".$Regis_Province."',
	        	                                                                         ".$Car_Code.",
                		                                                                '".$Car_Mile."',
                        		                                                        '".$Car_Color."'
                                                                              );
                               

                                                

                                        ";
            echo '<BR> 4'.$Str_Ins_Asset;  
            $Result = pg_query($Str_Ins_Asset);                         
			if($Result)
			{
			}	
			else{
				$status++;
			}	
		
		echo 'Val status '.$status;	
		if($status==0){
			
pg_query("COMMIT");	
			
			echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย รอการอนุมัติ</font></h2></center>";
		}else{

pg_query("ROLLBACK");

			echo "<center><h2><font color=\"#0000FF\">ไม่สามารถบันทึกข้อมูลได้</font></h2></center>";
			
		}
		
		   echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"javascript:RefreshMe();\"></center>";
				
			
	

?>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>