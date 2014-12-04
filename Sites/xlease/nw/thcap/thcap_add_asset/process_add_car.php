<?php
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
list($emplevel)=pg_fetch_array($qrylevel);

$nowdate=nowDateTime();

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

// ใช้งาน Transaction
pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_check_concurency = pg_query("select * from \"thcap_asset_biz_detail_central\" where \"assetDetailID\" = '$Asset_ID' and \"add_or_edit\" = '0' and \"statusapp\" <> '2' ");
$num_check_concurency = pg_num_rows($qry_check_concurency);
if($num_check_concurency > 0)
{
	$status++;
	$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
}
else
{
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
																		'0' 
																		)
									     			             RETURNING         
                                                    		            \"ascenID\";        
														
					";		
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
            $Result = pg_query($Str_Ins_Asset);                         
			if($Result){
			}	
			else{
				$status++;
			}	
}

if($status==0)
{
	pg_query("COMMIT");	
	echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย รอการอนุมัติ</font></h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">ไม่สามารถบันทึกข้อมูลได้!! $error</font></h2></center>";
}

 echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"javascript:RefreshMe();\" style=\"cursor:pointer;\" /></center>";
?>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>