<?php
session_start(); 
include("../../../config/config.php");
include('get_text_assettype.php');
$Type_ID_Chk = pg_escape_string($_GET["ascenID"]);
// ดังประเภทสินทรัพย์
$Sql = " 
			SELECT 
					thcap_asset_biz_astype.\"astypeName\"
			FROM 
					thcap_asset_biz_detail_central,
					thcap_asset_biz_detail,
					thcap_asset_biz_astype
			WHERE  
					thcap_asset_biz_detail_central.\"ascenID\" = $Type_ID_Chk  AND
       				thcap_asset_biz_detail_central.\"assetDetailID\" = thcap_asset_biz_detail.\"assetDetailID\" AND 
       				thcap_asset_biz_astype.\"astypeID\" = thcap_asset_biz_detail.\"astypeID\"
       	";			      

$Result = pg_query($Sql);
$Data = pg_fetch_array($Result);
$car_type = $Data["astypeName"];
$In_ascenID = pg_escape_string($_GET["ascenID"]);
$Str_Get_For_View = "
						SELECT  
								\"thcap_asset_biz_brand\".\"brand_name\",
								\"thcap_asset_biz_model\".\"model_name\",
								\"thcap_asset_biz_detail_car_temp\".\"engine_no\",
								\"thcap_asset_biz_detail_car_temp\".\"frame_no\",
								\"thcap_asset_biz_detail_car_temp\".car_mileage,
								\"thcap_asset_biz_detail_car_temp\".car_color,
								\"thcap_asset_biz_detail_car_temp\".\"EngineCC\",
								\"thcap_asset_biz_detail_car_temp\".\"year_regis\",
								\"thcap_asset_biz_detail_car_temp\".\"regiser_no\",
								\"thcap_asset_biz_detail_car_temp\".\"register_date\",
								\"thcap_asset_biz_detail_car_temp\".\"register_province\"
	
						FROM 
								\"thcap_asset_biz_detail_car_temp\",
								\"thcap_asset_biz_detail_central\",
								\"thcap_asset_biz_detail\",
								\"thcap_asset_biz_brand\",
								\"thcap_asset_biz_model\"
								
						WHERE 	
								(thcap_asset_biz_detail_car_temp.\"ascenID\" = $In_ascenID) AND
      							(thcap_asset_biz_detail_car_temp.\"ascenID\" =  thcap_asset_biz_detail_central.\"ascenID\") AND
      							(thcap_asset_biz_detail.\"assetDetailID\" = thcap_asset_biz_detail_central.\"assetDetailID\") AND
      							(thcap_asset_biz_detail.brand = thcap_asset_biz_brand.\"brandID\") AND
      							(thcap_asset_biz_detail.model = thcap_asset_biz_model.\"modelID\")
      
				";

	
	$Result = pg_query($Str_Get_For_View);
	$Data = pg_fetch_array($Result);
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แสดงรายละเอียดทรัพย์สินสำหรับเช่า-ขาย</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
$(document).ready(function(){	
	<?php if($readonly != 't'){ ?>
		$("#dateregis").datepicker({
        	showOn: 'button',
	        buttonImage: '../images/calendar.gif',
    	    buttonImageOnly: true,
        	changeMonth: true,
	        changeYear: true,
    	    dateFormat: 'yy-mm-dd'
			display : none
    	});
	<?php 
	} 
	?>
});

 	


</script>
</head>
<body>
<div style="padding-top:50px;"></div>
<div align="center">
	<div style="width:900px; display:block;">
		<form action="process_add_car.php" method="POST" name="frm">
			<input type="hidden" name="method" value="<?php echo $method; ?>">
			<input type="hidden" name="appvauto" value="<?php echo $appvauto; ?>">
			<input type="hidden" name="hdassetDetailID" value="<?php echo $assetDetailID; ?>"> 
			<input type="hidden" name="Car_Code" value="<?php echo pg_escape_string($_GET['assettypeID']); ?>">
			<fieldset style="margin-bottom:15px;">
				<legend><b><?php echo $txthead;?>ข้อมูลรถ</b></legend>
				<table width="95%" frame="box" bgcolor="#EEEEEE">
					<tr>
						<td>
							<table width="100%" >
								<tr>
									<td width="5%"></td>
									<td width="40%"><b>ยี่ห้อ :</b> <?php echo $Data["brand_name"]; ?></td>
									<td ><b>รุ่น: </b><?php echo $Data["model_name"]; ?> </td>
				
								</tr>				
								<tr>
									<td></td>
									<td><b>เลขเครื่อง : </b><?php echo $Data["engine_no"]; ?> </td>
									<td colspan="2"><b>เลขตัวถัง :</b><?php echo $Data["frame_no"]; ?>
									<INPUT TYPE = HIDDEN Name = Frame_No  Value = "<?php echo $result["productCode"]; ?>">
									</td>
								</tr>
							</table>	
							<table width="100%" >	
								<tr>
									<td colspan="7" align="center"><hr></td>
								</tr>
								<tr>
									<td width="15%" align="right"><b>ประเภทรถ  : </b></td>
									<td width="20%"><input type="text" name="car_type" size="30" value="<?php echo $car_type; ?>" readonly="true"></td>
									<td width="20%" align="right" ><b>ระยะทางไมล์ : </b></td>
									<td width="25%"><input type="text" name="car_mileage" id = "car_mileage"  size="10" onkeypress="check_num(event);" value="<?php echo $Data["car_mileage"]; ?>" readonly = "true"><b> กิโลเมตร</b><font color="red" >*</font></td>
									<td width="25%" align="right" ><b>สีรถ : </b></td>
									<td width="20%" colspan="2" >
									<select name="car_color" id = "car_color" disabled>
										<option value="">--เลือก--</option>
										<?php	
											$qrycolor=pg_query("select * from \"thcap_asset_biz_detail_car_color\" order by \"car_color\"");
											while($rescolor=pg_fetch_array($qrycolor)){
												$id=$rescolor["auto_id"];
												$colorname=$rescolor["car_color"];
												
												$chk="";
												if($id==$Data["car_color"]){ $chk="selected"; }
												echo "<option value=\"$id\" $chk>$colorname</option>";
											}
										?>
									</select><font color="red" >*</font></td>
								</tr>
								<tr>
									<td align="right"><b>เลขเครื่อง :</b></td>
									<td><input type="text" size="50" name="engineno" id = "engineno" value = "<?php echo $Data["engine_no"];; ?>" readonly /><font color="red" >*</font></td>
									<td align="right" width="10%"><b>เลขตัวถัง : </b></td>
									<td colspan="4"><input type="text" size="50" name="bodyno" id = "bodyno" value="<?php echo $Data["frame_no"]; ?>" <?php echo $look; ?> readonly /><font color="red" >*</font></td>
								</tr>
								<tr>
									<td width="15%" align="right" ><b>ขนาด ซี.ซี. : </b></td>
									<td width="20%"><input type="text" name="cceg" id = "cceg" size="10" onkeypress="check_num(event);" value="<?php echo $Data["EngineCC"]; ?>" readonly ><font color="red" >*</font></td>
									<td width="20%" align="right"><b>ปีที่จดทะเบียน (พ.ศ.) : </b></td>
									<td><input type="text" size="10" name="yearregis" id = "yearregis" onkeypress="check_num(event);" value="<?php echo $Data["year_regis"]; ?>" readonly ><font color="red" >*</font></td>
									<td width="15%" align="right"  ><b>ทะเบียนรถ : </b></td>
									<td width="20%" colspan="2"><input type="text" name="regis" id = "regis" size="10" <?php echo $look; ?> value="<?php echo $Data["regiser_no"]; ?>" readonly ><font color="red" >*</font></td>
								
								</tr>
								<tr>
									<td width="20%" align="right"><b>วันที่จดทะเบียนรถ :</b></td>
									<td><input type="text" size="10" name="dateregis" id="dateregis" <?php echo $look; ?> value="<?php echo $Data["register_date"]; ?>" readonly ></td>
									<td width="25%" align="right"><b>จังหวัดที่จดทะเบียน : </b></td>
									<td>
									<select name="regis_province" id = "regis_province" disabled >
										<option value="">--เลือก--</option>
										<?php	
											$qryprovince=pg_query("select * from \"nw_province\" order by \"proName\"");
											while($resprovince=pg_fetch_array($qryprovince)){
												$id=$resprovince["proID"];
												$provincename=$resprovince["proName"];
												
												$chk="";
												if($provincename==$Data["register_province"]){ $chk="selected"; }
												echo "<option value=\"$provincename\" $chk>$provincename</option>";
											}
										?>
									</select><font color="red" >*</font></td>
									
								</tr>
							</table>	
								<div style="padding-top:20px;"></div>
							<table width="100%" >							
								<tr align="center">
									<td><input type="button" value=" ปิด "  onclick="window.close();" style="width:150px; height:50px; cursor:pointer;"/></td>
								</tr>
							</table>
						</td>
					</tr>	
			</fieldset>
		</form>
    </div>
</div>
</body>
</html>