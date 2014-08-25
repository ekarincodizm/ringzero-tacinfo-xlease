<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	$username=$_POST['tbxUserName'];
	$password=$_POST['tbxPassword'];
	$sql="SELECT * FROM \"TrMember\" WHERE \"Username\"='$username' AND \"Password\"='".md5($password)."'";
	$dbquery=pg_query($sql);
	$error="";
	$loginResult="";
	$loginStatus=0;
	if(!$dbquery)
	{
		$error="ไม่สามารถติดต่อฐานข้อมูลได้";
	}
	$rows=pg_num_rows($dbquery);
	if($rows!=1)
	{
		$loginResult="คุณกรอกชื่อผู้ใช้หรือรหัสผ่านผิด  กรุณาลองใหม่";
	}
	else
	{
		$result=pg_fetch_assoc($dbquery);
		$userType="";
		if($result[isAdmin]==0)
		{
			$loginResult="คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
		}
		else if($result[isAdmin]==1)
		{
			$userType="admin";
			session_register("username");
			session_register("loginTime");
			session_register("userType");
			$_SESSION['username']=$result[Username];
			$_SESSION['loginTime']=date("d F Y");
			$_SESSION['userType']=$userType;
			$loginResult="ยินดีต้อนรับคุณ ".$_SESSION['username'];
			$loginStatus=1;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div align="center">
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td height="190">
                    <iframe width="800" height="220" src="header.php" frameborder="0" name="iframe_header"></iframe>
                    </td>
           	  	</tr>
                <tr>
                	<td align="center" valign="top" id="content">
               	  <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="400" align="center" valign="top" id="content">
                                    <div id="content_box">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="middle" id="td_top_menu">Admin Login :: ผู้ดูแลเข้าสู่ระบบ</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                                                        <tr>
                                                            <td align="right" id="usernameLabel"></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" id="lbWelcome">
                                                            <?php
																echo $loginResult;
																if($loginStatus==1)
																{
																	echo"<h2>ไปยังแผงควบคุมสำหรับผู้ดูแลระบบ <a href=\"adminMenu.php\">คลิกที่นี่</a></h2>";
																}
															?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td id="td_login_button"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td height="30">
                    	<iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</body>
</html>