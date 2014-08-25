<?php
session_start();
include("../config/config.php");
$idno = $_GET["ID"];
/*$qry_chk1 = pg_query("	SELECT * FROM \"Fp\" 
						WHERE \"IDNO\" = '$idno' AND \"asset_id\" IN (SELECT \"CarID\" FROM \"Fc\")
					");
$row_chk1 = pg_num_rows($qry_chk1);
IF($row_chk1 > 0){
//หาในตาราง Fc 
	$typecar = 'normal';
	$qry_chk=pg_query("	SELECT * FROM \"Fp\" A 
						LEFT OUTER JOIN \"Fc\" B on B.\"CarID\" = A.\"asset_id\" 
						WHERE  	A.\"IDNO\" = '$idno' AND							
							   (B.\"fc_brand\" IS NULL OR
								B.\"fc_model\" IS NULL OR
								B.\"fc_category\" IS NULL OR
								B.\"fc_newcar\" IS NULL OR
								B.\"fc_gas\" IS NULL OR
								B.\"C_CARNUM\" IS NULL OR
								B.\"C_MARNUM\" IS NULL OR
								B.\"C_REGIS_BY\" IS NULL OR
								B.\"C_REGIS\" IS NULL OR
								B.\"C_YEAR\" IS NULL OR
								B.\"C_COLOR\" IS NULL OR
								B.\"C_Milage\" IS NULL) 
					");
	$row_chk = pg_num_rows($qry_chk);
	$res_fc = pg_fetch_array($qry_chk);
				$fc_color=trim($res_fc["C_COLOR"]);
				$fc_num=trim($res_fc["C_CARNUM"]);
				$fc_mar=trim($res_fc["C_MARNUM"]);
				$fc_mi=trim($res_fc["C_Milage"]);				
				$fp_fc_type = $res_fc["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
				$fp_fc_brand = $res_fc["fc_brand"]; //ยี่ห้อ
				$fp_fc_model = $res_fc["fc_model"]; //รุ่น
				$fp_fc_category = $res_fc["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
				$fp_fc_newcar = $res_fc["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
				$fp_fc_gas = $res_fc["fc_gas"]; //ระบบแก๊ส	
				$fc_year=trim($res_fc["C_YEAR"]);
				$fc_regis=trim($res_fc["C_REGIS"]);
				$fcs_regis_by=trim($res_fc["C_REGIS_BY"]);
				if(empty($fcs_regis_by))
				{
					$fc_regis_by="เลือก";
					$reg_value=" ";
				}
				else
				{
					$fc_regis_by=$fcs_regis_by;
					$reg_value=$fcs_regis_by;
				}
				$fc_cartype=trim($res_fc["CarType"]);
				if($fc_cartype==1)
				{
					$st_type="แท็กซี่บริษัท";
				}
				else if($fc_cartype==2)
				{
					$st_type="แท็กซี่เขียวเหลือง";
				}
				
				else if($fc_cartype==3)
				{
					$st_type="แท็กซี่สีอื่น ๆ";
				}
			   
				else if($fc_cartype=="0")
				{
					$st_type="ศูููนย์รถยนต์ทั่วไป";
				}else{
					$fc_cartype="";
					$st_type="----เลือก----";
				}
				
}ELSE{	

//หาในตาราง FGas
	$typecar = 'Gas';
	$qry_chk=pg_query("	SELECT * FROM \"Fp\" A 
						LEFT OUTER JOIN \"FGas\" B on B.\"GasID\" = A.\"asset_id\" 
						WHERE  	A.\"IDNO\" = '$idno' AND							
							   (B.\"car_regis\" IS NULL OR
								B.\"car_regis_by\" IS NULL OR
								B.\"car_year\" IS NULL OR
								B.\"carnum\" IS NULL OR
								B.\"marnum\" IS NULL OR
								B.\"fc_type\" IS NULL OR
								B.\"fc_brand\" IS NULL OR
								B.\"fc_model\" IS NULL OR
								B.\"fc_category\" IS NULL OR
								B.\"fc_newcar\" IS NULL OR
								B.\"fc_milage\" IS NULL) 
					");
	$row_chk = pg_num_rows($qry_chk);
	$re_chk = pg_fetch_array($qry_chk);
	
				//$fc_color=trim($re_chk["C_COLOR"]);
				$fc_num=trim($re_chk["carnum"]);
				$fc_mar=trim($re_chk["marnum"]);
				$fc_mi=trim($re_chk["fc_milage"]);				
				$fp_fc_type = $re_chk["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
				$fp_fc_brand = $re_chk["fc_brand"]; //ยี่ห้อ
				$fp_fc_model = $re_chk["fc_model"]; //รุ่น
				$fp_fc_category = $re_chk["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
				$fp_fc_newcar = $re_chk["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
				$fp_fc_gas = $re_chk["fc_gas"]; //ระบบแก๊ส	
				$fc_year=trim($re_chk["car_year"]);
				$fc_regis=trim($re_chk["car_regis"]);
				$fcs_regis_by=trim($re_chk["car_regis_by"]);
				if(empty($fcs_regis_by))
				{
					$fc_regis_by="เลือก";
					$reg_value=" ";
				}
				else
				{
					$fc_regis_by=$fcs_regis_by;
					$reg_value=$fcs_regis_by;
				}
}*/

$qry_chk1 = pg_query("	SELECT * FROM \"Fp\" 
						WHERE \"IDNO\" = '$idno' AND \"asset_id\" IN (SELECT \"CarID\" FROM \"Fc\")
					");
$row_chk1 = pg_num_rows($qry_chk1);
IF($row_chk1 > 0){
//หาในตาราง Fc 
	$typecar = 'normal';
}
ELSE{
//หาในตาราง FGas
	$typecar = 'Gas';
}

$qry_chk = pg_query("select *,to_char(\"C_TAX_ExpDate\", 'YYYY-MM-DD') AS exp_date from \"VCarregistemp\" where \"IDNO\" ='$idno'");
$row_chk = pg_num_rows($qry_chk);
$res_fc = pg_fetch_array($qry_chk);

$fc_color =trim($res_fc["C_COLOR"]);
$fc_num =trim($res_fc["C_CARNUM"]);
$fc_mar =trim($res_fc["C_MARNUM"]);
$fc_mi=trim($res_fc["C_Milage"]);				
$fp_fc_type = $res_fc["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
$fp_fc_brand = $res_fc["fc_brand"]; //ยี่ห้อ
$fp_fc_model = $res_fc["fc_model"]; //รุ่น
$fp_fc_category = $res_fc["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = $res_fc["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
$fp_fc_gas = $res_fc["fc_gas"]; //ระบบแก๊ส	
$fc_year=trim($res_fc["C_YEAR"]);
$fc_regis=trim($res_fc["C_REGIS"]);
$fcs_regis_by=trim($res_fc["C_REGIS_BY"]);

if(empty($fcs_regis_by))
{
	$fc_regis_by="เลือก";
	$reg_value=" ";
}
else
{
	$fc_regis_by=$fcs_regis_by;
	$reg_value=$fcs_regis_by;
}
$fc_cartype=trim($res_fc["CarType"]);
if($fc_cartype==1)
{
	$st_type="แท็กซี่บริษัท";
}
else if($fc_cartype==2)
{
	$st_type="แท็กซี่เขียวเหลือง";
}

else if($fc_cartype==3)
{
	$st_type="แท็กซี่สีอื่น ๆ";
}

else if($fc_cartype=="0")
{
	$st_type="ศูููนย์รถยนต์ทั่วไป";
}else{
	$fc_cartype="";
	$st_type="----เลือก----";
}

IF($row_chk == 0){

	echo "<meta http-equiv=\"refresh\" content=\"0; URL=pdf/pdf_contract.php?ID=$idno\">";
	exit();
}
echo $fc_color_c;





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
</script>



<body>
<center>
<form name="frm_edit" method="post" action="process_chk_car_print.php">
	<input type="hidden" name="ID" value="<?php echo $idno; ?>">
	<input type="hidden" name="typecar" value="<?php echo $typecar; ?>">
	<!--ที่-->
	<input type="hidden" name="fc_color" value="<?php echo $fc_color; ?>">
	<input type="hidden" name="fc_num" value="<?php echo $fc_num; ?>">
	<input type="hidden" name="fc_mar" value="<?php echo $fc_mar; ?>">
	<input type="hidden" name="fc_mi" value="<?php echo $fc_mi; ?>">			
	<input type="hidden" name="fp_fc_type" value="<?php echo $fp_fc_type ; ?>">
	<input type="hidden" name="fp_fc_brand" value="<?php echo $fp_fc_brand ; ?>">
	<input type="hidden" name="fp_fc_model" value="<?php echo $fp_fc_model; ?>">
	<input type="hidden" name="fp_fc_category" value="<?php echo $fp_fc_category ; ?>">
	<input type="hidden" name="fp_fc_newcar" value="<?php echo $fp_fc_newcar ; ?>">
	<input type="hidden" name="fp_fc_gas" value="<?php echo $fp_fc_gas; ?>">
	<input type="hidden" name="fc_year" value="<?php echo $fc_year; ?>">
	<input type="hidden" name="fc_regis" value="<?php echo $fc_regis; ?>">
	<input type="hidden" name="fcs_regis_by" value="<?php echo $fcs_regis_by; ?>">
	
	
	<name="fc" value="<?php echo $fc; ?>">
	<table frame="box" bgcolor="#FFFACD">
				<tr bgcolor="#CDC9A5">
					<td colspan="3" align="center"><h3><?php echo $idno ?></h3><br><font color="#8B4513">กรุณากรอกข้อมูลต่อไปนี้ให้ครบถ้วนก่อนการพิมพ์สัญญา</font></td>
				</tr>
				<tr>
					<td align="right">ประเภทรถ </td>
					<td >
						<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();lockcat(this);">
							<?php 	$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" LIKE 'รถ%'  AND \"astypeStatus\" = '1'");
									echo "<option value=\"\" >- เลือกประเภทรถ -</option>";
									while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
										$astype_astypeID = $re_sel_astype["astypeID"];
										$astype_astypeName = $re_sel_astype["astypeName"];	
										if($astype_astypeID == $fp_fc_type){ $selected = "selected"; }else{ $selected = ""; }
										echo "<option value=\"$astype_astypeID\" $selected >$astype_astypeName</option>";
									}
							?>	
							<?php 
									$qry_sel_astype = pg_query("select \"astypeID\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถจักรยานยนต์'  AND \"astypeStatus\" = '1'"); 
									list($motercycle) = pg_fetch_array($qry_sel_astype);
									
									echo "<input type=\"hidden\" name=\"chk_mocy\" value=\"$motercycle\">";				
							?>										
							
						</select><font color="red">*</font>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<?php 
					if($typecar != 'Gas'){ 
						IF($fp_fc_brand == ""){
							echo "<tr>
										<td ></td>
										<td colspan=\"2\"><font color=\"gray\">ยี่ห้อ\รุ่นเดิม : <b>$fc_name</b></font></td>
									</tr>";
						}
					}
				?>
				<tr id="tr_show_brand" >
					<td align="right">ยี่ห้อ </td>
					<td colspan="2"><span id="show_brand"></td>
				</tr>							
				<tr id="tr_show_model">
					<td align="right">รุ่น </td>
					<td colspan="2"><span id="show_model"></td></td>
					
				</tr>
		<?php if($typecar != 'Gas'){ ?>
			<tr>
				<td height="30" align="right">รูปแบบรถ</td>
				<td>
					<select name="f_cartype" tabindex="24" <?php echo $disabled;?>>
						<option value="<?php echo $fc_cartype; ?>"><?php echo $st_type; ?></option> 
						<option value="1">แท็กซี่บริษัท</option>
						<option value="2">แท็กซี่เขียวเหลือง</option>
						<option value="3">แท็กซี่สีอื่น ๆ</option>
						<option value="0">ศูููนย์รถยนต์ทั่วไป</option>
					</select><font color="red">*</font>
				</td>
				<td colspan="4">&nbsp;</td>
			</tr>
		<?php } ?>
			<tr>
				<td align="right">รุ่นปี</td>
				<td><input type="text" name="f_caryear" value="<?php echo $fc_year; ?>" tabindex="25" <?php echo $readonly;?>/><font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">เลขตัวถัง</td>
				<td><input size="30" type="text" name="f_carnum" value="<?php echo $fc_num; ?>" tabindex="26" <?php echo $readonly;?>/><font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">เลขเครื่องยนต์</td>
				<td><input type="text" name="f_carmar" value="<?php echo $fc_mar; ?>" tabindex="27" <?php echo $readonly;?>/><font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">ทะเบียน</td>
				<td><input type="text" name="f_carregis" value="<?php echo $fc_regis; ?>" tabindex="28" <?php echo $readonly;?>/><font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">จังหวัดที่จดทะเบียน</td>
				<td>
					<select name="f_pprovince" size="1" >
					<option value="" <?php if($reg_value == "" ){?> selected <?php }?>>---เลือก---</option>
						<?php
						$query_province=pg_query("select * from \"nw_province\" order by \"proName\"");
						while($res_pro = pg_fetch_array($query_province)){
						?>
						<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$reg_value){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
						<?php
						}
						?>
					</select>	<font color="red">*</font>
				</td>
				<td colspan="4">&nbsp;</td>
			</tr>
		<?php if($typecar != 'Gas'){ ?>	
			<tr>
				<td align="right">สี</td>
				<td>
				<select name="f_carcolor" >
											<!--option value="เหลือง" <?php if($fc_color == "เหลือง"){ echo "selected"; } ?>>เหลือง</option>
										 <option value="เขียว-เหลือง"<?php if($fc_color == "เขียว-เหลือง"){ echo "selected"; } ?>>เขียว-เหลือง</option>
										 <option value="ฟ้า" <?php if($fc_color == "ฟ้า"){ echo "selected"; } ?>>ฟ้า</option>
										 <option value="ดำ" <?php if($fc_color == "ดำ"){ echo "selected"; } ?>>ดำ</option-->
				<?php
					$qry_carcolor = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '3'");
					while($objResuut = pg_fetch_array($qry_carcolor)){
						$carcolorname = trim($objResuut["elementsName"]);
						if($carcolorname == $fc_color){  $selected = "selected"; }else{ $selected = ""; }
						echo "<option value=\"$carcolorname\" $selected>$carcolorname</option>";
					}?>
				</select>
				<font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
		<?php } ?>	
				<tr>
					<td align="right">ชนิดรถ </td>
					<td>
						<select name="f_useful_vehicle" id="f_useful_vehicle">
							<!--option value="" <?php if($fp_fc_category == ""){ echo "selected"; } ?>>- ไม่ระบุ -</option>
							<option value="รถรับจ้าง" <?php if($fp_fc_category == "รถรับจ้าง"){ echo "selected"; } ?>>รถรับจ้าง</option>
							<option value="เก๋ง" <?php if($fp_fc_category == "เก๋ง"){ echo "selected"; } ?>>เก๋ง</option>
							<option value="กระบะ" <?php if($fp_fc_category == "กระบะ"){ echo "selected"; } ?>>กระบะ</option>
							<option value="เอนกประสงค์" <?php if($fp_fc_category == "เอนกประสงค์"){ echo "selected"; } ?>>เอนกประสงค์</option>
							<option value="ตู้นั่งสี่ตอน" <?php if($fp_fc_category == "ตู้นั่งสี่ตอน"){ echo "selected"; } ?>>ตู้นั่งสี่ตอน</option>
							<option value="รถยนต์บริการทัศนาจร" <?php if($fp_fc_category == "รถยนต์บริการทัศนาจร"){ echo "selected"; } ?>>รถยนต์บริการทัศนาจร</option-->
							<?php
								$qry_useful_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '2'");
								while($objResuut = pg_fetch_array($qry_useful_vehicle)){				$useful_vehiclename = trim($objResuut["elementsName"]);
									if($useful_vehiclename  == $fp_fc_category){ $selected = "selected"; }else{ $selected = ""; }
									echo "<option value=\"$useful_vehiclename\" $selected>$useful_vehiclename</option>";
								}?>
						</select><font color="red">*</font>
					</td>
				</tr>		
				<tr>
					<td align="right">เป็นรถ </td>
					<td>
						<select name="f_status_vehicle" id="f_status_vehicle">
							<!--option value="1" <?php if($fp_fc_newcar == "1"){ echo "selected"; } ?>>รถใหม่</option>
							<option value="2" <?php if($fp_fc_newcar == "2"){ echo "selected"; } ?>>รถใช้แล้ว</option-->
							<?php
								$qry_status_vehicle = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '1'");
								while($objResuut = pg_fetch_array($qry_status_vehicle)){
									$status_vehicleID = trim($objResuut["auto_id"]);
									$status_vehiclename = trim($objResuut["elementsName"]);
									if($status_vehicleID == $fp_fc_newcar){ $selected = "selected"; }else{ $selected = ""; }
									echo "<option value=\"$status_vehicleID\" $selected>$status_vehiclename</option>";
								}?>
						</select><font color="red">*</font>
					</td>
				</tr>
			<tr>
				<td align="right">เลขไมล์</td>
				<td><input type="text" name="f_carmi" value="<?php echo $fc_mi;?>" tabindex="31" <?php echo $readonly;?>/><font color="red">*</font></td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				  <td align="right">ระบบแก๊สรถยนต์ </td>
				  <td colspan="2">
					<select name="gas_system">
						<?php
							$qry_gas = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '4'");
							echo "<option value=\"\" >เลือกรายการ</option>";
							while($objResuut = pg_fetch_array($qry_gas)){
								$gasname = trim($objResuut["elementsName"]);
								if($gasname== $fp_fc_gas){ $selected = "selected"; }else{ $selected = ""; }
									echo "<option value=\"$gasname\" $selected>$gasname</option>";
							}?>
					</select><font color="red">*</font>
				  </td>
			</tr>
			<tr>
				<td><div style="padding-top:20px;" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value=" บันทึก " onclick="return chklist();" style="width:100px;height:50px;">
				<input type="button" value=" ปิด " onclick="window.close();" style="width:80px;height:50px;"></td>
			</tr>
	</table>
</form>
</center>
</body>		
<script type="text/javascript">
function chklist(){
	
	if(document.frm_edit.f_type_vehicle.value==""){
		alert("   กรุณาระบุประเภทรถ" ); 
		return false;	
	}else{
		if(document.frm_edit.f_brand){
			if(document.frm_edit.f_brand.value==""){
				alert("   กรุณาระบุยี่ห้อ" ); 
				return false;	
			}else{
				if(document.frm_edit.f_model.value==""){
					alert("   กรุณาระบุรุ่น" ); 
					return false;					
				}
			}
		}	
	}
	if (document.frm_edit.f_caryear.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ รุ่นปี" );         
		document.frm_edit.f_caryear.focus();
		return false;
	}
	if (document.frm_edit.f_carnum.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ เลขตัวถัง" );         
		document.frm_edit.f_carnum.focus();
		return false;
	}
<?php if($typecar != 'Gas'){ ?>
	if (document.frm_edit.f_cartype.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ รูปแบบรถ" );         
		document.frm_edit.f_cartype.focus();
		return false;
	}
	if (document.frm_edit.f_carcolor.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ สีรถ" );         
		document.frm_edit.f_carcolor.focus();
		return false;
	}
<?php } ?>	
	if (document.frm_edit.f_carmar.value.replace( /\s+$/, "" )==""){
		alert("กรุณาใส่ เลขเครื่องยนต์" );         
		document.frm_edit.f_carmar.focus();
		return false;
	}
	if (document.frm_edit.f_carregis.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่  ทะเบียน" );         
		document.frm_edit.f_carregis.focus();
		return false;
	}

	if (document.frm_edit.f_carmi.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่ เลขไมล์" );         
		document.frm_edit.f_carmi.focus();
		return false;
	}
	if (document.frm_edit.f_pprovince.value.replace( /\s+$/, "" )==""){
		alert(" กรุณาใส่ จังหวัด" );         
		document.frm_edit.f_pprovince.focus();
		return false;
	}
	
	if (document.frm_edit.gas_system.disabled==false){	
		if (document.frm_edit.gas_system.value.replace( /\s+$/, "" )==""){
			alert("   กรุณาระบุระบบแก๊สรถยนต์" );         
			return false;
		}else if (document.frm_edit.f_useful_vehicle.value.replace( /\s+$/, "" )==""){
			alert(" กรุณาใส่ ชนิดรถ" );         
			document.frm_edit.f_useful_vehicle.focus();
			return false;
		}
	}
	
	
}

var type1 = '<?php echo $fp_fc_type; ?>';
if(type1 != ""){
	var brandID1 = '<?php echo $fp_fc_brand; ?>';
	var model1 = '<?php echo $fp_fc_model; ?>';
	$("#show_brand").load("combo_brand_list.php?type="+type1+"&brand="+brandID1);
	$("#show_model").load("combo_model_list.php?brandID="+brandID1+"&model="+model1);
}else{
	$('#tr_show_brand').hide();
	$('#tr_show_model').hide();
}
function show_brand_func(){	
	var type = $('#f_type_vehicle option:selected').attr('value');
	if(type == ''){
		$('#tr_show_brand').hide();
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_brand').show();
		$('#tr_show_model').hide();
		$("#show_brand").load("combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	var brandID = $('#f_brand option:selected').attr('value');
	
	if(brandID == ''){	
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_model').show();
		$("#show_model").load("combo_model_list.php?brandID="+brandID);
	}	
} 
if(document.frm_edit.f_type_vehicle.value == document.frm_edit.chk_mocy.value){ 
	document.frm_edit.f_useful_vehicle.value='';
	document.frm_edit.f_useful_vehicle.disabled = true;
	document.frm_edit.gas_system.value='';
	document.frm_edit.gas_system.disabled = true;
}
function lockcat(type){
		if(type.value == document.frm_edit.chk_mocy.value){ 
			document.frm_edit.f_useful_vehicle.value='';
			document.frm_edit.f_useful_vehicle.disabled = true;
			document.frm_edit.gas_system.value='';
			document.frm_edit.gas_system.disabled = true;
		}else{
			document.frm_edit.f_useful_vehicle.value='รถรับจ้าง';
			document.frm_edit.f_useful_vehicle.disabled = false;
			document.frm_edit.gas_system.value='';
			document.frm_edit.gas_system.disabled = false;
		}
}	
</script>				