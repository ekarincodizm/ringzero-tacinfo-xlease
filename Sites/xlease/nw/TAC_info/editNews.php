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
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='1'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle1=$result['NewsTypeName'];
	$newsValue1=$result['NewsTypeID'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='2'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle2=$result['NewsTypeName'];
	$newsValue2=$result['NewsTypeID'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='3'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle3=$result['NewsTypeName'];
	$newsValue3=$result['NewsTypeID'];
	
	$id=$_GET['id'];
	$sql="select * from \"Main_News\" where \"NewsID\"='$id'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$title=$result['Subject'];
	$message=$result['Message'];
	$type=$result['Type'];
	$time=$result['doerStamp'];
	
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
<link href="style.css" rel="stylesheet" type="text/css">
<link href="jquery_ui_datepicker.css" rel="stylesheet" type="text/css">
<script src="jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="jquery_ui_datepicker.js" type="text/javascript"></script>
<script src="ui.datepicker-de.js" type="text/javascript"></script>
<script src="timepicker.js" type="text/javascript"></script>
<script type="text/javascript">
		$(function() {
			$('#tbxTime').datetime({
				userLang	: 'en',
				americanMode: true
			});
		});
</script>
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
                                                <td valign="middle" id="td_top_menu">Add News :: เพิ่มข่าวสาร</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                               		<form name="addNews" method="post" action="editNews_Process.php?id=<?php echo $id; ?>">
                                                    	<table border="0" cellpadding="0" cellspacing="0" id="tb_postNews">
                                                        	<tr>
                                                            	<td id="td_title" valign="middle" align="right">หัวข้อข่าว.:</td>
                                                                <td><input name="tbxSubject" type="text" id="tbxSubject" size="70" value="<?php echo $title; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                            	<td id="td_message" valign="top" align="right">เนื้อหาข่าว.:</td>
                                                                <td id="td_NewsMessage">
                                                                	<script type="text/javascript" src="nicEdit.js"></script>
																	<script type="text/javascript">
																		bkLib.onDomLoaded(function() {
																			new nicEditor({iconsPath : 'nicEditorIcons.gif'}).panelInstance('tbxMessage');
																		});
																	</script>
                                                                	<textarea name="tbxMessage" id="tbxMessage"><?php echo $message; ?>
                                                                    </textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td id="td_message" valign="middle" align="right">ประเภทข่าว.:</td>
                                                                <td id="td_NewsMessage">
                                                                	<select name="lbNewsType" id="lbNewsType">
																		<?php
                                                                            echo"<option value=\"$newsValue1\""; if($type==$newsValue1){echo "selected";} echo ">$newsTitle1</option>";
                                                                            echo"<option value=\"$newsValue2\""; if($type==$newsValue2){echo "selected";} echo ">$newsTitle2</option>";
                                                                            echo"<option value=\"$newsValue3\""; if($type==$newsValue3){echo "selected";} echo ">$newsTitle3</option>";
                                                                        ?>
                                                   	    	    </select>
                                                                    <input name="forMember" type="checkbox" id="forMember" value="1">สำหรับสมาชิกเท่านั้น
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td id="td_message" valign="middle" align="right">วันที่และเวลา.:</td>
                                                                <td id="td_NewsMessage">
                                                           	    <input name="tbxTime" type="text" id="tbxTime" size="70" value="<?php echo $time; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                            	<td id="td_message" valign="middle" align="right"></td>
                                                                <td id="td_NewsMessage" align="left" valign="middle">
                                                           	    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td></td>
                                                                <td id="td_saveNews" valign="bottom" align="left"><input type="image" src="images/save_button.png"></td>
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
                	<td></td>
                </tr>
                <tr>
                	<td height="30"><iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
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