<?php
include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>เพิ่มรถยนต์</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../book_car_check/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../post/fancybox/lib/jquery-1.7.2.min.js"></script>  
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){

	$("#dateregis").datepicker({
			showOn: 'button',
			buttonImage: '../book_car_check/images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
	});
	
	$("#C_TAX_ExpDate").datepicker({
			showOn: 'button',
			buttonImage: '../book_car_check/images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
	});
	
		
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}		

function check_num(e){
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
}; 

function checklist(){
	var error = "__ Error! ________________ \n\nกรุณากรอกข้อมูล\n";
	var status = 0;
	if(document.frm.f_type_vehicle.disabled == false){
		if(document.frm.f_type_vehicle.value==""){
			error += "	- ประเภทรถ\n";
			status += 1;
		}else{
			if(document.frm.f_brand){
				if(document.frm.f_brand.value==""){
					error += "	- ยี่ห้อ\n";
					status += 1;
				}else{
					if(document.frm.f_model.value==""){
						error += "	- รุ่น\n";
						status += 1;	
					}
				}
			}	
		}
	}
	if(document.frm.C_YEAR.value == ""){
		error += "	- ปีรถยนต์ \n";
		status += 1;
	}
	if(document.frm.C_REGIS.value == ""){
		error += "	- เลขทะเบียน \n";
		status += 1;
	}
	if(document.frm.C_REGIS_BY.value == ""){
		error += "	- ทะเบียนจังหวัด \n";
		status += 1;
	}
	if(document.frm.f_carcolor.value == ""){
		error += "	- สีรถยนต์ \n";
		status += 1;
	}
	if(document.frm.C_CARNUM.value == ""){
		error += "	- รหัสตัวถังรถยนต์ \n";
		status += 1;
	}
	if(document.frm.C_MARNUM.value == ""){
		error += "	- รหัสเครื่องรถยนต์ \n";
		status += 1;
	}
	if(document.frm.C_Milage.value == ""){
		error += "	- เลขไมล์ \n";
		status += 1;
	}
	if(document.frm.C_StartDate.value == ""){
		error += "	- วันที่จดทะเบียน \n";
		status += 1;
	}
	if(document.frm.C_CAR_CC.value == ""){
		error += "	- ความจุเครื่องยนต์ \n";
		status += 1;
	}
	if(document.frm.gas_system.value == ""){
		error += "	- ระบบแก๊สรถยนต์ \n";
		status += 1;
	}
	error += "ให้ครบถ้วนด้วย ขอบคุณครับ/ค่ะ \n\n";
	error += "________________ Error! __ \n";
	if(status > 0){
		alert(error);
		return false;
	}else{
		return true;
	}
};

function clearlist(){
	
	document.frm.C_YEAR.value = "";
	document.frm.C_REGIS.value = "";
	document.frm.C_REGIS_BY.value = "";
	document.frm.f_carcolor.value = "";
	document.frm.C_CARNUM.value = "";
	document.frm.C_MARNUM.value = "";
	document.frm.C_Milage.value = "";
	document.frm.C_StartDate.value = "";
	document.frm.C_CAR_CC.value = "";
	document.frm.C_TAX_ExpDate.value = "";
	document.frm.C_TAX_MON.value = "";
	document.frm.RadioID.value = "";
};

</script>
</head>
<body bgcolor="#DFE6EF">
<form name="frm" method="post" action="process_add.php" enctype="multipart/form-data">
<table width="900" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center"> 			 
			<table width="850" frame="box" cellSpacing="0" cellPadding="5" align="center" bgcolor="#528B8B">
					<tr>						
						<td align="center">
						<div style="padding-top:20px;"></div>
						<font size="50px;"><b><font color="white">เพิ่มรถยนต์</font></b></font>
						<div style="padding-top:20px;"></div>
						</td>
					</tr>						
			</table>			
		</td>
    </tr>
	<tr>
        <td align="center"> 
			<table width="850" cellSpacing="0" cellPadding="1" frame="box"  align="center" bgcolor="#79CDCD">
				<tr>		
						<td rowspan="16" width="30" style="background-color:#79CDCD;"></td>
						<td colspan="7" align="right">วันที่  <?php echo $date = date('d-m-Y');?></td>
				</tr> 

				<tr>
					<td align="right">ประเภทรถ :</td>
					<td colspan="5">
						<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();">
							<?php 	$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถยนต์'  AND \"astypeStatus\" = '1'");
									while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
										$astype_astypeID = $re_sel_astype["astypeID"];
										$astype_astypeName = $re_sel_astype["astypeName"];	
										echo "<option value=\"$astype_astypeID\" >$astype_astypeName</option>";
									}
							?>		
							
						</select><font color="red">*</font>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr id="tr_show_brand" >
					<td align="right">ยี่ห้อ :</td>
					<td colspan="5"><span id="show_brand"></td>
					<td></td>
				</tr>
				<tr id="tr_show_model">
					<td align="right">รุ่น :</td>
					<td colspan="5"><span id="show_model"></td></td>
					<td></td>
				</tr>
				
				<tr>
					<td width="130" align="right">ทะเบียนรถ :</td>
					<td width="100" ><input type="text" size="10px" name="C_REGIS"><font color="red">*</font></td>
					<td width="70" align="right">จังหวัด :</td>
					<td colspan="2">
						<select style="width:150px;" name="C_REGIS_BY">
						<?php 
							$qry_provice = pg_query("SELECT * FROM nw_province order by \"proID\" ");
								while($re_provice = pg_fetch_array($qry_provice)){
									if($re_provice["proID"] == '02'){ $selected = "selected";}else{ $selected = "";}
									echo "<option value=\"$re_provice[proName]\" $selected>$re_provice[proName]</option>";
								}
						?>	
						</select><font color="red">*</font>
					</td>
					<td></td>	
				</tr>
				<tr>
					<td align="right">ปีรถ(ค.ศ.) :</td>
					<td><input type="text" size="5px" onkeypress="check_num(event)" maxlength="4" name="C_YEAR"><font color="red">*</font></td>
					<td align="right">สีรถ :</td>
					<td width="100">
					<?php include"../../post/default_car_color.php"; ?><font color="red">*</font>
					</td>
					<td width="130" align="right">ความจุเครื่องยนต์ :</td>
					<td><input type="text" size="10px" onkeypress="check_num(event)" maxlength="4" name="C_CAR_CC"> cc.<font color="red">*</font></td>	
				</tr>	
				<tr>
					<td align="right">เลขตัวถัง :</td>
					<td colspan="3"><input type="text" size="25px" name="C_CARNUM"><font color="red">*</font></td>
					<td align="right">รหัสเครื่องยนต์ :</td>
					<td colspan="2"><input type="text" size="25px" name="C_MARNUM"><font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right">เลขไมล์ :</td>
					<td colspan="3"><input type="text" size="10px" onkeypress="check_num(event)" name="C_Milage"><font color="red">*</font>กิโลเมตร</td>					
				</tr>
				<tr>
					<td align="right">วันที่หมดอายุภาษี :</td>
					<td><input type="text" size="10px" name="C_TAX_ExpDate" id="C_TAX_ExpDate"  value="<?php echo nowDate();?>"></td>	
					<td align="right">ค่าภาษี :</td> 
					<td><input type="text" size="10px" name="C_TAX_MON" onkeypress="check_num(event)"></td>		
				</tr>
				<tr>
					<td align="right">เลขวิทยุ :</td>
					<td colspan="5"><input type="text" size="20px" name="RadioID"> (เลขวิทยุ หรือ โทรศัพท์ที่ลูกค้าใช้ในรถ)</td>					
				</tr>
				<tr>
					<td align="right">ลักษณะรถยนต์ :</td>
					<td colspan="5">
						<select name="CarType">
							<option value="0">รถนั่งทั่วไป</option>
							<option value="1">แท็กซี่บริษัท</option>
							<option value="2">แท็กซี่เขียวเหลือง</option>
							<option value="3">แท็กซี่สีอื่นๆ</option>
						</select>
					</td>					
				</tr>
				<tr>
					<td align="right">วันที่จดทะเบียน :</td>
					<td colspan="5"><input type="text" size="10px" id="dateregis" name="C_StartDate" value="<?php echo nowDate();?>"><font color="red">*</font></td>									
				</tr>				
				<tr>
					<td align="right">ชนิดรถ :</td>
					<td colspan="5">
						<select name="f_useful_vehicle" id="f_useful_vehicle">
							<option value="รถรับจ้าง" selected>รถรับจ้าง</option>
							<option value="เก๋ง">เก๋ง</option>
							<option value="กระบะ">กระบะ</option>
							<option value="เอนกประสงค์">เอนกประสงค์</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align="right">เป็นรถ :</td>
					<td colspan="5">
						<select name="f_status_vehicle" id="f_status_vehicle">
							<option value="1" selected>รถใหม่</option>
							<option value="2">รถใช้แล้ว</option>
						</select>
					</td>
				</tr>
				<tr>
					  <td align="right">ระบบแก๊สรถยนต์ </td>
					  <td colspan="2">
						<select name="gas_system">
							<option value="" selected >- เลือก -</option>
							<option value="ไม่มีระบบ Gas" >ไม่มีระบบ Gas</option>
							<option value="NGV 100" >NGV 100</option>
							<option value="NGV 80" >NGV 80</option>
							<option value="LPG 100" >LPG 100</option>
						</select><font color="red">*</font>
					  </td>
				</tr>
				<tr>
					 <TD align="right">เอกสารแนบ :</TD>
					 <TD colspan="3"><INPUT TYPE="file" NAME="filedoc" id="filedoc"></TD>
				</tr>
				<tr>
					<td colspan="10"><hr width="550px"></td>
				</tr>
				<tr>	
				</tr>
				<tr>				
				</tr>
				<tr>
					<td align="center" colspan="5"><input type="submit" value=" บันทึก " style="width:150px;height:50px;" onclick="return checklist();"></td>
					<td align="center" colspan="5"><input type="button" value=" ยกเลิก " style="width:150px;height:50px;" onclick="clearlist()"></td>
				</tr>	
			</table>		
		</td>
    </tr>
	<tr>
		<td>
			<div style="padding-top:25px;"></div>
		</td>
	</tr>	
</table>
<table align="center" width="1250">
	<tr><td align="left"><font color="red">รายการที่รออนุมัติและอนุมัติแล้ว 30 รายการล่าสุด ! </font></td></tr>
</table>
<table align="center" frame="box" width="1250">
				
				<tr bgcolor="#9AC0CD">
					<th width="150">ยี่ห้อ</th>
					<th width="150">รุ่น</th>
					<th width="150">เลขทะเบียน</th>
					<th width="120">ความจุเครื่องยนต์</th>
					<th width="150">เลขตัวถังรถ</th>
					<th width="110">วันจดทะเบียน</th>
					<th width="110">รูปแบบรถยนต์</th>
					<th width="150">ผู้ขออนุมัติ</th>
					<th width="150">วันที่ขออนุมัติ</th>
					<th width="150">ผู้อนุมัติ</th>
					<th width="150">วันที่อนุมัติ</th>
					<th width="50">เพิ่มเติม</th>	
					<th width="50">สถานะ</th>					
				</tr>
<?php 			
			for($loop = 1;$loop<=2;$loop++){
					$i = 0;
					IF($loop == '1'){
						$qry_waitapp = pg_query("	SELECT * FROM \"Fc_temp\" WHERE \"appstatus\" = '0' 												
													ORDER BY \"date_submit\" 
													DESC limit(30)
												");
						$rowsqry1 = pg_num_rows($qry_waitapp);
						$trcolor1="'#BFEFFF'";
						$trcolor2="'#B2DFEE'";
					}
					
					IF($loop == '2' && $rowsqry1 < 30){
					
						$limit2 = 30 - $rowsqry1;
						$qry_waitapp = pg_query("	SELECT * FROM \"Fc_temp\" WHERE \"appstatus\" != '0' 												
													ORDER BY \"date_app\" DESC
													limit $limit2
												");
						$trcolor1="'#DDDDDD'";
						$trcolor2="'#EEEEEE'";				
					}else IF($loop == '2' && $rowsqry1 >= 30){
						$stop = 'stop';	
					}
										
											
					IF($stop != 'stop'){
							while($re_waitapp = pg_fetch_array($qry_waitapp)){
								$iduser = $re_waitapp['id_user'];
								$qry_user = pg_query("select fullname from \"Vfuser\" where \"id_user\" = '$iduser'");
								list($fullname) = pg_fetch_array($qry_user);
								
										$appuser = $re_waitapp['app_user'];
										$qry_user = pg_query("select fullname from \"Vfuser\" where \"id_user\" = '$appuser'");
										list($fullnameapp) = pg_fetch_array($qry_user);
										$appdate = $re_waitapp['date_app'];
										
								if($re_waitapp['appstatus'] == '0'){
									$status = 'รออนุมัติ';
									$fullnameapp = "-";
									$appdate = "-";
								}else if($re_waitapp['appstatus'] == '1'){
									$status = 'อนุมัติ';
									
								}else{
									$status = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('note_popup.php?cartempid=".$re_waitapp['CarIDtemp']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')\" ><u>ไม่อนุมัติ</u></a>";
								}
								
								if($re_waitapp['CarType'] == '0'){
									$CarType = 'รถนั่งทั่วไป';
								}else if($re_waitapp['CarType'] == '1'){
									$CarType = 'แท็กซี่บริษัท';
								}else if($re_waitapp['CarType'] == '2'){
									$CarType = 'แท็กซี่เขียวเหลือง';
								}else{
									$CarType = 'แท็กซี่สีอื่นๆ';
								}
								
								$fp_fc_model = $re_waitapp["fc_model"]; //รุ่น
								$fp_fc_brand = $re_waitapp["fc_brand"]; //ยี่ห้อ
								if($fp_fc_brand != ""){
									//หายี่ห้อ
									$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '$fp_fc_brand' ");
									list($fp_band) = pg_fetch_array($qry_sel_brand);
									
									//หารุ่น
									$qry_sel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '$fp_fc_model' ");
									list($fp_model) = pg_fetch_array($qry_sel_model);
								}else{
										$fp_band = $re_waitapp['C_CARNAME'];
										$fp_model = "";
								}
							
							$i++;
							if($i%2==0){
								echo "<tr bgcolor=$trcolor1 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = $trcolor1;\" align=center>";
							}else{
								echo "<tr bgcolor=$trcolor2 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = $trcolor2;\" align=center>";
							} ?>
								
										<td align="left"><?php echo $fp_band;?></td>
										<td align="left"><?php echo $fp_model; ?></td>
										<td><?php echo $re_waitapp['C_REGIS']."<br>".$re_waitapp['C_REGIS_BY'] ?></td>
										<td><?php echo $re_waitapp['C_CAR_CC'] ?></td>
										<td><?php echo $re_waitapp['C_CARNUM'] ?></td>
										<td><?php echo $re_waitapp['C_StartDate'] ?></td>
										<td><?php echo $CarType ?></td>
										<td><?php echo $fullname ?></td>
										<td><?php echo $re_waitapp['date_submit'] ?></td>
										<td><?php echo $fullnameapp ?></td>
										<td><?php echo $appdate ?></td>
										<td><img src="../manageCustomer/images/detail.gif" style="cursor:pointer;" onclick="javascript:popU('frm_detail.php?cartempid=<?php echo $re_waitapp['CarIDtemp'] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=450')"></td>		
										<td><?php echo $status ?></td>								
									</tr>
		<?php							
							}
					}		
				}
?>					
</table>
<table align="center" width="1250">
		<tr>
			<td align="right">
				<font color="red" size="2px;">*รายการที่ไม่อนุมัติ สามารถดูเหตุผลได้โดยการคลิกที่คำว่า " ไม่อนุมัติ "</font>
			</td>
		</tr>
</table>
</form>
</body>
<script type="text/javascript">
$('#tr_show_model').hide();
$('#tr_show_brand').hide();
show_brand_func();
function show_brand_func(){	
	var type = $('#f_type_vehicle option:selected').attr('value');
	if(type == ''){
		$('#tr_show_brand').hide();
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_brand').show();
		$('#tr_show_model').hide();
		$("#show_brand").load("../../post/combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	$('#tr_show_model').show();	
	var brandID = $('#f_brand option:selected').attr('value');
	$("#show_model").load("../../post/combo_model_list.php?brandID="+brandID);
} 
</script>