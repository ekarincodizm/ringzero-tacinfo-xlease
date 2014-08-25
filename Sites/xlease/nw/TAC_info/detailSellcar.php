<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$id=$_GET['id'];
	
	
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
<link rel="stylesheet" type="text/css" href="styles.css" />

<!--<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />-->    
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>

<script type="text/javascript">
	var _siteRoot='index.html',_root='index.html';
</script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="scripts.js"></script>

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
                                <?php
									$sql="select * from \"TrPostSell\" where \"carSellID\"='$id'";
									$dbquery=pg_query($sql);
									$result=pg_fetch_assoc($dbquery);
									$carBrand=$result['carBrand'];
									
									$sql2="select * from \"TrCarBrand\" where \"brandID\"='$carBrand'";
									$dbquery2=pg_query($sql2);
									$result2=pg_fetch_assoc($dbquery2);
									
									$brandName=$result2['brandName'];
									
									$carModel=$result['carModel'];
									
									$sql3="select * from \"TrCarModel\" where \"modelID\"='$carModel'";
									$dbquery3=pg_query($sql3);
									$result3=pg_fetch_assoc($dbquery3);
									
									$modelName=$result3['modelName'];
									
									$carType=$result['carType'];
									$carYear=$result['carYear'];
									$gasType=$result['gasType'];
									$gasSystem=$result['gasSystem'];
									$gasTankSize=$result['gasTankSize'];
									$liveProvince=$result['liveProvince'];
									$postDetail=$result['postDetail'];
									$carColor=$result['carColor'];
									$carPrice=$result['carPrice'];
								?>
                               	  <div id="carInfoTitle" class="carInfoTitle" onMouseOver="this.className='carInfoTitle1'" onMouseOut="this.className='carInfoTitle'"><strong><?php echo $postDetail; ?></strong></div>
                                	<div id="divCarInfo">
                                    	<div id="divCarInfoImage" class="CarInfoImageBG">
                                            <div id="divCarInfoBorder">
                                                <ul id="ulCarInfoImage">
                                                    <li><img src="taxi/Full/1/1.jpg" class="imagePreview" width="260" height="173" /></li>
                                                    <li><strong>ติดต่อ : <strong class="hilightText1">บริษัท ไทยเอซ ลิสซิ่ง จำกัด</strong></strong></li>
                                                    <li><strong>เบอร์โทรศัพท์ : <strong class="normallHilightText">0-2944-2000</strong></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div id="divCarInfoHead"><strong>ข้อมูลแบบย่อ</strong></div>
                                    	<div id="divCarInfoText">
                                        	<ul id="ulCarInfoText">
                                            	<li>ยี่ห้อ : <strong class="hilightText"><?php echo $brandName; ?></strong></li>
                                                <li>รุ่น : <strong class="hilightText"><?php echo $modelName; ?></strong></li>
                                                <li>สี : <span class="normallHilightText"><?php echo $carColor; ?></span></li>
                                                <li>ประเภทรถ : <span class="normallHilightText"><?php echo $carType; ?></span></li>
                                                <li>ปีรถ : <strong class="hilightText"><?php echo $carYear; ?></strong></li>
                                                <li>ประเภทแก๊ส : <span class="normallHilightText"><?php echo $gasType; ?></span></li>
                                                <li>ระบบแก๊ส : <span class="normallHilightText"><?php echo $gasSystem; ?></span></li>
                                                <li>ขนาดถังแก๊ส : <span class="normallHilightText"><?php echo $gasTankSize; ?></span></li>
                                                <li>จังหวัด : <span class="normallHilightText"><?php echo $liveProvince; ?></span></li>
                                                <li>อัตราผ่อนต่อเดือน : <strong class="bigHiligthText"><?php echo $carPrice; ?> บาท</strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="divSliderShowTitle">อัลบั้มภาพถ่าย</div>
                                  <div id="divCarAlbum">
                                  	<div id="header">
                                    <div class="wrap">
                                    <div id="slide-holder">
                                    <div id="slide-runner">
                                    <a href=""><img id="slide-img-1" src="taxi/Full/1/1.jpg" class="slide" alt="" /></a>
                                    <a href=""><img id="slide-img-2" src="taxi/Full/1/2.jpg" class="slide" alt="" /></a>
                                    <a href=""><img id="slide-img-3" src="taxi/Full/1/3.jpg" class="slide" alt="" /></a>
                                    <div id="slide-controls">
                                    <p id="slide-client" class="text"><strong>post: </strong><span></span></p>
                                    <p id="slide-desc" class="text"></p>
                                    <p id="slide-nav"></p>
                                    </div>
                                    </div>
                                    <script type="text/javascript">
if(!window.slider)var slider={};slider.data=[{"id":"slide-img-1","client":"Thai ACE","desc":""},{"id":"slide-img-2","client":"Thai ACE","desc":""},{"id":"slide-img-3","client":"Thai ACE","desc":""}];
									</script>
                                    </div>
                                    </div>
                                    </div>
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
</body>
</html>