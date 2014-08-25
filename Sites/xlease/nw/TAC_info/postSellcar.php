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

<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script type="text/javascript">
function getCarModel()
{
	var brandID=$("#lbxBrand").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "getCarModel.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"brandID="+brandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;          
        $("#lbxModel").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
</script>
<script type="text/javascript">
function make_blank()
{
document.addNews.tbxYearCar.value ="";
document.getElementById('tbxYearCar').style.color="#555";
document.getElementById('tbxYearCar').style.fontStyle="normal";
}
</script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
                                                <td valign="middle" id="td_top_menu">Sell Car :: เพิ่มประกาศขายรถ</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                           		  <form name="addNews" method="post" action="postCarSell_Process.php">
                                       	  <table border="0" cellpadding="0" cellspacing="10" id="tb_postCarSell">
                                                        	<tr>
                                                            	<td align="right">
                                                                	ยี่ห้อ : 
                                                                      <span id="spryselect1">
                                                                      <select name="lbxBrand" id="lbxBrand" class="tbxlong" onChange="getCarModel()">
                                                                        <option>กรุณาเลือกยื่ห้อ</option>
                                                                        <?php
                                                                        $sql="select * from \"TrCarBrand\" order by \"brandID\"";
																		$dbquery=pg_query($sql);
																		while($rs=pg_fetch_assoc($dbquery))
																		{
																			$brandID=$rs['brandID'];
																			$brandName=$rs['brandName'];
																			echo"<option value=\"$brandID\">$brandName</option>";
																		}
																		?>
                                                                      </select>
<br><span class="selectRequiredMsg">Please select an item.</span></span></td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	รุ่น : 
                                                                      <span id="spryselect2">
                                                                      <select name="lbxModel" id="lbxModel" class="tbxlong">
                                                                        <option>กรุณาเลือกรุ่น</option>
                                                                      </select>
                                                                <br><span class="selectRequiredMsg">Please select an item.</span></span></td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	ประเภท : 
                                                                    <select name="lbxTypeProduct" id="lbxTypeProduct">
                                                                      <option value="รถป้ายแดง">ป้ายแดง</option>
                                                                      <option value="รถมือสอง">มือสอง</option>
                                                                    </select>
                                                                    ปีรถ : 
                                                                    <input type="text" name="tbxYearCar" id="tbxYearCar" value="ระบุเป็นปี ค.ศ." onFocus="make_blank();">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	ประเภทแก๊ส : 
                                                                    <select name="lbxTypeGas" id="lbxTypeGas" class="middleLong">
                                                                      <option value="NGV">NGV</option>
                                                                      <option value="LPG">LPG</option>
                                                                    </select>
                                                                    ระบบแก๊ส : 
                                                                    <select name="lbxGasSystem" id="lbxGasSystem" class="middleLong">
                                                                      <option value="ระบบดูด">ระบบดูด</option>
                                                                      <option value="ระบบฉีด">ระบบฉีด</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	ขนาดถัง : <input type="text" name="tbxTankSize" id="tbxTankSize" class="tbxlong">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="left" id="tdAlignLeft">
                                                                	จังหวัด : 
                                                                    <select name="lbxThaiProvince" id="lbxThaiProvince">
                                                                      <option value="0">กรุณาเลือกจังหวัด</option>
                                                                    <?php
																		$data=file("thai_province.txt");
																		for($i=1;$i<count($data);$i++)
																		{
																			  echo"<option value=\"$data[$i]\">$data[$i]</option>";
																		}
																	?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right" valign="top">
                                                                	<table border="0" cellpadding="0" cellspacing="0" id="tbInPostCarSell">
                                                                    	<tr>
                                                                        	<td align="right" valign="top">รายละเอียด : </td>
                                                                            <td>
                                                                            	<script type="text/javascript" src="nicEdit.js"></script>
																				<script type="text/javascript">
                                                                                    bkLib.onDomLoaded(function() {
                                                                                        new nicEditor({iconsPath : 'nicEditorIcons.gif'}).panelInstance('tarDetailCarSell');
                                                                                    });
                                                                                </script>
                                                                            	<div id="divPostCarSell"><textarea name="tarDetailCarSell" id="tarDetailCarSell" cols="45" rows="25"></textarea></div>
                                                                            </td>
                                                                        </tr>
                                                                	</table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td><input type="image" name="btnSubmitCarSell" id="btnSubmitCarSell" src="images/save_button.png"></td>
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
<script type="text/javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
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