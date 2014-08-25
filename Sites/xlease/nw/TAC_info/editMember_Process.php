<?php
	session_start();
	include("config.php");
	$id=$_GET['id'];
	$username=$_POST['tbxUsername'];
	$password=md5($_POST['tbxPassword']);
	$beforName=$_POST['lbxBeforeName'];
	$firstName=$_POST['tbxName'];
	$lastName=$_POST['tbxLastName'];
	$carCode=$_POST['tbxCarNumber'];
	$telNumber=$_POST['tbxTelephoneNumber'];
	$isAdmin=$_POST['lbxUserType'];
	$loginResult="";
	$loginStatus=0;
	$sql="update \"TrMember\" set \"Username\"='$username', \"Password\"='$password', \"isAdmin\"='$isAdmin', \"carregistrationnumber\"='$carCode', \"telephonenumber\"='$telNumber', \"beforName\"='$beforName', \"firstName\"='$firstName', \"lastName\"='$lastName' where \"UserID\"='$id'";
	$dbquery=pg_query($sql);
	$loginResult="แก้ไขข้อมูลสมาชิกเรียบร้อยแล้ว";
	$loginStatus=1;
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
                                                <td valign="middle" id="td_top_menu">Add Member :: เพิ่มสมาชิก</td>
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
																	echo"<h2>กลับไปยังหน้าเพิ่มสมาชิก <a href=\"addMember.php\">คลิกที่นี่</a></h2>";
																}
																else
																{
																	echo "<h2>กลับไปยังหน้าจัดการสมาชิก <a href=\"manageUser.php\">คลิกที่นี่</a></h2>";
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