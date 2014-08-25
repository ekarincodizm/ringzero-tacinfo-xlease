<?php
	session_start();
	if($_SESSION['language']==""){
		$_SESSION['language']="TH";
	}
    include("company.php");
	$showtext=pg_escape_string($_GET["showtext"]);
	if($showtext=="1"){
		$showtext="* ไม่มีผู้ใช้งานนี้ในระบบ กรุณาตรวจสอบชื่อผู้ใช้งานใหม่อีกครั้ง";
	}else if($showtext=="2"){
		$showtext="* ผู้ใช้งานนี้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ";
	}else if($showtext=="3"){
		$showtext="* รหัสผ่านไม่ถูกต้อง กรุณากรอกรหัสผ่านใหม่อีกครั้ง";
	}
	
	$passlog = pg_escape_string($_GET['passlog']);

	if($passlog == "1")
	{
		$user_login = $_SESSION['user_login'];
		$pass_login = $_SESSION['pass_login'];
	}
	else
	{
		$user_login = "";
		$pass_login = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title>Login</title>

<style type="text/css">
BODY{
    font-family: tahoma;
    font-size: 14px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 20px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 20px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
INPUT {
    font-family: tahoma;
    font-size: 14px;
    font-weight: normal;
    /*color: #585858;
    background-color: #E0E0E0;*/
}
HR {
    border: 0;
    color: #ACACAC;
    background-color: #ACACAC;
    height: 1px;
}

.roundedcornr_box {
   background: #ffffff;
   width: 500px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}

.roundedcornr_content #chkbxremember {
	margin: 0px;
	padding: 0px;
}
.roundedcornr_content label {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
	font-weight: normal;
	color: #333;
	text-decoration: none;
}
</style>

</head>
<body onload="document.form1.username.focus();">

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h2>Xlease</h2>
<hr/>
<div>
<?php
	if($showtext !=""){
?>
<div style="padding:10px;background-color:#FFF3D7;"><B><?php echo $showtext;?></B></div>
<?php }?>
<FORM method="post" action="login.php" style="margin:0px" name="form1">
<TABLE width="260" cellspacing="0" cellpadding="3" border="0" align="center">
<TR>
    <TD><B>Username</B></TD>
	<?php
	if($passlog == "1")
	{
	?>
		<TD><INPUT TYPE="text" autocomplete="off" NAME="username" value="<?php echo $user_login; ?>"></TD>
	<?php
	}
	else
	{
	?>
		<TD><INPUT TYPE="text" autocomplete="off" NAME="username" value="<?php if(isset($_COOKIE['xleaseUsername'])&&$_COOKIE['xleaseUsername']!=""){ echo $_COOKIE['xleaseUsername']; } ?>"></TD>
	<?php
	}
	?>
</TR>
<TR>
    <TD><B>Password</B></TD>
    <TD><INPUT TYPE="password" autocomplete="off" NAME="password" value="<?php echo $pass_login; ?>"></TD>
</TR>
<TR>
    <TD><B>Company</B></TD>
    <TD>
<select name="comp" id="comp">
<?php
foreach($company as $v){
    echo "<option value=\"$v[code]\">$v[name]</option>\n";
}
?>
</select>
    </TD>
</TR>
<TR>
    <TD>&nbsp;</TD>
    <TD><label><input type="checkbox" name="chkbxremember" id="chkbxremember" value="1" <?php if(isset($_COOKIE['xleaseUsername'])&&$_COOKIE['xleaseUsername']!=""){ echo "checked=\"checked\""; } ?> /> จดจำฉัน</label></TD>
</TR>
<TR>
    <TD>&nbsp;</TD>
    <TD><input type="hidden" name="nubi" value="<?php echo $nubi;?>"><INPUT TYPE="submit" name="btnLogin" id="btnLogin" VALUE="Login">
	<a href="nw/Repassword/frm1.php"><font color="red" size="2">ลืมรหัสผ่าน/ปลดล็อค</font></a></TD>
</TR>
<TR>
    <TD>&nbsp;</TD>
    
</TR>
</TABLE>
</FORM>

<FORM method="post" action="swap_language.php" style="margin:0px" name="form2">
	<INPUT TYPE="submit" VALUE='<?php echo $_SESSION['language'];?>'></TD>
</FORM>
</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>

<script type="text/javascript">
	var passlog = '<?php echo $passlog; ?>';
	if(passlog == '1')
	{
		document.getElementById("btnLogin").click();
	}
</script>

</html>