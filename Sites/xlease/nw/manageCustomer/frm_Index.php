<?php
session_start();
include("../../config/config.php");		
$pathsan = redirect($_SERVER['PHP_SELF'],'post/'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
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

function select_country()
{ // เลือกประเทศ
	if(document.frm_edit.f_country.value!="TH" && document.frm_edit.f_country.value!="")
	{
		document.frm_edit.f_province.value = "ไม่ระบุ";
	}
	else if(document.frm_edit.f_country.value=="TH")
	{
		document.frm_edit.f_province.value = "";
	}
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	//กรณีเลือกบัตรประเภทอื่นๆ
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

	//กรณีเลือกคนไทย
	if($("#cus1").is(':checked')){
		//กรณีเลือกบัตรประเภทอื่นๆ
		if(document.frm_edit.chk_other.checked == true){
			//ถ้าเลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')=='หนังสือเดินทาง(ต่างประเทศ)' || $('#list_other') .attr( 'value')=='บัตรต่างด้าว'){
				theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
			}else{
				if (document.frm_edit.f_cardid.value=="") {
					theMessage = theMessage + "\n -->  กรุณาใส่หมายเลขบัตรประชาชน";
				}			
			}
		}else{
			if (document.frm_edit.f_cardid.value=="") {
				theMessage = theMessage + "\n -->  กรุณาใส่หมายเลขบัตรประชาชน";
			}
		}
	}
	
	//กรณีเลือกชาวต่างชาติ
	if($("#cus2").is(':checked')){
		//กรณีเลือกบัตรประเภทอื่นๆ
		if(document.frm_edit.chk_other.checked == true){				
			//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')!='หนังสือเดินทาง(ต่างประเทศ)' 
			&& $('#list_other') .attr( 'value')!='บัตรต่างด้าว' && $('#list_other') .attr( 'value')!='other'){
				theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
			}
		}else{
			theMessage = theMessage + "\n -->  กรุณาระบุประเภทบัตรอื่นๆ";
		}
	}
	
	//กรณีเลือกบริษัท
	if($("#cus3").is(':checked')){
		//กรณีเลือกบัตรประเภทอื่นๆ
		if(document.frm_edit.chk_other.checked == true){				
			//ถ้าไม่ได้เลือกบัตรอื่นๆ เป็นหนังสือเดินทาง (ต่างประเทศ) หรือบัตรต่างด้าว ให้แจ้งว่าไม่ถูกต้อง
			if($('#list_other') .attr( 'value')!='เลขทะเบียนนิติบุคคล' 
			&& $('#list_other') .attr( 'value')!='เลขที่การค้า' && $('#list_other') .attr( 'value')!='other'){
				theMessage = theMessage + "\n -->  กรุณาเลือกประเภทบัตรให้ถูกต้อง";
			}
		}else{
			theMessage = theMessage + "\n -->  กรุณาระบุประเภทบัตรอื่นๆ";
		}
	}
	
	if (document.frm_edit.valuechk.value=="1") {
			theMessage = theMessage + "\n -->  เลขบัตรประชาชนซ้ำ !!";
	}
	if (document.frm_edit.valuechk1.value=="1") {
			theMessage = theMessage + "\n -->  เลขบัตรอื่นๆซ้ำ !!";
	}
	if (document.frm_edit.f_datecard.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ วันที่ออกบัตร";
	}

	if (document.frm_edit.f_card_by.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ผู้ออกบัตร";
	}

	if (document.frm_edit.f_name.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ ชื่อ";
	}

	if (document.frm_edit.f_surname.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ นามสกุล";
	}
	
	if (document.frm_edit.f_san.value=="") {
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
	
	if (document.frm_edit.f_brithday.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกวันเกิด";
	}

	if (document.frm_edit.f_age.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ อายุ";
	}
	
	if (document.frm_edit.f_status.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุสถานภาพ";
	}

	if (document.frm_edit.f_no.value=="") {
	theMessage = theMessage + "\n -->  กรุณาใส่ บ้านเลขที่";
	}

	if (document.frm_edit.f_subno.value==""){
		if($('#f_subnochk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ หมู่ที่";
		}
	}

	if (document.frm_edit.f_soi.value==""){
		if($('#f_soichk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ ซอย";
		}
	}

	if (document.frm_edit.f_rd.value==""){
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
	
	if (document.frm_edit.f_post.value==""){
		if($('#f_postchk').attr( 'checked')==false){
			theMessage = theMessage + "\n -->  กรุณาใส่ รหัสไปรษณีย์";
		}
	}
	
	if (document.frm_edit.f_country.value=="")
	{
		theMessage = theMessage + "\n -->  กรุณาเลือกประเทศ";
	}
	else if(document.frm_edit.f_country.value=="TH")
	{ // ถ้าเลือกประเทศไทย
		if (document.frm_edit.f_province.value=="" || document.frm_edit.f_province.value=="ไม่ระบุ")
		{
			theMessage = theMessage + "\n -->  กรุณาเลือกจังหวัด";
		}
	}
	else if(document.frm_edit.f_country.value!="TH")
	{ // ถ้าไม่ใช่ประเทศไทย
		if (document.frm_edit.f_province.value!="ไม่ระบุ")
		{
			theMessage = theMessage + "\n -->  ถ้าไม่ใช่ประเทศไทย จังหวัดต้อง ไม่ระบุ เท่านั้น";
		}
	}
	
	if (document.frm_edit.f_mobile.value=="") {
		if (document.frm_edit.f_telephone.value=="") {
			theMessage = theMessage + "\n -->  กรุณาระบุเบอร์มือถือหรือเบอร์บ้าน";
		}
	}	

	if (document.frm_edit.f_extadd.value==0) {
	theMessage = theMessage + "\n -->  กรุณาเลือกที่อยู่ติดต่อ";
	}
	
	if(document.frm_edit.f_extadd.value==2){
		if (document.frm_edit.f_ext.value=="") {
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
		}else if (document.frm_edit.f_cardid.value!="" && $("#cus1").is(':checked')) {
			if(!checkID(document.getElementById("f_cardid").value)){
				alert('รหัสประชาชนไม่ถูกต้อง');
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
	$("#tb_other").hide();
	$("#othertype").hide();
	
	$("#chk_other").click(function(){ 
		if($('#chk_other') .attr( 'checked')==true){
			$("#tb_other").show();
			
		}else{
			$("#tb_other").hide();
			document.frm_edit.add_other.value="";
			document.frm_edit.N_CAPDREF.value="";
			document.frm_edit.list_other.value="บัตรข้าราชการ";
			$("#require").show();
			$("#othertype").hide();
			checkcardref();
		}
	});
	
	$("#list_other").click(function(){ 
		if($('#list_other') .attr( 'value')=='other'){
			$("#othertype").show();
		}else{
			$("#othertype").hide();
			document.frm_edit.add_other.value="";

		}
		
		if($('#list_other') .attr( 'value')=='หนังสือเดินทาง(ต่างประเทศ)'){
			$("#require").hide();
		}else{
		
			$("#require").show();
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
			$('#f_cardid').css('color', '#000000');
			if($('#f_cardid').val()==""){
				$('#f_cardid').css('background-color', '#FFFFFF');	
			}else{
				$('#f_cardid').css('background-color', '#33FF33');	
			}
				
			$('#f_cardid').attr('readonly', false); //ให้เลขที่บัตรประชาชนสามารถกรอกได้
		}
	});
	
	//กรณีเลือกชาวต่างชาติ
	$('#cus2').click(function(){
		if($("#cus2").is(':checked')){
			$('#valuechk').val('0');
			$("#f_cardidtext").hide();
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');	
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
	});
	
	//กรณีเลือกบริษัท
	$('#cus3').click(function(){
		if($("#cus3").is(':checked')){
			$('#valuechk').val('0');
			$("#f_cardidtext").hide();
			$('#f_cardid').css('color', '#DDDDDD');
			$('#f_cardid').css('background-color', '#DDDDDD');
			$('#f_cardid').attr('readonly', true); //ให้เลขที่บัตรประชาชนไม่สามารถกรอกได้
		}
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

function checkiden(){
	//ให้ตรวจสอบเฉพาะกรณีเลือกคนไทย
	if($("#cus1").is(':checked')){		
		$.post("checkid.php",{
			id : document.frm_edit.f_cardid.value
			
		},
		function(data){				
			if(data=='No'){
					//alert(' รหัสซ้ำครับกรุณาเปลี่ยนด้วย ');
					document.getElementById("f_cardid").style.backgroundColor ="#FF0000";
					var textalert = ' เลขบัตรประชาชนนี้มีอยู่ในระบบแล้ว ';
					$("#f_cardidtext").text(textalert);
					document.getElementById("valuechk").value='1';
			}else if(data == 'YES'){
					document.getElementById("f_cardid").style.backgroundColor = "#33FF33";
					$("#f_cardidtext").text("");
					document.getElementById("valuechk").value='0';
			}else if(data == 'null'){
					document.getElementById("f_cardid").style.backgroundColor = "#FFFFFF";
					$("#f_cardidtext").text("");
					document.getElementById("valuechk").value='0';								
			}
		});
	}
			
};

function checkcardref(){

		
			$.post("checkidref.php",{
					id : document.frm_edit.N_CAPDREF.value
					
				},
				function(data){		
					
						if(data=='No'){
								//alert(' รหัสซ้ำครับกรุณาเปลี่ยนด้วย ');
								document.getElementById("N_CAPDREF").style.backgroundColor ="#FF0000";
								var textalert = ' เลขบัตรนี้มีอยู่ในระบบแล้ว ';
								$("#f_cardidreftext").text(textalert);
								document.getElementById("valuechk1").value='1';
						}else if(data == 'YES'){
								document.getElementById("N_CAPDREF").style.backgroundColor = "#33FF33";
								$("#f_cardidreftext").text("");
								document.getElementById("valuechk1").value='0';
						}else if(data == 'null'){
								document.getElementById("N_CAPDREF").style.backgroundColor = "#FFFFFF";
								$("#f_cardidreftext").text("");	
								document.getElementById("valuechk1").value='0';								
						}
				});
			
};
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
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
	</div>

	
	
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;"><b>เพิ่มข้อมูลลูกค้า</b> <br /><hr />
	<form name="frm_edit" method="post" action="process_customer.php">
	<input type="hidden" name="valuechk" id="valuechk">
	<input type="hidden" name="valuechk1" id="valuechk1">
		<input type="hidden" name="method" value="add" />
		<table width="785" border="0" cellpadding="1" cellspacing="1">
		<tr>
			<td colspan="6" style="background-color:#FFFFCC;">ข้อมูลลูกค้า</td>
		</tr>
		<tr>
			<td width="144">เลขบัตรประชาชน</td>
			<td width="227" colspan="4"><input type="text" name="f_cardid" autocomplete="off" id="f_cardid" onkeyup="javascript : checkiden();" onblur="javascript : checkiden();" maxlength="13" /><font color="red"><span id="require">*</span><span name="f_cardidtext" id="f_cardidtext"></span></font>
			<input type="radio" name="statuscus" id="cus1" value="0" checked> คนไทย
			<input type="radio" name="statuscus" id="cus2" value="1"> ชาวต่างชาติ
			<input type="radio" name="statuscus" id="cus3" value="2"> บริษัท
			</td>
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_datecard" value="" />
			  <input name="button_otdate" type="button" onclick="displayCalendar(document.frm_edit.f_datecard,'yyyy-mm-dd',this)" value="ปฏิทิน" /><font color="red">*</font></td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_card_by" value="" /><font color="red">*</font></td>
		</tr>
		<tr bgcolor="#E1E1E1">
			<td width="144">บัตรประเภทอื่นๆ</td>
			<td width="227" colspan="6"><input type="checkbox" name="chk_other" id="chk_other" value="1"/></td>			
		</tr>
		<tr bgcolor="#E1E1E1">
		<td colspan="7">
		<table name="tb_other" id="tb_other" width="700" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="145" >ประเภท</td>
			<td width="230"><select name="list_other" id="list_other" onchange="hsother()" />
							<option value="บัตรข้าราชการ">บัตรข้าราชการ</option>
							<option value="เลขทะเบียนนิติบุคคล">เลขทะเบียนนิติบุคคล</option>
							<option value="เลขที่การค้า">เลขที่การค้า</option>
							<option value="บัตรต่างด้าว">บัตรต่างด้าว</option>						
							<option value="หนังสือเดินทาง(ต่างประเทศ)">หนังสือเดินทาง(ต่างประเทศ)</option>
							<option value="other">อื่นๆ</option>
							</select>
			</td>	
			<td width="155">หมายเลขบัตรอื่นๆ</td>
			<td width=""><input type="text" name="N_CAPDREF" id="N_CAPDREF" autocomplete="off" onkeyup="javascript : checkcardref();" onblur="javascript : checkcardref();" /><font color="red">*<span name="f_cardidreftext" id="f_cardidreftext"></span></font></td>	
		</tr>
		<tr name="othertype" id="othertype">
			<td>บัตรอื่นๆ</td>
			<td><input type="text" name="add_other" id="add_other"/></td>	
		</tr>
		</table>
		</td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td>คำนำหน้าชื่อ (ไทย)</td>
			<td><input type="text" name="f_fri_name" id="f_fri_name" value=""/></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="" /></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="f_name" value=""/><font color="red">*</font></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="" /></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="f_surname" value=""/><font color="red">*</font></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="" />
			เพศ 
				<select name="A_SEX">
					<option value="">ไม่ระบุ</option>
					<option value="1">หญิง</option>
					<option value="2">ชาย</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value=""/></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" value="" onchange="calbrith();" size="15"/><font color="red">*</font>
			อายุ
			<input type="text" name="f_age" id="f_age" value="" onkeyup="data_change(this);" Readonly="true" size="5"/> ปี</td>
		</tr>
		
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227">
			
			<select name="f_san" id="f_san" size="1">
					<option value="">----เลือก----</option>
					<option value="ไม่ระบุ">ไม่ระบุ</option>
					<?php
					$query_country=pg_query("select \"CountryCode\",\"Nationality_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE' AND \"Nationality_THAI\" is not null order by \"Nationality_THAI\"") ;
					while($res_country = pg_fetch_array($query_country)){
					?>
					<option value="<?php echo $res_country["Nationality_THAI"];?>" <?php if($res_country["Nationality_THAI"]=='TH'){ echo "selected"; }?>><?php echo $res_country["Nationality_THAI"];?></option>
					<?php
					}
					?>
				
			</select>
			<font color="red">*</font></td>
			
			<td width="90">ระดับการศึกษา</td>
			<td colspan="3">
				<select name="f_education">
					<option value="">---เลือก---</option>
					<option value="1">ต่ำกว่ามัธยมศึกษาตอนต้น</option>
					<option value="2">มัธยมศึกษาตอนต้น</option>
					<option value="3">มัธยมศึกษาตอนปลาย</option>
					<option value="4">ปวช.</option>
					<option value="5">ปวส.</option>
					<option value="6">อนุปริญญา</option>
					<option value="7">ปริญญาตรี</option>
					<option value="8">ปริญญาโท</option>
					<option value="9">ปริญญาเอก</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" onkeypress="return check_number(event);" value="0.00"/></td>
			<td width="90">สถานภาพ</td>
			<td colspan="3">
				<select name="f_status">
					<option value="">---เลือก---</option>
					<option value="0002">โสด</option>
					<option value="0001">สมรส</option>
					<option value="0005">สมรสไม่จดทะเบียน</option>
					<option value="0004">หย่า</option>
					<option value="0003">หม้าย</option>
					<option value="0000">ไม่ระบุ</option>
				</select>
				<font color="red">*</font>
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="f_pair" value=""/></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value=""/></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		
		
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_no" value="" /><font color="red">*</font></td>
			<td>หมู่ที่</td>
			<td colspan="3">
				<input type="text" name="f_subno" value=""/><font color="red">*</font>
				<input type="checkbox" id="f_subnochk" onClick="javaScript:if(this.checked){document.frm_edit.f_subno.disabled=true;document.frm_edit.f_subno.value='';}else{document.frm_edit.f_subno.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30"></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30"></td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30"></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30"></td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td>
				<input type="text" name="f_soi" value=""/><font color="red">*</font>
				<input type="checkbox" id="f_soichk" onClick="javaScript:if(this.checked){document.frm_edit.f_soi.disabled=true;document.frm_edit.f_soi.value='';}else{document.frm_edit.f_soi.disabled=false;}">ไม่มีข้อมูล
			</td>
			<td>ถนน</td>
			<td colspan="3">
				<input type="text" name="f_rd" value=""/><font color="red">*</font>
				<input type="checkbox" id="f_rdchk" onClick="javaScript:if(this.checked){document.frm_edit.f_rd.disabled=true;document.frm_edit.f_rd.value='';}else{document.frm_edit.f_rd.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" value=""/><font color="red">*</font></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" name="f_aum" value=""/><font color="red">*</font></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td>
				<select name="f_province" size="1">
				<?php
				if($fa1_pro==""){
				?>
					<option value="">---เลือก---</option>
				<?php
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
					<option value="ไม่ระบุ">ไม่ระบุ</option>
				</select><font color="red">*</font>
			</td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3">
				<input type="text" name="f_post" value="" maxlength="5"/><font color="red">*</font>
				<input type="checkbox" id="f_postchk" onClick="javaScript:if(this.checked){document.frm_edit.f_post.disabled=true;document.frm_edit.f_post.value='';}else{document.frm_edit.f_post.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td>
				<select name="f_country" id="f_country" size="1" onChange="select_country()">
					<option value="">----เลือก----</option>
					<?php
					$query_country=pg_query("select \"CountryCode\",\"CountryName_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE'");
					while($res_country = pg_fetch_array($query_country)){
					?>
					<option value="<?php echo $res_country["CountryCode"];?>" <?php if($res_country["CountryCode"]=='TH'){ echo "selected"; }?>><?php echo $res_country["CountryName_THAI"];?></option>
					<?php
					}
					?>
				</select><font color="red">*</font>
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" value=""/><font color="orange">*</font></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value=""/><font color="orange">*</font></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="" size="30"/></td>
		</tr>
		<tr>
			<td valign="top">ที่อยู่ใช้ติดต่อ</td>
			<td valign="top">
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
			<td colspan="2" valign="top">
				<input type="hidden" name="fh_adds" value="<?php echo $ext_addr; ?>" /><textarea name="f_ext" cols="50" rows="5" disabled ></textarea>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="4">&nbsp;</td>
		</tr> 
		<tr>
			<td colspan="7" align="center">
				<input name="submit" type="submit" value="บันทึก" onclick="return validate();"/>
				<input type="reset" value="ล้างข้อความ"/>
			</td>
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
</body>
</html>
