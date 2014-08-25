<?php
include("config/config.php");
if(isset($_SESSION['app_username']))
{
	if($_SESSION['app_user_type']=="user")
	{
		echo "<script type=\"text/javascript\">window.location.href='home.php';</script>";
	}
	else
	{
		echo "<script type=\"text/javascript\">window.location.href='admin.php';</script>";
	}
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>เข้าสู่ระบบ :: แบบฟอร์มขอสินเชื่อ</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
	<div class="main_container">
    	<form name="frm_admin_login" id="frm_admin_login">
        	<ul>
            	<li class="logo"></li>
            	<li class="login_title bold">เข้าสู่ระบบ :: แบบฟอร์มขอสินเชื่อ</li>
                <li class="login_label">ชื่อผู้ใช้</li>
                <li class="login_input"><input type="text" name="tbx_super_user" id="tbx_super_user" class="login_tbx" maxlength="50" /></li>
                <li class="login_label">รหัสผ่าน</li>
                <li class="login_input"><input type="password" name="tbx_super_passwd" id="tbx_super_passwd" class="login_tbx" maxlength="30" /></li>
                <li class="login_label"><a  class="link" href="register.php">สมัครสมาชิก</a></li>
                <li class="login_btn"><span class="btn primary" onclick="login();">เข้าสู่ระบบ</span></li>
                <li class="login_note"></li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#tbx_super_passwd').keypress(function(e){
		if(e.which==13) {
			login();
		}
	});
});
function login() {
	$('.login_note.error').removeClass('error');
	$('.login_note.success').removeClass('success');
	$('.login_note').hide();
	var usr = $('#tbx_super_user').val();
	var pwd = $('#tbx_super_passwd').val();
	if(usr==''||pwd=='')
	{
		$('.login_note').addClass('error').html('กรุณาระบุข้อมูลให้ครบทุกช่อง').fadeIn('slow');
	}
	else
	{
		$.post('proc/login.php',$('#frm_admin_login').serialize(),function(data){
		//alert(data);
			if(data=='10')
			{
				window.location.href = 'admin.php';
			}
			else if(data=='11')
			{
				window.location.href = 'home.php';
			}
			else if(data=='01')
			{
				$('.login_note').addClass('error').html('คุณยังไม่ทำการยืนยันการสมัครสมาชิก กรุณาตรวจสอบอีเมล์ของคุณเพื่อยืนยันการสมัครสมาชิกก่อนครับ').fadeIn('slow');
			}
			else
			{
				$('.login_note').addClass('error').html('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง').fadeIn('slow');
			}
		});
	}
}
</script>
</body>
</html>
<?php
}
?>