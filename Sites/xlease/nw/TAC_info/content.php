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
	
	function transformDate($date)
	{
		$month=substr($date,5,2);
		$thaiMonth="";
		switch($month)
		{
			case 01:
			$thaiMonth="มกราคม";
			break;
			case 02:
			$thaiMonth="กุมภาพันธ์";
			break;
			case 03:
			$thaiMonth="มีนาคม";
			break;
			case 04:
			$thaiMonth="เมษายน";
			break;
			case 05:
			$thaiMonth="พฤษภาคม";
			break;
			case 06:
			$thaiMonth="มิถุนายน";
			break;
			case 07:
			$thaiMonth="กรกฎาคม";
			break;
			case 08:
			$thaiMonth="สิงหาคม";
			break;
			case 09:
			$thaiMonth="กันยายน";
			break;
			case 10:
			$thaiMonth="ตุลาคม";
			break;
			case 11:
			$thaiMonth="พฤศจิกายน";
			break;
			case 12:
			$thaiMonth="ธันวาคม";
			break;
		}
		$year=substr($date,0,4);
		$thaiYear=$year+543;
		$day=substr($date,8,2);
		$thaidate=$day." ".$thaiMonth." ".$thaiYear;
		return $thaidate;
	};
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
                                    <div id="content_box1">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table width="760" border="0" cellpadding="0" cellspacing="0">
                                                        <?php
                                                            $NewsID=$_GET['NewsID'];
                                                            $sql="SELECT * FROM \"Main_News\" WHERE \"NewsID\"=$NewsID";
                                                            $dbquery=pg_query($sql);
                                                            if(!$dbquery)
                                                            {
                                                                Echo"<tr><td id=\"td_news2\">ไม่สามารถติดต่อฐานข้อมูลได้</td></tr>";
                                                            }
                                                            $result=pg_fetch_assoc($dbquery);
                                                            $numDate=substr($result[doerStamp],0,10);
                                                            $thaiDate=transformDate($numDate);
                                                            $poster=$result[doerID];
															$postTime=substr($result[doerStamp],11,5);
                                                            echo"<tr><td id=\"content_news_title\">$result[Subject]</td></tr>";
															if($result[isMember]==1)
															{
																if(!isset($_SESSION['username']))
																{
																	echo"<tr><td valign=\"top\" id=\"content_news_content\">ประกาศข่าวนี้สำหรับสมาชิกเท่านั้น กรุณาเข้าสู่ระบบก่อน</td></tr>";
																}
																else
																{
                                                            		echo"<tr><td valign=\"top\" id=\"content_news_content\">$result[Message]</td></tr>";
																}
															}
															else
															{
																echo"<tr><td valign=\"top\" id=\"content_news_content\">$result[Message]</td></tr>";
															}
                                                            echo"<tr><td id=\"content_news_date\"><img src=\"images/icon-date.png\"> ประกาศเมื่อวันที่ $thaiDate เวลา $postTime น. <img src=\"images/icon-user.png\"> ประกาศโดย $poster </td></tr>";
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