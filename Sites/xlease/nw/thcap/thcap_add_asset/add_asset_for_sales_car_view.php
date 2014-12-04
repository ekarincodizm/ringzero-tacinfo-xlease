<?php
session_start();
include("../../../config/config.php");

$id_user=$_SESSION["av_iduser"];
//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

$assetDetailID = pg_escape_string($_GET["assetdetailID"]);
$readonly = pg_escape_string($_GET["readonly"]);
$appv = pg_escape_string($_GET["appv"]);
$method = pg_escape_string($_GET["method"]);
$ascenID = pg_escape_string($_GET["ascenID"]); 
$txttitle="(THCAP) แสดงรายละเอียดสินทรัพย์สำหรับเช่า-ขาย";
$txthead="เพิ่ม";


	$Sql_load_data = "
						SELECT 
								\"thcap_asset_biz_brand\".\"brand_name\",
								\"thcap_asset_biz_model\".\"model_name\",
								\"thcap_asset_biz_detail_car_temp\".\"frame_no\",
								\"thcap_asset_biz_detail_car_temp\".\"engine_no\",
								d.\"astypeName\",
								\"thcap_asset_biz_detail_car_temp\".\"car_mileage\",
								\"thcap_asset_biz_detail_car_temp\".\"car_color\",
								\"thcap_asset_biz_detail_car_temp\".\"EngineCC\",
								\"thcap_asset_biz_detail_car_temp\".\"year_regis\",
								\"thcap_asset_biz_detail_car_temp\".\"regiser_no\",
								\"thcap_asset_biz_detail_car_temp\".\"register_date\",
								\"thcap_asset_biz_detail_car_temp\".\"register_province\"
	 					FROM 	
								\"thcap_asset_biz_detail\" c 
									LEFT JOIN \"thcap_asset_biz_astype\" d
										ON c.\"astypeID\" = d.\"astypeID\",
								\"thcap_asset_biz_brand\",
								\"thcap_asset_biz_model\",
								\"thcap_asset_biz_detail_central\" a
									LEFT JOIN \"Vfuser\" b
										ON a.\"appID\" = b.\"id_user\",
								\"thcap_asset_biz_detail_car_temp\",
								\"Vfuser\" 
						WHERE 
								(c.\"assetDetailID\" = a.\"assetDetailID\") AND 
								(\"thcap_asset_biz_model\".\"modelID\" = c.\"model\") AND 
								(\"thcap_asset_biz_detail_car_temp\".\"ascenID\" = a.\"ascenID\") AND 
								(c.\"brand\" = \"thcap_asset_biz_brand\".\"brandID\") AND 
								(a.\"doerID\" = \"Vfuser\".\"id_user\") and 
								(a.\"statusapp\" > 0) and 
								(a.\"ascenID\" = $ascenID ) 
	
	
	
					 ";
	// echo '<BR>test<BR>'.$Sql_load_data;
	$qry_motorcycle = pg_query($Sql_load_data);
					 
	$result = pg_fetch_array($qry_motorcycle);

	
		
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
	
		$('#dialog').load('pop-conf.php?ascenID=<?php echo $ascenID; ?>');
		$('#dialog').dialog({
			title: 'ยืนยันการตรวจสอบ ข้อมูลไม่ถูกต้อง',
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
									<td><b>เลขเครื่อง : </b><?php echo $result["engine_no"]; ?> </td>
									<td colspan="2"><b>เลขตัวถัง : </b>
									<?php 
										echo $result["frame_no"]; 
										
									?> 
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
									<td width="20%"><input type="text" name="car_type" size="15" value="<?php echo $result["astypeName"]; ?>" readonly="true"></td>
									<td width="20%" align="right" ><b>ระยะทางไมล์ : </b></td>
									<td width="25%"><input type="text" name="car_mileage" id = "car_mileage"  size="10" onkeypress="check_num(event);" value="<?php echo $result["car_mileage"]; ?>" <?php echo $look; ?>><b> กิโลเมตร</b><font color="red" >*</font></td>
									<td width="25%" align="right" ><b>สีรถ : </b></td>
									<td width="20%" colspan="2" >
									<select name="car_color" id = "car_color" disabled >
										<option value="">--เลือก--</option>
										<?php	
											$qrycolor=pg_query("select * from \"thcap_asset_biz_detail_car_color\" order by \"car_color\"");
											while($rescolor=pg_fetch_array($qrycolor)){
												$id=$rescolor["auto_id"];
												$colorname=$rescolor["car_color"];
												$chk="";
												if($id==$result["car_color"]){ $chk="selected"; }
												echo "<option value=\"$id\" $chk>$colorname</option>";
											}
										?>
									</select><font color="red" >*</font></td>
								</tr>
								<tr>
									<td align="right" width="10%"><b>เลขตัวถัง : </b></td>
									<td><input type="text" size="50" name="bodyno" id = "bodyno" value="<?php echo $result["frame_no"]; ?>" readonly = true><font color="red" >*</font></td>
									<td align="right"><b>เลขเครื่อง :</b></td>
									<td colspan = "4"><input type="text" size="50" name="engineno" id = "engineno" value = "<?php echo $result["engine_no"]; ?>" readonly = true ><font color="red" >*</font></td>
								</tr>
								<tr>
									<td width="15%" align="right" ><b>ขนาด ซี.ซี. : </b></td>
									<td width="20%"><input type="text" name="cceg" id = "cceg" size="10" onkeypress="check_num(event);" value="<?php echo $result["EngineCC"]; ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td width="20%" align="right"><b>ปีที่จดทะเบียน (พ.ศ.) : </b></td>
									<td><input type="text" size="10" name="yearregis" id = "yearregis" onkeypress="check_num(event);" value="<?php echo $result["year_regis"]; ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td width="15%" align="right"  ><b>ทะเบียนรถ : </b></td>
									<td width="20%" colspan="2"><input type="text" name="regis" id = "regis" size="10" readonly = true value="<?php echo $result["regiser_no"]; ?>"><font color="red" >*</font></td>
								
								</tr>
								<tr>
									<td width="20%" align="right"><b>วันที่จดทะเบียนรถ :</b></td>
									<td><input type="text" size="10" name="dateregis" id="dateregis" <?php echo $look; ?> value="<?php echo $result["register_date"]; ?>" readonly ></td>
									<td width="25%" align="right"><b>จังหวัดที่จดทะเบียน : </b></td>
									<td>
									<select name="regis_province" id = "regis_province" disabled >
										<option value="">--เลือก--</option>
										<?php	
											$qryprovince=pg_query("select * from \"nw_province\" order by \"proName\"");
											while($resprovince=pg_fetch_array($qryprovince)){
												$id=$resprovince["proID"];
												$provincename = $resprovince["proName"];
												$chk="";
												if($provincename==$result["register_province"]){ $chk="selected"; }
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
		<form name="my" method="post" action="process_approve.php">
			<input type="hidden" name="ascenID" id="ascenID" value="<?php echo $ascenID;?>">
			<input type="hidden" name="frompage" id="frompage" value="appvdetail">
			<input name="appv" type="submit" value="อนุมัติ" hidden /></td>
		</form>	
    </div>
</div>
</body>
</html>