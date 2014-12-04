<?php
session_start();
include("../../../config/config.php");

$id_user=$_SESSION["av_iduser"];  
//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')"); 
list($emplevel)=pg_fetch_array($qrylevel); 

$ascenID = pg_escape_string($_GET["ascenID"]);
$assetDetailID = pg_escape_string($_GET["assetDetailID"]);
$readonly = pg_escape_string($_GET["readonly"]); 
$appv = pg_escape_string($_GET["appv"]);
$method = pg_escape_string($_GET["method"]);
if ($method=='edit'){ 
 //* Old Par Code Not Use */
}else{
	$txttitle="(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย";
	$txthead="เพิ่ม"; 
	if($appv == 't'){ $txthead= "อนุมัติ";} 
	if($readonly == 't'){
		$look = 'readonly';
		$look2 = 'disabled';
		$ascenID = pg_escape_string($_GET["ascenID"]);
		// หา SQl Comand สำหรับดึงข้อมูลมาแสดง
		$Str_Qry = 	" SELECT 
  								thcap_asset_biz_brand.brand_name,
  								thcap_asset_biz_model.model_name,	
  								thcap_asset_biz_detail.\"secondaryID\",
								thcap_asset_biz_detail.\"productCode\",
  								thcap_asset_biz_detail_car_temp.frame_no,
  								thcap_asset_biz_astype.\"astypeName\",
  								thcap_asset_biz_detail_car_temp.\"car_mileage\",
  								thcap_asset_biz_detail_car_temp.\"car_color\",
  								thcap_asset_biz_detail_car_temp.\"engine_no\",
  								thcap_asset_biz_detail_car_temp.\"EngineCC\",
  								thcap_asset_biz_detail_car_temp.\"year_regis\",
  								thcap_asset_biz_detail_car_temp.\"regiser_no\",
  								thcap_asset_biz_detail_car_temp.\"register_date\",
  								thcap_asset_biz_detail_car_temp.\"register_province\",
  								thcap_asset_biz_detail.\"assetDetailID\"
					  FROM 
  								thcap_asset_biz_detail, 
  								thcap_asset_biz_model, 
  								thcap_asset_biz_brand, 
  								thcap_asset_biz_astype, 
  								thcap_asset_biz_detail_car_temp, 
  								thcap_asset_biz_detail_central
					  WHERE 
  								thcap_asset_biz_detail.brand = thcap_asset_biz_brand.\"brandID\" AND
  								thcap_asset_biz_model.\"modelID\" = thcap_asset_biz_detail.model AND
  								thcap_asset_biz_astype.\"astypeID\" = thcap_asset_biz_detail.\"astypeID\" AND
  								thcap_asset_biz_detail_central.\"ascenID\" = thcap_asset_biz_detail_car_temp.\"ascenID\" AND
  								thcap_asset_biz_detail_central.\"assetDetailID\" = thcap_asset_biz_detail.\"assetDetailID\" AND
  								thcap_asset_biz_detail_central.statusapp = '0' AND
  								thcap_asset_biz_detail_central.\"ascenID\" = '$ascenID'
					";
					
		$qry_detail = pg_query($Str_Qry);
		$resultdetail = pg_fetch_array($qry_detail);
		  
		$assetDetailID = $resultdetail["assetDetailID"];
		$engine_no = $resultdetail["engine_no"];
		$sopeg = $resultdetail["Pump_num"];
		$cceg = $resultdetail["EngineCC"];
		$yearregis = $resultdetail["year_regis"];
		$regis = $resultdetail["regiser_no"];
		$dateregis = $resultdetail["register_date"];
		$car_type = $resultdetail["astypeName"]; //ชนิดรถ
		$car_mileage = $resultdetail["car_mileage"]; //ระยะทางไมล์
		$car_color = $resultdetail["car_color"]; //สีรถ
		$car_province = $resultdetail["register_province"]; // echo 'ln71 '.$car_province.'<BR>';
		$frameno = $resultdetail["frame_no"]; 
		$engineno = $resultdetail["engine_no"]; 
		$secondaryID = $resultdetail["secondaryID"]; // เลขเครื่องเดิม
		$productCode = $resultdetail["productCode"]; // เลขถังเดิม
	}
	$qry_motorcycle = pg_query("
								SELECT *
								FROM \"thcap_asset_biz_detail\" a
								LEFT JOIN \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
								LEFT JOIN \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
								WHERE a.\"assetDetailID\" = '$assetDetailID'
						 ");
	$result = pg_fetch_array($qry_motorcycle);
}
	
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $txttitle;?></title>
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
		
    });
<?php } ?>
	$("#notappButton").click(function(){
			
		$('body').append('<div id="dialog"></div>');
	
		$('#dialog').load('pop-conf-no.php?ascenID=<?php echo $ascenID; ?>&assetDetailID=<?php echo $assetDetailID; ?>');
		$('#dialog').dialog({
			title: 'บันทึกเหตุผลการที่ไม่อนุมัติ',
			resizable: false,
			modal: true,  
			width: 500,
			height: 280,
			close: function(ev, ui){
				$('#dialog').remove();
			}
		});	
    });	


});
function confirmappv(){
	if(confirm('ยืนยันการอนุมัติ')==true){
		return true;
	}else{
		return false;
	}
}
function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
	if (key > 57)
		  window.event.returnValue = false;
		}else{
			key = e.which; // Firefox       
	if (key > 57)
		  key = e.preventDefault();
	  }
} 	

function chklist(){
	
	var message = 'กรุณากรอกข้อมูลให้ครบ ----------\n';
	numchk = 0; 
	
	
	var txt = document.getElementById('car_mileage').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- ระยะทางไมล์\n';	
		numchk = numchk + 1;
	}
	
	var txt = document.getElementById('car_color').value;
	if(txt == "")
	{
		message = message + '- สีรถ\n';	
		numchk = numchk + 1;
	}
	
	
	var txt = document.getElementById('bodyno').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- เลขตัวถัง\n';	
		numchk = numchk + 1;
	}
	
	
	var txt = document.getElementById('cceg').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- ขนาด ซี.ซี.\n';
		numchk = numchk + 1;
	}
	
	var txt = document.getElementById('cceg').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- ขนาด ซี.ซี.\n';
		numchk = numchk + 1;
	}
	
	var txt = document.getElementById('yearregis').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- ปีที่จดทะเบียน (พ.ศ.)\n';	
		numchk = numchk + 1;
	}
	
	
	var txt = document.getElementById('regis').value;
	txt = txt.trim(); 
	if(txt.length == 0){
		message = message + '- ทะเบียนรถ \n';	
		numchk = numchk + 1;
	}
	
	var txt = document.getElementById('dateregis').value;
	if(txt.length == 0){
		message = message + '- วันที่จดทะเบียนรถ \n';	
		numchk = numchk + 1;
	}
	
	var txt = document.getElementById('regis_province').value;
	if(txt == ""){
		message = message + '- จังหวัดที่จดทะเบียน \n';	
		numchk = numchk + 1;
	}
	
	
	if(numchk == 0){
		if(confirm('ยืนยันการบันทึกข้อมูล')==true){
				return true;
		}else{
				return false;
		}
	}else{
		alert(message);
		return false;
	}

}
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
									<td width="40%"><b>ยี่ห้อ :</b> <?php echo $result["brand_name"]; ?></td>
									<td ><b>รุ่น: </b><?php echo $result["model_name"]; ?> </td>
				
								</tr>				
								<tr>
									<td></td>
									<td ><b>เลขเครื่อง : </b><?php echo $secondaryID; ?></td>
									<td colspan="2"><b>เลขตัวถัง : </b><?php echo $productCode; ?></td>
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
									<td width="25%"><input type="text" name="car_mileage" id = "car_mileage"  size="10" onkeypress="check_num(event);" value="<?php echo $car_mileage ?>" <?php echo $look; ?>><b> กิโลเมตร</b><font color="red" >*</font></td>
									<td width="25%" align="right" ><b>สีรถ : </b></td>
									<td width="20%" colspan="2" >
									<select name="car_color" id = "car_color" <?php echo $look2; ?>>
										<option value="">--เลือก--</option>
										<?php	
											$qrycolor=pg_query("select * from \"thcap_asset_biz_detail_car_color\" order by \"car_color\"");
											while($rescolor=pg_fetch_array($qrycolor)){
												$id=$rescolor["auto_id"];
												$colorname=$rescolor["car_color"];
												
												$chk="";
												if($id==$car_color){ $chk="selected"; }
												echo "<option value=\"$id\" $chk>$colorname</option>";
											}
										?>
									</select><font color="red" >*</font></td>
								</tr>
								<tr>
									<td align="right"><b>เลขเครื่อง :</b></td>
									<td><input type="text" size="50" name="engineno" id = "engineno" value = "<?php echo $engineno; ?>" <?php echo $look; ?> ><font color="red" >*</font></td>
									<td align="right" width="10%"><b>เลขตัวถัง : </b></td>
									<td colspan="4"><input type="text" size="50" name="bodyno" id = "bodyno" value="<?php echo $frameno; ?>" <?php echo $look; ?>><font color="red" >*</font></td>
								</tr>
								<tr>
									<td width="15%" align="right" ><b>ขนาด ซี.ซี. : </b></td>
									<td width="20%"><input type="text" name="cceg" id = "cceg" size="10" onkeypress="check_num(event);" value="<?php echo $cceg ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td width="20%" align="right"><b>ปีที่จดทะเบียน (พ.ศ.) : </b></td>
									<td><input type="text" size="10" name="yearregis" id = "yearregis" onkeypress="check_num(event);" value="<?php echo $yearregis ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td width="15%" align="right"  ><b>ทะเบียนรถ : </b></td>
									<td width="20%" colspan="2"><input type="text" name="regis" id = "regis" size="10" <?php echo $look; ?> value="<?php echo $regis ?>"><font color="red" >*</font></td>
								
								</tr>
								<tr>
									<td width="20%" align="right"><b>วันที่จดทะเบียนรถ :</b></td>
									<td><input type="text" size="10" name="dateregis" id="dateregis" <?php echo $look; ?> value="<?php echo $dateregis ?>" readonly ></td>
									<td width="25%" align="right"><b>จังหวัดที่จดทะเบียน : </b></td>
									<td>
									<select name="regis_province" id = "regis_province" <?php echo $look2; ?>>
										<option value="">--เลือก--</option>
										<?php	
											$qryprovince=pg_query("select * from \"nw_province\" order by \"proName\"");
											while($resprovince=pg_fetch_array($qryprovince)){
												$id=$resprovince["proID"];
												$provincename=$resprovince["proName"];
												
												$chk="";
												if($provincename==$car_province){ $chk="selected"; }
												echo "<option value=\"$provincename\" $chk>$provincename</option>";
											}
										?>
									</select><font color="red" >*</font></td>
									
								</tr>
							</table>	
								<div style="padding-top:20px;"></div>
							<table width="100%" >							
								<tr align="center">
									<?php if($readonly != 't'){ ?>
									<td><input type="submit" value=" บันทึก " onclick="return chklist();" style="width:150px;height:50px;"> </td>
									<?php }else{ 
												if($appv == 't'){ ?>

													<!--td><input type="button" value=" อนุมัติ " id="appButton" style="width:150px;height:50px;"> </td>
													<td><input type="button" value=" ไม่อนุมัติ " id="notappButton"  style="width:150px;height:50px;"--> 
													<td><input type="button" value="อนุมัติ" id="appButton" style="width:150px;height:50px; "
													onclick="if(confirmappv()){ document.forms['my'].appv.click();}" 
													
													/></td>
												<td><input type="button" value=" ไม่อนุมัติ " id="notappButton"  style="width:150px;height:50px;"></td>
									<?php		}			
									 } ?>
									<td><input type="button" value=" ปิด "  onclick="window.close();" style="width:150px;height:50px;"> </td>
									
								</tr>
							</table>
						</td>
					</tr>	
			</fieldset>
		</form>
		<form name="my" method="post" action="process_approve-yes.php">
			<input type="hidden" name="ascenID" id="ascenID" value="<?php echo $ascenID;?>">
			<input type="hidden" name="assetDetailID" id="assetDetailID" value="<?php echo $assetDetailID;?>">
			<input type="hidden" name="frompage" id="frompage" value="appvdetail">
			<input name="appv" type="submit" value="อนุมัติ" hidden /></td>
		</form>	
    </div>
</div>
</body>
</html>