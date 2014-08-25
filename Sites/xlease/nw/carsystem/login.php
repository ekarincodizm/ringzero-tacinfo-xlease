<?php
session_start();
include("../../config/config.php");
header ('Content-type: text/html; charset=utf-8');
echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=9\" />";
?>
<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function login(){
	$("#login_fail").hide();
	var username = document.mainformlogin.maintbxusername.value;
	var password = document.mainformlogin.maintbxpassword.value;
	var remember = $("#maincbxremember:checked").val();
  
	if(username==""){
	$("#maindivusernameerror").fadeIn(700).show("slow");      
	}
	else{
		$("#maindivusernameerror").fadeOut(700).hide("slow");
	}
 
	if(password==""){
		$("#maindivpassworderror").fadeIn(700).show("slow");      
	}
	else {
		$("#maindivpassworderror").fadeOut(700).hide("slow");
	}
  
	if(username!="" && password!=""){
		var str = Math.random();
		var datastring = 'str'+str + '&username='+username +'&password='+password+'&remember='+remember;
		$.ajax({
		type:'POST',
		url:'member_login_chk.php',
		data:datastring,
	  
			success:function(data){
				if(data==1){
					window.location.reload();
						//ประยุกต์ใช้ส่วนนี้สั่งโหลด profile ของ member แต่ละคนได้
				}
				else if(data==2){
					$("#maindivloginalert").fadeIn(700).show("slow").html('<font style="color:red;font-size:14px;line-height: 50px;">ท่านถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ</font>');
					document.mainformlogin.maintbxusername.value="";
					document.mainformlogin.maintbxpassword.value="";
				}
				else if(data==0){
					$("#maindivloginalert").fadeIn(700).show("slow").html('<font style="color:red;font-size:14px;line-height: 50px;">ชื่อผู้ใช้หรือรหัสผ่าน ไม่ถูกต้อง</font>');
					document.mainformlogin.maintbxusername.value="";
					document.mainformlogin.maintbxpassword.value="";
				}
			}
		});
	}
}
function chkfocus(type)
{
	if(type=='user')
	{
		$('#maindivusernameerror').fadeOut(700).hide("slow");
		$('#maintbxusername').css("border","solid 1px #46b5e9");
	}
	else if(type=='password')
	{
		$('#maindivpassworderror').fadeOut(700).hide("slow");
		$('#maintbxpassword').css("border","solid 1px #46b5e9");
	}
}
function chkblur(type)
{
	if(type=='user')
	{
		$('#maintbxusername').css("border","solid 1px #cdcdcd");
	}
	else if(type=='password')
	{
		$('#maintbxpassword').css("border","solid 1px #cdcdcd");
	}
}
$(function(){  
   $('#maintbxpassword').bind('keyup',function(e){ //on keydown for all textboxes  
	   if(e.keyCode==13)
	   {
		   $("#mainbtnsubmit").click();
	   }
							
   });  
});  
</script>
<style type="text/css">
@charset"utf-8";
#mainlogincontainer {
	height: auto;
	width: 550px;
	border: 2px solid #cecece;
	padding-top: 5px;
	padding-right: 25px;
	padding-bottom: 25px;
	padding-left: 25px;
	border-radius:5px;
	margin-top: 25px;
	margin-right: 0px;
	margin-bottom: 240px;
	margin-left: 0px;
}

#mainlogincontainer * {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 13px;
	line-height: 30px;
	font-weight: normal;
	color: #444;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
}
#mainlogincontainer #maindivloginalert {
	line-height: 50px;
	text-align: left;
	margin: 0px;
	width: 497px;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 53px;
	background-attachment: scroll;
	background-image: url(images/info.png);
	background-repeat: no-repeat;
	background-position: left center;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #cecece;
	font-size: 14px;
}
#mainlogincontainer #maindivlogintitlebar {
	font-weight: bold;
	color: #1f73c1;
	text-decoration: none;
	padding: 0px;
	width: 100%;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 15px;
	margin-left: 0px;
	font-size: 14px;
	text-align: left;
}
#mainlogincontainer .mainloginlabel    {
	font-size: 13px;
	font-weight: normal;
	text-decoration: none;
	text-align: right;
	width: 120px;
	padding-right: 0px;
	vertical-align: text-bottom;
}
#mainlogincontainer #maindivusername {
	width: 100%;
}
#mainlogincontainer .maintbxlogin {
	text-decoration: none;
	text-align: left;
	margin: 0px;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 22px;
	border: 1px solid #cdcdcd;
	border-radius:3px;
	width: 247px;
	line-height: 27px;
	height: 27px;
	font-family: Arial;
	font-size: 14px;
	font-weight: normal;
}
#mainlogincontainer #maintbxusername {
	background-attachment: scroll;
	background-image: url(images/1344571084_user_business_boss.png);
	background-repeat: no-repeat;
	background-position: 2px center;
}
#mainlogincontainer #maindivpassword {
	width: 100%;
	margin-top: 5px;
}
#mainlogincontainer #maintbxpassword {
	background-attachment: scroll;
	background-image: url(images/1344571190_icon_key.gif);
	background-repeat: no-repeat;
	background-position: 2px center;
}
#mainlogincontainer .maintdlink  {
	margin: 0px;
	width: 115px;
	font-size: 13px;
	font-weight: normal;
	text-decoration: none;
	text-align: left;
	vertical-align: bottom;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 3px;
}
#mainlogincontainer .maintdlink a {
	color: #1f73c1;
	text-decoration: none;
	font-size: 13px;
	font-weight: normal;
	width: 85px;
}
#mainlogincontainer #maintdremember {
	text-align: left;
}
#mainlogincontainer #mainbtnsubmit {
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff;
	box-shadow:inset 0px 0px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0b8cd1), color-stop(1, #0c7cd1) );
	background:-moz-linear-gradient( center top, #0b8cd1 5%, #0c7cd1 100% );
	background-color:#0b8cd1;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	display:inline-block;
	color:#ffffff;
	text-decoration:none;
	padding-top: 0px;
	padding-right: 20px;
	padding-bottom: 0px;
	padding-left: 20px;
	margin: 0px;
	left: -4px;
	position: relative;
}
#mainlogincontainer #mainbtnsubmit:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0c7cd1), color-stop(1, #0b8cd1) );
	background:-moz-linear-gradient( center top, #0c7cd1 5%, #0b8cd1 100% );
	background-color:#0c7cd1;
	cursor:pointer;
}
#mainlogincontainer #mainbtnsubmit:active {
	position:relative;
	top:1px;
}
#mainlogincontainer #maindivusernameerror {
	display: none;
}
#mainlogincontainer #maindivpassworderror {
	color: red;
	text-decoration: none;
	display: none;
}
#mainlogincontainer .error {
	color: red;
	text-decoration: none;
}
</style>

<div id="mainlogincontainer">
	<div id="maindivloginalert">ขออภัยครับ คุณยังไม่ได้เข้าสู่ระบบ กรุณาเข้าสู่ระบบก่อนครับ</div>
  <div id="maindivlogintitlebar">สมาชิกเข้าสู่ระบบ</div>
	<form id="mainformlogin" name="mainformlogin" action="">
    <div id="maindivusername">
    	<table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
            	<td class="mainloginlabel" valign="bottom">ชื่อผู้ใช้ .:</td>
              <td align="left"><input type="text" id="maintbxusername" name="maintbxusername" class="maintbxlogin" onfocus="chkfocus('user');" onblur="chkblur('user');" value="<?php if(isset($_COOKIE['carSystemUsername'])&&$_COOKIE['carSystemUsername']!=""){ echo $_COOKIE['carSystemUsername']; } ?>" tabindex="1"></td>
              <td class="maintdlink" valign="bottom"><a href="register.php">สมัครสมาชิก</a></td>
            </tr>
        </table>
    </div>
    <div id="maindivusernameerror">
    	<table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
           	  <td class="mainloginlabel" valign="bottom"></td>
              <td align="left"><span class="error">โปรดระบุชื่อผู้ใช้</span></td>
              <td class="maintdlink" valign="bottom"></td>
            </tr>
        </table>
    </div>
    <div id="maindivpassword">
    	<table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
            	<td class="mainloginlabel" valign="bottom">รหัสผ่าน .:</td>
              	<td align="left"><input type="password" id="maintbxpassword" name="maintbxpassword" class="maintbxlogin" onfocus="chkfocus('password');" onblur="chkblur('password');" tabindex="2"></td>
                <td class="maintdlink" valign="bottom"><a href="#">ลืมรหัสผ่าน</a></td>
            </tr>
        </table>
    </div>
    <div id="maindivpassworderror">
    	<table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
           	  <td class="mainloginlabel" valign="bottom"></td>
              <td align="left"><span class="error">โปรดระบุรหัสผ่าน</span></td>
              <td class="maintdlink" valign="bottom"></td>
            </tr>
        </table>
    </div>
    <div id="maindivremember">
   	  <table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
            	<td class="mainloginlabel"></td>
                <td id="maintdremember"><label><input type="checkbox" id="maincbxremember" name="maincbxremember" value="1" <?php if(isset($_COOKIE['carSystemUsername'])&&$_COOKIE['carSystemUsername']!=""){ echo "checked=\"checked\""; } ?> /> จดจำฉัน</label></td>
                <td class="maintdlink"></td>
            </tr>
        </table>
    </div>
    <div id="maindivbuttonsubmit">
    	<table width="500px" border="0" cellpadding="3" cellspacing="0">
        	<tr>
            	<td class="mainloginlabel"></td>
                <td align="left"><span id="mainbtnsubmit" onclick="login();">เข้าสู่ระบบ</span></td>
                <td class="maintdlink"></td>
            </tr>
        </table>
    </div>
    </form>
</div>