<?php
include("config/config.php");
if($_SESSION['app_username']=="")
{
	echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
}
else
{
$user = $_SESSION['app_username'];
$q = "select * from $schema.\"app_member\" where \"app_usr\"='$user'";
$qr = pg_query($q);
$rs = pg_fetch_array($qr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/profile.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="main_container">
    	<form name="frm_register" id="frm_register">
        	<ul>
            	<li class="register_title bold">โปรไฟล์ :: แบบฟอร์มขอสินเชื่อ</li>
                <li class="register_label">ชื่อผู้ใช้</li>
                <li class="register_input"><input type="text" name="tbx_user" id="tbx_user" class="register_tbx" maxlength="50" value="<?php echo $rs['app_usr']; ?>" disabled="disabled" /></li>
                <li class="register_label">รหัสผ่าน</li>
                <li class="register_input"><input type="password" name="first_passwd" id="first_passwd" class="register_tbx" maxlength="30" onblur="chk_match();" /></li>
                <li class="register_label">ยืนยันรหัสผ่าน</li>
                <li class="register_input"><input type="password" name="second_passwd" id="second_passwd" class="register_tbx" maxlength="30" onblur="chk_match();" /></li>
                <li class="register_label">ชื่อ-นามสกุล</li>
                <li class="register_input"><input type="text" name="fullname" id="fullname" class="register_tbx" maxlength="150" value="<?php echo $rs['app_fullname']; ?>" onchange="chk_dup('name');" /></li>
                <li class="register_label">อีเมล์</li>
                <li class="register_input"><input type="text" name="Email" id="Email" class="register_tbx" maxlength="75" value="<?php echo $rs['app_email'] ?>" onchange="chk_dup('email');" /></li>
                <li class="register_label">เบอร์โทรศัพท์บ้าน</li>
                <li class="register_input"><input type="text" name="telephone" id="telephone" class="register_tbx" maxlength="20" value="<?php echo $rs['app_telephone']; ?>" onchange="chk_dup('telephone');" /></li>
                <li class="register_label">เบอร์โทรศัพท์มือถือ</li>
                <li class="register_input"><input type="text" name="mobile" id="mobile" class="register_tbx" maxlength="20" value="<?php echo $rs['app_mobile']; ?>" onchange="chk_dup('mobile');" /></li>
                <li class="register_btn"><span class="btn primary" onclick="submit_data();">บันทึกข้อมูล</span></li>
                <li class="register_note"></li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
var state2 = 1;
var state3 = 1;
var state4 = 1;
var state5 = 1;
var state6 = 1;
function chk_dup(input) {
	$('.register_note.error').removeClass('error');
	$('.register_note.success').removeClass('success');
	$('.register_note').hide();
	switch(input)
	{
		case 'name':
			var name = $('#fullname').val();
			var old = '<?php echo $rs['app_fullname']; ?>';
			if(name=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุชื่อเต็มด้วยครับ').fadeIn('slow');
				state3 = 0;
			}
			else
			{
				if(name!=old)
				{
					$.post('proc/chk_dup.php',{data:name,type:'name'},function(data){
						if(data=='1')
						{
							$('.register_note').addClass('success').html('ชื่อสมาชิกนี้สามารถใช้งานได้ครับ').fadeIn('slow');
							state3 = 1;
						}
						else
						{
							$('.register_note').addClass('error').html('ชื่อสมาชิกนี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
							state3 = 0;
						}
					});
				}
			}
			break;
		case 'email':
			var old = '<?php echo $rs['app_email']; ?>';
			var email = $('#Email').val();
			if(email=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุอีเมล์ด้วยครับ').fadeIn('slow');
				state4 = 0;
			}
			else
			{
				if(old!=email)
				{
					$.post('proc/chk_dup.php',{data:email,type:'email'},function(data){
						if(data=='1')
						{
							$('.register_note').addClass('success').html('อีเมล์นี้สามารถใช้งานได้ครับ').fadeIn('slow');
							state4 = 1;
						}
						else
						{
							$('.register_note').addClass('error').html('อีเมล์นี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
							state4 = 0;
						}
					});
				}
			}
			break;
		case 'telephone':
			var old = '<?php echo $rs['app_telephone']; ?>';
			var tel = $('#telephone').val();
			if(tel=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุเบอร์โทรศัพท์ด้วยครับ').fadeIn('slow');
				state5 = 0;
			}
			else
			{
				if(old!=tel)
				{
					$.post('proc/chk_dup.php',{data:tel,type:'tel'},function(data){
						if(data=='1')
						{
							$('.register_note').addClass('success').html('เบอร์โทรศัพท์นี้สามารถใช้งานได้ครับ').fadeIn('slow');
							state5 = 1;
						}
						else
						{
							$('.register_note').addClass('error').html('เบอร์โทรศัพท์นี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
							state5 = 0;
						}
					});
				}
			}
			break;
		case 'mobile':
			var old = '<?php echo $rs['app_mobile']; ?>';
			var mobile = $('#mobile').val();
			if(mobile=='')
			{
				$('.register_note').addClass('error').html('กรุณาระบุเบอร์โทรศัพท์มือถือด้วยครับ').fadeIn('slow');
				state6 = 0;
			}
			else
			{
				if(old!=mobile)
				{
					$.post('proc/chk_dup.php',{data:mobile,type:'mobile'},function(data){
						if(data=='1')
						{
							$('.register_note').addClass('success').html('เบอร์มือถือนี้สามารถใช้งานได้ครับ').fadeIn('slow');
							state6 = 1;
						}
						else
						{
							$('.register_note').addClass('error').html('เบอร์มือถือนี้ไม่สามารถใช้งานได้ครับ').fadeIn('slow');
							state6 = 0;
						}
					});
				}
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
	if(passwd!=''||confpasswd!='')
	{
		if(passwd==confpasswd)
		{
			$('.register_note').addClass('success').html('รหัสผ่านถูกต้อง').fadeIn('slow');
			state2 = 1;
		}
		else
		{
			$('.register_note').addClass('error').html('รหัสผ่านไม่ตรงกันครับ').fadeIn('slow');
			state2 = 0;
		}
	}
}
function submit_data() {
	chk_match();
	if(state2==0)
	{
		return false;
	}
	chk_dup('name');
	if(state3==0)
	{
		return false;
	}
	chk_dup('email');
	if(state4==0)
	{
		return false;
	}
	chk_dup('telephone');
	if(state5==0)
	{
		return false;
	}
	chk_dup('mobile');
	if(state6==0)
	{
		return false;
	}
	$('.register_note.error').removeClass('error');
	$('.register_note.success').removeClass('success');
	$('.register_note').hide();
	$.post('proc/profile_process.php',$('#frm_register').serialize(),function(data){
		if(data=='1')
		{
			$('.register_note').addClass('success').html('บันทึกข้อมูลเรียบร้อยแล้วครับ').fadeIn('slow');
		}
		else
		{
			$('.register_note').addClass('error').html('ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ภายหลังครับ').fadeIn('slow');
			return false;
		}
	});
}
</script>
</body>
</html>
<?php
}
?>