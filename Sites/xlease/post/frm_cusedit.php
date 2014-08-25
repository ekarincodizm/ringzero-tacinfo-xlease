<?php
session_start();
include("../config/config.php");
$srcid=trim($_POST["h_id"]);

if($srcid==""){
	echo "<div style=\"text-align:center;padding:20px;\"><b>--กรุณาเลือกลูกค้าที่ต้องการแก้ไขค่ะ--</b></div>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_av_editcus.php'>";
}else{


if(empty($srcid))
	{
		$edt_idno=$_GET["idnog"];
		// ไม่ pass ค่าลูกค้ากลับมา >> ไม่พบค่าลูกค้า $edt_idno ตอนนี้น่าจะเท่ากับ NULL
	}
	else
	{  
		$subid=substr($srcid,0,6);		 
		$edt_idno=$subid;
	}
		 
	$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$edt_idno' ");
	$res_fa1=pg_fetch_array($qry_fa1);
	$fa1_cusid=trim($res_fa1["CusID"]);
	$fa1_firname=trim($res_fa1["A_FIRNAME"]);
	$fa1_name=trim($res_fa1["A_NAME"]);
	$fa1_surname=trim($res_fa1["A_SIRNAME"]);
	$fa1_pair=trim($res_fa1["A_PAIR"]);
	$fa1_no=trim($res_fa1["A_NO"]);
	$fa1_subno=trim($res_fa1["A_SUBNO"]);
	$fa1_soi=trim($res_fa1["A_SOI"]);
	$fa1_rd=trim($res_fa1["A_RD"]);	
	$fa1_tum=trim($res_fa1["A_TUM"]);	
	$fa1_aum=trim($res_fa1["A_AUM"]);
	$fa1_pro=trim($res_fa1["A_PRO"]);	
	$fa1_post=trim($res_fa1["A_POST"]);
	
	$fa1_firname_eng=trim($res_fa1["A_FIRNAME_ENG"]);
	$fa1_name_eng=trim($res_fa1["A_NAME_ENG"]);
	$fa1_surname_eng=trim($res_fa1["A_SIRNAME_ENG"]);
	$fa1_nickname=trim($res_fa1["A_NICKNAME"]);
	$fa1_status=trim($res_fa1["A_STATUS"]);
	$fa1_revenue=trim($res_fa1["A_REVENUE"]);
	$fa1_education=trim($res_fa1["A_EDUCATION"]);
	$fa1_country=trim($res_fa1["addr_country"]);
	$fa1_mobile=trim($res_fa1["A_MOBILE"]);
	$fa1_telephone=trim($res_fa1["A_TELEPHONE"]);
	$fa1_email=trim($res_fa1["A_EMAIL"]);
	$fa1_birthday=trim($res_fa1["A_BIRTHDAY"]);
	
	$fa1_A_SEX=trim($res_fa1["A_SEX"]);
	$fa1_A_ROOM=trim($res_fa1["A_ROOM"]);
	$fa1_A_FLOOR=trim($res_fa1["A_FLOOR"]);
	$fa1_A_BUILDING=trim($res_fa1["A_BUILDING"]);
	$fa1_A_VILLAGE=trim($res_fa1["A_VILLAGE"]);
	  
	$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$edt_idno' ");
	$res_fn1=pg_fetch_array($qry_Fn);
	$ext_addr=$res_fn1["N_ContactAdd"];	
	$N_SAN=$res_fn1["N_SAN"];
	$N_AGE=$res_fn1["N_AGE"];
	$N_CARD=$res_fn1["N_CARD"];
	$N_IDCARD=str_replace(" ","",$res_fn1["N_IDCARD"]);
	$N_OT_DATE=$res_fn1["N_OT_DATE"];
	$N_BY=$res_fn1["N_BY"];
	$N_OCC=$res_fn1["N_OCC"];
	$N_CARDREF=$res_fn1["N_CARDREF"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript">     
function data_change(field)
{
	var check = true;
	var value = field.value; //get characters
	//check that all characters are digits, ., -, or ""
	for(var i=0;i < field.value.length; ++i)
	{
		var new_key = value.charAt(i); //cycle through characters
		if(((new_key < "0") || (new_key > "9")) && !(new_key == ""))
		{
			check = false;
			break;
		}
	}
     
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.frm_edit.f_name.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
	}

	if (document.frm_edit.f_surname.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
	}
	
	if (document.frm_edit.f_status.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุสถานภาพ";
	}

	if (document.frm_edit.f_no.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
	}

	if (document.frm_edit.f_subno.value=="") {
		if($('#f_subnochk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
		}	
	}

	if (document.frm_edit.f_soi.value=="") {
		if($('#f_soichk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ ซอย";
		}	
	}

	if (document.frm_edit.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ ถนน";
		}	
	}

	if (document.frm_edit.f_tum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ แขวง/ตำบล";
	}

	if (document.frm_edit.f_aum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ";
	}
	
	if (document.frm_edit.f_country.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุประเทศ";
	}
	
	if (document.frm_edit.f_province.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกจังหวัด";
	}
	
	if (document.frm_edit.f_post.value=="") {
		if($('#f_postchk').attr('checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์";
		}	
	}
	
	if (document.frm_edit.f_mobile.value=="") {
		if (document.frm_edit.f_telephone.value=="") {
			theMessage = theMessage + "\n -->  กรุณาระบุเบอร์มือถือหรือเบอร์บ้าน";
		}
	}	

	if (document.frm_edit.f_san.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ สัญชาติ";
	}

	if (document.frm_edit.f_age.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
	}

	// if (document.frm_edit.f_card.value=="") {
	// theMessage = theMessage + "\n -->  กรุณาใส่ บัตรแสดงตัว";
	// }

	if (document.frm_edit.f_cardid.value=="") {
		if (document.frm_edit.N_CAPDREF.value=="") {
			theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัตร";
		}	
	}

	if (document.frm_edit.f_datecard.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
	}

	if (document.frm_edit.f_card_by.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
	}

	if (document.frm_edit.f_brithday.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ วัน/เดือน/ปี เกิด";
	}
	
	if (document.frm_edit.f_extadd.value==0) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่ติดต่อ";
	}
	
	if(document.frm_edit.f_extadd.value==2){
		if (document.frm_edit.f_ext.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกที่อยู่";
		}
	}
	if(document.frm_edit.chk_other.checked == true){
		if (document.frm_edit.N_CAPDREF.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกหมายเลขบัตร ";
		}
		if (document.frm_edit.list_other.value=="other") {
			if (document.frm_edit.add_other.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกประเภทบัตรอื่นๆ";
			}
		}		
	}
	
	if(document.getElementById("f_email").value!=""){   // ตรวจสอบว่า email ถูกต้องหรือไม่
			var emailFilter=/^.+@.+\..{2,3}$/;
			var str=document.getElementById("f_email").value;
			if (!(emailFilter.test(str))) {
				   theMessage = theMessage + "\n -->  ใส่อีเมล์ไม่ถูกต้อง";
			}	
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {	
			return true;
	}else{
		alert(theMessage);
		return false;
	}
}
function checkID(id) {
	for(i=0, sum=0; i < 12; i++){
		sum += parseFloat(id.charAt(i))*(13-i);
	}
	if((11-sum%11)%10!=parseFloat(id.charAt(12))){
		return false;
	}else{
		return true;
	}
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<script type="text/javascript">
function fn_cus()
{
	var s1=document.frm_edit.fh_adds.value;
	var s2="";
	var tcard="ที่อยู่ตามบัตรประชาชน"
	if(document.frm_edit.f_extadd.value==2)
	{
		//alert("ใช้ที่อยู่ปัจจุบัน");
		document.frm_edit.f_ext.disabled=false;
		document.frm_edit.f_ext.value=s1;
		document.frm_edit.f_ext.focus(); 
	}
	else if(document.frm_edit.f_extadd.value==1)
	{
		document.frm_edit.f_ext.disabled=true;
		document.frm_edit.f_ext.value=tcard;
	}
	else if(document.frm_edit.f_extadd.value==0)
	{
		document.frm_edit.f_ext.disabled=true;
		document.frm_edit.f_ext.value=s1;
	}
}
function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		document.getElementById("f_revenue").focus();
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">	
$(document).ready(function(){ 
<?php if($N_CARD == "บัตรประชาชน"){ ?>
$("#tb_other").hide();
document.frm_edit.add_other.value="";
document.frm_edit.N_CAPDREF.value="";
<?php } ?>

	$("#chk_other").click(function(){ 
		if($('#chk_other') .attr( 'checked')==true){
			$("#tb_other").show();
		}else{
			$("#tb_other").hide();
			document.frm_edit.add_other.value="";
			document.frm_edit.N_CAPDREF.value="";
		}
	});
	
	$("#list_other").click(function(){ 
		if($('#list_other') .attr( 'value')=='other'){
			$("#othertype").show();
		}else{
			$("#othertype").hide();
			document.frm_edit.add_other.value="";
			document.frm_edit.N_CAPDREF.value="";

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
	
	$("#f_fri_name").autocomplete({
        source: "s_title.php",
        minLength:1
    });
});	



function calbrith(){

	var byear = new Date(document.frm_edit.f_brithday.value);
    var current = new Date();
    var age = current.getFullYear() - byear.getFullYear();
	var m = current.getMonth() - byear.getMonth();
	if (m < 0 || (m === 0 && current.getDate() < byear.getDate())) {
        age--;
    }
	document.frm_edit.f_age.value = age;
	
}	


</script>
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

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}


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

-->
</style>
<!-- InstanceEndEditable -->

</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
แก้ไขข้อมูลลูกค้า (พิเศษ)<br /><hr />
<div class="style5" style="width:auto;  padding-left:10px;">
<?php
	
 
	if($res_fa1["Approved"]=="t")
	{
		echo "<br>"." !!! ข้อมูลนี้ได้ตรวจสอบไปแล้ว การเปลี่ยนข้อมูลลูกค้ารายนี้ จะมีผลต่อเลขที่สัญญาอื่น ๆ ที่ใช้ข้อมูลลูกค้านี้ !!! ";
	}
	else
	{
		echo "";
	}

	// เช็คว่ามีข้อมูลลูกค้าหรือไม่ ถ้าไม่มี คือ เพิ่มลูกค้าใหม่
	if($fa1_cusid) $action = "edit_cusdata.php"; // update การแก้ไขข้อมูลลูกค้า
	else $action = "ins_cusdata.php"; // เพิ่มลูกค้าใหม่
?>
</div>
<form name="frm_edit" method="post" action="<?php echo $action; ?>" onsubmit="return validate(this);">
	<input type="hidden" name="fcus_id" value="<?php echo $edt_idno; ?>" />
	<table width="785" border="0" cellpadding="1" cellspacing="1">
	<tr>
    <td colspan="6" style="background-color:#FFFFCC;">ข้อมูลลูกค้า</td>
    </tr>
	
	
		<?php 
	
		$dig1 = substr($N_IDCARD,0,1)*13;
		$dig2 = substr($N_IDCARD,1,1)*12;
		$dig3 = substr($N_IDCARD,2,1)*11;
		$dig4 = substr($N_IDCARD,3,1)*10;
		$dig5 = substr($N_IDCARD,4,1)*9;
		$dig6 = substr($N_IDCARD,5,1)*8;
		$dig7 = substr($N_IDCARD,6,1)*7;
		$dig8 = substr($N_IDCARD,7,1)*6;
		$dig9 = substr($N_IDCARD,8,1)*5;
		$dig10 = substr($N_IDCARD,9,1)*4;
		$dig11 = substr($N_IDCARD,10,1)*3;
		$dig12 = substr($N_IDCARD,11,1)*2;
		$dig13 = substr($N_IDCARD,12,1);
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
	
	
	<tr>
			<td width="144">เลขที่บัตรประชาชน</td>
			<td width="227"><input type="text" name="f_cardid" readonly="true" id="f_cardid" <?php if($indencard=='true'){ ?> style="background-color:#66FF66" <?php }else{ ?> style="background-color:#FF3030" <?php } ?> value="<?php echo str_replace(" ","",$N_IDCARD); ?>" maxlength="13"><font color="red">*</font>
			<?php if($indencard == 'false'){ ?> <a href="javascript:popU('../nw/manageCustomer/Re_indentify/frm_reiden.php?cusidd=<?php echo $fa1_cusid; ?>')"><u><font color="red">แก้ไขเลขบัตร</font></u></a> <?php } ?>
			</td>
		<?php if($N_CARD == "บัตรประชาชน"){ ?>
			<td width="90">บัตรแสดงตัว</td>
			<td colspan="3">
			<input type="text" name="f_card" value="<?php echo $N_CARD; ?>" readonly="true" />		
		
			<font color="red">*</font></td>
		<?php } ?>		
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" /><input name="button_otdate" type="button" onclick="displayCalendar(document.frm_edit.f_datecard,'yyyy-mm-dd',this)" value="ปฏิทิน" /><font color="red">*</font>
</td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_card_by" value="<?php echo $N_BY; ?>" /><font color="red">*</font></td>
		</tr>
		<tr bgcolor="#E1E1E1">
			<td width="144">บัตรประเภทอื่นๆ</td>
			<td width="227" colspan="6"><input type="checkbox" name="chk_other" id="chk_other" value="1" <?php if($N_CARD != "บัตรประชาชน"){ echo "checked"; } ?> /></td>			
		</tr>
		
		
		<tr bgcolor="#E1E1E1">
		<td colspan="7">
		<table name="tb_other" id="tb_other" width="700" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="145" >ประเภท</td>
			<td width="230">
			
			<select name="list_other" id="list_other" onchange="hsother()" />
						<option <?php if($N_CARD == "บัตรข้าราชการ"){ echo "selected"; } ?> value="บัตรข้าราชการ">บัตรข้าราชการ</option>
						<option <?php if($N_CARD == "เลขทะเบียนนิติบุคคล"){ echo "selected"; } ?> value="เลขทะเบียนนิติบุคคล">เลขทะเบียนนิติบุคคล</option>
						<option <?php if($N_CARD == "เลขที่การค้า"){ echo "selected"; } ?> value="เลขที่การค้า">เลขที่การค้า</option>
						<option <?php if($N_CARD == "บัตรต่างด้าว"){ echo "selected"; } ?> value="บัตรต่างด้าว">บัตรต่างด้าว</option>
						<option <?php if($N_CARD == "หนังสือเดินทาง(ประเทศไทย)"){ echo "selected"; } ?> value="หนังสือเดินทาง(ประเทศไทย)">หนังสือเดินทาง(ประเทศไทย)</option>
						<option <?php if($N_CARD == "หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected"; } ?> value="หนังสือเดินทาง(ต่างประเทศ)">หนังสือเดินทาง(ต่างประเทศ)</option>
						<option <?php if($N_CARD != "บัตรข้าราชการ" ||$N_CARD != "เลขทะเบียนนิติบุคคล" ||$N_CARD != "หนังสือเดินทาง(ประเทศไทย)" || $N_CARD != "เลขที่การค้า" ||$N_CARD != "บัตรข้าราชการ" ||$N_CARD != "บัตรต่างด้าว" ||$N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected"; } ?> value="other">อื่นๆ</option>
					</select>
			</td>	
			<td width="155">หมายเลขบัตรอื่นๆ</td>
			<td width=""><input type="text" name="N_CAPDREF" id="N_CAPDREF" value="<?php echo $N_CARDREF; ?>"/><font color="red">*</font></td>	
		</tr>
	 <?php if($N_CARD != "บัตรข้าราชการ" ||$N_CARD != "เลขทะเบียนนิติบุคคล" ||$N_CARD != "หนังสือเดินทาง(ประเทศไทย)" || $N_CARD != "เลขที่การค้า" ||$N_CARD != "บัตรข้าราชการ" ||$N_CARD != "บัตรต่างด้าว" ||$N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ ?>	
		<tr name="othertype" id="othertype">
			<td>บัตรอื่นๆ</td>
			<td><input type="text" name="add_other" id="add_other" value="<?php echo $N_CARD; ?>"/><font color="red">*</font></td>	
		</tr>
	<?php } ?>	
		</table>
		</td>
		</tr>	
		<tr>
			<td colspan="6"><hr></td>
		</tr>
	 <tr>
			<td>คำนำหน้าชื่อ (ไทย)</td>
			<td><input type="text" name="f_fri_name" id="f_fri_name" value="<?php echo $fa1_firname; ?>" /></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo $fa1_firname_eng; ?>" /></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>" /><font color="red">*</font></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo $fa1_name_eng; ?>" /></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>" /><font color="red">*</font></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo $fa1_surname_eng; ?>" />
			เพศ 
				<select name="A_SEX">
					<option value="" <?php if(trim($fa1_A_SEX)=="") echo "selected"; ?>>ไม่ระบุ</option>
					<option value="1" <?php if(trim($fa1_A_SEX)=="1") echo "selected"; ?>>หญิง</option>
					<option value="2" <?php if(trim($fa1_A_SEX)=="2") echo "selected"; ?>>ชาย</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value="<?php echo $fa1_nickname; ?>" /></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" value="<?php echo $fa1_birthday; ?>" onchange="calbrith();" size="15"/><font color="red">*</font>
			อายุ 		
			<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" onload="calbrith();"  onfocus="calbrith();" onblur="calbrith();" Readonly="true" size="5"/> ปี</td>
		</tr>
		
	
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227">
			
			<select name="f_san">
				<option value="ไม่ระบุ" <?php if($N_SAN=="" || $N_SAN=="ไม่ระบุ"){ echo "selected";} ?>>ไม่ระบุ</option>
				<option value="ไทย" <?php if($N_SAN=="ไทย"){ echo "selected";} ?>>ไทย</option>
				<option value="จีน" <?php if($N_SAN=="จีน"){ echo "selected";} ?>>จีน</option>
				<option value="ญี่ปุ่น" <?php if($N_SAN=="ญี่ปุ่น"){ echo "selected";} ?>>ญี่ปุ่น</option>
				<option value="อเมริกัน" <?php if($N_SAN=="อเมริกัน"){ echo "selected";} ?>>อเมริกัน</option>
				<option value="อินเดีย" <?php if($N_SAN=="อินเดีย"){ echo "selected";} ?>>อินเดีย</option>
				<option value="พม่า"<?php if($N_SAN=="พม่า"){ echo "selected";} ?>>พม่า</option>
				<option value="ไนจีเรีย" <?php if($N_SAN=="ไนจีเรีย"){ echo "selected";} ?>>ไนจีเรีย</option>
				<option value="อื่นๆ" <?php if($N_SAN=="อื่นๆ"){ echo "selected";} ?>>อื่นๆ</option>
			</select>
			
			
			
			<font color="red">*</font></td>
			<td width="90">ระดับการศึกษา</td>
			<td colspan="3">
				<select name="f_education">
					<option value="" <?php if($fa1_education==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="1" <?php if($fa1_education=="1"){ echo "selected"; }?>>ต่ำกว่ามัธยมศึกษาตอนต้น</option>
					<option value="2" <?php if($fa1_education=="2"){ echo "selected"; }?>>มัธยมศึกษาตอนต้น</option>
					<option value="3" <?php if($fa1_education=="3"){ echo "selected"; }?>>มัธยมศึกษาตอนปลาย</option>
					<option value="4" <?php if($fa1_education=="4"){ echo "selected"; }?>>ปวช.</option>
					<option value="5" <?php if($fa1_education=="5"){ echo "selected"; }?>>ปวส.</option>
					<option value="6" <?php if($fa1_education=="6"){ echo "selected"; }?>>อนุปริญญา</option>
					<option value="7" <?php if($fa1_education=="7"){ echo "selected"; }?>>ปริญญาตรี</option>
					<option value="8" <?php if($fa1_education=="8"){ echo "selected"; }?>>ปริญญาโท</option>
					<option value="9" <?php if($fa1_education=="9"){ echo "selected"; }?>>ปริญญาเอก</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo $fa1_revenue; ?>" onkeypress="return check_number(event);"/></td>
			<td width="90">สถานภาพ</td>
			<td colspan="3">
				<select name="f_status">
					<option value="" <?php if($fa1_status==""){ echo "selected"; }?>>---เลือก---</option>
					<option value="0002" <?php if($fa1_status=="0002"){ echo "selected"; }?>>โสด</option>
					<option value="0001" <?php if($fa1_status=="0001"){ echo "selected"; }?>>สมรส</option>
					<option value="0005" <?php if($fa1_status=="0005"){ echo "selected"; }?>>สมรสไม่จดทะเบียน</option>
					<option value="0004" <?php if($fa1_status=="0004"){ echo "selected"; }?>>หย่า</option>
					<option value="0003" <?php if($fa1_status=="0003"){ echo "selected"; }?>>หม้าย</option>
					<option value="0000" <?php if($fa1_status=="0000"){ echo "selected"; }?>>ไม่ระบุ</option>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>" /></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value="<?php echo $N_OCC; ?>" /></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" /><font color="red">*</font></td>
			<td>หมู่ที่</td>
			<td colspan="3"><input type="text" <?php if(trim($fa1_subno) == ""){ echo "disabled";} ?> name="f_subno" id="f_subno" value="<?php echo $fa1_subno; ?>" /><font color="red">*</font>
			<input type="checkbox" id="f_subnochk" <?php if(trim($fa1_subno) == ""){ echo "checked"; } ?> onClick="javaScript:if(this.checked){document.frm_edit.f_subno.disabled=true;document.frm_edit.f_subno.value='';}else{document.frm_edit.f_subno.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>"></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?>">
			</td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>"></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>">
			</td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td><input type="text" name="f_soi"  <?php if(trim($fa1_soi) == ""){ echo "disabled";} ?> value="<?php echo $fa1_soi; ?>" /><font color="red">*</font>
			<input type="checkbox" id="f_soichk" <?php if(trim($fa1_soi) == ""){ echo "checked"; } ?> onClick="javaScript:if(this.checked){document.frm_edit.f_soi.disabled=true;document.frm_edit.f_soi.value='';}else{document.frm_edit.f_soi.disabled=false;}">ไม่มีข้อมูล
			</td>
			<td>ถนน</td>
			<td colspan="3"><input type="text" <?php if(trim($fa1_rd) == ""){ echo "disabled";} ?> name="f_rd" value="<?php echo $fa1_rd; ?>" /><font color="red">*</font>
			<input type="checkbox" id="f_rdchk" <?php if(trim($fa1_rd) == ""){ echo "checked"; } ?> onClick="javaScript:if(this.checked){document.frm_edit.f_rd.disabled=true;document.frm_edit.f_rd.value='';}else{document.frm_edit.f_rd.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>" /><font color="red">*</font></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>" /><font color="red">*</font></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td>	
				<select name="f_province" size="1">
				<?php
				if($fa1_pro=="" || $fa1_pro=="---เลือก---"){
					echo "<option value=\"\">---เลือก---</option>";
				}else{
					echo "<option value=\"\"></option>";
				}
				$query_province=pg_query("select * from \"nw_province\" order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>" <?php if($fa1_pro == $res_pro["proName"]){ echo "selected";} ?>><?php echo $res_pro["proName"];?></option>
				<?php
				}
				?>
				</select><font color="red">*</font>
			</td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3"><input type="text" name="f_post" <?php if(trim($fa1_post) == ""){ echo "disabled";} ?> value="<?php echo $fa1_post; ?>" maxlength="5" /><font color="red">*</font>
			<input type="checkbox" id="f_postchk" <?php if(trim($fa1_post) == ""){ echo "checked"; } ?> onClick="javaScript:if(this.checked){document.frm_edit.f_post.disabled=true;document.frm_edit.f_post.value='';}else{document.frm_edit.f_post.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td>
				<select name="f_country" size="1">
					<option value="">----เลือก----</option>
					<?php
					$query_country=pg_query("select \"CountryCode\",\"CountryName_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE'");
					while($res_country = pg_fetch_array($query_country)){
					?>
					<option value="<?php echo $res_country["CountryCode"];?>" <?php if($res_country["CountryCode"]==$fa1_country){ echo "selected"; }?>><?php echo $res_country["CountryName_THAI"];?></option>
					<?php
					}
					?>
				</select><font color="red">*</font>
			</td>
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" /></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" /></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" /></td>
		</tr>
		<tr>
			<td>ที่อยู่ใช้ติดต่อ</td>
			<td>
				<select name="f_extadd" onchange="fn_cus();">
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
				<input type="hidden" name="fh_adds" value="<?php echo $ext_addr; ?>" /><textarea name="f_ext" cols="50" rows="5" disabled ><?php echo $ext_addr; ?></textarea>
			</td>
		</tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <?php	
  $qry_cc=pg_query("select * from \"ContactCus\" where \"IDNO\" ='$edt_idno' ");
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
					   where A.\"IDNO\"='$edt_idno' AND \"CusState\"!=0 order by \"CusState\" ");
					      
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
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" value="SAVE" /></td>
    <td colspan="4"><input type="button" value="BACK" onclick="window.location='frm_av_editcus.php'" /></td>
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
</body>
<!-- InstanceEnd --></html>
<?php
}
?>
