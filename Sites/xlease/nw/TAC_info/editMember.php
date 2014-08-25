<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$id=$_GET['userID'];
	$sql="select * from \"TrMember\" where \"UserID\"='$id'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$username=$result['Username'];
	$isAdmin=$result['isAdmin'];
	$memberBeforName=$result['beforName'];
	$memberFirstName=$result['firstName'];
	$memberLastName=$result['lastName'];
	$carCode=$result['carregistrationnumber'];
	$telephonenumber=$result['telephonenumber'];
	
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
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery-1.3.2.js"></script>
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
                                                <td valign="middle" id="td_top_menu">Edit Member :: แก้ไขข้อมูลสมาชิก</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                	<form action="editMember_Process.php?id=<?php echo $id; ?>" method="post" name="formAddMember">
                                                        <table border="0" cellpadding="0" cellspacing="5" id="tbAddMember">
                                                            <tr>
                                                              <td align="right" valign="middle">ชื่อผู้ใช้ :</td>
                                                                <td colspan="5" align="left" valign="middle"  id="tdRequired">
                                                                	<span id="sprytextfield1">
                                                                  		<input name="tbxUsername3" type="text" id="tbxUsername3" value="<?php echo $username; ?>" maxlength="50">*
                                                                		<span class="textfieldRequiredMsg">Required.</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">คำนำหน้า :</td>
                                                                <td align="left" valign="middle">
                                                                    <select name="lbxBeforeName" id="lbxBeforeName">
                                                                      <option value="ไม่ระบุ" <?php if($memberBeforName=="ไม่ระบุ"){echo "selected";} ?> >ไม่ระบุ</option>
                                                                      <option value="นาย" <?php if($memberBeforName=="นาย"){echo "selected";} ?> >นาย</option>
                                                                      <option value="นาง" <?php if($memberBeforName=="นาง"){echo "selected";} ?> >นาง</option>
                                                                      <option value="นางสาว" <?php if($memberBeforName=="นางสาว"){echo "selected";} ?> >นางสาว</option>
                                                                    </select>
                                                                </td>
                                                                <td align="right" valign="middle"> ชื่อ :</td>
                                                                <td align="left" valign="middle" id="tdRequired"><input name="tbxName" type="text" id="tbxName" value="<?php echo $memberFirstName; ?>" maxlength="50">*</td>
                                                                <td align="right" valign="middle"> นามสกุล :</td>
                                                                <td align="left" valign="middle" id="tdRequired"><input name="tbxLastName" type="text" id="tbxLastName" value="<?php echo $memberLastName; ?>">*</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">ทะเบียนรถ :</td>
                                                                <td colspan="5" align="left" valign="middle"><input name="tbxCarNumber3" type="text" id="tbxCarNumber3" value="<?php echo $carCode; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">เบอร์โทรศัพท์ :</td>
                                                                <td colspan="5" align="left" valign="middle">
                                                                	<input name="tbxTelephoneNumber" type="text" id="tbxTelephoneNumber3" value="<?php echo $telephonenumber; ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" valign="middle">ประเภทสมาชิก :</td>
                                                                <td colspan="5" align="left" valign="middle">
                                                                	<select name="lbxUserType" id="lbxUserType">
                                                                      	<option value="0" <?php if($isAdmin=="0"){echo "selected";} ?> >user</option>
                                                                        <option value="1" <?php if($isAdmin=="1"){echo "selected";} ?>>admin</option>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
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
</body>
</html>