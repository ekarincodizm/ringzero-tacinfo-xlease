<?php
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    <script type="text/javascript" src="../autocomplete.js"></script>  
    <link rel="stylesheet" href="../autocomplete.css"  type="text/css"/>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script type="text/javascript">
function chtb() {
        document.frm_1.f_firname.disabled = true;
        document.frm_1.f_firname.value ='ใช้ข้อมูลที่เลือก';
        document.frm_1.f_name.disabled = true;    
        document.frm_1.f_name.value='ใช้ข้อมูลที่เลือก';
        document.frm_1.f_sirname.disabled = true;
        document.frm_1.f_sirname.value='ใช้ข้อมูลที่เลือก';
		
		document.frm_1.f_sirname.style.backgroundColor='';
		document.frm_1.f_name.style.backgroundColor='';
		document.frm_1.f_firname.style.backgroundColor='';
		
		document.frm_1.ch_status.value=1;

}
function chtb_cls() {
        document.frm_1.id_search.value ='';
        document.frm_1.f_firname.disabled = false;
        document.frm_1.f_firname.value ='';
        document.frm_1.f_name.disabled = false;    
        document.frm_1.f_name.value='';
        document.frm_1.f_sirname.disabled = false;
        document.frm_1.f_sirname.value='';
		
		document.frm_1.f_sirname.style.backgroundColor="#FFCCCC";
		document.frm_1.f_name.style.backgroundColor="#FFCCCC";
		document.frm_1.f_firname.style.backgroundColor="#FFCCCC";
		
		document.frm_1.ch_status.value=0;
}
//fc 
function chtb_fc() {
        document.frm_1.g_regis.disabled = true;
        document.frm_1.g_regis.value ='ใช้ข้อมูลที่เลือก';
		document.frm_1.g_carnum.disabled = true;
		document.frm_1.g_carnum.value = 'ใช้ข้อมูลที่เลือก';		
		document.frm_1.g_marnum.disabled = true;
		document.frm_1.g_marnum.value ='ใช้ข้อมูลที่เลือก';	
		document.frm_1.g_province.disabled = true;
		document.frm_1.g_province.selectedIndex ="77";
		//Sdocument.frm_1.g_province.options[selectedIndex].value		
		document.frm_1.g_year.disabled = true;
		document.frm_1.g_year.value = 'ใช้ข้อมูลที่เลือก';	
		$('#tr_show_model').hide();
		$('#tr_show_brand').hide();
		document.frm_1.C_Milage.value='ใช้ข้อมูลที่เลือก';
		document.frm_1.C_Milage.disabled = true;
		document.frm_1.f_type_vehicle.value='';
		document.frm_1.f_type_vehicle.disabled = true;
		document.frm_1.f_useful_vehicle.disabled = true;
		document.frm_1.f_status_vehicle.disabled = true;
		document.frm_1.gas_system.disabled = true;
		
		document.frm_1.g_regis.style.backgroundColor='';
		document.frm_1.g_carnum.style.backgroundColor='';
		document.frm_1.g_marnum.style.backgroundColor='';
		document.frm_1.g_province.style.backgroundColor='';
		document.frm_1.g_year.style.backgroundColor='';
		document.frm_1.C_Milage.style.backgroundColor='';
		document.frm_1.f_type_vehicle.style.backgroundColor='';
		document.frm_1.f_useful_vehicle.style.backgroundColor='';
		document.frm_1.f_status_vehicle.style.backgroundColor='';
		document.frm_1.gas_system.style.backgroundColor='';
	
		
		
		document.frm_1.ch_fc_status.value=1;
}
function chtb_fc_cls() {
        document.frm_1.id_search_fc.value ='';
		
		document.frm_1.g_regis.disabled = false;
		document.frm_1.g_regis.value = '';
		
		document.frm_1.g_carnum.disabled = false;
		document.frm_1.g_carnum.value = '';
		
		document.frm_1.g_marnum.disabled = false;
		document.frm_1.g_marnum.value = '';
		
		document.frm_1.g_province.disabled = false;
		document.frm_1.g_province.selectedIndex ="2";
		
		document.frm_1.g_year.disabled = false;
		document.frm_1.g_year.value = '';
		
		document.frm_1.C_Milage.value='';
		document.frm_1.C_Milage.disabled = false;
		document.frm_1.f_type_vehicle.value='';
		document.frm_1.f_type_vehicle.disabled = false;
		document.frm_1.f_useful_vehicle.disabled = false;
		document.frm_1.f_status_vehicle.disabled = false;
		document.frm_1.gas_system.value='';
		document.frm_1.gas_system.disabled = false;
		
		document.frm_1.g_regis.style.backgroundColor="#FFCCCC";
		document.frm_1.g_carnum.style.backgroundColor="#FFCCCC";
		document.frm_1.g_marnum.style.backgroundColor="#FFCCCC";
		document.frm_1.g_year.style.backgroundColor="#FFCCCC";
		document.frm_1.f_type_vehicle.style.backgroundColor="#FFCCCC";
		document.frm_1.gas_system.style.backgroundColor="#FFCCCC";
		
		
		
		
		document.frm_1.ch_fc_status.value=0;
}

//end fc
function check_num(e){
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
		if(key > 57)
			window.event.returnValue = false;
	}else{
		key = e.which; // Firefox       
		if(key > 57)
			key = e.preventDefault();
	}
} 

function chklist(){
	chkrq();
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage;
	
	if (document.frm_1.f_firname.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่คำขึ้นต้น";
	}
	if (document.frm_1.f_name.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่ชื่อ";
	}
	if (document.frm_1.f_sirname.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่นามสกุล";
	}
	if (document.frm_1.g_regis.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่เลขทะเบียน";
	}
	
	if(document.frm_1.f_type_vehicle.disabled == false){
		if(document.frm_1.f_type_vehicle.value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุประเภทรถ";
		}else{
			if(document.frm_1.f_brand){
				if(document.frm_1.f_brand.value==""){
					theMessage = theMessage + "\n -->  กรุณาระบุยี่ห้อ";
				}else{
					if(document.frm_1.f_model.value==""){
						theMessage = theMessage + "\n -->  กรุณาระบุรุ่น";	
					}
				}
			}	
		}
	}
	
	if(document.frm_1.gas_system.disabled == false){
		if (document.frm_1.gas_system.value=="") {
			theMessage = theMessage + "\n -->  กรุณาระบุระบบแก๊สรถยนต์";
		}
	}

	if (document.frm_1.g_carnum.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่เลขตัวถัง";
	}
	if (document.frm_1.g_marnum.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่เลขเครื่อง";
	}
	if (document.frm_1.g_year.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่ปีรถ";
	}
	if (document.frm_1.gas_type.value=="") {
		theMessage = theMessage + "\n -->  กรุณาระบุประเภทแก๊ส";
	}
	if (document.frm_1.g_name.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่ยี่ห้อแก๊ส";
	}
	if (document.frm_1.g_tanknumber.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่เลขถังแก๊ส";
	}

	
	if (theMessage == noErrors) {
		return true;
	}else{
		alert(theMessage);
		return false;
	}
}
</script>    
    
</head>
<body>    
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>


<fieldset><legend><B>สัญญาแก๊ส - ซื้อสด</B></legend>

<form name="frm_1" method="post" action="save_gas_cash.php">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
    <td bgcolor="#FFFFC0" colspan="2"><b>ผู้ทำสัญญา</b></td>
</tr>
<tr bgcolor="#C7ECFA">
    <td width="20%"><b>ตรวจสอบชื่อ หรือ นามสกุล</b></td>
    <td width="80%">
        <input name="f_search" type="hidden" id="f_search" value="" />
		<input name="ch_status" type="hidden" id="ch_status" value="" />
        <input type="text" id="id_search" name="id_search" size="60" value="" onchange="chtb();"><input name="bt1" type="button" value="ค้นหาใหม่" onclick="chtb_cls();">
    </td>
</tr>
<tr>
    <td>คำนำหน้า</td>
    <td><input type="text" name="f_firname" size="15" value="" onkeyup="passrq(this);"><font color="red">*</font></td>
</tr>
<tr>
    <td>ชื่อ</td>
    <td><input type="text" name="f_name" size="30" value="" onkeyup="passrq(this);"><font color="red">*</font></td>
</tr>
<tr>
    <td>นามสกุล</td>
    <td><input type="text" name="f_sirname" size="30" value="" onkeyup="passrq(this);"><font color="red">*</font></td>
</tr>
</table>

<br><br> 

<table width="100%" border="0" cellpadding="3" cellspacing="0">

<tr bgcolor="#C7ECFA">
    <td><b>ตรวจสอบทะเบียน</b></td>
    <td colspan="3">
    <input name="f_search_fc" type="hidden" id="f_search_fc" value="" />
    <input name="ch_fc_status" type="hidden" id="ch_fc_status" value="" />
        <input type="text" id="id_search_fc" name="id_search_fc" size="60" value="" onchange="chtb_fc();"><input name="bt1" type="button" value="ค้นหาใหม่" onclick="chtb_fc_cls();">
    </td>
</tr>
		<tr>
			<td >ประเภทรถ </td>
			<td >
				<select name="f_type_vehicle" id="f_type_vehicle" onchange="show_brand_func();lockcat(this);passrq(this);">
					<?php 	$qry_sel_astype = pg_query("select \"astypeID\",\"astypeName\" from \"thcap_asset_biz_astype\" where \"astypeName\" = 'รถยนต์'  AND \"astypeStatus\" = '1'");
							echo "<option value=\"\" >- เลือกประเภทรถ -</option>";
							while($re_sel_astype = pg_fetch_array($qry_sel_astype)){
								$astype_astypeID = $re_sel_astype["astypeID"];
								$astype_astypeName = $re_sel_astype["astypeName"];	
								echo "<option value=\"$astype_astypeID\" >$astype_astypeName</option>";
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
		<tr id="tr_show_brand" >
			<td >ยี่ห้อ </td>
			<td colspan="2"><span id="show_brand"></td>
			
		</tr>
		<tr id="tr_show_model">
			<td >รุ่น </td>
			<td colspan="2"><span id="show_model"></td></td>
			
		</tr>
<tr>
        <td>ทะเบียน</td>
        <td><input type="text" name="g_regis" size="30" onkeyup="passrq(this);"><font color="red">*</font></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>

<tr>
        <td width="20%">เลขเครื่อง</td>
        <td width="30%"><input type="text" name="g_marnum" size="30" onkeyup="passrq(this);" /><font color="red">*</font></td>
        <td width="15%">&nbsp;</td>
        <td width="35%">&nbsp;</td>
    </tr>
  


    <tr>
        <td width="20%">เลขตัวถัง</td>
        <td width="30%"><input type="text" name="g_carnum" size="30" onkeyup="passrq(this);" /><font color="red">*</font></td>
        <td width="15%">&nbsp;</td>
        <td width="35%">&nbsp;</td>
    </tr>
    <tr>
        <td>จดทะเบียนจังหวัด</td>
        <td><select name="g_province">
          <option value="0">กรุณาเลือกจังหวัด</option>
          <option value="กระบี่">กระบี่</option>
          <option value="กรุงเทพ" selected="selected">กรุงเทพมหานคร</option>
          <option value="กาญจนบุรี">กาญจนบุรี</option>
          <option value="กาฬสินธุ์">กาฬสินธุ์</option>
          <option value="กำแพงเพชร">กำแพงเพชร</option>
          <option value="ขอนแก่น">ขอนแก่น</option>
          <option value="จันทบุรี">จันทบุรี</option>
          <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
          <option value="ชลบุรี">ชลบุรี</option>
          <option value="ชัยนาท">ชัยนาท</option>
          <option value="ชัยภูมิ">ชัยภูมิ</option>
          <option value="ชุมพร">ชุมพร</option>
          <option value="เชียงราย">เชียงราย</option>
          <option value="เชียงใหม่">เชียงใหม่</option>
          <option value="ตรัง">ตรัง</option>
          <option value="ตราด">ตราด</option>
          <option value="ตาก">ตาก</option>
          <option value="นครนายก">นครนายก</option>
          <option value="นครปฐม">นครปฐม</option>
          <option value="นครพนม">นครพนม</option>
          <option value="นครราชสีมา">นครราชสีมา</option>
          <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
          <option value="นครสวรรค์">นครสวรรค์</option>
          <option value="นนทบุรี">นนทบุรี</option>
          <option value="นราธิวาส">นราธิวาส</option>
          <option value="น่าน">น่าน</option>
          <option value="บุรีรัมย์">บุรีรัมย์</option>
		  <option value="บึงกาฬ">บึงกาฬ</option>
          <option value="ปทุมธานี">ปทุมธานี</option>
          <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
          <option value="ปราจีนบุรี">ปราจีนบุรี</option>
          <option value="ปัตตานี">ปัตตานี</option>
          <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
          <option value="พะเยา">พะเยา</option>
          <option value="พังงา">พังงา</option>
          <option value="พัทลุง">พัทลุง</option>
          <option value="พิจิตร">พิจิตร</option>
          <option value="พิษณุโลก">พิษณุโลก</option>
          <option value="เพชรบุรี">เพชรบุรี</option>
          <option value="เพชรบูรณ์">เพชรบูรณ์</option>
          <option value="แพร่">แพร่</option>
          <option value="ภูเก็ต">ภูเก็ต</option>
          <option value="มหาสารคาม">มหาสารคาม</option>
          <option value="มุกดาหาร">มุกดาหาร</option>
          <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
          <option value="ยโสธร">ยโสธร</option>
          <option value="ยะลา">ยะลา</option>
          <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
          <option value="ระนอง">ระนอง</option>
          <option value="ระยอง">ระยอง</option>
          <option value="ราชบุรี">ราชบุรี</option>
          <option value="ลพบุรี">ลพบุรี</option>
          <option value="ลำปาง">ลำปาง</option>
          <option value="ลำพูน">ลำพูน</option>
          <option value="เลย">เลย</option>
          <option value="ศรีสะเกษ">ศรีสะเกษ</option>
          <option value="สกลนคร">สกลนคร</option>
          <option value="สงขลา">สงขลา</option>
          <option value="สตูล">สตูล</option>
          <option value="สมุทรปราการ">สมุทรปราการ</option>
          <option value="สมุทรสงคราม">สมุทรสงคราม</option>
          <option value="สมุทรสาคร">สมุทรสาคร</option>
          <option value="สระแก้ว">สระแก้ว</option>
          <option value="สระบุรี">สระบุรี</option>
          <option value="สิงห์บุรี">สิงห์บุรี</option>
          <option value="สุโขทัย">สุโขทัย</option>
          <option value="สุพรรณบุรี">สุพรรณบุรี</option>
          <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
          <option value="สุรินทร์">สุรินทร์</option>
          <option value="หนองคาย">หนองคาย</option>
          <option value="หนองบัวลำภู">หนองบัวลำภู</option>
          <option value="อ่างทอง">อ่างทอง</option>
          <option value="อำนาจเจริญ">อำนาจเจริญ</option>
          <option value="อุดรธานี">อุดรธานี</option>
          <option value="อุตรดิตถ์">อุตรดิตถ์</option>
          <option value="อุทัยธานี">อุทัยธานี</option>
          <option value="อุบลราชธานี">อุบลราชธานี</option>
          <option value="77">ใช้ข้อมูลที่เลือก</option>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
		<tr>
			<td>ชนิดรถ </td>
			<td>
				<select name="f_useful_vehicle" id="f_useful_vehicle">
					<option value="" >- ไม่ระบุ -</option>
					<option value="รถรับจ้าง" selected>รถรับจ้าง</option>
					<option value="เก๋ง">เก๋ง</option>
					<option value="กระบะ">กระบะ</option>
					<option value="เอนกประสงค์">เอนกประสงค์</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td>เป็นรถ </td>
			<td>
				<select name="f_status_vehicle" id="f_status_vehicle">
					<option value="1" selected>รถใหม่</option>
					<option value="2">รถใช้แล้ว</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>เลขไมล์ </td>
			<td colspan="2"><input type="text" name="C_Milage" size="7px;" onkeypress="check_num(event)"/> กิโลเมตร</td>
		</tr>
		<tr>
			  <td>ระบบแก๊สรถยนต์ </td>
			  <td colspan="2">
				<select name="gas_system" onchange="passrq(this);">
					<option value="" selected >- เลือก -</option>
					<option value="ไม่มีระบบ Gas" >ไม่มีระบบ Gas</option>
					<option value="NGV 100">NGV 100</option>
					<option value="NGV 80">NGV 80</option>
					<option value="LPG 100">LPG 100</option>
				</select><font color="red">*</font>
			  </td>
		</tr>
    <tr>
        <td>รถปี (ค.ศ.)</td>
        <td><input type="text" name="g_year" size="10" onkeyup="passrq(this);"/><font color="red">*</font></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
    </tr>
    <tr>
      <td   bgcolor="#FFFFC0" colspan="4"> <strong>ข้อมูลผ่อนแก๊ส</strong></td>
    </tr>
    <tr>
      <td>วันทำสัญญา</td>
      <td><input name="signDate" type="text" readonly="true" size="12" value="<?php echo date("Y/m/d"); ?>"/>
        <input name="button" type="button" onclick="displayCalendar(document.frm_1.signDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
      <td>ประเภทแก๊ส </td>
      <td>
		<select name="gas_type" onchange="passrq(this);">
			<option value="">---เลือก---</option>
			<option value="NGV">NGV</option>
			<option value="LPG">LPG</option>
		</select><font color="red">*</font>
	  </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>ยี่ห้อแก๊ส </td>
      <td><input type="text" name="g_name" size="30" onkeyup="passrq(this);"/><font color="red">*</font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>เลขถังแก๊ส</td>
      <td><input type="text" name="g_tanknumber" size="30" onkeyup="passrq(this);"/><font color="red">*</font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="submit" type="submit" value="SAVE" onclick="return chklist(this);" /></td>
      <td><input name="button3" type="button" onclick="window.location='gas_step1.php'" value="BACK" /></td>
      <td>&nbsp;</td>
    </tr>
    </table>
</form>

</fieldset>

<div align="center"><br><input type="button" value="กลับหน้าหลัก" onclick="location.href='../../list_menu.php'"></div>

        </td>
    </tr>
</table>

<script type="text/javascript">
$('#tr_show_model').hide();
$('#tr_show_brand').hide();
function show_brand_func(){	
	var type = $('#f_type_vehicle option:selected').attr('value');
	if(type == ''){
		$('#tr_show_brand').hide();
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_brand').show();
		$('#tr_show_model').hide();
		$("#show_brand").load("../combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	$('#tr_show_model').show();	
	var brandID = $('#f_brand option:selected').attr('value');
	$("#show_model").load("../combo_model_list.php?brandID="+brandID);
} 

function lockcat(type){
		if(type.value == document.frm_1.chk_mocy.value){ 
			document.frm_1.f_useful_vehicle.value='';
			document.frm_1.f_useful_vehicle.disabled = true;
		}else{
			document.frm_1.f_useful_vehicle.value='รถรับจ้าง';
			document.frm_1.f_useful_vehicle.disabled = false;
		}
}	
</script>

<script type="text/javascript">
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata_name.php?q=" + this.value;
    });
}
 
make_autocom("id_search","f_search");
</script>

<script type="text/javascript">
function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata_car.php?q=" + this.value;
    });
}
 
make_autocom("id_search_fc","f_search_fc");

function chkrq(){

	if (document.frm_1.f_firname.value=="") {
		document.frm_1.f_firname.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.f_name.value=="") {
		document.frm_1.f_name.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.f_sirname.value=="") {
		document.frm_1.f_sirname.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.g_regis.value=="") {
		document.frm_1.g_regis.style.backgroundColor="#FFCCCC";
	}
	
	if(document.frm_1.f_type_vehicle.disabled == false){
		if(document.frm_1.f_type_vehicle.value==""){
			document.frm_1.f_type_vehicle.style.backgroundColor="#FFCCCC";
		}else{
			if(document.frm_1.f_brand){
				if(document.frm_1.f_brand.value==""){
					document.frm_1.f_brand.style.backgroundColor="#FFCCCC";
				}else{
					if(document.frm_1.f_model.value==""){
						document.frm_1.f_model.style.backgroundColor="#FFCCCC";
					}
				}
			}	
		}
	}
	
	if(document.frm_1.gas_system.disabled == false){
		if (document.frm_1.gas_system.value=="") {
			document.frm_1.gas_system.style.backgroundColor="#FFCCCC";
		}
	}

	if (document.frm_1.g_carnum.value=="") {
		document.frm_1.g_carnum.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.g_marnum.value=="") {
		document.frm_1.g_marnum.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.g_year.value=="") {
		document.frm_1.g_year.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.gas_type.value=="") {
		document.frm_1.gas_type.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.g_name.value=="") {
		document.frm_1.g_name.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_1.g_tanknumber.value=="") {
		document.frm_1.g_tanknumber.style.backgroundColor="#FFCCCC";
	}
	
}
//สำหรับตรวจว่าหาก textbox กรอกค่าแล้วให้เอาสีแดงออก แต่หากยังไม่กรอกให้ใส่สีแดง สำหรับ Require field
function passrq(object){
	if(object.value != ""){
		object.style.backgroundColor="";
	}else{
		object.style.backgroundColor="#FFCCCC";
	}	
}
</script>




</body>
</html>