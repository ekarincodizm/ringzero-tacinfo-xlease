<?php
session_start();
  include("../config/config.php");
  $vidno=$_GET["vidno"];
  $CusState=$_GET["CusState"];
?>

<?php

  
  
  $gp_cusid=$_GET["p_cusid"]; 
         $gp_cusname=$_GET["p_cusname"];		 
		 $id_cusid=$_GET["cusID"];
		
$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$id_cusid' ");
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
	  
	$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$id_cusid' ");
	$res_fn1=pg_fetch_array($qry_Fn);
	$ext_addr=$res_fn1["N_ContactAdd"];	
	$N_SAN=$res_fn1["N_SAN"];
	$N_AGE=$res_fn1["N_AGE"];
	$N_CARD=$res_fn1["N_CARD"];
	$N_IDCARD=$res_fn1["N_IDCARD"];
	$N_OT_DATE=$res_fn1["N_OT_DATE"];
	$N_BY=$res_fn1["N_BY"];
	$N_OCC=$res_fn1["N_OCC"];
	$N_CARDREF=$res_fn1["N_CARDREF"];
	$statuscus=$res_fn1["statuscus"];  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
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
	
	//กรณีเลือกคนไทย
	if($("#cus1").is(':checked')){
		if (document.frm_editcus.f_cardid.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่ เลขที่บัตรประชาชน";
		}
	}
	
	if(document.frm_editcus.checkdigit.value == 'fail'){
	theMessage = theMessage + "\n -->  รูปแบบหมายเลขบัตรประชาชนไม่ถูกต้อง";		
	}

	if (document.frm_editcus.f_datecard.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
	}

	if (document.frm_editcus.f_card_by.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
	}
	
	if(document.frm_editcus.chk_other.checked == true){
		if (document.frm_editcus.N_CAPDREF.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกหมายเลขบัตร ";
		}else{
			if($("#cus1").is(':checked')){//กรณีเลือกคนไทย
				//ถ้าเลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
				if($('#list_other') .attr( 'value')=='หนังสือเดินทาง(ต่างประเทศ)' || $('#list_other') .attr( 'value')=='บัตรต่างด้าว'){
					theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
				}
			}else if($("#cus2").is(':checked')){ //กรณีเลือกชาวต่างชาติ			
				//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
				if($('#list_other') .attr( 'value')!='หนังสือเดินทาง(ต่างประเทศ)' 
				&& $('#list_other') .attr( 'value')!='บัตรต่างด้าว' && $('#list_other') .attr( 'value')!='other'){
					theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
				}
			}else if($("#cus3").is(':checked')){//กรณีเลือกบริษัท
				//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นเลขทะเบียนนิติบุคคล หรือบัตรเลขที่การค้า ให้แจ้งว่าไม่ถูกต้อง
				if($('#list_other') .attr( 'value')!='เลขทะเบียนนิติบุคคล' 
				&& $('#list_other') .attr( 'value')!='เลขที่การค้า' && $('#list_other') .attr( 'value')!='other'){
					theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
				}
			}
		}
		
		if (document.frm_editcus.list_other.value=="other") {
			if (document.frm_editcus.add_other.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกประเภทบัตรอื่นๆ";
			}
		}
		
		
	}else{
		if($("#cus2").is(':checked') || $("#cus3").is(':checked')){
			theMessage = theMessage + "\n -->  กรุณาเลือกบัตรอื่นๆ";
		}
	}

	if (document.frm_editcus.f_name.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
	}

	if (document.frm_editcus.f_surname.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
	}
	
	if (document.frm_editcus.f_brithday.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ วัน/เดือน/ปี เกิด";
	}

	if (document.frm_editcus.f_age.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
	}
	
	if (document.frm_editcus.f_san.value=="") {
		theMessage = theMessage + "\n -->  กรุณาใส่ สัญชาติ";
	}else{
		//กรณีเลือกคนไทย
		if($("#cus1").is(':checked')){
			//ถ้าเลือกสัญชาติที่ไม่ใช่ไทยถือว่าผิด
			if($('#f_san') .attr('value')!='ไทย' && $('#f_san') .attr('value')!='ไม่ระบุ'){
				theMessage = theMessage + "\n -->  กรุณาเลือกสัญชาติไทยหรือไม่ระบุ";
			}
		}
		
		//กรณีเลือกชาวต่างชาติ
		if($("#cus2").is(':checked')){
			//ถ้าเลือกสัญชาติไทยถือว่าผิด
			if($('#f_san') .attr('value')=='ไทย'){
				theMessage = theMessage + "\n -->  กรุณาเลือกสัญชาติอื่นที่ไม่ใช่ไทย";
			}
		}
	}
	
	if (document.frm_editcus.f_status.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุสถานภาพ";
	}

	if (document.frm_editcus.f_no.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
	}

	if (document.frm_editcus.f_subno.value==""){
		if($('#f_subnochk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
		}
	}

	if (document.frm_editcus.f_soi.value==""){
		if($('#f_soichk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ ซอย";
		}
	}

	if (document.frm_editcus.f_rd.value=="") {
		if($('#f_rdchk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ ถนน";
		}
	}
	
	if (document.frm_editcus.f_tum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ แขวง/ตำบล";
	}

	if (document.frm_editcus.f_aum.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ เขต/อำเภอ";
	}
	
	if (document.frm_editcus.f_post.value=="") {
		if($('#f_postchk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์";
		}
	}
	
	if (document.frm_editcus.f_country.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุประเทศ";
	}
	
	if (document.frm_editcus.f_mobile.value=="") {
		if (document.frm_editcus.f_telephone.value=="") {
			theMessage = theMessage + "\n -->  กรุณาระบุเบอร์มือถือหรือเบอร์บ้าน";
		}
	}	

	

	// if (document.frm_editcus.f_card.value=="") {
	// theMessage = theMessage + "\n -->  กรุณาใส่ บัตรแสดงตัว";
	// }

	

	if (document.frm_editcus.f_extadd.value==0) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่ติดต่อ";
	}
	

	
	if(document.frm_editcus.f_extadd.value==2){
		if (document.frm_editcus.f_ext.value=="") {
			theMessage = theMessage + "\n -->  กรุณากรอกที่อยู่";
		}
	}
	

	// If no errors, submit the form
	if (theMessage == noErrors) {
    //ตรวจสอบว่า email ถูกต้องหรือไม่
		if(document.getElementById("f_email").value!=""){
			var emailFilter=/^.+@.+\..{2,3}$/;
			var str=document.getElementById("f_email").value;
			if (!(emailFilter.test(str))) { 
				   alert ("ใส่อีเมล์ไม่ถูกต้อง");
				   document.getElementById("f_email").select();
				   return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	
	}else{
		alert(theMessage);
		return false;
	}
}

</script>
<script type="text/javascript">
function fn_cus()
{
  var s1=document.frm_editcus.fh_adds.value;
  var s2="";
  var tcard="ที่อยู่ตามบัตรประชาชน"
  if(document.frm_editcus.f_extadd.value==2)
  {
    
	//alert("ใช้ที่อยู่ปัจจุบัน");
	document.frm_editcus.f_ext.disabled=false;
	document.frm_editcus.f_ext.value=s1;
	document.frm_editcus.f_ext.focus();
    
  }
  else if(document.frm_editcus.f_extadd.value==1)
  {
   document.frm_editcus.f_ext.disabled=true;
   document.frm_editcus.f_ext.value=tcard;
  }
   else if(document.frm_editcus.f_extadd.value==0)
  {
   document.frm_editcus.f_ext.disabled=true;
   document.frm_editcus.f_ext.value=s1;
  }
 
}
</script>
<script type="text/javascript">	
$(document).ready(function(){ 
<?php if($N_CARD == "บัตรประชาชน"){ ?>
$("#tb_other").hide();
document.frm_editcus.add_other.value="";
document.frm_editcus.N_CAPDREF.value="";
<?php } ?>

<?php
if($statuscus==0){
?>
	$("#showedit").show();
<?php
}else{
?>
	$("#showedit").hide();
<?php
}
?>

$("#linkchid").hide();
<?php
if($statuscus==0){
?>
digit();
<?php
}
?>
	$("#chk_other").click(function(){ 
		if($('#chk_other') .attr( 'checked')==true){
			$("#tb_other").show();
		}else{
			$("#tb_other").hide();
			document.frm_editcus.add_other.value="";
			document.frm_editcus.N_CAPDREF.value="";
		}
	});
	
	$("#list_other").click(function(){ 
		if($('#list_other') .attr( 'value')=='other'){
			$("#othertype").show();
		}else{
			$("#othertype").hide();
			document.frm_editcus.add_other.value="";
			document.frm_editcus.N_CAPDREF.value="";

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
	
	//กรณีเลือกคนไทย
	$('#cus1').click(function(){
		if($("#cus1").is(':checked')){
			$('#showedit').show();
			$('#f_cardid').css('color', '#000000');
			if($('#checkdigit').val()=='pass'){
				$('#f_cardid').css('background-color', '#66FF66');
			}else{
				$('#f_cardid').css('background-color', '#FF6666');
			}
			
			$('#f_cardid').attr('readonly', false); //ให้เลขที่บัตรประชาชนสามารถกรอกได้
		}
	});
	
	//กรณีเลือกชาวต่างชาติ
	$('#cus2').click(function(){
		if($("#cus2").is(':checked')){	
			$('#showedit').hide();
			$('#checkdigit').val('pass');
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
	});
	
	//กรณีเลือกบริษัท
	$('#cus3').click(function(){
		if($("#cus3").is(':checked')){
			$('#showedit').hide();
			$('#checkdigit').val('pass');
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
	});
});	

function calbrith(){

	var byear = new Date(document.frm_editcus.f_brithday.value);
    var current = new Date();
    var age = current.getFullYear() - byear.getFullYear();
	var m = current.getMonth() - byear.getMonth();
	if (m < 0 || (m === 0 && current.getDate() < byear.getDate())) {
        age--;
    }
	document.frm_editcus.f_age.value = age;
	
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}


function digit(){
var str= document.frm_editcus.f_cardid.value;
	if(str.length < 13){
		alert('เลขบัตรประชาชนไม่ครบ 13 หลัก');
		$("#linkchid").show();
		document.frm_editcus.checkdigit.value = 'fail';
		document.getElementById("f_cardid").style.backgroundColor = "#FF6666";
	}else{
		var dig1 = (str.substring(0, 1))*13;
		var dig2 = (str.substring(1, 2))*12;
		var dig3 = (str.substring(2, 3))*11;
		var dig4 = (str.substring(3, 4))*10;
		var dig5 = (str.substring(4, 5))*9;
		var dig6 = (str.substring(5, 6))*8;
		var dig7 = (str.substring(6, 7))*7;
		var dig8 = (str.substring(7, 8))*6;
		var dig9 = (str.substring(8, 9))*5;
		var dig10 = (str.substring(9, 10))*4;
		var dig11 = (str.substring(10, 11))*3;
		var dig12 = (str.substring(11, 12))*2;
		var dig13 = (str.substring(12, 13));
		var digcheck1 = (dig1+dig2+dig3+dig4+dig5+dig6+dig7+dig8+dig9+dig10+dig11+dig12)%11;
		var	digcheck2 = 11-digcheck1;
			digcheck3 =	digcheck2.toString();
			checknum = digcheck3.length;
		if(checknum == 2){
			var dig14 = (digcheck3.substring(1,2));
		}else{
			
			var dig14 = digcheck3;
		}
		
		if(dig14 == dig13){
			document.frm_editcus.checkdigit.value = 'pass';
			document.getElementById("f_cardid").style.backgroundColor = "#66FF66";
			$("#linkchid").hide();
		}else{
			alert('รูปแบบหมายเลขบัตรไม่ถูกต้อง');
			document.frm_editcus.checkdigit.value = 'fail';
			document.getElementById("f_cardid").style.backgroundColor = "#FF6666";
			$("#linkchid").show();
			
		}
	}
}	
</script>

<title>AV. leasing co.,ltd</title>
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

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"><?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:20px; padding-left:10px;">
  </div>
  
  <div class="style5" style="width:auto; height:50px; padding-left:10px;">
    <form name="frm_editcus" method="post" action="update_contactcus.php">
	  <input type="hidden" name="fcus_id" value="<?php echo $id_cusid; ?>" />
	  <input type="hidden" name="f_idno" value="<?php echo $vidno; ?>" />
	
	
	<b>  ผู้ค้ำประกัน (คนที่ <?php echo $CusState;?>) </b>
	  <p>
	  <table width="785" border="0" cellpadding="1" cellspacing="1">
	   <tr>
		<td colspan="6" style="background-color:#FFFFCC;">ข้อมูลผู้ค้ำประกัน</td></tr>
		<tr>
			<td colspan="6">
				<input type="radio" name="statuscus" id="cus1" value="0" <?php if($statuscus==0){ echo "checked"; } ?>> คนไทย
				<input type="radio" name="statuscus" id="cus2" value="1" <?php if($statuscus==1){ echo "checked"; } ?>> ชาวต่างชาติ
				<input type="radio" name="statuscus" id="cus3" value="2" <?php if($statuscus==2){ echo "checked"; } ?>> บริษัท
			</td>
		</tr>
		<tr>
			<!--hidden files for check ditgi of identity number-->	
			<input type="hidden" name="checkdigit" id="checkdigit" value="">
			<input type="hidden" name="checkidensame" id="checkidensame" value="">
		
			<td width="144">เลขที่บัตรประชาชน</td>
			<td width="227"><input type="text" name="f_cardid" Readonly="true" id="f_cardid" onblur="javascript:digit()" value="<?php echo str_replace(" ","",$N_IDCARD);?>" maxlength="13"  /><font color="red">*</font>
				<a id="showedit" href="javascript:popU('../nw/manageCustomer/Re_indentify/frm_reiden.php?cusidd=<?php echo $id_cusid; ?>')" name="linkchid" id="linkchid"><u><font color="red">แก้ไขเลขบัตร</font></u></a>
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
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" /><input name="button_otdate" type="button" onclick="displayCalendar(document.frm_editcus.f_datecard,'yyyy-mm-dd',this)" value="ปฏิทิน" /><font color="red">*</font>
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
			<select name="list_other" id="list_other" onchange="hsother()">
						<option value="บัตรข้าราชการ" <?php if($N_CARD=="บัตรข้าราชการ"){ echo "selected";} ?>>บัตรข้าราชการ</option>
						<option value="เลขทะเบียนนิติบุคคล" <?php if($N_CARD=="เลขทะเบียนนิติบุคคล"){ echo "selected";} ?>>เลขทะเบียนนิติบุคคล</option>
						<option value="เลขที่การค้า" <?php if($N_CARD=="เลขที่การค้า"){ echo "selected";} ?>>เลขที่การค้า</option>
						<option value="บัตรต่างด้าว" <?php if($N_CARD=="บัตรต่างด้าว"){ echo "selected";} ?>>บัตรต่างด้าว</option>
						<option value="หนังสือเดินทาง(ต่างประเทศ)" <?php if($N_CARD=="หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected";} ?>>หนังสือเดินทาง(ต่างประเทศ)</option>
						<option <?php if($N_CARD != "บัตรข้าราชการ" and $N_CARD != "เลขทะเบียนนิติบุคคล"  and $N_CARD != "เลขที่การค้า" and $N_CARD != "บัตรต่างด้าว" and $N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ echo "selected"; } ?> value="other">อื่นๆ</option>
					</select>
			</td>	
			<td width="155">หมายเลขบัตร อื่นๆ</td>
			<td width=""><input type="text" name="N_CAPDREF" id="N_CAPDREF" value="<?php echo $N_CARDREF; ?>"/><font color="red">*</font></td>
		</tr>
<?php if($N_CARD != "บัตรข้าราชการ" and $N_CARD != "เลขทะเบียนนิติบุคคล"  and $N_CARD != "เลขที่การค้า" and $N_CARD != "บัตรต่างด้าว" and $N_CARD != "หนังสือเดินทาง(ต่างประเทศ)"){ ?>	
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
			<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" Readonly="true" size="5"/> ปี</td>
		</tr>
		
	
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227">
			<?php
			//กำหนดตัวแปรสัญชาติเป็น $f_san เพื่อนำไปใช้ใน select_nationality.php
			$f_san=$N_SAN;
			include "select_nationality.php";
			?>
			
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
			<td colspan="3">
				<input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>" /><font color="red">*</font>
				<input type="checkbox" id="f_subnochk" <?php if(trim($fa1_subno) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_editcus.f_subno.disabled=true;document.frm_editcus.f_subno.value='';}else{document.frm_editcus.f_subno.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>"></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?>"></td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>"></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>"></td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td>
				<input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>" /><font color="red">*</font>
				<input type="checkbox" id="f_soichk" <?php if(trim($fa1_soi) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.frm_editcus.f_soi.disabled=true;document.frm_editcus.f_soi.value='';}else{document.frm_editcus.f_soi.disabled=false;}">ไม่มีข้อมูล
			</td>
			<td>ถนน</td>
			<td colspan="3">
				<input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>" /><font color="red">*</font>
				<input type="checkbox" id="f_rdchk" <?php if(trim($fa1_rd) == ""){ echo "checked";} ?>  onClick="javaScript:if(this.checked){document.frm_editcus.f_rd.disabled=true;document.frm_editcus.f_rd.value='';}else{document.frm_editcus.f_rd.disabled=false;}">ไม่มีข้อมูล
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
				if($fa1_pro==""){
					echo "<option>---เลือก---</option>";
				}else{
					echo "<option value=$fa1_pro>$fa1_pro</option>";
				}
				$query_province=pg_query("select * from \"nw_province\" where \"proName\" != '$fa1_pro' order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>"><?php echo $res_pro["proName"];?></option>
				<?php
				}
				?>
				</select>	
			</td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3">
				<input type="text" name="f_post" value="<?php echo $fa1_post; ?>" maxlength="5" /><font color="red">*</font>
				<input type="checkbox" id="f_postchk" <?php if(trim($fa1_post) == ""){ echo "checked";} ?>  onClick="javaScript:if(this.checked){document.frm_editcus.f_post.disabled=true;document.frm_editcus.f_post.value='';}else{document.frm_editcus.f_post.disabled=false;}">ไม่มีข้อมูล
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
			<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" /><font color="orange">*</font></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" /><font color="orange">*</font></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" /></td>
		</tr>
		<tr>
			<td>ที่อยู่ใช้ติดต่อ</td>
			<td colspan="1">
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
			<td colspan="2">
				<input type="hidden" name="fh_adds" value="<?php echo $ext_addr; ?>" /><textarea name="f_ext" cols="50" rows="5" disabled ><?php echo $ext_addr; ?></textarea>
			</td>
		</tr>
	  <tr>
		<td><p></td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td colspan="2"><input type="hidden" name="CusState" value="<?php echo $CusState;?>"><input name="submit" type="submit" value="SAVE" tabindex="22" onclick="return validate()" style="height:50px;width:70px;" /></td>
	    <td colspan="3"><input name="button" type="button" onclick="window.location='frm_edit.php?idnog=<?php echo $vidno;?>'" value="BACK" tabindex="23" style="height:50px;width:70px;"/></td>
	    </tr>
	<tr>
		<td><p></td>
	</tr>	
	</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
