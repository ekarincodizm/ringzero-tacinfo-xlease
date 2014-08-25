<?php
	session_start();
	include("../../config/config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$id=$_GET['id'];
	
	
	pg_query("BEGIN");
	$status=0;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>รายละเอียด :: ระบบประกาศขาย</title>
<link href="info.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles.css" />

<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="script/jquery-1.5.2.min.js"></script>
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="jquery.aw-showcase.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#divheader1").load("header.php");
	$("#divfooter1").load("footer.php");
	$("#showcase").awShowcase(
	{
		content_width:			600,
		content_height:			450,
		fit_to_parent:			false,
		auto:					true,
		interval:				3000,
		continuous:				true,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					true,
		buttons:				true,
		btn_numbers:			false,
		keybord_keys:			true,
		mousetrace:				false, /* Trace x and y coordinates for the mouse */
		pauseonover:			true,
		stoponclick:			true,
		transition:				'fade', /* hslide/vslide/fade */
		transition_delay:		300,
		transition_speed:		500,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				true,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'vertical', /* vertical/horizontal */
		thumbnails_slidex:		0, /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
		dynamic_height:			false, /* For dynamic height to work in webkit you need to set the width and height of images in the source. Usually works to only set the dimension of the first slide in the showcase. */
		speed_change:			true, /* Set to true to prevent users from swithing more then one slide at once. */
		viewline:				false /* If set to true content_width, thumbnails, transition and dynamic_height will be disabled. As for dynamic height you need to set the width and height of images in the source. */
	});
});

</script>
</head>
<body>
	<div align="center">
    <div id="divheader1"></div>
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0">
                <tr>
                	<td align="center" valign="top" id="content">
               	  		<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="400" align="center" valign="top" id="content">
                                <?php
									$sql="select * from carsystem.\"TrPostSell\" where \"carSellID\"='$id'";
									$dbquery=pg_query($sql);
									$rs=pg_fetch_assoc($dbquery);
									$id=$rs['carSellID'];
									$carBrand=$rs['carBrand'];
									$carSubBrand=$rs['carSubBrand'];
									$carModel=$rs['carModel'];
									$carType=$rs['carType'];
									$carYear=$rs['carYear'];
									$gasType=$rs['gasType'];
									$gasSystem=$rs['gasSystem'];
									$gasTankSize=$rs['gasTankSize'];
									$liveProvince=$rs['liveProvince'];
									$postDetail=$rs['postDetail'];
									$carColor=$rs['carColor'];
									$carInstallment=$rs['carInstallment'];
									$fullpicpath=$rs['cropImagePath'];
									$thumpicpath=$rs['thumnailsImagePath'];
									$carPrice=$rs['carprice'];
									$installmentMonth=$rs['installmentMonth'];
									$files = array_diff( scandir($fullpicpath), array(".", "..") );
									
									$sql1="select * from carsystem.\"productBrand\" where \"productBrandID\"='$carBrand'";
									$dbquery1=pg_query($sql1);
									$rs1=pg_fetch_assoc($dbquery1);
									$brandName=$rs1['productBrandName'];
									
									$sql1="select * from carsystem.\"productSubBrand\" where \"productSubBrandID\"='$carSubBrand'";
									$dbquery1=pg_query($sql1);
									$rs1=pg_fetch_assoc($dbquery1);
									$subBrandName=$rs1['productSubBrandName'];
									
									$sql2="select * from carsystem.\"productModel\" where \"productModelID\"='$carModel'";
									$dbquery2=pg_query($sql2);
									$rs2=pg_fetch_assoc($dbquery2);
									$modelName=$rs2['productModelName'];
								?>
                               	  <div id="carInfoTitle" class="carInfoTitle" onMouseOver="this.className='carInfoTitle1'" onMouseOut="this.className='carInfoTitle'"><strong><?php echo $postDetail; ?></strong></div>
                                	<div id="divCarInfo">
                                    	<div id="divCarInfoImage" class="CarInfoImageBG">
                                            <div id="divCarInfoBorder">
                                                <ul id="ulCarInfoImage">
                                                    <li><img src="<?php echo $thumpicpath.$files[2]; ?>" class="imagePreview" width="260" height="173" /></li>
                                                    <li><strong>ติดต่อ : <strong class="hilightText1">บริษัท ไทยเอซ ลิสซิ่ง จำกัด</strong></strong></li>
                                                    <li><strong>เบอร์โทรศัพท์ : </strong><strong class="normallHilightText">0-2944-2000</strong></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div id="divCarInfoHead"><strong>ข้อมูลแบบย่อ</strong></div>
                                    	<div id="divCarInfoText">
                                        	<ul id="ulCarInfoText">
                                            	<li>ยี่ห้อ : <strong class="hilightText"><?php echo $brandName; ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;ยี่ห้อรอง : <strong class="hilightText"><?php echo $subBrandName; ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;รุ่น : <strong class="hilightText"><?php echo $modelName; ?></strong></li>
                                                <li>สี : <span class="normallHilightText"><?php echo $carColor; ?></span></li>
                                                <li>ประเภทรถ : <span class="normallHilightText"><?php echo $carType; ?></span></li>
                                                <li>ปีรถ : <strong class="hilightText"><?php echo $carYear; ?></strong></li>
                                                <li>ประเภทแก๊ส : <span class="normallHilightText"><?php echo $gasType; ?></span></li>
                                                <li>ระบบแก๊ส : <span class="normallHilightText"><?php echo $gasSystem; ?></span></li>
                                                <li>ขนาดถังแก๊ส : <span class="normallHilightText"><?php echo $gasTankSize; ?></span></li>
                                                <li>จังหวัด : <span class="normallHilightText"><?php echo $liveProvince; ?></span></li>
                                                <li>อัตราผ่อนต่อเดือน : <strong class="bigHiligthText"><?php echo number_format($carInstallment,2,'.',','); ?> บาท (<?php echo $installmentMonth; ?> เดือน)</strong></li>
                                                <li>ราคารวม : <strong class="bigHiligthText"><?php echo number_format($carPrice,2,'.',',') ?> บาท</strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="divSliderShowTitle">อัลบั้มภาพถ่าย</div>
                                    <div id="divalbumcontainer">
                                    	<div style="width: 780px; padding-top:20px; padding-bottom:20px; margin: auto; background-color:#dfdfdf; border-radius:7px;">
                                        <?php
											$image = array_diff( scandir($fullpicpath), array(".", ".."));
											$sumpicture=sizeof($image);
										?>
                                        	<div id="divsumpicture"><?php echo $sumpicture; ?> รูปภาพ</div>
                                            <div id="showcase" class="showcase">
                                            <?php
                                                foreach ($image as $value) {
                                                    echo "<div class=\"showcase-slide\">";
                                                    echo "<div class=\"showcase-content\">";
                                                    echo "<img src=\"".$fullpicpath.$value."\" alt=\"$value\" width=\"600px\" />";
                                                    echo "</div>";
                                                    echo "<div class=\"showcase-thumbnail\">";
                                                    echo "<img src=\"".$thumpicpath.$value."\" alt=\"$value\" width=\"140px\" />";
                                                    echo "<div class=\"showcase-thumbnail-cover\"></div>";
                                                    echo "</div>";
                                                    echo "<div class=\"showcase-caption\">";
                                                    echo "<span id=\"h2\">$brandName $subBrandName $modelName</span>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                }
                                            ?>
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
            </table>
        </div>
        <div id="divfooter1"></div>
	</div>
</body>
</html>