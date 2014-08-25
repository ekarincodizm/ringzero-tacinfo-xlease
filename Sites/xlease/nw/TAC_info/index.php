<?php
	session_start();
	include("config.php");
	mb_internal_encoding('utf-8');
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='1'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle1=$result['NewsTypeName'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='2'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle2=$result['NewsTypeName'];
	
	$sql="SELECT * FROM \"TrNewsType\" WHERE \"NewsTypeID\"='3'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$newsTitle3=$result['NewsTypeName'];
	
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
 <script type="text/javascript">
 var pkBaseURL = (("https:" == document.location.protocol) ? "https://172.16.2.247/piwik/" : "http://172.16.2.247/piwik/");
 document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
 </script><script type="text/javascript">
 try {
 var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
 piwikTracker.trackPageView();
 piwikTracker.enableLinkTracking();
 } catch( err ) {}
 </script><noscript><p><img src="http://172.16.2.247/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
	<div align="center">
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0" id="tdColorBG">
            	<tr>
                	<td height="190">
                    <iframe width="800" height="220" src="header.php" frameborder="0" name="iframe_header"></iframe>
                    </td>
           	  	</tr>
                <tr>
                	<td align="center" valign="middle" id="content">
                    	<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="left" valign="top" id="content">
                              <div id="content_box2">
                              	<div id="div_checkOil">
                                    <table border="0" cellpadding="0" cellspacing="0" width="175">
                                    	<tr>
                                        	<td id="checkOilTitle">ราคาน้ำมันวันนี้</td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="middle" id="td_oil">
                                                <iframe marginWidth=0 marginHeight=0 src="http://www.pttplc.com/th/oilprice.asp" frameBorder=0 width=173 scrolling=no height=305>
                                                </iframe>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                    <table width="580" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td valign="middle" id="td_top_menu2"><div id="showAllNews"><a href="showAllNews.php?newsType=1">ดูทั้งหมด</a></div><?php echo $newsTitle1; ?></td>
                                        </tr>
                                        <tr>
                                            <td valign="top" id="top_menu_border2">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <?php
                                                        $sql="SELECT * FROM \"Main_News\" WHERE \"Type\"='1' and \"disabled\"<>'y' ORDER BY \"NewsID\" DESC LIMIT 15";
                                                        $dbquery=pg_query($sql);
                                                        if(!$dbquery)
                                                        {
                                                            Echo"<tr><td id=\"td_news2\">ไม่สามารถติดต่อฐานข้อมูลได้</td></tr>";
                                                        }
                                                        while($result=pg_fetch_assoc($dbquery))
                                                        {
                                                            $contentID=$result[NewsID];
                                                            echo"<tr><td id=\"td_news2\"><img src=\"images/link_bullet.gif\"> <a href=\"content.php?NewsID=$contentID\">$result[Subject]</a> ($result[doerStamp])</td></tr>";
                                                        }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="both_menu">
                                    <table width="800" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td width="400" lign="center" valign="middle">
                                                <div id="div_both_menu1">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td valign="middle" id="td_both_menu2"><div id="showAllNews"><a href="showAllNews.php?newsType=2">ดูทั้งหมด</a></div><?php echo $newsTitle2; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" id="both_menu_border">
                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                    <?php
                                                                        $sql="SELECT * FROM \"Main_News\" WHERE \"Type\"='2' and \"disabled\"<>'y' ORDER BY \"NewsID\" DESC LIMIT 7";
                                                                        $dbquery=pg_query($sql);
                                                                        if(!$dbquery)
                                                                        {
                                                                            Echo"<tr><td id=\"td_news2\">ไม่สามารถติดต่อฐานข้อมูลได้</td></tr>";
                                                                        }
                                                                        while($result=pg_fetch_assoc($dbquery))
                                                                        {
                                                                            $contentID=$result[NewsID];
                                                                            echo"<tr><td id=\"td_news2\"><img src=\"images/link_bullet.gif\"> <a href=\"content.php?NewsID=$contentID\">".mb_substr($result[Subject],0,30)."...</a> ($result[doerStamp])</td></tr>";
                                                                        }
                                                                    ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                          <td width="400" height="180" align="center">
                                            <div id="div_both_menu2">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="middle" id="td_both_menu2"><div id="showAllNews"><a href="showAllNews.php?newsType=3">ดูทั้งหมด</a></div><?php echo $newsTitle3; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top" id="both_menu_border">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <?php
                                                                    $sql="SELECT * FROM \"Main_News\" WHERE \"Type\"='3' and \"disabled\"<>'y' ORDER BY \"NewsID\" DESC LIMIT 7";
                                                                    $dbquery=pg_query($sql);
                                                                    if(!$dbquery)
                                                                    {
                                                                        Echo"<tr><td id=\"td_news2\">ไม่สามารถติดต่อฐานข้อมูลได้</td></tr>";
                                                                    }
                                                                    while($result=pg_fetch_assoc($dbquery))
                                                                    {
                                                                        $contentID=$result[NewsID];
                                                                        echo"<tr><td id=\"td_news2\"><img src=\"images/link_bullet.gif\"> <a href=\"content.php?NewsID=$contentID\">".mb_substr($result[Subject],0,40)."...</a> ($result[doerStamp])</td></tr>";
                                                                    }
                                                                ?>
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
                        </table>
                    </td>
                </tr>
                <tr>
                	<td height="30"><iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</body>
</html>