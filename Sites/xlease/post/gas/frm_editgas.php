<?php
session_start();
include("../../config/config.php");
$idnos=$_POST["h_id"];
if(!empty($idnos))
{
	//$idno=substr($idnos,0,11);
	$idno=$_POST["h_id"];
}
else
{
	$idno=$_GET["idnos"];
}
	
//ดึงข้อมูลเลขที่สัญญาใน Fp_Fa1 มาแสดง
$qry_lt2=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$idno'  and \"edittime\"='0' and \"CusState\"='0'");
$numr_lt2=pg_num_rows($qry_lt2);
						
if($numr_lt2==0)
{
	$add_lt="---ใช้ตามที่อยู่ลูกค้า---";											 
}
else
{
	$res_lt2=pg_fetch_array($qry_lt2);
	$fs_no=trim($res_lt2["A_NO"]);
	$fs_subno=trim($res_lt2["A_SUBNO"]);
	$fs_room=trim($res_lt2["A_ROOM"]);
	$fs_floor=trim($res_lt2["A_FLOOR"]);
	$fs_building=trim($res_lt2["A_BUILDING"]);
	$fs_ban=trim($res_lt2["A_BAN"]);
	$fs_soi=trim($res_lt2["A_SOI"]); 
	$fs_rd=trim($res_lt2["A_RD"]);
	$fs_tum=trim($res_lt2["A_TUM"]);
	$fs_aum=trim($res_lt2["A_AUM"]);
	$fs_province=trim($res_lt2["A_PRO"]);
	$fs_post=trim($res_lt2["A_POST"]);
	
	if($fs_no!="" and $fs_no!="-" and $fs_no!="--" and $fs_no!="''"){
		$fs_no="บ้านเลขที่ $fs_no";
	}else{
		$fs_no = "";
	}														
	if($fs_subno!="" and $fs_subno!="-" and $fs_subno!="--" and $fs_subno!="''"){
		$fs_subno="  หมู่ $fs_subno";
	}else{
		$fs_subno = "";
	}	
	if($fs_room!="" and $fs_room!="-" and $fs_room!="--" and $fs_room!="''"){
		$fs_room="  ห้อง $fs_room";
	}else{
		$fs_room = "";
	}
	if($fs_floor!="" and $fs_floor!="-" and $fs_floor!="--" and $fs_floor!="''"){
		$fs_floor="  ชั้น $fs_floor";
	}else{
		$fs_floor = "";
	}
	if($fs_building!="" and $fs_building!="-" and $fs_building!="--" and $fs_building!="''"){
		$fs_building="  อาคาร$fs_building";
	}else{
		$fs_building = "";
	}
	if($fs_ban!="" and $fs_ban!="-" and $fs_ban!="--" and $fs_ban!="''"){
		$fs_ban="  หมู่บ้าน$fs_ban";
	}else{
		$fs_ban = "";
	}
	if($fs_soi!="" and $fs_soi!="-" and $fs_soi!="--" and $fs_soi!="''"){
		$soi="  ซอย$fs_soi";
	}else{
		$soi = "";
	}
	if($fs_rd!="" and $fs_rd!="-" and $fs_rd!="--" and $fs_rd!="''"){
		$road="  ถนน$fs_rd";
	}else{
		$road = "";
	}
	if($fs_province=="กรุงเทพมหานคร" || $fs_province=="กรุงเทพ" || $fs_province=="กรุงเทพฯ" || $fs_province=="กทม."){
		if($fs_tum!="" and $fs_tum!="-" and $fs_tum!="--"){
			$txttum="  แขวง".$fs_tum;
		}
		if($fs_aum!="" and $fs_aum!="-" and $fs_aum!="--"){
			$txtaum="  เขต".$fs_aum;
		}
		$txtpro="  $fs_province";

	}else{
		if($fs_tum!="" and $fs_tum!="-" and $fs_tum!="--"){
			$txttum="  ตำบล".$fs_tum;
		}
		if($fs_aum!="" and $fs_aum!="-" and $fs_aum!="--"){
			$txtaum="  อำเภอ".$fs_aum;
		}
		$txtpro="  จังหวัด$fs_province";
	}
	$add_lt="$fs_no$fs_subno$fs_room$fs_floor$fs_building$fs_ban$soi$road$txttum$txtaum$txtpro  $fs_post";
								
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>

<script>
$(document).ready(function(){
	$("#btnaddnew").hide();
	
	$("#addidno").change(function(){
		var src = $('#addidno option:selected').attr('value');
		if(src== "1"){
			$("#btnaddnew").hide();
			$("#f_letter").val('---ใช้ตามที่อยู่ลูกค้า---')
		}else if(src=="2"){
			$("#btnaddnew").show();
			$("#f_letter").val('');
		}else{
			$("#btnaddnew").hide();
			$("#f_letter").val('<?php echo $add_lt;?>');
		}
		
	});
	
	$("#f_brithday").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
			
    });
	
	$("#f_firname").autocomplete({
        source: "../s_title.php",
        minLength:1
    });
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function validate() 
{
chkrq();
 var theMessage = "Please complete the following: \n-----------------------------------\n";
 var noErrors = theMessage



	if (document.frm_gasedit.cus_name.value=="") {        
		theMessage = theMessage + "\n -->  กรุณาใส่ชื่อ";
	}
	if (document.frm_gasedit.cus_surname.value=="") {     
		theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
	} 
	if (document.frm_gasedit.f_brithday.value=="") {    
		theMessage = theMessage + "\n -->  กรุณาระบุวันเกิด";
	}
	
	if (document.frm_gasedit.f_age.value=="") {     
		theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
	}
	
	if (document.frm_gasedit.f_status.value=="") {        
		theMessage = theMessage + "\n -->  กรุณาระบุสถานภาพ";
	}
	
	if (document.frm_gasedit.f_cardid.value=="") {       
		theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัตร";
	}
	if (document.frm_gasedit.f_otdate.value=="") {      
		theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
	}
	if (document.frm_gasedit.f_cardby.value=="") {    
		theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
	}
	
	if(document.frm_gasedit.chk_other.checked == true){
		if (document.frm_gasedit.N_CAPDREF.value=="") {      
			theMessage = theMessage + "\n -->  กรุณากรอกหมายเลขบัตร";
		}
		if (document.frm_gasedit.list_other.value=="other") {
			if (document.frm_gasedit.add_other.value=="") {     
				theMessage = theMessage + "\n -->  กรุณากรอกประเภทบัตรอื่นๆ";
			}
		}	
	}
	if (document.frm_gasedit.hdidcardchk.value=='false') {       
		theMessage = theMessage + "\n -->  เลขบัตรประชาชนของลูกค้าผิด";
	}
	
	if (document.frm_gasedit.f_addno.value=="") {    
		theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่ ";
	}
	if (document.frm_gasedit.f_subno.value=="") {
		if($('#f_subnochk').attr( 'checked')==false){	      
			theMessage = theMessage + "\n --> กรุณาใส่ หมู่ที่ ";
		}	
	}
	if (document.frm_gasedit.f_soi.value=="") {
		if($('#f_soichk').attr( 'checked')==false){       
			theMessage = theMessage + "\n --> กรุณาใส่ ซอย ";
		}	
	}
	
		if(document.frm_gasedit.f_type_vehicle.value==""){
			theMessage = theMessage + "\n --> กรุณาระบุประเภทรถ";
		}else{
			if(document.frm_gasedit.f_brand){
				if(document.frm_gasedit.f_brand.value==""){
					theMessage = theMessage + "\n --> กรุณาระบุยี่ห้อ";
				}else{
					if(document.frm_gasedit.f_model.value==""){
						theMessage = theMessage + "\n --> กรุณาระบุรุ่น";	
					}
				}
			}	
		}
	
	if (document.frm_gasedit.gas_system.value=="") {
		theMessage = theMessage + "\n --> กรุณาระบุระบบแก๊สรถยนต์";
	}
	if (document.frm_gasedit.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){    
			theMessage = theMessage + "\n --> กรุณาใส่ ถนน ";
		}	
	}
	
	if (document.frm_gasedit.f_tum.value=="") {      
		theMessage = theMessage + "\n --> กรุณาใส่ แขวง/ตำบล";
	}
	
	if (document.frm_gasedit.f_aum.value=="") {     
		theMessage = theMessage + "\n --> กรุณาใส่ เขต/อำเภอ";
	}
	
	if (document.frm_gasedit.f_pro.value=="") {
		theMessage = theMessage + "\n --> กรุณาเลือกจังหวัด";
	}
		
	if (document.frm_gasedit.f_post.value=="") {
		if($('#f_postchk').attr( 'checked')==false){       
			theMessage = theMessage + "\n --> กรุณาใส่ รหัสไปรษณีย์ ";
		}	
	}
	
	if (document.frm_gasedit.f_country.value=="") {      
		theMessage = theMessage + "\n --> กรุณาระบุประเทศ";
	}
	if (document.frm_gasedit.f_mobile.value=="") {
		if (document.frm_gasedit.f_telephone.value=="") {      
			theMessage = theMessage + "\n --> กรุณาระบุเบอร์มือถือหรือเบอร์บ้าน";
		}
	}
	
	
	if (document.frm_gasedit.f_extadd.value==0) {   
		theMessage = theMessage + "\n --> กรุณาเลือกที่อยู่ติดต่อ";
	}
	if(document.frm_gasedit.f_extadd.value==2){
		if (document.frm_gasedit.f_ext.value=="") {   
			theMessage = theMessage + "\n --> กรุณากรอกที่อยู่";
		}
	}
// If no errors, submit the form
if (theMessage == noErrors) {
return true;

} 

else 

{

// If errors were found, show alert message
alert(theMessage);
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

<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script type="text/javascript">
function data_change(field){
	var check = true;
	var value = field.value; //get characters
	//check that all characters are digits, ., -, or ""
	for(var i=0;i < field.value.length; ++i)
	{
		var new_key = value.charAt(i); //cycle through characters
		if(((new_key < "0") || (new_key > "9")) && 
			!(new_key == ""))
		{
			check = false;
			break;
		}
	}     
}
function fn_cus()
{
	var s1=document.frm_gasedit.fh_adds.value;
	var s2="";
	var tcard="ที่อยู่ตามบัตรประชาชน"
	if(document.frm_gasedit.f_extadd.value==2)
	{
		//alert("ใช้ที่อยู่ปัจจุบัน");
		document.frm_gasedit.f_ext.disabled=false;
		document.frm_gasedit.f_ext.value=s1;
		document.frm_gasedit.f_ext.focus(); 
	}
	else if(document.frm_gasedit.f_extadd.value==1)
	{
		document.frm_gasedit.f_ext.disabled=true;
		document.frm_gasedit.f_ext.value=tcard;
	}
	else if(document.frm_gasedit.f_extadd.value==0)
	{
		document.frm_gasedit.f_ext.disabled=true;
		document.frm_gasedit.f_ext.value=s1;
	}
}
function calbrith(){
	var byear = new Date(document.frm_gasedit.f_brithday.value);
    var current = new Date();
    var age = current.getFullYear() - byear.getFullYear();
	var m = current.getMonth() - byear.getMonth();
	if (m < 0 || (m === 0 && current.getDate() < byear.getDate())) {
        age--;
    }
	document.frm_gasedit.f_age.value = age;
	
}
</script>

<style type="text/css">
#warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
</style>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;"">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"><?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div  id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  แก้ไขสัญญาแก๊ส <hr />
  <div class="style5" style="width:auto; padding-left:10px;">
    <?php
	$qry_fgas=pg_query("select A.*,B.*,C.* from \"Fp\" A 
	                    LEFT OUTER JOIN \"Fa1\" B
						on A.\"CusID\"=B.\"CusID\"
						LEFT OUTER JOIN \"Fn\" C 
						on B.\"CusID\"=C.\"CusID\"
	                    WHERE  A.\"IDNO\"='$idno' ");
	$res_id=pg_fetch_array($qry_fgas);
	echo "เลขที่สัญญา".$res_id["IDNO"];
	$ass_id=trim($res_id["asset_id"]);
	$ff_stdate=$res_id["P_STDATE"];
	
	$ff_down=$res_id["P_DOWN"];
	$ff_vdown=$res_id["P_VatOfDown"];
	
	$ff_month=$res_id["P_MONTH"];
	$ff_vmonth=$res_id["P_VAT"];
	
	$ff_total=$res_id["P_TOTAL"];
	$ff_fdate=$res_id["P_FDATE"];
	$ff_begin=$res_id["P_BEGIN"];
	$ff_beginx=$res_id["P_BEGINX"];
	$ff_approved=$res_id["Approved"];
	
	
	$id_cusid=$res_id["CusID"];
	
	
	 $qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$id_cusid' ");
	  $res_fn1=pg_fetch_array($qry_Fn);
	  $ext_addr=$res_fn1["N_ContactAdd"];
	 
	 //ในส่วนนี้ไม่ต้องใช้แล้ว เนื่องจากกำหนดให้สามารถแก้ไขข้อมูลลูกค้าได้ ถึงแม้ว่าจะมีการ LOCK ลูกค้า
	 // if($ff_approved=="t")
	  // {
	    // $dis_cusid="readonly=true";
		// $disabled_cusid = "disabled";
	  // }
	  // else
	  // {
	   // $dis_cusid="";
	   // $disabled_cusid="";
	  // }
	 
    ?>
    <form name="frm_gasedit" method="post" action="update_gas.php" onsubmit="return validate(this);">
	<input type="hidden"  name="fcus_id" value="<?php echo trim($res_id["CusID"]); ?>"  />
	<input type="hidden" name="fidno" value="<?php echo trim($res_id["IDNO"]); ?>"  />
	<input type="hidden" name="f_gasid" value="<?php echo trim($ass_id); ?>" />
  <table width="785" border="0" cellpadding="1" cellspacing="1">
  <td colspan="6" style="background-color:#FFFFCC;">แก้ไขข้อมูลผู้ทำสัญญา</td>
  <tr>
			<td>คำนำหน้าชื่อ (ไทย)</td>
			<td><input type="text" name="f_firname" id="f_firname" value="<?php echo trim($res_id["A_FIRNAME"]); ?>"  /></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo trim($res_id["A_FIRNAME_ENG"]); ?>"/></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="cus_name" value="<?php echo trim($res_id["A_NAME"]); ?>" onkeyup="passrq(this);"/><font color="red">*</font></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo trim($res_id["A_NAME_ENG"]); ?>"/></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="cus_surname" value="<?php echo trim($res_id["A_SIRNAME"]); ?>" onkeyup="passrq(this);"/><font color="red">*</font></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo trim($res_id["A_SIRNAME_ENG"]); ?>"   />
			เพศ 
				<select name="A_SEX">
					<option value="" <?php if(trim($res_id["A_SEX"])=="") echo "selected"; ?>>ไม่ระบุ</option>
					<option value="1" <?php if(trim($res_id["A_SEX"])=="1") echo "selected"; ?>>หญิง</option>
					<option value="2" <?php if(trim($res_id["A_SEX"])=="2") echo "selected"; ?>>ชาย</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value="<?php echo trim($res_id["A_NICKNAME"]); ?>"/></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" size="10" value="<?php echo trim($res_id["A_BIRTHDAY"]); ?>"   onchange="calbrith();passrq(this);" onkeyup="passrq(this);" size="15"/><font color="red">*</font>
			อายุ
			<input type="text" name="f_age" id="f_age" value="<?php echo trim($res_id["N_AGE"]); ?>"   onfocus="calbrith();"  Readonly="true" size="5"/> ปี</td>		</tr>
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227">
			<select name="f_san">
				<option value="ไม่ระบุ" <?php if(trim($res_id["N_SAN"])=="" || trim($res_id["N_SAN"])=="ไม่ระบุ"){ echo "selected";} ?>>ไม่ระบุ</option>
				<option value="ไทย" <?php if(trim($res_id["N_SAN"])=="ไทย"){ echo "selected";} ?>>ไทย</option>
				<option value="จีน" <?php if(trim($res_id["N_SAN"])=="จีน"){ echo "selected";} ?>>จีน</option>
				<option value="ญี่ปุ่น" <?php if(trim($res_id["N_SAN"])=="ญี่ปุ่น"){ echo "selected";} ?>>ญี่ปุ่น</option>
				<option value="อเมริกัน" <?php if(trim($res_id["N_SAN"])=="อเมริกัน"){ echo "selected";} ?>>อเมริกัน</option>
				<option value="อินเดีย" <?php if(trim($res_id["N_SAN"])=="อินเดีย"){ echo "selected";} ?>>อินเดีย</option>
				<option value="พม่า"<?php if(trim($res_id["N_SAN"])=="พม่า"){ echo "selected";} ?>>พม่า</option>
				<option value="ไนจีเรีย" <?php if(trim($res_id["N_SAN"])=="ไนจีเรีย"){ echo "selected";} ?>>ไนจีเรีย</option>
				<option value="อื่นๆ" <?php if(trim($res_id["N_SAN"])=="อื่นๆ"){ echo "selected";} ?>>อื่นๆ</option>
			</select>

			</td>
			<td width="90">ระดับการศึกษา</td>
			<td colspan="3">
				<select name="f_education">
					<option value="" <?php if(trim($res_id["A_EDUCATION"])==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="1" <?php if(trim($res_id["A_EDUCATION"])=="1"){ echo "selected"; }?>>ต่ำกว่ามัธยมศึกษาตอนต้น</option>
					<option value="2" <?php if(trim($res_id["A_EDUCATION"])=="2"){ echo "selected"; }?>>มัธยมศึกษาตอนต้น</option>
					<option value="3" <?php if(trim($res_id["A_EDUCATION"])=="3"){ echo "selected"; }?>>มัธยมศึกษาตอนปลาย</option>
					<option value="4" <?php if(trim($res_id["A_EDUCATION"])=="4"){ echo "selected"; }?>>ปวช.</option>
					<option value="5" <?php if(trim($res_id["A_EDUCATION"])=="5"){ echo "selected"; }?>>ปวส.</option>
					<option value="6" <?php if(trim($res_id["A_EDUCATION"])=="6"){ echo "selected"; }?>>อนุปริญญา</option>
					<option value="7" <?php if(trim($res_id["A_EDUCATION"])=="7"){ echo "selected"; }?>>ปริญญาตรี</option>
					<option value="8" <?php if(trim($res_id["A_EDUCATION"])=="8"){ echo "selected"; }?>>ปริญญาโท</option>
					<option value="9" <?php if(trim($res_id["A_EDUCATION"])=="9"){ echo "selected"; }?>>ปริญญาเอก</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo trim($res_id["A_REVENUE"]); ?>"   onkeypress="return check_number(event);"/></td>
			<td width="90">สถานภาพ</td>
			<td colspan="3">
				<select name="f_status" onchange="passrq(this);">
					<option value="" <?php if(trim($res_id["A_STATUS"])==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="0002" <?php if(trim($res_id["A_STATUS"])=="0002"){ echo "selected"; }?>>โสด</option>
					<option value="0001" <?php if(trim($res_id["A_STATUS"])=="0001"){ echo "selected"; }?>>สมรส</option>
					<option value="0005" <?php if(trim($res_id["A_STATUS"])=="0005"){ echo "selected"; }?>>สมรสไม่จดทะเบียน</option>
					<option value="0004" <?php if(trim($res_id["A_STATUS"])=="0004"){ echo "selected"; }?>>หย่า</option>
					<option value="0003" <?php if(trim($res_id["A_STATUS"])=="0003"){ echo "selected"; }?>>หม้าย</option>
					<option value="0000" <?php if(trim($res_id["A_STATUS"])=="0000"){ echo "selected"; }?>>ไม่ระบุ</option>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="cus_pair" value="<?php echo trim($res_id["A_PAIR"]); ?>" /></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value="<?php echo trim($res_id["N_OCC"]); ?>" /></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
	<?php 
		$dig1 = substr(trim($res_id["N_IDCARD"]),0,1)*13;
		$dig2 = substr(trim($res_id["N_IDCARD"]),1,1)*12;
		$dig3 = substr(trim($res_id["N_IDCARD"]),2,1)*11;
		$dig4 = substr(trim($res_id["N_IDCARD"]),3,1)*10;
		$dig5 = substr(trim($res_id["N_IDCARD"]),4,1)*9;
		$dig6 = substr(trim($res_id["N_IDCARD"]),5,1)*8;
		$dig7 = substr(trim($res_id["N_IDCARD"]),6,1)*7;
		$dig8 = substr(trim($res_id["N_IDCARD"]),7,1)*6;
		$dig9 = substr(trim($res_id["N_IDCARD"]),8,1)*5;
		$dig10 = substr(trim($res_id["N_IDCARD"]),9,1)*4;
		$dig11 = substr(trim($res_id["N_IDCARD"]),10,1)*3;
		$dig12 = substr(trim($res_id["N_IDCARD"]),11,1)*2;
		$dig13 = substr(trim($res_id["N_IDCARD"]),12,1);
		$digcheck1 = ($dig1+$dig2+$dig3+$dig4+$dig5+$dig6+$dig7+$dig8+$dig9+$dig10+$dig11+$dig12)%11;
		$digcheck2 = 11-$digcheck1;
		$digcheck3 = (String)$digcheck2;
		$checknum = strlen($digcheck3);
		if($checknum == 2){
			$dig14 = substr($digcheck3,1,2);
		}else{
			
			$dig14 = $digcheck3;
		}
		
		if($dig14 == $dig13){
			$indencard = 'true';
		}else{
			$indencard = 'false';
		}

	?>	
		
		<input type="hidden" name="hdidcardchk" id="hdidcardchk" value="<?php echo $indencard; ?>">
		<tr>
			<td width="144">เลขที่บัตรประชาชน</td>
			<td width="227">
			<input type="text" <?php if($indencard=='true'){ ?> style="background-color:#66FF66" <?php }else{ ?> style="background-color:#FF3030" <?php } ?> name="f_cardid" id="f_cardid" onkeyup="passrq(this);"  value="<?php echo str_replace(" ","",trim($res_id["N_IDCARD"])); ?>" <?php if(trim($res_id["N_IDCARD"]) != null){ echo "readOnly"; } ?> maxlength="13"><font color="red">*</font>
			<?php if($indencard == 'false'){ ?> <a href="javascript:popU('../../nw/manageCustomer/Re_indentify/frm_reiden.php?cusidd=<?php echo $id_cusid; ?>')"><u><font color="red">แก้ไขเลขบัตรประจำตัวประชาชน</font></u></a> <?php } ?>
			</td>
		<?php if(trim($res_id["N_CARD"]) == "บัตรประชาชน"){ ?>
			<td width="90">บัตรแสดงตัว</td>
			<td colspan="3">
			<input type="text" name="f_card" value="<?php echo trim($res_id["N_CARD"]); ?>" />		
		
			<font color="red">*</font></td>
		<?php } ?>		
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_otdate" onkeyup="passrq(this);" onchange="passrq(this);" value="<?php echo trim($res_id["N_OT_DATE"]); ?>"/><input name="button_otdate" type="button" onclick="displayCalendar(document.frm_gasedit.f_otdate,'yyyy-mm-dd',this)" value="ปฏิทิน" /><font color="red">*</font>
</td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_cardby" value="<?php echo trim($res_id["N_BY"]); ?>" onkeyup="passrq(this);"/><font color="red">*</font></td>
		</tr>
		<tr bgcolor="#E1E1E1">
			<td width="144">บัตรประเภทอื่นๆ</td>
			<td width="227" colspan="6"><input type="checkbox" name="chk_other" id="chk_other" value="1" <?php if(trim($res_id["N_CARD"]) != "บัตรประชาชน"){ echo "checked"; } ?>/></td>			
		</tr>
		
		<tr bgcolor="#E1E1E1">
		<td colspan="7">
		<table name="tb_other" id="tb_other" width="700" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="145" >ประเภท</td>
			<td width="230">
			<select name="list_other" id="list_other" onchange="hsother()">
						<option value="บัตรข้าราชการ" <?php if(trim($res_id["N_CARD"])=="บัตรข้าราชการ"){ echo "selected";} ?>>บัตรข้าราชการ</option>
						<option value="เลขทะเบียนนิติบุคคล" <?php if(trim($res_id["N_CARD"])=="เลขทะเบียนนิติบุคคล"){ echo "selected";} ?>>เลขทะเบียนนิติบุคคล</option>
						<option value="เลขที่การค้า" <?php if(trim($res_id["N_CARD"])=="เลขที่การค้า"){ echo "selected";} ?>>เลขที่การค้า</option>
						<option value="บัตรต่างด้าว" <?php if(trim($res_id["N_CARD"])=="บัตรต่างด้าว"){ echo "selected";} ?>>บัตรต่างด้าว</option>
						<option value="หนังสือเดินทาง(ต่างประเทศ)" <?php if(trim($res_id["N_CARD"])=="หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected";} ?>>หนังสือเดินทาง(ต่างประเทศ)</option>
						<option <?php if(trim($res_id["N_CARD"]) != "บัตรข้าราชการ" and trim($res_id["N_CARD"]) != "เลขทะเบียนนิติบุคคล"  and trim($res_id["N_CARD"]) != "เลขที่การค้า" and trim($res_id["N_CARD"]) != "บัตรต่างด้าว" and trim($res_id["N_CARD"]) != "หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected"; } ?> value="other">อื่นๆ</option>
					</select>
			</td>	
			<td width="155">หมายเลขบัตรอื่น</td>
			<td width=""><input type="text" name="N_CAPDREF" id="N_CAPDREF" value="<?php echo trim($res_id["N_CARDREF"]); ?>" onkeyup="passrq(this);" /><font color="red">*</font></td>	
		</tr>
<?php if(trim($res_id["N_CARD"]) != "บัตรข้าราชการ" and trim($res_id["N_CARD"]) != "เลขทะเบียนนิติบุคคล"  and trim($res_id["N_CARD"]) != "เลขที่การค้า" and trim($res_id["N_CARD"]) != "บัตรต่างด้าว" and trim($res_id["N_CARD"]) != "หนังสือเดินทาง(ต่างประเทศ)"){ ?>	
		<tr name="othertype" id="othertype">
			<td>บัตรอื่นๆ</td>
			<td><input type="text" name="add_other" id="add_other" value="<?php echo trim($res_id["N_CARD"]); ?>" onkeyup="passrq(this);"/><font color="red">*</font></td>	
		</tr>
<?php } ?>	
		</table>
		</td>
		</tr>	
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_addno" value="<?php echo trim($res_id["A_NO"]); ?>" onkeyup="passrq(this);"/><font color="red">*</font></td>
			<td>หมู่ที่</td>
			<td colspan="3"><input type="text" name="f_subno" onkeyup="passrq(this);" <?php if(trim($res_id["A_SUBNO"]) == ""){ echo "disabled";} ?> value="<?php echo trim($res_id["A_SUBNO"]); ?>"/><font color="red">*</font>
			<input type="checkbox" id="f_subnochk" <?php if(trim($res_id["A_SUBNO"]) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_gasedit.f_subno.disabled=true;document.frm_gasedit.f_subno.value='';document.frm_gasedit.f_subno.style.backgroundColor='';}else{document.frm_gasedit.f_subno.disabled=false;document.frm_gasedit.f_subno.style.backgroundColor='#FFCCCC';}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo trim($res_id["A_ROOM"]); ?>"></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo trim($res_id["A_FLOOR"]); ?>">
			</td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo trim($res_id["A_BUILDING"]); ?>"></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo trim($res_id["A_VILLAGE"]); ?>">
			</td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td><input type="text" name="f_soi" onkeyup="passrq(this);" <?php if(trim($res_id["A_SOI"]) == ""){ echo "disabled";} ?> value="<?php echo trim($res_id["A_SOI"]); ?>" /><font color="red">*</font>
			<input type="checkbox" id="f_soichk" <?php if(trim($res_id["A_SOI"]) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_gasedit.f_soi.disabled=true;document.frm_gasedit.f_soi.value='';document.frm_gasedit.f_soi.style.backgroundColor='';}else{document.frm_gasedit.f_soi.disabled=false;document.frm_gasedit.f_soi.style.backgroundColor='#FFCCCC';}">ไม่มี
			</td>
			<td>ถนน</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" <?php if(trim($res_id["A_RD"]) == ""){ echo "disabled";} ?> name="f_rd" value="<?php echo trim($res_id["A_RD"]); ?>" /><font color="red">*</font>
			<input type="checkbox" id="f_rdchk" <?php if(trim($res_id["A_RD"])== ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_gasedit.f_rd.disabled=true;document.frm_gasedit.f_rd.value='';document.frm_gasedit.f_rd.style.backgroundColor='';}else{document.frm_gasedit.f_rd.disabled=false;document.frm_gasedit.f_rd.style.backgroundColor='#FFCCCC';}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" onkeyup="passrq(this);" value="<?php echo trim($res_id["A_TUM"]); ?>" /><font color="red">*</font></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" name="f_aum" value="<?php echo trim($res_id["A_AUM"]); ?>" /><font color="red">*</font></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td>
				<select name="f_pro" size="1" onchange="passrq(this);">
				<?php
				if(trim($res_id["A_PRO"])=="" || trim($res_id["A_PRO"])=="---เลือก---"){
					echo "<option value=\"\">---เลือก---</option>";
				}else{
					echo "<option value=\"\"></option>";
				}
				$query_province=pg_query("select * from \"nw_province\" order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>" <?php if(trim($res_id["A_PRO"]) == $res_pro["proName"]){ echo "selected";} ?>><?php echo $res_pro["proName"];?></option>
				<?php
				}
				?>
				</select><font color="red">*</font>
			</td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3"><input type="text" onkeyup="passrq(this);" name="f_post" <?php if(trim($res_id["A_POST"]) == ""){ echo "disabled";} ?> value="<?php echo trim($res_id["A_POST"]); ?>" maxlength="5" /><font color="red">*</font>
			<input type="checkbox" id="f_postchk"  <?php if(trim($res_id["A_POST"]) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_gasedit.f_post.disabled=true;document.frm_gasedit.f_post.value='';document.frm_gasedit.f_post.style.backgroundColor='';}else{document.frm_gasedit.f_post.disabled=false;document.frm_gasedit.f_post.style.backgroundColor='#FFCCCC';}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td>
				<select name="f_country" size="1" onchange="passrq(this);">
					<option value="">----เลือก----</option>
					<?php
					$query_country=pg_query("select \"CountryCode\",\"CountryName_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE'");
					while($res_country = pg_fetch_array($query_country)){
					?>
					<option value="<?php echo $res_country["CountryCode"];?>" <?php if(trim($res_id["A_COUNTRY"])==""){ if($res_country["CountryCode"]=="TH"){ echo "selected"; } }else if($res_country["CountryCode"]==trim($res_id["A_COUNTRY"])){ echo "selected"; }?>><?php echo $res_country["CountryName_THAI"];?></option>
					<?php
					}
					?>
				</select><font color="red">*</font>
			</td>
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" onkeyup="passrq(this);" value="<?php echo trim($res_id["A_MOBILE"]); ?>"/></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" onkeyup="passrq(this);" value="<?php echo trim($res_id["A_TELEPHONE"]); ?>"/></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo trim($res_id["A_EMAIL"]); ?>" size="30"/></td>
		</tr>
		<tr>
			<td>ที่อยู่ใช้ติดต่อ</td>
			<td>
				<select name="f_extadd" onchange="fn_cus();passrq(this);">
					<option value="0">กรุณาเลือกที่ติดต่อ</option>
					<option value="1">ใช้ที่อยู่ตามบัตรประชาชน</option>
					<option value="2">ใช้ที่อยู่ปัจุบัน</option>
				</select><font color="red">*</font>
			</td>
			<td colspan="4" valign="top" rowspan="2">
			
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<input type="hidden" name="fh_adds" value="<?php echo $ext_addr; ?>" /><textarea name="f_ext" cols="50" rows="5" disabled onkeyup="passrq(this);"><?php echo $ext_addr; ?></textarea>
			</td>
		</tr>
	<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <?php	
  $qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$idno' ");
  $res_cc=pg_fetch_array($qry_cc);
  $residno_cc=$res_cc["IDNO"];
   if(empty($residno_cc))
    {
  ?>
   <?php
    }
	else
	{
	 $qry_fn=pg_query("select A.*,C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",C.\"CusID\" 
	                   from \"ContactCus\" A
                       LEFT OUTER JOIN \"Fa1\" C on C.\"CusID\" = A.\"CusID\" 
					   where A.\"IDNO\"='$idno' AND \"CusState\"!=0 order by \"CusState\" ");
					      
      while($res_fn=pg_fetch_assoc($qry_fn))
      {
	   $fullname=trim($res_fn["A_FIRNAME"])." ".trim($res_fn["A_NAME"])." ".trim($res_fn["A_SIRNAME"]);
	   $a++;                 
   ?>
	<?php
	}
   ?>
   <?php
     }
   ?>
  
				
				<tr>
					<td valign="top"></td>
					<td valign="top"></td>
					<td colspan="4" height="30" valign="top"><b>ที่อยู่ในสัญญา</b>
					<select name="addidno" id="addidno">
						<?php
							//ดึงข้อมูลเลขที่สัญญาใน Fp_Fa1 มาแสดง
							$qry_lt=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$idno'  and \"edittime\"='0' and \"CusState\"='0'");
							$numr_lt=pg_num_rows($qry_lt);
							if($numr_lt>0){
								$addidno=3;
							}					
						if($addidno=="3"){
						?>
						<option value="3" id="addnow" <?php if($addidno=="3") echo "select";?>>ใช้ที่อยู่ในสัญญา</option>
						<?php }?>
						<option value="1" <?php if($addidno=="") echo "select";?>>ใช้ที่อยู่ผู้ทำสัญญา</option>
						<option value="2">ใช้ที่อยู่ใหม่</option>		
					</select>
					<input type="button" name="btnaddnew" id="btnaddnew" value="ระบุที่อยู่ใหม่" onclick="javascript:popU('frm_Idnonew.php?IDNO=<?php echo $idno?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')">
					</td>
				</tr>
				<tr>
					<td valign="top">รายละเอียดสัญญา</td>
					<?php
					 $qry_contactnote=pg_query($db_connect,"select * from \"Fp_Note\" where (\"IDNO\"='$idno') ");
					 $numr_contactnote=pg_num_rows($qry_contactnote);
					 if($numr_contactnote == 0){
						$add_con = "กรุณากรอกรายละเอียดสัญญา";
					 }else{
						$res_contact = pg_fetch_array($qry_contactnote);
						$add_con = $res_contact["ContactNote"];
					 }
					?>
					<td><textarea name="contactnote" cols="40"  rows="5"><?php echo $add_con;?></textarea></td>
					<td colspan="4">
						<textarea name="f_letter" id="f_letter" cols="50"  rows="5" readonly="true"><?php echo $add_lt; ?></textarea>
					</td>
				</tr>
				<tr height="40">
					<td>ค่าแนะนำ</td>
					<td colspan="5">
						<?php
							$qryguide=pg_query("SELECT \"GuidePeople\" FROM \"nw_IDNOGuidePeople\" where \"IDNO\"='$idno'");
							$numguide=pg_num_rows($qryguide);
							if($numguide>0){
								$resguide=pg_fetch_array($qryguide);
								list($GuidePeople)=$resguide;
								echo "มีค่าแนะนำ ผู้แนะนำคือ $GuidePeople";
							}else{
								echo "สัญญานี้ไม่มีค่าแนะนำ";
							}
						?>
					</td>
				</tr>	
		
		
		
  
  <tr>
    <td colspan="6" style="background-color:#D5DEE1;">รายละเอียดแก๊ส</td>
    </tr>
	
	<?php
	$qry_gas=pg_query("select * from \"FGas\" WHERE \"GasID\"='$ass_id' ");
	$res_gs=pg_fetch_array($qry_gas);
	$gs_proa=trim($res_gs["car_regis_by"]);
	$gs_year=trim($res_gs["car_year"]);
	
	$fp_fc_type = $res_gs["fc_type"]; // ประเภท รถยนต์/จักรยายนต์
	$fp_fc_brand = $res_gs["fc_brand"]; //ยี่ห้อ
	$fp_fc_model = $res_gs["fc_model"]; //รุ่น
	$fp_fc_category = $res_gs["fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
	$fp_fc_newcar = $res_gs["fc_newcar"]; //รถใหม่หรือรถใช้แล้ว	
	$fc_milage = $res_gs["fc_milage"]; //เลขไมล์	
	$fc_gas = $res_gs["fc_gas"]; //ระบบแก๊สรถยนต์
	
	
	if($fp_fc_type == ""){
		$qry_gas1=pg_query("select * from \"FGas\" WHERE \"GasID\"='$ass_id' ");
		$res_gs1=pg_fetch_array($qry_gas1);
	}
	
	?>

  <tr>
    <td>ยี่ห้อแก๊ส</td>
    <td><input type="text" name="g_name" value="<?php echo trim($res_gs["gas_name"]); ?>"/></td>
    <td width="92">&nbsp;</td>
    <td width="144">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>เลขถังแก๊ส</td>
    <td><input type="text" name="g_tanknumber" value="<?php echo trim($res_gs["gas_number"]); ?>"/></td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>ระบบแก๊ส</td>
    <td><select name="g_type">	     
	     <?php $g_type=trim($res_gs["gas_type"]); ?> 
		 <option value="" <?php if($g_type == ""){ echo "selected";}?>>---เลือก---</option>
	     <option value="NGV" <?php if($g_type == 'NGV'){ echo "selected";}?>>NGV</option>
		 <option value="LPG" <?php if($g_type == 'LPG'){ echo "selected";}?>>LPG</option>
	    </select>	</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
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
		<tr id="tr_show_brand" >
			<td >ยี่ห้อ </td>
			<td colspan="2"><span id="show_brand"></td>
		</tr>							
		<tr id="tr_show_model">
			<td >รุ่น </td>
			<td colspan="2"><span id="show_model"></td></td>
			
		</tr>
  <tr>
    <td>ทะเบียนรถ</td>
    <td><input type="text" name="g_regis" value="<?php echo trim($res_gs["car_regis"]); ?>"  /></td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>จดทะเบียนจังหวัด</td>
    <td><select name="g_province">
				<?php
				echo "<option value=\"$gs_proa\">$gs_proa</option>";
				$query_province=pg_query("select * from \"nw_province\" order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>" <?php if($gs_proa == $res_pro["proName"]){ echo "selected";} ?>><?php echo $res_pro["proName"];?></option>
				<?php
				}
				?>
				</select>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>รถปี</td>
    <td><select name="g_year">
	    <option value="<?php echo $gs_year; ?>"><?php echo $gs_year; ?></option>
	    <option value="<?php $nowd=date('Y'); echo $nowd; ?>"><?php echo $nowd; ?></option>
		<option value="<?php $nowd=date('Y'); echo $nowd-2; ?>"><?php echo $nowd-2; ?></option>
		<option value="<?php $nowd=date('Y'); echo $nowd-1; ?>"><?php echo $nowd-1; ?></option>
		<option value="<?php $nowd=date('Y'); echo $nowd+1; ?>"><?php echo $nowd+1; ?></option>
		<option value="<?php $nowd=date('Y'); echo $nowd+2; ?>"><?php echo $nowd+2; ?></option>
		</select>	    </td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>เลขตัวถัง</td>
    <td><input type="text" name="g_carnum"  value="<?php echo trim($res_gs["carnum"]); ?>" /></td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>เลขเครื่องยนต์</td>
    <td><input type="text" name="g_marnum" value="<?php echo trim($res_gs["marnum"]); ?>" /></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  
				<tr>
						<td>ชนิดรถ </td>
						<td>
							<select name="f_useful_vehicle" id="f_useful_vehicle">
								<option value="" <?php if($fp_fc_category == ""){ echo "selected"; } ?>>- ไม่ระบุ -</option>
								<option value="รถรับจ้าง" <?php if($fp_fc_category == "รถรับจ้าง"){ echo "selected"; } ?>>รถรับจ้าง</option>
								<option value="เก๋ง" <?php if($fp_fc_category == "เก๋ง"){ echo "selected"; } ?>>เก๋ง</option>
								<option value="กระบะ" <?php if($fp_fc_category == "กระบะ"){ echo "selected"; } ?>>กระบะ</option>
								<option value="เอนกประสงค์" <?php if($fp_fc_category == "เอนกประสงค์"){ echo "selected"; } ?>>เอนกประสงค์</option>
							</select>
						</td>
					</tr>		
					<tr>
						<td>เป็นรถ </td>
						<td>
							<select name="f_status_vehicle" id="f_status_vehicle">
								<option value="1" <?php if($fp_fc_newcar == "1"){ echo "selected"; } ?>>รถใหม่</option>
								<option value="2" <?php if($fp_fc_newcar == "2"){ echo "selected"; } ?>>รถใช้แล้ว</option>
							</select>
						</td>
					</tr>
				<tr>
					<td>เลขไมล์</td>
					<td><input type="text" name="f_carmi" value="<?php echo $fc_milage;?>" size="7"/> กิโลเมตร</td>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					  <td>ระบบแก๊สรถยนต์ </td>
					  <td colspan="2">
						<select name="gas_system" onchange="passrq(this);">
							<option value="" <?php if($fc_gas == ""){ echo "selected"; } ?> >- เลือก -</option>
							<option value="ไม่มีระบบ Gas" <?php if($fc_gas == "ไม่มีระบบ Gas"){ echo "selected"; } ?> >ไม่มีระบบ Gas</option>
							<option value="NGV 100" <?php if($fc_gas == "NGV 100"){ echo "selected"; } ?>>NGV 100</option>
							<option value="NGV 80" <?php if($fc_gas == "NGV 80"){ echo "selected"; } ?>>NGV 80</option>
							<option value="LPG 100" <?php if($fc_gas == "LPG 100"){ echo "selected"; } ?>>LPG 100</option>
						</select><font color="red">*</font>
					  </td>
				</tr>
  
  <tr>
    <td>วันทำสัญญา</td>
    <td><input name="signDate" type="text" readonly="true" value="<?php echo $ff_stdate; ?>"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_gasedit.signDate,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>เงินดาวน์</td>
    <td><input type="text" name="g_down" value="<?php echo $ff_down+$ff_vdown; ?>" onkeypress="return check_number(event);"/></td>
    <td colspan="2">เงินดาวน์ไม่รวม vat<br /> 
      <input type="text"  value="<?php echo $ff_down; ?>" readonly=""  onkeypress="return check_number(event);" /></td>
    <td colspan="2">vat เงินดาวน์<br /><input type="text" name="g_vatdown" value="<?php echo $ff_vdown; ?>"   onkeypress="return check_number(event);"/>
	                    <input type="hidden" value="<?php echo $ff_vdown; ?>" name="ch_dvat"  />						  </td>
  </tr>
  <tr>
    <td>จำนวนงวด</td>
    <td><input type="text" name="g_total" value="<?php echo $ff_total; ?>"  onkeypress="return check_number(event);"/></td>
    <td colspan="4">&nbsp;</td>
    </tr>
  <tr>
    <td>ค่างวด</td>
    <td><input type="text" name="g_month" value="<?php echo $ff_month+$ff_vmonth; ?>" /></td>
    <td colspan="2">ค่างวดไม่รวม vat <br /><input type="text" readonly="" value="<?php echo $ff_month; ?>"  onkeypress="return check_number(event);" /></td>
    <td colspan="2">vat ค่างวด<br /><input type="text" value="<?php echo $ff_vmonth; ?>" name="g_vatmonth"  onkeypress="return check_number(event);" />
	                  <input type="hidden" value="<?php echo $ff_vmonth; ?>" name="ch_mvat"  /> <br />  </td>
  </tr>
  <tr>
    <td>วันที่จ่ายงวดแรก</td>
    <td><input name="f_Date" type="text" readonly="true" value="<?php echo $ff_fdate; ?>" />
      <input name="button2" type="button" onclick="displayCalendar(document.frm_gasedit.f_Date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>เงินต้นลูกค้า</td>
    <td><input type="text" name="g_begin" value="<?php echo $ff_begin; ?>"  onkeypress="return check_number(event);"/></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>เงินต้นทางบัญชี</td>
    <td><input type="text" name="g_beginx" value="<?php echo $ff_beginx; ?>"  onkeypress="return check_number(event);" /></td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" value="SAVE" /></td>
    <td colspan="4"><input name="button4" type="button" onclick="window.location='frm_av_findgas.php'" value="BACK"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
<script type="text/javascript">	
$(document).ready(function(){ 
<?php if(trim($res_id["N_CARD"]) == "บัตรประชาชน"){ ?>
$("#tb_other").hide();
document.frm_gasedit.add_other.value="";
document.frm_gasedit.N_CAPDREF.value="";
<?php } ?>

	$("#chk_other").click(function(){ 
		if($('#chk_other') .attr( 'checked')==true){
			$("#tb_other").show();
		}else{
			$("#tb_other").hide();
			document.frm_gasedit.add_other.value="";
			document.frm_gasedit.N_CAPDREF.value="";
		}
	});
	
	$("#list_other").click(function(){ 
		if($('#list_other') .attr( 'value')=='other'){
			$("#othertype").show();
		}else{
			$("#othertype").hide();
			document.frm_gasedit.add_other.value="";
			document.frm_gasedit.N_CAPDREF.value="";

		}
	});
	
	
});	
</script>
</body>
<!-- InstanceEnd --></html>
<script type="text/javascript">
var type1 = '<?php echo $fp_fc_type; ?>';
if(type1 != ""){
	var brandID1 = '<?php echo $fp_fc_brand; ?>';
	var model1 = '<?php echo $fp_fc_model; ?>';
	$("#show_brand").load("../combo_brand_list.php?type="+type1+"&brand="+brandID1);
	$("#show_model").load("../combo_model_list.php?brandID="+brandID1+"&model="+model1);
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
		$("#show_brand").load("../combo_brand_list.php?type="+type);
	}	
}
function show_model_func(){
	var brandID = $('#f_brand option:selected').attr('value');
	
	if(brandID == ''){	
		$('#tr_show_model').hide();
	}else{
		$('#tr_show_model').show();
		$("#show_model").load("../combo_model_list.php?brandID="+brandID);
	}	
} 

if(document.frm_gasedit.f_type_vehicle.value == document.frm_gasedit.chk_mocy.value){ 
	document.frm_gasedit.f_useful_vehicle.value='';
	document.frm_gasedit.f_useful_vehicle.disabled = true;
}
function lockcat(type){
		if(type.value == document.frm_gasedit.chk_mocy.value){ 
			document.frm_gasedit.f_useful_vehicle.value='';
			document.frm_gasedit.f_useful_vehicle.disabled = true;
		}else{
			document.frm_gasedit.f_useful_vehicle.value='รถรับจ้าง';
			document.frm_gasedit.f_useful_vehicle.disabled = false;
		}
}

function chkrq(){
	if (document.frm_gasedit.cus_name.value=="") {        
		document.frm_gasedit.cus_name.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.cus_surname.value=="") {     
		document.frm_gasedit.cus_surname.style.backgroundColor="#FFCCCC";
	} 
	if (document.frm_gasedit.f_brithday.value=="") {    
		document.frm_gasedit.f_brithday.style.backgroundColor="#FFCCCC";
	}	
	
	if (document.frm_gasedit.f_status.value=="") {        
		document.frm_gasedit.f_status.style.backgroundColor="#FFCCCC";
	}	
	if (document.frm_gasedit.f_cardid.value=="") {       
		document.frm_gasedit.f_cardid.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_otdate.value=="") {      
		document.frm_gasedit.f_otdate.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_cardby.value=="") {    
		document.frm_gasedit.f_cardby.style.backgroundColor="#FFCCCC";
	}
	if(document.frm_gasedit.chk_other.checked == true){
		if (document.frm_gasedit.N_CAPDREF.value=="") {      
			document.frm_gasedit.N_CAPDREF.style.backgroundColor="#FFCCCC";
		}
		if (document.frm_gasedit.list_other.value=="other") {
			if (document.frm_gasedit.add_other.value=="") {     
				document.frm_gasedit.add_other.style.backgroundColor="#FFCCCC";
			}
		}	
	}
	if (document.frm_gasedit.f_addno.value=="") {    
		document.frm_gasedit.f_addno.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_subno.value=="") {
		if($('#f_subnochk').attr( 'checked')==false){	      
			document.frm_gasedit.f_subno.style.backgroundColor="#FFCCCC";
		}	
	}
	if (document.frm_gasedit.f_soi.value=="") {
		if($('#f_soichk').attr( 'checked')==false){       
			document.frm_gasedit.f_soi.style.backgroundColor="#FFCCCC";
		}	
	}
	
		if(document.frm_gasedit.f_type_vehicle.value==""){
			document.frm_gasedit.f_type_vehicle.style.backgroundColor="#FFCCCC";
		}else{
			if(document.frm_gasedit.f_brand){
				if(document.frm_gasedit.f_brand.value==""){
					document.frm_gasedit.f_brand.style.backgroundColor="#FFCCCC";
				}else{
					if(document.frm_gasedit.f_model.value==""){
						document.frm_gasedit.f_model.style.backgroundColor="#FFCCCC";
					}
				}
			}	
		}
	if (document.frm_gasedit.gas_system.value=="") {
		document.frm_gasedit.gas_system.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){    
			document.frm_gasedit.f_rd.style.backgroundColor="#FFCCCC";
		}	
	}	
	if (document.frm_gasedit.f_tum.value=="") {      
		document.frm_gasedit.f_tum.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_aum.value=="") {     
		document.frm_gasedit.f_aum.style.backgroundColor="#FFCCCC";
	}	
	if (document.frm_gasedit.f_pro.value=="") {
		document.frm_gasedit.f_pro.style.backgroundColor="#FFCCCC";
	}		
	if (document.frm_gasedit.f_post.value=="") {
		if($('#f_postchk').attr( 'checked')==false){       
			document.frm_gasedit.f_post.style.backgroundColor="#FFCCCC";
		}	
	}
	if (document.frm_gasedit.f_country.value=="") {      
		document.frm_gasedit.f_country.style.backgroundColor="#FFCCCC";
	}
	if (document.frm_gasedit.f_mobile.value=="") {
		if (document.frm_gasedit.f_telephone.value=="") {      
			document.frm_gasedit.f_telephone.style.backgroundColor="#FFCCCC";
			document.frm_gasedit.f_mobile.style.backgroundColor="#FFCCCC";
		}
	}	

	if (document.frm_gasedit.f_extadd.value==0) {      
		document.frm_gasedit.f_extadd.style.backgroundColor="#FFCCCC";
	}
	if(document.frm_gasedit.f_extadd.value==2){
		if (document.frm_gasedit.f_ext.value=="") {       
			document.frm_gasedit.f_ext.style.backgroundColor="#FFCCCC";
		}
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