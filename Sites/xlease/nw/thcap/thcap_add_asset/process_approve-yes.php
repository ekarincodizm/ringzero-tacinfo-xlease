<?php
	session_start();
	include("../../../config/config.php");
	include("../../function/checknull.php");
?>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	
	<script>
		function RefreshMe(){
			opener.location.reload(true);
			self.close();
		}
	</script>
<?php
	$ascenID = pg_escape_string($_POST["ascenID"]);
	$assetDetailID = pg_escape_string($_POST["assetDetailID"]);
	$note = checknull(pg_escape_string($_POST["note"])); //เหตุผลการปฎิเสธการอนุมัติ
	
	if($autoapp == 't'){
		$id_user = '000';
	}else{
		$id_user = $_SESSION["av_iduser"];
	}
	
	$nowDateTime = nowDateTime();
	
	$Err_Msg = "";

	pg_query("BEGIN");
	$status = 0; 

	// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
	$qry_chk = pg_query("select \"statusapp\" from \"thcap_asset_biz_detail_central\" where \"ascenID\" = '$ascenID' ");
	$chk_statusapp_old = pg_fetch_result($qry_chk,0);
	
	if($chk_statusapp_old == "1")
	{
		$status++;
		$Err_Msg .= " มีการอนุมัติไปก่อนหน้านี้แล้ว";
	}
	elseif($chk_statusapp_old == "2")
	{
		$status++;
		$Err_Msg .= " มีการปฎิเสธไปก่อนหน้านี้แล้ว";
	}
	else
	{
		$Str_Update = "
						UPDATE 
								\"thcap_asset_biz_detail_central\"
						SET 
								\"statusapp\" = '1',
								\"appID\" = '$id_user',
								\"appDate\" = '$nowDateTime'
								
						WHERE 
								\"ascenID\" =	'$ascenID' AND
								\"statusapp\" = '0'
				  		";
		$Result = pg_query($Str_Update);	
		if($Result){ // Update ข้อมูลได้
			
		}else{ // Update ข้อมูลไม่ได้
			$status++;
			$Err_Msg .= " ไม่สามารถ UPDATE ข้อมูลในตาราง thcap_asset_biz_detail_central"."\n\r";
		}
			
		// บันทึกข้อมูลลงในตาราง  
		$Str_Ins =  "
						INSERT INTO thcap_asset_biz_detail_car(
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
							\"car_color\"
						)
						SELECT
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
							\"car_color\"
						FROM
							\"thcap_asset_biz_detail_car_temp\" a, \"thcap_asset_biz_detail_central\" b
						WHERE
							a.\"ascenID\" = b.\"ascenID\" AND
							a.\"ascenID\" = '$ascenID'
					";
		$Result = pg_query($Str_Ins); 
		if($Result){
		}else{
			$status++;
			$Err_Msg .= " ไม่สามารถนำเข้าตาราง ในตาราง thcap_asset_biz_detail_car ได้"."\n\r";
		}
		
		// แก้ไข เลขเครื่อง และ เลขตัวถัง ในตาราง thcap_asset_biz_detail
		$Str_Update = "
						UPDATE 
							\"thcap_asset_biz_detail\"
						SET 
							\"productCode\" = (select \"frame_no\" from \"thcap_asset_biz_detail_car\" where \"assetDetailID\" = '$assetDetailID'),
							\"secondaryID\" = (select \"engine_no\" from \"thcap_asset_biz_detail_car\" where \"assetDetailID\" = '$assetDetailID')
						WHERE 
							\"assetDetailID\" =	'$assetDetailID'
				  	";
		$Result = pg_query($Str_Update);	
		if($Result){ // Update ข้อมูลได้
		}else{ // Update ข้อมูลไม่ได้
			$status++;
			$Err_Msg .= " ไม่สามารถ UPDATE ข้อมูลในตาราง thcap_asset_biz_detail"."\n\r";
		}
	}

if($status == 0)
{
	pg_query("COMMIT");
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" style=\"cursor:pointer;\" onClick=\"RefreshMe();\"></center>";	
	}
}
else
{
	pg_query("ROLLBACK"); 
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#FF0000\">ไม่สามารถอนุมัติข้อมูล โดยอัตโนมัติได้ จำเป็นต้องอนุมัติด้วยตัวบุคคล $Err_Msg</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		echo "<center><h2><font color=\"#FF0000\">ไม่สามารถอนุมัติข้อมูลได้ $Err_Msg</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" style=\"cursor:pointer;\" onClick=\"RefreshMe();\"></center>";
	}
}