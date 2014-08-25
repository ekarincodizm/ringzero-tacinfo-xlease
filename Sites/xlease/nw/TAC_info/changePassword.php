<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
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
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
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
                                                <td valign="middle" id="td_top_menu">Change Password :: เปลี่ยนรหัสผ่าน</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                	<form name="loginForm" method="post" action="checkChangePassword.php">
                                                    	<table border="0" cellpadding="0" cellspacing="0" width="600">
                                                        	<tr>
                                                        		<td align="right" id="usernameLabel">รหัสผ่านเดิม.:</td>
                                                        		<td><span id="sprytextfield1">
                                                                <input name="tbxOldPassword" type="password" id="tbxOldPassword" size="30" maxlength="25">
                                                                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                                                        	</tr>
                                                        	<tr>
                                                        		<td align="right" id="passwordLabel">รหัสผ่านใหม่.:</td>
                                                        		<td>
                                                        		  <input name="tbxNewPassword" type="password" id="tbxNewPassword" size="30" maxlength="25">
                                                       		    </td>
                                                        	</tr>
                                                            <tr>
                                                        		<td align="right" id="passwordLabel">ยืนยันรหัสผ่าน.:</td>
                                                        		<td><span id="spryconfirm1">
                                                        		  <input name="tbxConfirmPassword" type="password" id="tbxConfirmPassword" size="30" maxlength="25">
                                                       		    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
                                                        	</tr>
                                                        	<tr>
                                                        		<td></td>
                                                        		<td id="td_login_button"><input type="image" src="images/confirm_button.png"></td>
                                                        	</tr>
                                                        </table>
                                                    </form>
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "tbxNewPassword");
</script>
</body>
</html>