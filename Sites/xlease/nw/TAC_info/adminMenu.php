<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	if(!isset($_SESSION['username']))
	{
		header("Location:index.php");
	}
	else if($_SESSION['userType']!=admin)
	{
		header("Location:index.php");
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
                                    <table width="780" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table width="780" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="middle" id="td_top_menu">Admin Menu :: เมนูผู้ดูแลระบบ</td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top" align="middle" id="top_menu_border3">
                                                            <table border="0" cellpadding="0" cellspacing="15">
                                                                <tr>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/news_add.png"><br><span>เพิ่มข่าวสาร</span><a href="addNews.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/news_new.png"><br><span>แก้ไขข่าวสาร</span><a href="editAllNews.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/id_card_add.png"><br><span>เพิ่มสมาชิก</span><a href="addMember.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/id_card_view.png"><br><span>ค้นหาสมาชิก</span><a href="viewUser.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/id_card_new.png"><br><span>จัดการสมาชิก</span><a href="manageUser.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/mail_add.png"><br><span>ส่ง SMS</span><a href="sendSMS.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="divMenuContrainer"><div class="eachAdminMenu"><img src="icons/USDollarSignAdd48.png"><br><span>เพิ่มประกาศขายรถ</span><a href="postSellcar.php"><div id="divAdminMenu1"></div></a></div></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
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