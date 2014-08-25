<?php
	session_start();
	include("../../config/config.php");
	if(!isset($_SESSION['languege']))
	{
		$lang="th";
	}
	else
	{
		$lang=$_SESSION['languege'];
	}
	$v_regis = array();
	$i=0;
	if($lang=="th")
	{
		$query=pg_query("select \"word\" from carsystem.\"v_language_th\" where	\"path_file\"='register.php'");
		while($rs=pg_fetch_assoc($query))
		{
			$v_regis[$i]=$rs['word'];
			$i++;
		}
	}
	else if($lang=="en")
	{
		$query=pg_query("select \"word\" from carsystem.\"v_language_en\" where	\"path_file\"='register.php'");
		while($rs=pg_fetch_assoc($query))
		{
			$v_regis[$i]=$rs['word'];
			$i++;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Untitled Document</title>

<link rel="shortcut icon" type="image/x-icon" href="icon/icon.ico">

<link href="css/main.css" rel="stylesheet" type="text/css">
<link href="css/smoothness/ui.all.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="script/jquery-1.3.2.js"></script>
<script type="text/javascript" src="script/ui/ui.core.js"></script>
<script type="text/javascript" src="script/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="script/ui/i18n/ui.datepicker-th.js"></script>

<script type="text/javascript" src="script/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="script/jquery-ui.min.js"></script>
<script type="text/javascript" src="captcha/jquery.captcha.js"></script>
<link href="captcha/captcha.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">
	$(function() {
		//var d = new date();
		//var c = d.getYear();
		$('#tbxbirthday').datepicker({
			regional:'th',
			changeMonth: true,
			changeYear: true,
			yearRange: '1920:2012'
		});
	});
</script>
<script type="text/javascript">
function getDistrict()
{
	var provinceID=$("#slctprovince").val();
	//alert(provinceID);
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "getDistrict.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"province_id="+provinceID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;          
        $("#slctdistrict").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
</script>
<script type="text/javascript" charset="utf-8">
	$(function() {
		$(".ajax-fc-container").captcha({
			formId: "frm_register",
			borderColor: "silver",
			text: "โปรดพิสูจน์ตัวตน,<br />ลาก <span>กรรไกร</span> ไปยังวงกลม"
		});
	});
</script>
<script type="text/javascript">

$(document).ready(function(){
	var verb = $('#ajax-fc-task span').html();
	var new_verb;
	switch(verb)
	{
		case "pencil":
			new_verb = verb.replace("pencil","ดินสอ");
			break;
		case "scissor":
			new_verb = verb.replace("scissor","กรรไกร");
			break;
		case "clock":
			new_verb = verb.replace("clock","นาฬิกา");
			break;
		case "heart":
			new_verb = verb.replace("heart","หัวใจ");
			break;
		case "note":
			new_verb = verb.replace("note","ตัวโน๊ต");
			break;
	}
	$('#ajax-fc-task span').html(new_verb);
	
	$('#require_user').hide();
	$('#require_password').hide();
	$('#require_confirmpassword').hide();
	$('#require_dontmatchpassword').hide();
	$('#duplicate_user').hide();
	$('#min_user').hide();
	$('#righ_user').hide();
	$('#require_showname').hide();
	$('#duplicate_showname').hide();
	$('#min_showname').hide();
	$('#righ_showname').hide();
	$('#require_email').hide();
	$('#duplicate_email').hide();
	$('#invalid_email').hide();
	$('#righ_email').hide();
	$('#require_mobile').hide();
	$('#invalid_mobile').hide();
	$('#duplicate_mobile').hide();
	$('#righ_mobile').hide();
	
	$("#tbxuser_regis").change(function(){ //if theres a change in the username textbox
	
		var username = $("#tbxuser_regis").val();//Get the value in the username textbox
		
		if(username.length > 3)//if the lenght greater than 3 characters
		{
			$("#checkuser").html('<img src="images/89.gif" align="absmiddle">');
			//Add a loading image in the span id="availability_status"
			
			$.ajax({ //Make the Ajax Request
				 type: "POST",
				 url: "ajax_check.php", //file name
				 data: "username="+ username+"&chkpoint=user", //data
				 success: function(server_response){
					 
					 //$("#checkuser").ajaxComplete(function(event, request){
					 if(server_response == '0')//if ajax_check_username.php return value "0"
					 {
						 $("#checkuser").html('<img src="images/right.png" align="absmiddle">');
						 $("#tbxuser_regis").css('background-color','#B8F5B1');
						 document.getElementById('user_hidden').value='0';
						 $("#righ_user").fadeIn(700).show("slow").css('color','green');
						 setTimeout(function(){$("#righ_user").fadeOut(700).hide("slow")},5000);
						 //alert($("#checkuser").html());
					 //add this image to the span with id "availability_status"
					 }
					 else if(server_response = '1')//if it returns "1"
					 {
						 $("#checkuser").html('<img src="images/close1.png" align="absmiddle">');
						 $("#tbxuser_regis").css('background-color','#FF9F9F');
						 document.getElementById('user_hidden').value='1';
						 $("#duplicate_user").fadeIn(700).show("slow");
					 }
			 		//});
		 		}
		 	});
		}
		else
		{
			if(username.length == 0)
			{
				$("#checkuser").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxuser_regis").css('background-color','#FF9F9F');
				document.getElementById('user_hidden').value='1';
				$("#require_user").fadeIn(700).show("slow");
			}
			else
			{
				$("#checkuser").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxuser_regis").css('background-color','#FF9F9F');
				document.getElementById('user_hidden').value='1';
				$("#min_user").fadeIn(700).show("slow");
			}
			//if in case the username is less than or equal 3 characters only
		}
		return false;
	});
	$("#tbxshowname_regis").change(function(){ //if theres a change in the username textbox
		
		var showname = $("#tbxshowname_regis").val();//Get the value in the username textbox
		
		if(showname.length > 3)//if the lenght greater than 3 characters
		{
			$("#chkshowname").html('<img src="images/89.gif" align="absmiddle">');
			//Add a loading image in the span id="availability_status"
			
			$.ajax({ //Make the Ajax Request
				 type: "POST",
				 url: "ajax_check.php", //file name
				 data: "showname="+ showname +"&chkpoint=showname", //data
				 success: function(server_response){
					 //alert(server_response);
					 //$("#chkshowname").ajaxComplete(function(event, request){
					
					 if(server_response == '0')//if ajax_check_username.php return value "0"
					 {
					 $("#chkshowname").html('<img src="images/right.png" align="absmiddle">');
					 $("#tbxshowname_regis").css('background-color','#B8F5B1');
					 document.getElementById('showname_hidden').value='0';
					 $("#righ_showname").fadeIn(700).show("slow").css('color','green');
					 setTimeout(function(){$("#righ_showname").fadeOut(700).hide("slow")},5000);
					 //add this image to the span with id "availability_status"
					 }
					 else if(server_response = '1')//if it returns "1"
					 {
					 $("#chkshowname").html('<img src="images/close1.png" align="absmiddle">');
					 $("#tbxshowname_regis").css('background-color','#FF9F9F');
					 document.getElementById('showname_hidden').value='1';
					 $("#duplicate_showname").fadeIn(700).show("slow");
					 }
		 		}
		 	});
		}
		else
		{
			if(showname.length == 0)
			{
				$("#chkshowname").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxshowname_regis").css('background-color','#FF9F9F');
				document.getElementById('showname_hidden').value='1';
				$("#require_showname").fadeIn(700).show("slow");
				setTimeout(function(){$("#require_showname").fadeOut(700).hide("slow")},5000);
			}
			else
			{
				$("#chkshowname").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxshowname_regis").css('background-color','#FF9F9F');
				document.getElementById('showname_hidden').value='1';
				$("#min_showname").fadeIn(700).show("slow");
			}
			//if in case the username is less than or equal 3 characters only
		}
		return false;
	});
	$("#tbxemail").change(function(){ //if theres a change in the username textbox
		
		var email = $("#tbxemail").val();//Get the value in the username textbox
		
		if(email.length > 5)//if the lenght greater than 3 characters
		{
			$("#chkemail").html('<img src="images/89.gif" align="absmiddle">');
			//Add a loading image in the span id="availability_status"
			
			$.ajax({ //Make the Ajax Request
				 type: "POST",
				 url: "ajax_check.php", //file name
				 data: "email="+ email +"&chkpoint=email", //data
				 success: function(server_response){
					 //alert(server_response);
					 //$("#chkemail").ajaxComplete(function(event, request){
					
					 if(server_response == '0')//if ajax_check_username.php return value "0"
					 {
						 //alert($(".textfieldInvalidFormatMsg").css('display'));
						 $("#chkemail").html('<img src="images/right.png" align="absmiddle">');
						 $("#tbxemail").css('background-color','#B8F5B1');
						 document.getElementById('email_hidden').value='0';
						 $("#righ_email").fadeIn(700).show("slow").css('color','green');
						 setTimeout(function(){$("#righ_email").fadeOut(700).hide("slow")},5000);
					 //add this image to the span with id "availability_status"
					 }
					 else if(server_response == '1')//if it returns "1"
					 {
						 $("#chkemail").html('<img src="images/close1.png" align="absmiddle">');
						 $("#tbxemail").css('background-color','#FF9F9F');
						 document.getElementById('email_hidden').value='1';
						 $("#duplicate_email").fadeIn(700).show("slow");
					 }
		 		}
		 	});
		}
		else
		{
			if(email.length == 0)
			{
				$("#chkemail").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxemail").css('background-color','#FF9F9F');
				document.getElementById('email_hidden').value='1';
				$("#require_email").fadeIn(700).show("slow");
			}
			else
			{
				$("#chkemail").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxemail").css('background-color','#FF9F9F');
				document.getElementById('email_hidden').value='1';
				$("#invalid_email").fadeIn(700).show("slow");
			}
			//if in case the username is less than or equal 3 characters only
		}
		return false;
	});
	$("#tbxmobile").change(function(){ //if theres a change in the username textbox
		
		var mobile = $("#tbxmobile").val();//Get the value in the username textbox
		
		if(mobile.length > 9)//if the lenght greater than 3 characters
		{
			$("#chkmobile").html('<img src="images/89.gif" align="absmiddle">');
			//Add a loading image in the span id="availability_status"
			
			$.ajax({ //Make the Ajax Request
				 type: "POST",
				 url: "ajax_check.php", //file name
				 data: "mobile="+ mobile +"&chkpoint=mobile", //data
				 success: function(server_response){
					 //alert(server_response);
					 //$("#chkemail").ajaxComplete(function(event, request){
					
					 if(server_response == '0')//if ajax_check_username.php return value "0"
					 {
						 //alert($(".textfieldInvalidFormatMsg").css('display'));
						 $("#chkmobile").html('<img src="images/right.png" align="absmiddle">');
						 $("#tbxmobile").css('background-color','#B8F5B1');
						 document.getElementById('mobile_hidden').value='0';
						 $("#righ_mobile").fadeIn(700).show("slow").css('color','green');
						 setTimeout(function(){$("#righ_mobile").fadeOut(700).hide("slow")},5000);
					 //add this image to the span with id "availability_status"
					 }
					 else if(server_response == '1')//if it returns "1"
					 {
						 $("#chkmobile").html('<img src="images/close1.png" align="absmiddle">');
						 $("#tbxmobile").css('background-color','#FF9F9F');
						 document.getElementById('mobile_hidden').value='1';
						 $("#duplicate_mobile").fadeIn(700).show("slow");
					 }
			 		//});
		 		}
		 	});
		}
		else
		{
			if(mobile.length == 0)
			{
				$("#chkmobile").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxmobile").css('background-color','#FF9F9F');
				document.getElementById('mobile_hidden').value='1';
				$("#require_mobile").fadeIn(700).show("slow");
			}
			else
			{
				$("#chkmobile").html('<img src="images/close1.png" align="absmiddle">');
				$("#tbxmobile").css('background-color','#FF9F9F');
				document.getElementById('mobile_hidden').value='1';
				$("#invalid_mobile").fadeIn(700).show("slow");
			}
			//if in case the username is less than or equal 3 characters only
		}
		return false;
	});
	$('#tbxuser_regis').change(function(){
		$('#require_user').hide();
		$('#duplicate_user').hide();
		$('#min_user').hide();
		$('#righ_user').hide();
	});
	$('#tbxpassword_regis').change(function(){
		$('#require_password').hide();
	});
	$('#tbxconfirmpass_regis').change(function(){
		$('#require_confirmpassword').hide();
		$('#require_dontmatchpassword').hide();
	});
	$('#tbxshowname_regis').change(function(){
		$('#require_showname').hide();
		$('#duplicate_showname').hide();
		$('#min_showname').hide();
		$('#righ_showname').hide();
	});
	$('#tbxemail').change(function(){
		$('#require_email').hide();
		$('#duplicate_email').hide();
		$('#invalid_email').hide();
		$('#righ_email').hide();
	});
	$('#tbxmobile').change(function(){
		$('#require_mobile').hide();
		$('#invalid_mobile').hide();
		$('#duplicate_mobile').hide();
		$('#righ_mobile').hide();
	});
	$('#divheader1').load('header.php');
	$('#divfooter1').load('footer.php');
});
</script>
<script type="text/javascript">
function chkpass(){
	var pass;
	pass = document.getElementById('tbxpassword_regis').value;
	var confirmpass;
	confirmpass = document.getElementById('tbxconfirmpass_regis').value;
	//alert(pass);
	if(pass!='' && confirmpass!='')
	{
		if(pass==confirmpass){
			$('#chkpass').html('<img src="images/right.png" align="absmiddle">');
			$('#chkconfirmpass').html('<img src="images/right.png" align="absmiddle">');
			$('#tbxpassword_regis').css('background-color','#B8F5B1');
			$('#tbxconfirmpass_regis').css('background-color','#B8F5B1');
			document.getElementById('matchpassword_hidden').value='0';
			$('#require_password').hide();
			$('#require_confirmpassword').hide();
			$('#require_dontmatchpassword').hide();
		}else{
			$('#chkpass').html('<img src="images/close1.png" align="absmiddle">');
			$('#chkconfirmpass').html('<img src="images/close1.png" align="absmiddle">');
			$('#tbxpassword_regis').css('background-color','#FF9F9F');
			$('#tbxconfirmpass_regis').css('background-color','#FF9F9F');
			$('#require_dontmatchpassword').show();
			document.getElementById('matchpassword_hidden').value='1';
			//$('#tbxpassword_regis').focus();
		}
	}
	else
	{
		if(pass=='')
		{
			$('#require_password').show();
			$('#chkpass').html('<img src="images/close1.png" align="absmiddle">');
			$('#tbxpassword_regis').css('background-color','#FF9F9F');
			document.getElementById('matchpassword_hidden').value='1';
			//document.getElementById('password_hidden').value='1';
		}
		else if(confirmpass=='')
		{
			$('#require_confirmpassword').show();
			//document.getElementById('confirmpassword_hidden').value='1';
			$('#chkconfirmpass').html('<img src="images/close1.png" align="absmiddle">');
			$('#tbxconfirmpass_regis').css('background-color','#FF9F9F');
			document.getElementById('matchpassword_hidden').value='1';
		}
	}
}
function chkform(){
	if($("#user_hidden").val()=='0' && $("#showname_hidden").val()=='0' && /*$("#password_hidden").val()!='0' && $("#confirmpassword_hidden").val()!='0' && */$("#email_hidden").val()=='0' && $("#matchpassword_hidden").val()=='0' && $("#mobile_hidden").val()=='0')
	{
		
		$('#frm_register').submit();
	}
	else
	{
		window.location.href ='#divinfo1';
		//alert('user\t\t'+$("#user_hidden").val()+'\r\n'+'showname\t\t'+$("#showname_hidden").val()+'\r\n'+/*'password\t\t'+$("#password_hidden").val()+'\r\n'+'confirmpassword\t\t'+$("#confirmpassword_hidden").val()+'\r\n'+*/'email\t\t'+$("#email_hidden").val()+'\r\n'+'passwordresult\t\t'+$("#matchpassword_hidden").val()+'\r\n'+'mobile\t\t'+$("#mobile_hidden").val());
	}
}
</script>
<link href="regis.css" rel="stylesheet" type="text/css">
</head>

<body>
<input type="hidden" name="user_hidden" id="user_hidden" value="0">
<input type="hidden" name="showname_hidden" id="showname_hidden" value="0">
<input type="hidden" name="matchpassword_hidden" id="matchpassword_hidden" value="0">
<input type="hidden" name="email_hidden" id="email_hidden" value="0">
<input type="hidden" name="mobile_hidden" id="mobile_hidden" value="0">
<div id="divheader1"></div>
<div id="divbordycontrainer">
    <div align="center">
    	<table border="0" cellpadding="0" cellspacing="0" width="800">
        	<tr>
            	<td width="580" align="left" valign="top">
                	<div id="tdregister-bg">
                    <form id="frm_register" name="frm_register" action="register_process.php" method="post">
                        <div id="divregisterhead"><span id="registertext"><?php echo $v_regis[0]; ?></span></div>
                        <ul class="ulregister">
                            <li>
                                <div id="divinfo1" class="divinfo">
                                    <div id="divinfolabel1" class="divinfolabel"><img src="images/1.png"></div>
                                    <span class="spanregislebel"><?php echo $v_regis[1]; ?></span><br>
                                	<label>
                                		<input name="tbxuser_regis" type="text" class="tbx_regis_long" id="tbxuser_regis" maxlength="30">
                            			<span id="checkuser" class="required">*</span>
                                    	<span id="require_user" class="textfieldRequiredMsg">โปรดระบุชื่อผู้ใช้</span>
                                        <span id="duplicate_user" class="textfieldRequiredMsg">ชื่อนี้ถูกใช้แล้วกรุณาระบุใหม่</span>
                                        <span id="min_user" class="textfieldRequiredMsg">กรุณาระบุอย่างน้อยสี่ตัวอักษร</span>
                                        <span id="righ_user" class="textfieldRequiredMsg">ชื่อนี้สามารถใช้งานได้</span>
                                  </label>
                                    <br>
                                    <span class="spanregislebel"><?php echo $v_regis[2]; ?></span><br>
                                	<label>
                                        <input name="tbxpassword_regis" type="password" class="tbx_regis_long" id="tbxpassword_regis" onChange="chkpass()" maxlength="30">
                                        <span id="chkpass" class="required">*</span>
                                        <span id="require_password" class="textfieldRequiredMsg">โปรดระบุรหัสผ่าน</span>
                           		  </label>
                              		<br>
                                    <span class="spanregislebel"><?php echo $v_regis[3]; ?></span><br>
                                    <label>
                                    	<input name="tbxconfirmpass_regis" type="password" class="tbx_regis_long" id="tbxconfirmpass_regis" onChange="chkpass()" maxlength="30">
                                    	<span id="chkconfirmpass" class="required">*</span>
                                  		<span id="require_confirmpassword" class="confirmRequiredMsg">ยืนยันรหัสผ่านอีกครั้ง</span>
                                        <span id="require_dontmatchpassword" class="confirmInvalidMsg">รหัสผ่านไม่ตรงกัน</span>
                                  </label>
                                    <br>
                                    <span class="spanregislebel"><?php echo $v_regis[4]; ?></span><br>
                                    <label>
                                        <input name="tbxshowname_regis" type="text" class="tbx_regis_long" id="tbxshowname_regis" maxlength="50">
                                        <span id="chkshowname" class="required">*</span>
                                        <span id="require_showname" class="textfieldRequiredMsg">โปรดระบุชื่อที่ใช้แสดง</span>
                                        <span id="duplicate_showname" class="textfieldRequiredMsg">ชื่อนี้ถูกใช้แล้วกรุณาระบุใหม่</span>
                                        <span id="min_showname" class="textfieldRequiredMsg">โปรดระบุอย่างน้อยสี่ตัวอักษร</span>
                                        <span id="righ_showname" class="textfieldRequiredMsg">ชื่อนี้สามารถใช้งานได้</span>
                                  </label>
                                    <br>
                                    <ul id="ulphase2_2">
                                        <li>
                                            <ul>
                                                <li><span class="spanregislebel2"><?php echo $v_regis[17]; ?></span></li>
                                                <li><span class="spanregislebel2"><?php echo $v_regis[18]; ?></span><br></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>
                                                        <label>
                                                            <input name="tbxemail" type="text" class="tbx_half" id="tbxemail" maxlength="100">
                                                            <span id="chkemail" class="required1">*</span>
                                                            <span id="require_email" class="textfieldRequiredMsg">โปรดระบุอีเมล์</span>
                                                            <span id="duplicate_email" class="textfieldInvalidFormatMsg">อีเมล์นี้ถูกใช้งานแล้ว</span>
                                                            <span id="invalid_email" class="textfieldInvalidFormatMsg">คุณระบุอีเมล์ไม่ถูกต้อง</span>
                                                            <span id="righ_email" class="textfieldInvalidFormatMsg">อีเมล์นี้สามารถใช้งานได้</span>
                                                        </label>
                                                </li>
                                                <li>
                                                        <label>
                                                            <input name="tbxmobile" type="text" class="tbx_half" id="tbxmobile" maxlength="10">
                                                            <span id="chkmobile" class="required1">*</span>
                                                            <span id="require_mobile" class="textfieldRequiredMsg">โปรดระบุเบอร์มือถือ</span>
                                                            <span id="invalid_mobile" class="textfieldInvalidFormatMsg">เบอร์มือถือไม่ถูกต้อง</span>
                                                            <span id="duplicate_mobile" class="textfieldRequiredMsg">ถูกใช้งานแล้ว</span>
                                                            <span id="righ_mobile" class="textfieldRequiredMsg">สามารถใช้งานได้</span>
                                                        </label>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
          					</li>
                            <li>
                                <div id="divinfo2" class="divinfo">
                                    <div id="divinfolabel" class="divinfolabel"><img src="images/2.png"></div>
                                    <ul id="ulphase2_1">
                                        <li>
                                            <ul>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[5]; ?></span></li>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[6]; ?></span></li>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[7]; ?></span><br></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>
                                                    <select name="slctbefor" id="slctbefor">
                                                        <option value="<?php echo $v_regis[8]; ?>" selected><?php echo $v_regis[8]; ?></option>
                                                        <option value="<?php echo $v_regis[9]; ?>"><?php echo $v_regis[9]; ?></option>
                                                        <option value="<?php echo $v_regis[10]; ?>"><?php echo $v_regis[10]; ?></option>
                                                        <option value="<?php echo $v_regis[11]; ?>"><?php echo $v_regis[11]; ?></option>
                                                    </select>
                                                </li>
                                                <li><input type="text" name="tbxfullname" id="tbxfullname" class="tbx_half_long"></li>
                                                <li><input type="text" name="tbxlastname" id="tbxlastname" class="tbx_half_long"></li>
                                            </ul>
                                        </li>
                                    </ul><br>
                                    <span class="spanregislebel"><?php echo $v_regis[12]; ?></span><br>
                                    <input type="text" id="tbxbirthday" name="tbxbirthday" class="tbx_regis_long" readonly><br>
                                    <span class="spanregislebel"><?php echo $v_regis[13]; ?></span><br>
                                    <input type="text" id="tbxaddress" name="tbxaddress" class="tbx_regis_long"><br>
                                    <ul id="ulphase2_1">
                                        <li>
                                            <ul>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[14]; ?></span></li>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[15]; ?></span></li>
                                                <li><span class="spanregislebel1"><?php echo $v_regis[16]; ?></span><br></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>
                                                    <select name="slctprovince" id="slctprovince" onChange="getDistrict()">
                                                        <option value="0" selected><?php echo $v_regis[8]; ?></option>
                                                        <?php
                                                        $sql="select * from \"province\" order by \"PROVINCE_NAME\" asc";
                                                        $dbquery=pg_query($sql);
                                                        while($result=pg_fetch_assoc($dbquery))
                                                        {
                                                            $province_id=$result['PROVINCE_ID'];
                                                            $province=$result['PROVINCE_NAME'];
                                                            echo "<option value=\"$province_id\">$province</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </li>
                                                <li>
                                                    <select name="slctdistrict" id="slctdistrict">
                                                    <option value="0" selected><?php echo $v_regis[8]; ?></option>
                                                    </select>
                                                </li>
                                                <li><input type="text" name="tbxzipcode" id="tbxzipcode" class="tbx_half_long"></li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <ul id="ulphase2_2">
                                        <li>
                                            <ul>
                                                <li><span class="spanregislebel2"><?php echo $v_regis[19]; ?></span></li>
                                                <li><span class="spanregislebel2"><?php echo $v_regis[20]; ?></span><br></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li><input type="text" name="tbxphone" id="tbxphone" class="tbx_half"></li>
                                                <li><input type="text" name="tbxfax" id="tbxfax" class="tbx_half"></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div id="divinfo3" class="divinfo">
                                    <div id="divinfolabel" class="divinfolabel"><img src="images/3.png"></div>
                                    <div class="ajax-fc-container"></div>
                                </div>
                            </li>
                            <li>
                                <div id="divinfo4" class="divinfo1">
                               	  <input type="button" name="btnsubmit" id="btnsubmit" value="<?php echo $v_regis[27]; ?>" class="spansubmit" onClick="chkform()">
                                    <!--<span class="spansubmit" onClick="document.forms['frm_register'].submit()"></span>-->
                                </div>
                            </li>
                        </ul>
                    </form>
                    </div>
                </td>
                <td width="220" align="left" valign="top">
                	<div id="searchmenu">
                        <table id="tbsearchmenuform" border="0" cellpadding="0" cellspacing="5">
                            <tr>
                                <td align="right"><?php echo $v_regis[21]; ?></td>
                                <td>
                                    <select name="tbxsearch_brand" id="tbxsearch_brand" class="searchtextboxstyle">
                                        <option value>ทุกยี่ห้อ</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo $v_regis[22]; ?></td>
                                <td>
                                    <select name="tbxsearch_model" id="tbxsearch_model" class="searchtextboxstyle">
                                        <option value>ทุกรุ่น</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo $v_regis[23]; ?></td>
                                <td>
                                    <select name="tbxsearch_type" id="tbxsearch_type" class="searchtextboxstyle">
                                        <option value>ทุกประเภท</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo $v_regis[24]; ?></td>
                                <td>
                                    <select name="tbxsearch_year" id="tbxsearch_year" class="searchtextboxstyle">
                                        <option value>ทุกปี</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo $v_regis[25]; ?></td>
                                <td>
                                    <select name="tbxsearch_price" id="tbxsearch_price" class="searchtextboxstyle">
                                        <option value>ทุกราคา</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="10"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="submit" id="btnformsearchsubmit" name="btnformsearchsubmit" value="<?php echo $v_regis[26]; ?>">
                                </td>
        
                            </tr>
                            <tr>
                                <td height="5"></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
            	</td>
            </tr>
        </table>
  	</div>
</div>
<div id="divfooter1"></div>
</body>
</html>