<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	$loginStatus="";
	if(isset($_SESSION['username']))
	{
		$loginStatus="คุณ ".$_SESSION['username']." เข้าสู่ระบบเรียบร้อยแล้ว";
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
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery-1.3.2.js"></script>
</head>
<body>
	<ul id="navigation">
        <li class="home"><a href="index.php" title="หน้าหลัก"></a></li>
        <li class="admin"><a href="adminMenu.php" title="เมนูผู้ดูแลระบบ"></a></li>
        <li class="back"><a href="javascript:history.back(-1);" title="กลับก่อนหน้า"></a></li>
    </ul>
    <script type="text/javascript">
        $(function() {
            $('#navigation a').stop().animate({'marginLeft':'-95px'},1000);

            $('#navigation > li').hover(
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-2px'},200);
                },
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-95px'},200);
                }
            );
        });
    </script>
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
                                                	<form action="addMember_Process.php" method="post" name="formAddMember">
                                                        <table border="0" cellpadding="0" cellspacing="5" id="tbAddMember">
                                                            <tr>
                                                              <td align="right" valign="middle">ชื่อผู้ใช้ :</td>
                                                                <td colspan="5" align="left" valign="middle" id="tdRequired">
                                                                	<span id="sprytextfield1">
                                                                  		<input name="tbxUsername" type="text" id="tbxUsername" maxlength="50">*
                                                                		<span class="textfieldRequiredMsg">Required.</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                              <td align="right" valign="middle">รหัสผ่าน :</td>
                                                                <td colspan="5" align="left" valign="middle" id="tdRequired">
                                                                	<input name="tbxPassword" type="password" id="tbxPassword" maxlength="20">*
                                                               	</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">ยืนยันรหัสผ่าน :</td>
                                                                <td colspan="5" align="left" valign="middle" id="tdRequired"><span id="spryconfirm1">
                                                                  <input name="tbxConfirmPassword1" type="password" id="tbxConfirmPassword1" maxlength="20">*
                                                                <span class="confirmRequiredMsg">Required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">คำนำหน้า :</td>
                                                                <td align="left" valign="middle">
                                                                    <select name="lbxBeforeName" id="lbxBeforeName">
                                                                      <option value="ไม่ระบุ" selected>ไม่ระบุ</option>
                                                                      <option value="นาย">นาย</option>
                                                                      <option value="นาง">นาง</option>
                                                                      <option value="นางสาว">นางสาว</option>
                                                                    </select>
                                                                </td>
                                                                <td align="right" valign="middle"> ชื่อ :</td>
                                                                <td align="left" valign="middle" id="tdRequired"><span id="sprytextfield2">
                                                                  <input name="tbxName" type="text" id="tbxName" maxlength="50">*
                                                              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                                                                <td align="right" valign="middle"> นามสกุล :</td>
                                                                <td align="left" valign="middle" id="tdRequired"><span id="sprytextfield3">
                                                                  <input type="text" name="tbxLastName" id="tbxLastName">*
                                                                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">ทะเบียนรถ :</td>
                                                                <td colspan="5" align="left" valign="middle"><input type="text" name="tbxCarNumber" id="tbxCarNumber"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">เบอร์โทรศัพท์ :</td>
                                                                <td colspan="5" align="left" valign="middle">
                                                                	<input type="text" name="tbxTelephoneNumber" id="tbxTelephoneNumber">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">ประเภทสมาชิก :</td>
                                                                <td colspan="5" align="left" valign="middle">
                                                                	<select name="lbxUserType" id="lbxUserType">
                                                                      	<option value="0" selected="selected">user</option>
                                                                        <option value="1">admin</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle"></td>
                                                                <td colspan="5" align="left" valign="middle"><input type="image" src="images/save_button.png"></td>
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
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "tbxPassword", {validateOn:["blur"]});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
</script>
</body>
</html>