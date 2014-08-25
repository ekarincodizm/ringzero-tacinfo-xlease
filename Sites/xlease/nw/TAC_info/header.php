<?php
	session_start();
	$username="";
	if(!isset($_SESSION['username']))
	{
		$username="บุคคลที่วไป";
	}
	else
	{
		$username=$_SESSION['username'];
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="800" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td height="187"><img src="images/Header_-1.jpg" width="800" height="190"></td>
    </tr>
    <tr>
        <td height="30" background="images/menu-bg.gif" align="right" valign="middle">
        	<?php
			if(isset($_SESSION['username']))
			{
            	echo"<div id=\"user_info\">ชื่อผู้ใช้ : ".$username." เข้าสู่ระบบเมื่อ : ".$_SESSION['loginTime']."</div>";
			}
			?>
            <table>
                <tr>
                	<?php
					if(!isset($_SESSION['username']))
					{
						echo"<td id=\"top_menu\"><a href=\"./login.php\" target=\"_parent\">เข้าสู่ระบบ</a></td>";
					}
					else
					{
						echo"<td id=\"top_menu\"><a href=\"./logout.php\" target=\"_parent\">ออกจากระบบ</a></td>";
					}
					?>
                    <td id="top_menu"><a href="./index.php" target="_parent">เมนูหลัก</a></td>
                    <td id="top_menu"><a href="./contact.php" target="_parent">ติดต่อเรา</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>