<?PHP
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	//$loginStatus="";
	if(isset($_SESSION['username']))
	{
		if($_SESSION['userType']=="admin")
		{
			header("Location:adminMenu.php");
		}
		else
		{
			header("Location:index.php");
		}
	}
	
	pg_query("BEGIN");
	$status=0;
	
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
	if($params=="")
	{
		$currentUrl = $protocol . '://' . $host . $script;
	}
	else
	{
    	$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	}
	
	$server=$_SERVER["REMOTE_ADDR"];
	$visitDate=date("Y-m-d H:i:s");
	$visitor="";
	
	if(isset($_SESSION['username']))
	{
		$sql="select * from \"TrMember\" where \"Username\"='".$_SESSION['username']."'";
		$dbquery=pg_query($sql);
		$result=pg_fetch_assoc($dbquery);
		if($result['isAdmin']==0)
		{
			$visitor="user";
		}
		else
		{
			$visitor="admin";
		}
	}
	else
	{
		$visitor="general";
	}
	
	$sql="insert into \"TrStatistic\"(\"Remote_IP\", \"Remote_Time\", \"Visit_Path\", \"visitor_type\") values('$server','$visitDate','$currentUrl','$visitor')";
	if($result=pg_query($sql))
	{}
	else
	{
		$status++;
	}
	if($status==0)
	{
		pg_query("COMMIT");
	}
	else
	{
		pg_query("ROLLBACK");
		echo "บันทึกข้อมูลล้มเหลว";
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
                                                <td valign="middle" id="td_top_menu">Admin Login :: ผู้ดูแลระบบเข้าสู่ระบบ</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                	<?PHP
                                                    	echo"<form name=\"loginForm\" method=\"post\" action=\"checkAdminLogin.php\">";
                                                        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"400\">";
                                                        echo"<tr>";
                                                        echo"<td align=\"right\" id=\"usernameLabel\">ชื่อผู้ใช้.:</td>";
                                                        echo"<td><input name=\"tbxUserName\" type=\"text\" id=\"tbxUserName\" size=\"30\" maxlength=\"25\"></td>";
                                                        echo"</tr>";
                                                        echo"<tr>";
                                                        echo"<td align=\"right\" id=\"passwordLabel\">รหัสผ่าน.:</td>";
                                                        echo"<td><input name=\"tbxPassword\" type=\"password\" id=\"tbxPassword\" size=\"30\" maxlength=\"25\"></td>";
                                                        echo"</tr>";
                                                        echo"<tr>";
                                                        echo"<td></td>";
                                                        echo"<td id=\"td_login_button\"><input type=\"image\" src=\"images/login_button.png\" width=\"240\" height=\"36\"></td>";
                                                        echo"</tr>";
                                                        echo"</table>";
                                                    	echo"</form>";
													?>
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