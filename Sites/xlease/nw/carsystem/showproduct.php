<?php
	session_start();
	include("../../config/config.php");
	pg_query("BEGIN");
	$status=0;
	
	$s_page=$_GET['s_page'];
	if($s_page=="")
	{
		$s_page=0;
	}
	$querystr=$_GET['querystr'];
	$target=$_GET['type'];
	if($target=="")
	{
		$target="image";
	}
	$brand=$_GET['brand'];
	$subbrand=$_GET['lbxSearchsubBrandCar1'];
	$model=$_GET['tbxsearch_model'];
	$cartype=$_GET['cartype'];
	$carcolor=$_GET['carcolor'];
	$carprice=$_GET['carprice'];
	$startPrice="";
	$endPrice="";
	if($carprice=="0_10000")
	{
		$startPrice="0";
		$endPrice="10000";	
	}
	else if($carprice=="10000_25000")
	{
		$startPrice="10000";
		$endPrice="25000";
	}
	else if($carprice=="25000_40000")
	{
		$startPrice="25000";
		$endPrice="40000";
	}
	else if($carprice=="40000_55000")
	{
		$startPrice="40000";
		$endPrice="55000";
	}
	else if($carprice=="55000_1000000")
	{
		$startPrice="55000";
		$endPrice="";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Untitled Document</title>
<link rel="shortcut icon" type="image/x-icon" href="icon/icon.ico">
<link href="info.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles.css" />

<!--<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />-->    
<script type="text/javascript" src="script/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="script/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="script/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('#content_box5').hide();
	$('#quickSearch2').hide();
	$('#quickSearch').click(function(){
		$('#content_box5_1').hide();
		$('#content_box5').show();
	});
	$('#quickSearch1').click(function(){
		$('#content_box5_1').show();
		$('#content_box5').hide();
	});
	$('#divheader1').load('header.php');
	$('#divfooter1').load('footer.php');
	getTab(<?php echo "'".$s_page."'"; ?>,<?php echo "'".$querystr."'"; ?>,<?php echo "'".$target."'"; ?>);
	getSubBrand();
	getSubBrand1();
	getModel();
	getModel1();
});
</script>
<script type="text/javascript">
function getTab(s_page,querystr,type){
		$('#litab1').remove();
		$('#litab2').remove();
		var carBrand="";
		var carSubBrand="";
		var carModel="";
		var carType="";
		var carGasType="";
		var carGasSystem="";
		var carColor="";
		var carProvince="";
		var wordSearch="";
		
		carBrand=$('#lbxSearchBrandCar').val();
		carSubBrand=$('#lbxSearchsubBrandCar').val();
		carModel=$('#lbxSearchModelCar').val();
		carType=$('#lbxSearchTypeCar').val();
		carGasType=$('#lbxSearchGasTypeCar').val();
		carGasSystem=$('#lbxSearchGasSystemCar').val();
		carColor=$('#lbxSearchColorCar').val();
		carProvince=$('#lbxSearchProvinceCar').val();
		carFirstPrice=$('#tbxSearchCarFirstPrice').val();
		carSecondPrice=$('#tbxSearchCarSecondPrice').val();
		carFirstYear=$('#tbxSearchCarFirstYear').val();
		carSecondYear=$('#tbxSearchCarSecondYear').val();
		wordSearch=$('#tbxSearchCar').val();
		
		var url1='page/showImageResult.php';
		/* This code is executed after the DOM has been completely loaded */
		//alert('url1 : '+url1+'\r\n url2 : '+url2);
		/* Defining an array with the tab text and AJAX pages: */
		var Tabs = {
			'ผลการค้นหา'	: url1
		}
		
		/* The available colors for the tabs: */
		var colors = ['blue','green'];
		
		/* The colors of the line above the tab when it is active: */
		var topLineColor = {
			blue:'lightblue',
			green:'lightgreen'
		}
		
		/* Looping through the Tabs object: */
		var z=0;
		var b=0;
		$.each(Tabs,function(i,j){
			/* Sequentially creating the tabs and assigning a color from the array: */
			b++;
			var tmp = $('<li id="litab'+b+'"><a href="#" class="tab '+colors[(z++%2)]+'">'+i+' <span class="left" /><span class="right" /></a></li>');
			
			/* Setting the page data for each hyperlink: */
			tmp.find('a').data('page',j);
			
			/* Adding the tab to the UL container: */
			$('ul.tabContainer').append(tmp);
		})
	
		/* Caching the tabs into a variable for better performance: */
		var the_tabs = $('.tab');
		
		the_tabs.click(function(e){
			/* "this" points to the clicked tab hyperlink: */
			var element = $(this);
			
			/* If it is currently active, return false and exit: */
			if(element.find('#overLine').length) return false;
			
			/* Detecting the color of the tab (it was added to the class attribute in the loop above): */
			var bg = element.attr('class').replace('tab ','');
	
			/* Removing the line: */
			$('#overLine').remove();
			
			/* Creating a new line with jQuery 1.4 by passing a second parameter: */
			$('<div>',{
				id:'overLine',
				css:{
					display:'none',
					width:element.outerWidth()-2,
					background:topLineColor[bg] || 'white'
				}}).appendTo(element).fadeIn('slow');
			
			/* Checking whether the AJAX fetched page has been cached: */
			
			if(!element.data('cache'))
			{	
				/* If no cache is present, show the gif preloader and run an AJAX request: */
				$('#contentHolder').html('<img src="img/ajax_preloader.gif" width="48" height="48" class="preloader" />');
	
				$.get(element.data('page'),{s_page:s_page,querystr:querystr,carBrand:carBrand,carSubBrand:carSubBrand,carModel:carModel,carType:carType,carGasType:carGasType,carGasSystem:carGasSystem,carColor:carColor,carProvince:carProvince,carFirstPrice:carFirstPrice,carSecondPrice:carSecondPrice,carFirstYear:carFirstYear,carSecondYear:carSecondYear,wordSearch:wordSearch},function(msg){
					//msg=msg.replace(/\'/gi,"\\\'");
					$('#contentHolder').html(msg);
					//alert(msg);
					/* After page was received, add it to the cache for the current hyperlink: */
					element.data('cache',msg);
				});
			}
			else
			{
				$('#contentHolder').html(element.data('cache'));
				e.preventDefault();
			}
			
			e.preventDefault();
		})
		
		/* Emulating a click on the first tab so that the content area is not empty: */
		if(type=='image')
		{
			the_tabs.eq(0).click();
		}
		else
		{
			the_tabs.eq(1).click();
		}
		type='image';
		<?php $s_page=0; $target="image"; ?>
}
function getTab1(s_page,querystr,type){
		$('#litab1').remove();
		$('#litab2').remove();
		var carBrand="";
		var carSubBrand="";
		var carModel="";
		var carType="";
		var carGasType="";
		var carGasSystem="";
		var carColor="";
		var carProvince="";
		var wordSearch="";
		
		carBrand=$('#lbxSearchBrandCar1').val();
		carSubBrand=$('#lbxSearchsubBrandCar1').val();
		carModel=$('#lbxSearchModelCar1').val();
		carType=$('#lbxSearchTypeCar1').val();
		carGasType=$('#lbxSearchGasTypeCar1').val();
		carGasSystem=$('#lbxSearchGasSystemCar1').val();
		carColor=$('#lbxSearchColorCar1').val();
		carFirstPrice=$('#tbxSearchCarFirstPrice1').val();
		carSecondPrice=$('#tbxSearchCarSecondPrice1').val();
		carFirstYear=$('#tbxSearchCarFirstYear1').val();
		carSecondYear=$('#tbxSearchCarSecondYear1').val();
		
		var url1='page/showImageResult.php';
		
		//alert('url1 : '+url1+'\r\n url2 : '+url2);
		/* This code is executed after the DOM has been completely loaded */
		
		/* Defining an array with the tab text and AJAX pages: */
		var Tabs = {
			'ผลการค้นหา'	: url1
		}
		
		/* The available colors for the tabs: */
		var colors = ['blue','green'];
		
		/* The colors of the line above the tab when it is active: */
		var topLineColor = {
			blue:'lightblue',
			green:'lightgreen'
		}
		
		/* Looping through the Tabs object: */
		var z=0;
		var b=0;
		$.each(Tabs,function(i,j){
			/* Sequentially creating the tabs and assigning a color from the array: */
			b++;
			var tmp = $('<li id="litab'+b+'"><a href="#" class="tab '+colors[(z++%2)]+'">'+i+' <span class="left" /><span class="right" /></a></li>');
			
			/* Setting the page data for each hyperlink: */
			tmp.find('a').data('page',j);
			
			/* Adding the tab to the UL container: */
			$('ul.tabContainer').append(tmp);
		})
	
		/* Caching the tabs into a variable for better performance: */
		var the_tabs = $('.tab');
		
		the_tabs.click(function(e){
			/* "this" points to the clicked tab hyperlink: */
			var element = $(this);
			
			/* If it is currently active, return false and exit: */
			if(element.find('#overLine').length) return false;
			
			/* Detecting the color of the tab (it was added to the class attribute in the loop above): */
			var bg = element.attr('class').replace('tab ','');
	
			/* Removing the line: */
			$('#overLine').remove();
			
			/* Creating a new line with jQuery 1.4 by passing a second parameter: */
			$('<div>',{
				id:'overLine',
				css:{
					display:'none',
					width:element.outerWidth()-2,
					background:topLineColor[bg] || 'white'
				}}).appendTo(element).fadeIn('slow');
			
			/* Checking whether the AJAX fetched page has been cached: */
			
			if(!element.data('cache'))
			{	
				/* If no cache is present, show the gif preloader and run an AJAX request: */
				$('#contentHolder').html('<img src="img/ajax_preloader.gif" width="48" height="48" class="preloader" />');
	
				$.get(element.data('page'),{s_page:s_page,querystr:querystr,carBrand:carBrand,carSubBrand:carSubBrand,carModel:carModel,carType:carType,carGasType:carGasType,carGasSystem:carGasSystem,carColor:carColor,carProvince:carProvince,carFirstPrice:carFirstPrice,carSecondPrice:carSecondPrice,carFirstYear:carFirstYear,carSecondYear:carSecondYear,wordSearch:wordSearch},function(msg){
					//msg=msg.replace(/\'/gi,"\\\'");
					$('#contentHolder').html(msg);
					//alert(msg);
					/* After page was received, add it to the cache for the current hyperlink: */
					element.data('cache',msg);
				});
			}
			else
			{
				$('#contentHolder').html(element.data('cache'));
				e.preventDefault();
			}
			
			e.preventDefault();
		})
		
		/* Emulating a click on the first tab so that the content area is not empty: */
		if(type=='image')
		{
			the_tabs.eq(0).click();
		}
		else
		{
			the_tabs.eq(1).click();
		}
		type='image';
		<?php $s_page=0; $target="image"; ?>
}
</script>

<script type="text/javascript">

function getSubBrand()
{
	var brandID=$("#lbxSearchBrandCar").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getsubbrand.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"brandID="+brandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;         
        $("#lbxSearchsubBrandCar").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
function getSubBrand1()
{
	var brandID=$("#lbxSearchBrandCar1").val();
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getsubbrand.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"brandID="+brandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;
        $("#lbxSearchsubBrandCar1").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
function getModel()
{
	var subbrandID=$("#lbxSearchsubBrandCar").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getModel.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"subbrandID="+subbrandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText;       
        $("#lbxSearchModelCar").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
function getModel1()
{
	var subbrandID=$("#lbxSearchsubBrandCar1").val();
	//this.options[this.selectedIndex].value;
	
	var datalist2 = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datalist2  
              url: "page/getModel.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
              data:"subbrandID="+subbrandID, // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
              async: false 
        }).responseText; 
        $("#lbxSearchModelCar1").html(datalist2); // นำค่า datalist2 มาแสดงใน listbox ที่ชื่อ IndustypeID
}
</script>
<script type="text/javascript">
function make_blank()
{
document.addNews.tbxYearCar.value ="";
}
</script>
</head>
<body>
	<div id="divheader1"></div>
	<div align="center">
    	<div id="main">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="400" align="center" valign="top" id="content">
                    <!--<div id="quickSearch2" style="cursor:pointer;">ค้นหาเพิ่มเติม<img src="images/button_show.png"></div>-->
                        <div id="content_box5" class="box standard">
                            <div id="quickSearch1" style="cursor:pointer;">ค้นหาแบบรวดเร็ว</div>
                            <form name="frmSearchCar" id="frmSearchCar">
                                <table border="0" cellpadding="0" cellspacing="0" id="tbSearch">
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td class="tdSearchRow1"> ยี่ห้อหลัก :</td>
                                                    <td class="tdSearchRow1"> ยี่ห้อรอง :</td>
                                                  	<td class="tdSearchRow1"> รุ่น : </td>
                                                    <td class="tdSearchRow1"> ประเภทรถ : </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="lbxSearchBrandCar" id="lbxSearchBrandCar" class="tbxWidth160" onChange="getSubBrand()">
                                                          <option value="">ทุกยี่ห้อหลัก</option>
                                                            <?php
                                                                $sql="select * from carsystem.\"productBrand\" order by \"productBrandName\"";
                                                                $dbquery=pg_query($sql);
                                                                while($rs=pg_fetch_assoc($dbquery))
                                                                {
																	if($brand==$rs['productBrandName'])
																	{
                                                                    	echo "<option value=\"".$rs['productBrandID']."\" selected>".$rs['productBrandName']."</option>";
																	}
																	else
																	{
																		echo "<option value=\"".$rs['productBrandID']."\">".$rs['productBrandName']."</option>";
																	}
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchsubBrandCar" id="lbxSearchsubBrandCar" class="tbxWidth160" onChange="getModel()">
                                                        <option value="">ทุกยี่ห้อรอง</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchModelCar" id="lbxSearchModelCar" class="tbxWidth160">
                                                        <option value="">ทุกรุ่น</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchTypeCar" id="lbxSearchTypeCar" class="tbxWidth160">
                                                            <option value="">ทุกประเภท</option>
                                                            <?php
                                                            	if($cartype=="ป้ายแดง")
																{
																	echo "<option value=\"ป้ายแดง\" selected>ป้ายแดง</option>";
																}
																else
																{
																	echo "<option value=\"ป้ายแดง\">ป้ายแดง</option>";
																}
																if($cartype=="มือสอง")
																{
																	echo "<option value=\"มือสอง\" selected>มือสอง</option>";
																}
																else
																{
																	echo "<option value=\"มือสอง\">มือสอง</option>";
																}
															?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td class="tdSearchRow1">ประเภทแก๊ส : </td>
                                                    <td class="tdSearchRow1">ระบบแก๊ส : </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="lbxSearchGasTypeCar" id="lbxSearchGasTypeCar" class="tbxWidth2Col">
                                                          <option value="">ทุกประเภท</option>
                                                          <option value="NGV">NGV</option>
                                                          <option value="LPG">LPG</option>
                                                            <?php
                                                                
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchGasSystemCar" id="lbxSearchGasSystemCar" class="tbxWidth2Col">
                                                          <option value="">ทุกระบบ</option>
                                                          <option value="ระบบฉีด">ระบบฉีด</option>
                                                          <option value="ระบบดูด">ระบบดูด</option>
                                                            <?php
                                                                
                                                            ?>
                                                  </select></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td class="tdSearchRow1">สีรถ : </td>
                                                    <td class="tdSearchRow1">จังหวัด : </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="lbxSearchColorCar" id="lbxSearchColorCar" class="tbxWidth2Col">
                                                          <option selected value="">ทุกสี</option>
															<?php
                                                            if($carcolor=="สีฟ้า")
															{
																echo "<option value=\"สีฟ้า\" selected>สีฟ้า</option>";
															}
															else
															{
																echo "<option value=\"สีฟ้า\">สีฟ้า</option>";
															}
															if($carcolor=="สีชมพู")
															{
																echo "<option value=\"สีชมพู\" selected>สีชมพู</option>";
															}
															else
															{
																echo "<option value=\"สีชมพู\">สีชมพู</option>";
															}
															if($carcolor=="สีส้ม")
															{
																echo "<option value=\"สีส้ม\" selected>สีส้ม</option>";
															}
															else
															{
																echo "<option value=\"สีส้ม\">สีส้ม</option>";
															}
															if($carcolor=="สีเขียวเหลือง")
															{
																echo "<option value=\"สีเขียวเหลือง\" selected>สีเขียวเหลือง</option>";
															}
															else
															{
																echo "<option value=\"สีเขียวเหลือง\">สีเขียวเหลือง</option>";
															}
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchProvinceCar" id="lbxSearchProvinceCar" class="tbxWidth2Col">
                                                          <option selected value="">ทุกจังหวัด</option>
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
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td class="tdSearchRow1">อัตราผ่อนชำระ : </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="tdSearchRow1">ปีรถ : </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarFirstPrice" id="tbxSearchCarFirstPrice" class="tbxBetween" value="<?php echo $startPrice; ?>">
                                                    </td>
                                                    <td class="tdTo">
                                                        ถึง 
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarSecondPrice" id="tbxSearchCarSecondPrice" class="tbxBetween" value="<?php echo $endPrice; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarFirstYear" id="tbxSearchCarFirstYear" class="tbxBetween">
                                                    </td>
                                                    <td class="tdTo">
                                                        ถึง 
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarSecondYear" id="tbxSearchCarSecondYear" class="tbxBetween">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td colspan="2" class="tdSearchRow1">คำค้น : </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" name="tbxSearchCar" id="tbxSearchCar"></td>
                                                    <td>
                                                        <!--<input type="image" id="searchCarButton" name="searchCarButton" src="images/search-button.gif" onClick="getTab()">-->
                                                        <img src="images/search1.png" id="btnSearch" onClick="getTab(<?php echo "'".$s_page."'"; ?>,<?php echo "'".$querystr."'"; ?>,<?php echo "'".$target."'"; ?>)" style="cursor:pointer;" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div id="content_box5_1" class="box standard">
                        <div id="quickSearch" style="cursor:pointer;">ค้นหาแบบละเอียด</div>
                            <form name="frmSearchCar1" id="frmSearchCar1">
                                <table border="0" cellpadding="0" cellspacing="0" id="tbSearch1">
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5" id="tbFirstSearch">
                                                <tr>
                                                    <td class="tdSearchRow2"> ยี่ห้อหลัก :</td>
                                                    <td class="tdSearchRow2"> ยี่ห้อรอง :</td>
                                                    <td class="tdSearchRow2"> รุ่น : </td>
                                                    <td class="tdSearchRow2"> ประเภทรถ : </td>
                                                    <td class="tdSearchRow2"> สีรถ : </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="lbxSearchBrandCar1" id="lbxSearchBrandCar1" class="tbxWidth_2" onChange="getSubBrand1()">
                                                          <option value="">ทุกยี่ห้อหลัก</option>
                                                            <?php
                                                                $sql="select * from carsystem.\"productBrand\" order by \"productBrandName\"";
                                                                $dbquery=pg_query($sql);
                                                                while($rs=pg_fetch_assoc($dbquery))
                                                                {
                                                                    if($brand==$rs['productBrandName'])
																	{
                                                                    	echo "<option value=\"".$rs['productBrandID']."\" selected>".$rs['productBrandName']."</option>";
																	}
																	else
																	{
																		echo "<option value=\"".$rs['productBrandID']."\">".$rs['productBrandName']."</option>";
																	}
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchsubBrandCar1" id="lbxSearchsubBrandCar1" class="tbxWidth_2" onChange="getModel1()">
                                                        <option value="">ทุกยี่ห้อรอง</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchModelCar1" id="lbxSearchModelCar1" class="tbxWidth_2">
                                                          <option value="">ทุกรุ่น</option>
                                                            
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchTypeCar1" id="lbxSearchTypeCar1" class="tbxWidth_2">
                                                            <option value="">ทุกประเภท</option>
                                                            <?php
                                                            	if($cartype=="ป้ายแดง")
																{
																	echo "<option value=\"ป้ายแดง\" selected>ป้ายแดง</option>";
																}
																else
																{
																	echo "<option value=\"ป้ายแดง\">ป้ายแดง</option>";
																}
																if($cartype=="มือสอง")
																{
																	echo "<option value=\"มือสอง\" selected>มือสอง</option>";
																}
																else
																{
																	echo "<option value=\"มือสอง\">มือสอง</option>";
																}
															?>
                                                        </select>
                                                    </td><td>
                                                        <select name="lbxSearchColorCar1" id="lbxSearchColorCar1" class="tbxWidth_2">
                                                          <option selected value="">ทุกสี</option>
                                                          <?php
                                                            if($carcolor=="สีฟ้า")
															{
																echo "<option value=\"สีฟ้า\" selected>สีฟ้า</option>";
															}
															else
															{
																echo "<option value=\"สีฟ้า\">สีฟ้า</option>";
															}
															if($carcolor=="สีชมพู")
															{
																echo "<option value=\"สีชมพู\" selected>สีชมพู</option>";
															}
															else
															{
																echo "<option value=\"สีชมพู\">สีชมพู</option>";
															}
															if($carcolor=="สีส้ม")
															{
																echo "<option value=\"สีส้ม\" selected>สีส้ม</option>";
															}
															else
															{
																echo "<option value=\"สีส้ม\">สีส้ม</option>";
															}
															if($carcolor=="สีเขียวเหลือง")
															{
																echo "<option value=\"สีเขียวเหลือง\" selected>สีเขียวเหลือง</option>";
															}
															else
															{
																echo "<option value=\"สีเขียวเหลือง\">สีเขียวเหลือง</option>";
															}
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="5">
                                                <tr>
                                                    <td class="tdSearchRow1">ประเภทแก๊ส : </td>
                                                    <td class="tdSearchRow1">ระบบแก๊ส : </td>
                                                    <td class="tdSearchRow1">อัตราผ่อนชำระ : </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="tdSearchRow1">ปีรถ : </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="lbxSearchGasTypeCar1" id="lbxSearchGasTypeCar1" class="tbxWidth_2Col">
                                                          <option value="">ทุกประเภท</option>
                                                          <option value="NGV">NGV</option>
                                                          <option value="LPG">LPG</option>
                                                            <?php
                                                                
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lbxSearchGasSystemCar1" id="lbxSearchGasSystemCar1" class="tbxWidth_2Col">
                                                          <option value="">ทุกระบบ</option>
                                                          <option value="ระบบฉีด">ระบบฉีด</option>
                                                          <option value="ระบบดูด">ระบบดูด</option>
                                                            <?php
                                                                
                                                            ?>
                                                  </select></td>
                                                  <td>
                                                        <input type="text" name="tbxSearchCarFirstPrice1" id="tbxSearchCarFirstPrice1" class="tbxBetween_2" value="<?php echo $startPrice; ?>">
                                                    </td>
                                                    <td class="tdTo">
                                                        ถึง 
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarSecondPrice1" id="tbxSearchCarSecondPrice1" class="tbxBetween_2" value="<?php echo $endPrice; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarFirstYear1" id="tbxSearchCarFirstYear1" class="tbxBetween_2">
                                                    </td>
                                                    <td class="tdTo">
                                                        ถึง 
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tbxSearchCarSecondYear1" id="tbxSearchCarSecondYear1" class="tbxBetween_2">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td id="tdSearchCar"><img src="images/search.png" name="btnSearch1" width="100" height="29" id="btnSearch1" style="cursor:pointer;" onClick="getTab1(<?php echo "'".$s_page."'"; ?>,<?php echo "'".$querystr."'"; ?>,<?php echo "'".$target."'"; ?>)">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div id="divSearchResult">
                            <div id="mainBorder">
                                <ul class="tabContainer">
                                <!-- The jQuery generated tabs go here -->
                                </ul>
                                <div class="clear"></div>
                                <div id="tabContent">
                                    <div id="contentHolder">
                                        <!-- The AJAX fetched content goes here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
	</div>
    <div id="divfooter1"></div>
<?php
include("fix_menu.php");
?>
</body>
</html>