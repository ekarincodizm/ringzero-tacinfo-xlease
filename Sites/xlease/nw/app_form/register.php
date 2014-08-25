<?php
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/register.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<div class="main_container">
    	<form name="frm_register" id="frm_register" method="post" enctype="multipart/form-data" action="proc/register_process.php">
        	<ul>
            	<li class="register_title bold">เข้าสู่ระบบ :: แบบฟอร์มขอสินเชื่อ</li>
                <li class="register_label">ชื่อผู้ใช้</li>
                <li class="register_input"><input type="text" name="tbx_user" id="tbx_user" class="register_tbx" maxlength="50" onblur="chk_dup('user');" /></li>
                <li class="register_label">รหัสผ่าน</li>
                <li class="register_input"><input type="password" name="first_passwd" id="first_passwd" class="register_tbx" maxlength="30" onblur="chk_match();" /></li>
                <li class="register_label">ยืนยันรหัสผ่าน</li>
                <li class="register_input"><input type="password" name="second_passwd" id="second_passwd" class="register_tbx" maxlength="30" onblur="chk_match();" /></li>
                <li class="register_label">ชื่อ-นามสกุล</li>
                <li class="register_input"><input type="text" name="fullname" id="fullname" class="register_tbx" maxlength="150" onblur="chk_dup('name');" /></li>
                <li class="register_label">อีเมล์</li>
                <li class="register_input"><input type="text" name="Email" id="Email" class="register_tbx" maxlength="75" onblur="chk_dup('email');" /></li>
                <li class="register_label">เบอร์โทรศัพท์บ้าน</li>
                <li class="register_input"><input type="text" name="telephone" id="telephone" class="register_tbx" maxlength="20" onblur="chk_dup('telephone');" /></li>
                <li class="register_label">เบอร์โทรศัพท์มือถือ</li>
                <li class="register_input"><input type="text" name="mobile" id="mobile" class="register_tbx" maxlength="20" onblur="chk_dup('mobile');" /></li>
                <li class="register_label">อัพโหลดบัตรประชาชน</li>
                <li class="register_input file"><input type="file" name="id_card" id="id_card" onchange="show_file();" /></li>
                <li class="register_input upload"></li>
                <li class="register_input"><span class="pick_file" onclick="pick_file();">อัพโหลดภาพ</span></li>
                <li class="register_btn"><span class="btn primary" onclick="submit_data();">ลงทะเบียน</span></li>
                <li class="register_note"></li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function chk_dup(input) {
	$('.register_note.error').removeClass('error');
	$('.register_note.success').removeClass('success');
	$('.register_note').hide();
	switch(input)
	{
		case 'user':
			var usr = $('#tbx_user').val();
			if(usr=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุชื่อผู้ใช้ด้วยครับ').fadeIn('slow');
				return '0';
			}
			else
			{
				$.post('proc/chk_dup.php',{data:usr,type:'user'},function(data){
					if(data=='1')
					{
						//$('.register_note').addClass('success').html('ชื่อผู้ใช้นี้สามารถใช้งานได้ครับ').fadeIn('slow');
						return '1';
					}
					else
					{
						$('.register_note').addClass('error').html('ชื่อผู้ใช้นี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
						return '0';
					}
				});
			}
			break;
		case 'name':
			var name = $('#fullname').val();
			if(name=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุชื่อเต็มด้วยครับ').fadeIn('slow');
				return '0';
			}
			else
			{
				$.post('proc/chk_dup.php',{data:name,type:'name'},function(data){
					if(data=='1')
					{
						//$('.register_note').addClass('success').html('ชื่อสมาชิกนี้สามารถใช้งานได้ครับ').fadeIn('slow');
						return '1';
					}
					else
					{
						$('.register_note').addClass('error').html('ชื่อสมาชิกนี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
						return '0';
					}
				});
			}
			break;
		case 'email':
			var email = $('#Email').val();
			if(email=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุอีเมล์ด้วยครับ').fadeIn('slow');
				return '0';
			}
			else
			{
				$.post('proc/chk_dup.php',{data:email,type:'email'},function(data){
					if(data=='1')
					{
						//$('.register_note').addClass('success').html('อีเมล์นี้สามารถใช้งานได้ครับ').fadeIn('slow');
						return '1';
					}
					else
					{
						$('.register_note').addClass('error').html('อีเมล์นี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
						return '0';
					}
				});
			}
			break;
		case 'telephone':
			var tel = $('#telephone').val();
			if(tel=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุเบอร์โทรศัพท์ด้วยครับ').fadeIn('slow');
				return '0';
			}
			else
			{
				$.post('proc/chk_dup.php',{data:tel,type:'tel'},function(data){
					if(data=='1')
					{
						//$('.register_note').addClass('success').html('เบอร์โทรศัพท์นี้สามารถใช้งานได้ครับ').fadeIn('slow');
						return '1';
					}
					else
					{
						$('.register_note').addClass('error').html('เบอร์โทรศัพท์นี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
						return '0';
					}
				});
			}
			break;
		case 'mobile':
			var mobile = $('#mobile').val();
			if(mobile=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุเบอร์โทรศัพท์มือถือด้วยครับ').fadeIn('slow');
				return '0';
			}
			else
			{
				$.post('proc/chk_dup.php',{data:mobile,type:'mobile'},function(data){
					if(data=='1')
					{
						//$('.register_note').addClass('success').html('เบอร์มือถือนี้สามารถใช้งานได้ครับ').fadeIn('slow');
						return '1';
					}
					else
					{
						$('.register_note').addClass('error').html('เบอร์มือถือนี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
						return '0';
					}
				});
			}
			break;
	}
}
function chk_match() {
	$('.register_note.error').removeClass('error');
	$('.register_note.success').removeClass('success');
	$('.register_note').hide();
	var passwd = $('#first_passwd').val();
	var confpasswd = $('#second_passwd').val();
	if(passwd==''||confpasswd=='')
	{
		$('.register_note').addClass('error').html('รหัสผ่านไม่ถูกต้อง').fadeIn('slow');
		return '0';
	}
	else
	{
		if(passwd==confpasswd)
		{
			return '1';
		}
		else
		{
			$('.register_note').addClass('error').html('รหัสผ่านไม่ตรงกันครับ').fadeIn('slow');
			return '0';
		}
	}
}
function submit_data() {
	$('.register_note.error').removeClass('error');
	$('.register_note.success').removeClass('success');
	$('.register_note').hide();
	
	var chk_at = new Array('user','name','email','telephone','mobile');
	var sum_chk = chk_at.length;
	var va = '0';
	var i = 0;
	
	for(var i=0; i<sum_chk;i++)
	{
		var va = chk_dup(chk_at[i]);
		if(va=='0')
		{
			return false;
		}
	}
	
	va = chk_match();
	if(va=='false')
	{
		return false;
	}
	else
	{
		$('#frm_register').submit();
	}
}
function pick_file() {
	$('#id_card').click();
}
function show_file() {
	if($('#id_card').val().length>20)
	{
		$('.upload').html($('#id_card').val().substring(0,20)+'...');
	}
	else
	{
		$('.upload').html($('#id_card').val());
	}
	$('.upload').show();
}
</script>
</body>
</html>