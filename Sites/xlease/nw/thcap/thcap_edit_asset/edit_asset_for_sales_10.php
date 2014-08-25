<?php
session_start();
include("../../../config/config.php");

$id_user=$_SESSION["av_iduser"];
//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

$assetDetailID = $_GET["assetDetailID"];
$readonly = $_GET["readonly"];
$appvauto = $_GET["appvauto"];
if($readonly == 't'){
	$ascenID = $_GET["ascenID"];
	$look = 'readonly';
	$realdata = $_GET["realdata"];
	IF($realdata == 't'){
		$qry_detail = pg_query("
									select * from \"thcap_asset_biz_detail\" e	
									LEFT JOIN  \"thcap_asset_biz_detail_10\" a ON a.\"assetDetailID\" = e.\"assetDetailID\"
									LEFT JOIN \"thcap_asset_biz_model\" b ON e.\"model\" = b.\"modelID\"
									LEFT JOIN \"thcap_asset_biz_brand\" c ON e.\"brand\" = c.\"brandID\"
									WHERE e.\"assetDetailID\" = '$assetDetailID'
							 ");
	}else{
		$qry_detail = pg_query("
									select * from \"thcap_asset_biz_detail_10_temp\" d1
									left join \"thcap_asset_biz_detail_central\" d2 on d1.\"ascenID\" = d2.\"ascenID\" 
									LEFT JOIN \"thcap_asset_biz_detail\" e ON d2.\"assetDetailID\" = e.\"assetDetailID\"
									LEFT JOIN \"thcap_asset_biz_model\" b ON e.\"model\" = b.\"modelID\"
									LEFT JOIN \"thcap_asset_biz_brand\" c ON e.\"brand\" = c.\"brandID\"
									WHERE d2.\"ascenID\" = '$ascenID'
							 ");
	}						 
	$result = pg_fetch_array($qry_detail);	
	$assetDetailID = $result["assetDetailID"];
	$bodyno = $result["motorcycle_no"];
	$sopeg = $result["Pump_num"];
	$cceg = $result["EngineCC"];
	$yearregis = $result["year_regis"];
	$regis = $result["regiser_no"];
	$dateregis = $result["register_date"];
	
	
}else{
	$qry_motorcycle = pg_query("
								SELECT *
								FROM \"thcap_asset_biz_detail\" a
								LEFT JOIN \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
								LEFT JOIN \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
								LEFT JOIN \"thcap_asset_biz_detail_10\" d ON a.\"assetDetailID\" = d.\"assetDetailID\"
								WHERE a.\"assetDetailID\" = '$assetDetailID'
						 ");
	$result = pg_fetch_array($qry_motorcycle);
	$bodyno = $result["motorcycle_no"];
	$sopeg = $result["Pump_num"];
	$cceg = $result["EngineCC"];
	$yearregis = $result["year_regis"];
	$regis = $result["regiser_no"];
	$dateregis = $result["register_date"];
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) แก้ไขละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
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

	$("#appButton").click(function(){
			if(confirm('ยืนยันการอนุมัติ')==true){
				$.post("process_approve.php",{
					cmd : "app",
					ascenID :'<?php echo $ascenID;?>',
				},
				function(data){
					if(data == "1"){
						alert("อนุมัติเรียบร้อยแล้ว");
						opener.location.reload(true);
						self.close();
					}else if(data == "2"){
						alert("ผิดผลาด  ไม่สามารถอนุมัติรายการดังกล่าวได้!");
					}
				});	
			}else{
				return false;
			}
	});

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
	
	if(document.frm.bodyno.value==""){
		message = message + '- เลขตัวถัง\n';	
		numchk = numchk + 1;
	}
	if(document.frm.sopeg.value==""){
		message = message + '- จำนวนสูบ\n';	
		numchk = numchk + 1;
	}
	if(document.frm.cceg.value==""){
		message = message + '- ขนาด ซี.ซี.\n';	
		numchk = numchk + 1;
	}
	if(document.frm.yearregis.value==""){
		message = message + '- ปีที่จดทะเบียน\n';	
		numchk = numchk + 1;
	}
<?php IF($emplevel <= 1){	?>
	if(document.frm.newproductcode.value==""){
		message = message + '- เลขเครื่อง\n';	
		numchk = numchk + 1;
	}
<?php } ?>
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
	<div style="width:800px; display:block;">
	<form action="process_edit_10.php" method="POST" name="frm">	
			<input type="hidden" name="hdassetDetailID" value="<?php echo $assetDetailID; ?>">
			<input type="hidden" name="appvauto" value="<?php echo $appvauto ?>">
			<fieldset style="margin-bottom:15px;">
				<legend><b>แก้ไขข้อมูลรถจักรยานยนต์</b></legend>
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
									<td><b>รหัสรุ่น : </b><?php echo $result["secondaryID"]; ?> </td>
									<td colspan="2"><b>เลขเครื่อง : </b>
									<?php 
										IF($emplevel <= 1){
											echo "<input type=\"text\" name=\"newproductcode\" value=\"".$result["productCode"]."\"><font color=\"red\" >*</font>";
										}else{
											echo $result["productCode"]; 
										}	
									?> 
									</td>
								</tr>
							</table>	
							<table width="100%" >	
								<tr>
									<td colspan="6" align="center"><hr></td>
								</tr>
								<tr>
									<td align="right" width="10%"><b>เลขตัวถัง : </b></td>
									<td colspan="3"><input type="text" size="50" name="bodyno" value="<?php echo $bodyno ?>" <?php echo $look; ?>><font color="red" >*</font></td>
								</tr>
								<tr>
									<td align="right"><b>จำนวนสูบ : </b></td>
									<td width="15%"><input type="text" name="sopeg" size="10" onkeypress="check_num(event);" value="<?php echo $sopeg ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td width="15%" align="right" ><b>ขนาด ซี.ซี. : </b></td>
									<td width="20%"><input type="text" name="cceg" size="10" onkeypress="check_num(event);" value="<?php echo $cceg ?>" <?php echo $look; ?>><font color="red" >*</font></td>
									<td align="right"><b>ปีที่จดทะเบียน : </b></td>
									<td><input type="text" size="10" name="yearregis" onkeypress="check_num(event);" value="<?php echo $yearregis ?>" <?php echo $look; ?>><font color="red" >*</font></td>
								</tr>
								<tr>
									<td align="right"><b>ทะเบียนรถ : </b></td>
									<td><input type="text" name="regis" size="10" <?php echo $look; ?> value="<?php echo $regis ?>"></td>
									<td align="right"><b>วันที่จดทะเบียนรถ :</b></td>
									<td><input type="text" size="10" name="dateregis" id="dateregis" <?php echo $look; ?> value="<?php echo $dateregis ?>"></td>
									
								</tr>
							</table>	
								<div style="padding-top:20px;"></div>
							<table width="100%" >							
								<tr align="center">
									<?php if($readonly != 't'){ ?>
									<td><input type="submit" value=" บันทึก " onclick="return chklist();" style="width:150px;height:50px;"> </td>
									<?php }else{ 
												if($appv == 't'){ ?>
													<td><input type="button" value=" อนุมัติ " id="appButton" style="width:150px;height:50px;"> </td>
													<td><input type="button" value=" ไม่อนุมัติ " id="notappButton"  style="width:150px;height:50px;"> </td>
									<?php		}			
									 } ?>
									<td><input type="button" value=" ปิด "  onclick="window.close();" style="width:150px;height:50px;"> </td>
									
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