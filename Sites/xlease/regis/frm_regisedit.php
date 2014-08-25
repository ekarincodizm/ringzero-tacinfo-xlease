<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<div align="left"><input name="button" type="button" onclick="window.location='frm_regisindo.php'" value=" ย้อนกลับ " /></div> 

<fieldset><legend><B>แก้ไขทะเบียน</B></legend>

<script>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.frm_edit.f_car_cc.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก CC รถยนต์";
}

if (document.frm_edit.typecar.value==""){
	theMessage = theMessage + "\n -->  กรุณาระบุประเภทรถ";
}

if (document.frm_edit.gas_system.value==""){
	theMessage = theMessage + "\n -->  กรุณาระบุ ระบบแก๊สรถยนต์";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
	document.frm_edit.f_car_cc.focus();
    return false;
}

}

function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
</script>




<?php
$idno=trim($_POST["idno"]);
if(empty($idno)){
$idno=trim($_GET["idno"]);
}
$qry_fp=pg_query("select * from \"Fp\" where (\"IDNO\" ='$idno') ");
$res_fp=pg_fetch_array($qry_fp);
  
if(empty($res_fp["IDNO"])){
    echo "LOCKED "."$idno<br>";
    echo $resback="<input type=\"button\" value=\"BACK\" onclick=\"javascript:history.back()\"  />";
}else{
	$fp_cusid=trim($res_fp["CusID"]);
	$fp_carid=trim($res_fp["asset_id"]);
	$fp_stdate=$res_fp["P_STDATE"];
	$asset_type=$res_fp["asset_type"];
	$asset_id=$res_fp["asset_id"];

	$qry_gas=pg_query("select * from \"FGas\" where \"GasID\" ='$fp_carid' ");
	$num_gas=pg_num_rows($qry_gas);
	if($res_fc=pg_fetch_array($qry_gas)){
		$fc_regis=trim($res_fc["car_regis"]);
		$fc_regis_by=trim($res_fc["car_regis_by"]);
		$fc_year=trim($res_fc["car_year"]);
		$fc_mar=trim($res_fc["marnum"]);
		$fc_num=trim($res_fc["carnum"]);
		$fc_gas=trim($res_fc["fc_gas"]);
		$gas_type=trim($res_fc["gas_type"]);
	}
	
	if($num_gas==0){
		$qry_car=pg_query("select \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			\"C_TAX_MON\", \"C_StartDate\", \"CarID\",\"C_CAR_CC\",\"type_in_act\",\"fc_gas\"
			from \"Carregis_temp\" where \"IDNO\" ='$idno' order by auto_id DESC limit 1");
		$res_fc=pg_fetch_array($qry_car);
		list($fc_regis,$fc_name,$fc_year,$fc_regis_by,$fc_color,$fc_num,$fc_mar,$fc_mi,$fc_expert,$fc_mon,$fc_startdate,$fc_carid,$fc_car_cc,$typecar,$fc_gas)=$res_fc;
	}
	
	//หาว่าเลขทะเบียนนี้ปัจจุบันอยู่กับใคร
	$qrycarnow=pg_query("select \"C_REGIS\",\"IDNO\" from \"Fp\" a
	left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$fc_carid' order by \"P_STDATE\" DESC limit 1");
	$rescarnow=pg_fetch_array($qrycarnow);
	list($C_REGISnew,$idnonow)=$rescarnow;
	
	if($idnonow!=$idno and $num_gas==0){
		$C_REGISnew="<font color=red> ($idnonow / $C_REGISnew)</font>";
	}else{
		$C_REGISnew="";
	}
?>

<form name="frm_edit" method="post" action="edit_regis.php" onsubmit="return validate(this);">
    <input type="hidden" name="fidno" value="<?php echo $idno; ?>" />
    <input type="hidden" name="fcus_id" value="<?php echo $fp_cusid; ?>" />
    <input type="hidden" name="fcar_id" value="<?php echo $fp_carid; ?>" />
    <input type="hidden" name="assettype" value="<?php echo $asset_type; ?>" />
	<input type="hidden" name="assetid" value="<?php echo $asset_id; ?>" />
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="left">
      <td width="25%"><b>เลขที่สัญญา</b></td>
      <td width="75%" class="text_gray"><?php echo $idno; ?></td>
   </tr>
<?php
if($asset_type == 1){
?>
    <tr align="left">
      <td><b>ยี่ห้อรถ</b></td>
      <td class="text_gray"><?php echo $fc_name; ?></td>
   </tr>
<?php
}
?>
   <tr align="left">
      <td><b>รุ่นปี</b></td>
      <td class="text_gray"><?php echo $fc_year; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขตัวถัง</b></td>
      <td class="text_gray"><?php echo $fc_num; ?></td>
   </tr>
   <tr align="left">
      <td><b>เลขเครื่องยนต์</b></td>
      <td class="text_gray"><?php echo $fc_mar; ?></td>
   </tr>
   <tr align="left">
      <td><b>ทะเบียน</b></td>
      <td class="text_gray"><input type="text" name="f_carregis" value="<?php echo "$fc_regis"; ?>" /><?php echo $C_REGISnew;?></td>
   </tr>
   <tr align="left">
      <td><b>จังหวัดที่จดทะเบียน</b></td>
      <td class="text_gray"><?php echo $fc_regis_by; ?></td>
   </tr>
<?php
if($asset_type == 1){
?>
   <tr align="left">
      <td><b>วันที่จดทะเบียน</b></td>
      <td class="text_gray">
<input type="text" name="f_st_date" value="<?php echo $fc_startdate; ?>" /><input name="button22" type="button" onclick="displayCalendar(document.frm_edit.f_st_date,'yyyy-mm-dd',this)" value="ปฏิทิน" />     
      </td>
   </tr>
   <tr align="left">
      <td><b>ค่าภาษี</b></td>
      <td class="text_gray"><input name="f_tax_mon" type="text" value="<?php echo $fc_mon; ?>"/></td>
   </tr>
   <tr align="left">
      <td><b>วันที่ต่ออายุภาษี</b></td>
      <td class="text_gray">
<input type="text" name="f_exp_date" value="<?php echo $fc_expert; ?>" /><input name="button22" type="button" onclick="displayCalendar(document.frm_edit.f_exp_date,'yyyy-mm-dd',this)" value="ปฏิทิน" />
      </td>
   </tr>
	<tr align="left">
    <td><b>CC รถยนต์</b></td>
    <td class="text_gray">
		<input type="text" name="f_car_cc" value="<?php echo $fc_car_cc;?>" onkeypress="return check_number(event);" /><font color="red">*</font>
    </td>
   </tr>
   <tr align="left">
    <td><b>ประเภทรถ</b></td>
    <td class="text_gray">
		<?php 
		//ตัวแปรที่จะส่งไปตรวจสอบใน select_typecar.php ต้องตั้งชื่อว่า $typecar
		include "select_typecar.php"; 
		?><font color="red">*</font>
    </td>
   </tr>
<?php
}

if($asset_type == 2){
?>
	<tr>
		<td><b>ประเภทแก๊ส</b></td>
		<td colspan="2">
			<select name="g_type">
				<option value="" <?php if($gas_type == ""){ echo "selected";}?>>---เลือก---</option>
				<option value="NGV" <?php if($gas_type == 'NGV'){ echo "selected";}?>>NGV</option>
				<option value="LPG" <?php if($gas_type == 'LPG'){ echo "selected";}?>>LPG</option>
			</select>
		</td>
	</tr>
<?php
}
?>
   <tr>
		<td><b>ระบบแก๊สรถยนต์</b></td>
		<td colspan="2">
			<select name="gas_system" id="gas_system">
				<?php
				$qry_gas = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '4'");
				echo "<option value=\"\" >เลือกรายการ</option>";
				while($re_gas = pg_fetch_array($qry_gas)){
					$gas_astypeName = $re_gas["elementsName"];
					
					if($fc_gas == $gas_astypeName)
					{
						echo "<option value=\"$gas_astypeName\" selected>$gas_astypeName</option>";
					}
					else
					{
						echo "<option value=\"$gas_astypeName\" >$gas_astypeName</option>";
					}
				}
				?>
			</select><font color="red">*</font>
		</td>
	</tr>
   <tr align="center">
      <td colspan=2><br><input name="submit" type="submit" value="บันทึก" onclick="return validate()"/></td>
   </tr>
</table>
</form>

<?php
}
?>
 </fieldset> 

</div>
        </td>
    </tr>
</table>         


</body>
</html>