<?php
	session_start();
	include("../../config/config.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>TR Member</title>

<!--<script type="text/javascript" src="script/jquery-ui-1.8.2.custom.min.js"></script>-->
<script type="text/javascript" src="script/jquery-1.3.2.js"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script type="text/javascript" src="script/jquery-1.4.2.min.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script type="text/javascript">
function getCarSubBrand()
{
	var brandID=$("#lbxBrand").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getsubbrand1.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"brandID="+brandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;       
        $("#lbxSubBrand").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
function getCarModel()
{
	var subbrandID=$("#lbxSubBrand").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getModel1.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"subbrandID="+subbrandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;      
        $("#lbxModel").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
</script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#divheader1").load("header.php");
	$("#divfooter1").load("footer.php");
});
</script>
<script type="text/javascript">
function make_blank()
{
document.addNews.tbxYearCar.value ="";
document.getElementById('tbxYearCar').style.color="#555";
document.getElementById('tbxYearCar').style.fontStyle="normal";
}
function chklogin()
{
	<?php
	if(!isset($_SESSION['username']))
	{
		echo "$('#main').load('login.php');";
	}
	?>
}
</script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<link type="text/css" href="info.css" rel="stylesheet" />  
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="chklogin();">
	<div align="center">
    <div id="divheader1"></div>
    	<div id="main">
        <?php
		if(isset($_SESSION['username']))
		{
		?>
        	<table width="800" border="0" cellpadding="0" cellspacing="0" style="background-image:url(images/bg_regis.gif); border-bottom-left-radius:5px; border-bottom-right-radius:5px;">
            	<tr>
                	<td id="tdheadpostSellCar">ระบบประกาศขายรถ</td>
                </tr>
                <tr>
                	<td align="center" valign="top" id="content">
               	  		<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="400" align="center" valign="top" id="content">
                                    <div id="content_box">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                           		  <form name="addNews" method="post" action="postproduct_process.php">
                                       	  				<table border="0" cellpadding="0" cellspacing="10" id="tb_postCarSell">
                                                        	<tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">ยี่ห้อหลัก : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                                    <span id="spryselect1">
                                                                	<select name="lbxBrand" id="lbxBrand" class="tbxlong" onChange="getCarSubBrand()">
                                                                	  <option value="">กรุณาเลือกยื่ห้อ</option>
                                                                	  <?php
                                                                        $sql="select * from carsystem.\"productBrand\" order by \"productBrandID\"";
																		$dbquery=pg_query($sql);
																		while($rs=pg_fetch_assoc($dbquery))
																		{
																			$productBrandID=$rs['productBrandID'];
																			$productBrandName=$rs['productBrandName'];
																			echo"<option value=\"$productBrandID\">$productBrandName</option>";
																		}
																		?>
                                                              	 	</select>
                                                                	<br><span class="selectRequiredMsg alignTopRight">กรุณาเลือกยี่ห้อหลักก่อนครับ</span>
                                                            	</span></td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">ยี่ห้อรอง : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                               			    <span id="spryselect2">
                                                                	<select name="lbxSubBrand" id="lbxSubBrand" class="tbxlong" onChange="getCarModel()">
                                                                	  <option>กรุณาเลือกยื่ห้อรอง</option>
                                                       	  			</select>
                                                                	<br><span class="selectRequiredMsg alignTopRight">กรุณาเลือกยี่ห้อรองก่อนครับ</span>
                                                            	</span></td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">รุ่น : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                            <span id="spryselect3">
                                                                	<select name="lbxModel" id="lbxModel" class="tbxlong">
                                                                	  <option>กรุณาเลือกรุ่น</option>
                                                              	  	</select><br>
                                                            		<span class="selectRequiredMsg alignTopRight">กรุณาเลือกรุ่นก่อนครับ</span>
                                                            	</span></td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">ประเภท : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                                    <span id="spryselect4" class="setPossition">
                                                                    <select name="lbxTypeProduct" id="lbxTypeProduct">
                                                                      <option value="">กรุณาเลือกรุ่น</option>
                                                                      <option value="รถป้ายแดง">ป้ายแดง</option>
                                                                      <option value="รถมือสอง">มือสอง</option>
                                                                    </select>
                                                                    <span class="selectRequiredMsg alignTopRight1">กรุณาเลือกประเภทรถก่อนครับ</span>
                                                                  	</span>
                                                                     ปีรถ : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font>
                                                               <span id="sprytextfield1" class="setPossition">
                                                                     <input type="text" name="tbxYearCar" id="tbxYearCar" placeholder="ระบุเป็นปี ค.ศ." onFocus="make_blank();">
                                                               <span class="textfieldRequiredMsg alignTopRight1">กรุณาระบุปีรถก่อนครับ</span>
                                                               </span>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right" valign="middle" style="padding-top:7px;">
                                                                	<span class="spanAlignLeft">ประเภทแก๊ส : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                                    <span id="spryselect5" class="setPossition">
                                                                    <select name="lbxTypeGas" id="lbxTypeGas" class="middleLong">
                                                                    <option value="">กรุณาเลือกประเภทแก๊ส</option>
                                                                      <option value="NGV">NGV</option>
                                                                      <option value="LPG">LPG</option>
                                                                    </select>
                                                                    <span class="selectRequiredMsg alignTopRight1">เลือกประเภทแก๊สก่อนครับ</span>
                                                                    </span>
                                                                    ระบบแก๊ส : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font>
                                                                    <span id="spryselect6" class="setPossition">
                                                                    <select name="lbxGasSystem" id="lbxGasSystem" class="middleLong">
                                                                    <option value="">กรุณาเลือกระบบแก๊ส</option>
                                                                      <option value="ระบบดูด">ระบบดูด</option>
                                                                      <option value="ระบบฉีด">ระบบฉีด</option>
                                                                    </select>
                                                                    <span class="selectRequiredMsg alignTopRight1">เลือกระบบแก๊สก่อนครับ</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	ขนาดถัง : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font><span id="sprytextfield2" class="setPossition">
                                                                    <input type="text" name="tbxTankSize" id="tbxTankSize" class="tbxlong">
                                                                    <br><span class="textfieldRequiredMsg alignTopRight">กรุณาระบุขนาดถังก่อนครับ</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right" id="tdAlignLeft">
                                                                	<span class="spanAlignLeft">สีรถ : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                                    <span id="spryselect7" class="setPossition">
                                                                    <select name="lbxColor" id="lbxColor" class="middleLong1">
                                                                      <option value="">กรุณาเลือกสีรถ</option>
                                                                      <option value="สีฟ้า">สีฟ้า</option>
                                                                      <option value="สีชมพู">สีชมพู</option>
                                                                      <option value="สีส้ม">สีส้ม</option>
                                                                      <option value="สีเขียวเหลือง">สีเขียวเหลือง</option>
                                                                    </select>
                                                                    <span class="selectRequiredMsg alignTopRight1">เลือกสีรถก่อนครับ</span>
                                                                    </span>
                                                                     จังหวัด : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font>
                                                                    <span id="spryselect8" class="setPossition">
                                                                    <select name="lbxThaiProvince" id="lbxThaiProvince" class="middleLong1">
                                                                      <option value="">กรุณาเลือกจังหวัด</option>
                                                                    <?php
																	$sql="select * from carsystem.\"province\"";
																	$dbquery=pg_query($sql);
																	while($rs=pg_fetch_assoc($dbquery))
																	{
																		echo"<option value=\"".$rs['PROVINCE_NAME']."\">".$rs['PROVINCE_NAME']."</option>";
																	}
																	?>
                                                                    </select>
                                                                    <span class="selectRequiredMsg alignTopRight1">เลือกจังหวัดก่อนครับ</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right" valign="top">
                                                                	<table border="0" cellpadding="0" cellspacing="0" id="tbInPostCarSell">
                                                                    	<tr>
                                                                        	<td align="right" valign="top"><span class="spanAlignLeft">รายละเอียด : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">* </font></span></td>
                                                                            <td> 
                                                                            	<div id="divPostCarSell"><textarea name="tarDetailCarSell" id="tarDetailCarSell" cols="45" rows="25"></textarea></div>
                                                                            </td>
                                                                        </tr>
                                                                	</table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">ราคาผ่อน : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font></span>
                                                                    <span id="sprytextfield3" class="setPossition">
                                                                    <input name="tbxInstallmentPrice" type="text" class="middleLong2" id="tbxInstallmentPrice" maxlength="10">
                                                                    <span class="textfieldRequiredMsg alignTopRight1">กรุณาระบุราคาผ่อน</span>
                                                                    </span>
                                                                    เวลาผ่อน(เดือน) : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font>
                                                                    <span id="sprytextfield4" class="setPossition">
                                                                    <input name="tbxInstallmentMonth" type="text" class="middleLong2" id="tbxInstallmentMonth" maxlength="10">
                                                                    <span class="textfieldRequiredMsg alignTopRight1">กรุณาระบุเวลาผ่อน</span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td align="right">
                                                                	<span class="spanAlignLeft">ราคารวม : <font style="font-family:Tahoma, Geneva, sans-serif; color:red; font-size:14px;">*</font>
                                                                    </span>
                                                                    <input name="tbxAllPrice" type="text" class="tbxlong" id="tbxAllPrice" maxlength="10">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <input type="submit" value="ถัดไป" id="btnSubmitSellCar" name="btnSubmitSellCar">
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
            </table>
        <?php
		}
		?>
        </div>
		<div id="divfooter1"></div>
	</div>
<script type="text/javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
var spryselect6 = new Spry.Widget.ValidationSelect("spryselect6");
var spryselect7 = new Spry.Widget.ValidationSelect("spryselect7");
var spryselect7 = new Spry.Widget.ValidationSelect("spryselect8");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
</script>
</body>
</html>