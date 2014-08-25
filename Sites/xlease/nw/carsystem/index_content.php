<?php
	include("../../config/config.php");
	$sql="select * from carsystem.\"TrPostSell\" order by \"carSellID\" desc limit 5 offset 0";
	$dbquery=pg_query($sql);
	$linkID = array();
	$pic = array();
	$data1 = array();
	$data2 = array();
	$year = array();
	$m=0;
	
	while($rs=pg_fetch_assoc($dbquery))
	{
		if($m<5)
		{
			$id=$rs['carSellID'];
			$carBrand=$rs['carBrand'];
			$carSubBrand=$rs['carSubBrand'];
			$carModel=$rs['carModel'];
			$carYear=$rs['carYear'];
			$picpath=$rs['thumnailsImagePath'];
			
			$files = array_diff(scandir($picpath),array(".",".."));
			
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
			$linkID[$m]=$id;
			$size = sizeof($files);
			if($size!=0)
			{
				$pic[$m]=$picpath.$files[2];
			}
			else
			{
				$pic[$m]="images/404.png";
			}
			$data1[$m]=$brandName;
			$data2[$m]=$subBrandName." ".$modelName;
			$year[$m]=$carYear;
			$m++;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Untitled Document</title>
<link href="css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#li1').hover(function(){
		$('#span1').css('color','#333333');
		$(this).css('background-color','#ffd200');
		$(this).css('cursor','pointer');
	},function(){
		$('#span1').css('color','#1e6079');
		$(this).css('background-color','#ffffff');
	});	
	$('#li2').hover(function(){
		$('#span2').css('color','#333333');
		$(this).css('background-color','#ffd200');
		$(this).css('cursor','pointer');
	},function(){
		$('#span2').css('color','#1e6079');
		$(this).css('background-color','#ffffff');
	});	
	$('#li3').hover(function(){
		$('#span3').css('color','#333333');
		$(this).css('background-color','#ffd200');
		$(this).css('cursor','pointer');
	},function(){
		$('#span3').css('color','#1e6079');
		$(this).css('background-color','#ffffff');
	});	
	$('#li4').hover(function(){
		$('#span4').css('color','#333333');
		$(this).css('background-color','#ffd200');
		$(this).css('cursor','pointer');
	},function(){
		$('#span4').css('color','#1e6079');
		$(this).css('background-color','#ffffff');
	});	
	$('#li5').hover(function(){
		$('#span5').css('color','#333333');
		$(this).css('background-color','#ffd200');
		$(this).css('cursor','pointer');
	},function(){
		$('#span5').css('color','#1e6079');
		$(this).css('background-color','#ffffff');
	});	
});
</script>
</head>

<body>
<div id="divbordycontrainer">
    <div align="center">
    	<div id="divbanner">800x120</div>
        <table border="0" cellpadding="0" cellspacing="0" width="800">
        	<tr>
            	<td width="580" align="left">
                    <div id="divshowcar">
                        <ul id="ulmainshowcar">
                          <li>
                                <ul id="ulshowcarrow1">
                                    <li>
                                      
                                    </li>
                                    <li id="li1" onClick="window.open('product_detail.php?id=<?php echo $linkID[0]; ?>');">
                                        <span id="span1"><?php echo $year[0]; ?></span>
                                        <h1><?php echo $data1[0]; ?><br><?php echo $data2[0]; ?></h1>
                                        <img src="<?php echo $pic[0]; ?>" width="171" height="121">
                                    </li>
                                    <li id="li2" onClick="window.open('product_detail.php?id=<?php echo $linkID[1]; ?>');">
                                        <span id="span2"><?php echo $year[1]; ?></span>
                                      <h1><?php echo $data1[1]; ?><br><?php echo $data2[1]; ?></h1>
                                        <img src="<?php echo $pic[1]; ?>" width="171" height="121">
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <ul id="ulshowcarrow2">
                                    <li id="li3" onClick="window.open('product_detail.php?id=<?php echo $linkID[2]; ?>');">
                                        <span id="span3"><?php echo $year[2]; ?></span>
                                        <h1><?php echo $data1[2]; ?><br><?php echo $data2[2]; ?></h1>
                                        <img src="<?php echo $pic[2]; ?>" width="171" height="121">
                                    </li>
                                    <li id="li4" onClick="window.open('product_detail.php?id=<?php echo $linkID[3]; ?>');">
                                        <span id="span4"><?php echo $year[3]; ?></span>
                                        <h1><?php echo $data1[3]; ?><br><?php echo $data2[3]; ?></h1>
                                        <img src="<?php echo $pic[3]; ?>" width="171" height="121">
                                    </li>
                                    <li id="li5" onClick="window.open('product_detail.php?id=<?php echo $linkID[4]; ?>');">
                                        <span id="span5"><?php echo $year[4]; ?></span>
                                        <h1><?php echo $data1[4]; ?><br><?php echo $data2[4]; ?></h1>
                                        <img src="<?php echo $pic[4]; ?>" width="171" height="121">
                                    </li>
                              </ul>
                          </li>
                        </ul>
                    </div>
                </td>
                <td width="220" align="left" valign="top">
                	<div id="searchmenu">
                    <form name="quicksearch" id="quicksearch" action="showproduct.php" method="get">
                        <table id="tbsearchmenuform" border="0" cellpadding="0" cellspacing="5">
                            <tr>
                                <td align="right">ยี่ห้อหลัก :</td>
                                <td>
                                    <select name="brand" id="tbxsearch_brand" class="searchtextboxstyle">
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
                            </tr>
                            <tr>
                                <td align="right">สีรถ :</td>
                                <td>
                                    <select name="carcolor" id="lbxSearchsubBrandCar1" class="searchtextboxstyle">
                                        <option value="สีฟ้า">สีฟ้า</option>
                                        <option value="สีชมพู">สีชมพู</option>
                                        <option value="สีขาว">สีขาว</option>
                                        <option value="สีเขียวเหลือง">สีเขียวเหลือง</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">ประเภท :</td>
                                <td>
                                    <select name="cartype" id="tbxsearch_type" class="searchtextboxstyle">
                                        <option value="ป้ายแดง">ป้ายแดง</option>
                                        <option value="มือสอง">มือสอง</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">ราคาผ่อน :</td>
                                <td>
                                    <select name="carprice" id="carprice" class="searchtextboxstyle">
                                        <option value="0_10000">น้อยกว่า 10,000 บาท</option>
                                        <option value="10000_25000">10,000-25,000 บาท</option>
                                        <option value="25000_40000">25,000-40,000 บาท</option>
                                        <option value="40000_55000">40,000-55,000 บาท</option>
                                        <option value="55000_1000000">55,000 บาทขึ้นไป</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td height="10"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="submit" id="btnformsearchsubmit" name="btnformsearchsubmit" value="ค้นหา">
                                </td>
        
                            </tr>
                            <tr>
                                <td height="5"></td>
                                <td></td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
	</div>
</div>
</body>
</html>