<?php
	include("config.php");
	$brand=$_POST['lbxBrand'];
	$model=$_POST['lbxModel'];
	$carType=$_POST['lbxTypeProduct'];
	$carYear=$_POST['tbxYearCar'];
	if($carYear=="ระบุเป็นปี ค.ศ.")
	{
		$carYear="";
	}
	$gasType=$_POST['lbxTypeGas'];
	$gasSystem=$_POST['lbxGasSystem'];
	$gasTankSize=$_POST['tbxTankSize'];
	$province=$_POST['lbxThaiProvince'];
	$detail=$_POST['tarDetailCarSell'];
/*	$sql="select \"brandName\" from \"TrCarBrand\" where \"brandID\"='$brand'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$brand=$result['brandName'];*/
	
	$sql="insert into \"TrPostSell\"(\"carBrand\",\"carModel\",\"carType\",\"carYear\",\"gasType\",\"gasSystem\",\"gasTankSize\",\"liveProvince\",\"postDetail\") values('$brand','$model','$carType','$carYear','$gasType','$gasSystem','$gasTankSize','$province','$detail')";
	//pg_query($sql);
	if(pg_query($sql))
	{
		$saveStatus="บันทึกเรียบร้อยแล้ว<br>กลับไปยังหน้าประกาศขาย <a href=\"javascript:history.back(-1)\">คลิกที่นี่</a>";
	}
	else
	{
		$saveStatus="บันทึกล้มเหลว";
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
                                                <td valign="middle" id="td_top_menu">Login :: เข้าสู่ระบบ</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                                                        <tr>
                                                            <td align="right" id="usernameLabel"></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" id="lbWelcome">
                                                            <?php
																echo "<h2>".$saveStatus."</h2>";
															?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td id="td_login_button"></td>
                                                        </tr>
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