<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript">
	function addClass(stringClass)
	{
		//alert(stringClass);
		document.getElementById(stringClass).className='divEachResult activeResult';
	}
	function removeClass(stringClass1)
	{
		document.getElementById(stringClass1).className='divEachResult';
	}
</script>
<?php
	include("../../config/config.php");
	$carBrand=$_GET['carBrand'];
	if($carBrand=="ทุกยี่ห้อ")
	{
		$carBrand="";
	}
	$carModel=$_GET['carModel'];
		if($carModel=="ทุกรุ่น")
	{
		$carModel="";
	}
	$carType=$_GET['carType'];
		if($carType=="ทุกประเภท")
	{
		$carType="";
	}
	$carGasType=$_GET['carGasType'];
		if($carGasType=="ทุกประเภท")
	{
		$carGasType="";
	}
	$carGasSystem=$_GET['carGasSystem'];
		if($carGasSystem=="ทุกระบบ")
	{
		$carGasSystem="";
	}
	$carColor=$_GET['carColor'];
		if($carColor=="ทุกสี")
	{
		$carColor="";
	}
	$carProvince=$_GET['carProvince'];
		if($carProvince=="ทุกจังหวัด")
	{
		$carProvince="";
	}
	$wordSearch=$_GET['wordSearch'];
	$carFirstPrice=$_GET['carFirstPrice'];
	$carSecondPrice=$_GET['carSecondPrice'];
	$carFirstYear=$_GET['carFirstYear'];
	$carSecondYear=$_GET['carSecondYear'];
	if($carFirstPrice=="" || $carSecondPrice=="")
	{
		if($carFirstYear=="" || $carSecondYear=="")
		{
			$sql="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
		else
		{
			$sql="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carYear\" between '$carFirstYear' and '$carSecondYear' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
	}
	else
	{
		if($carFirstYear=="" || $carSecondYear=="")
		{
			$sql="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carPrice\" between '$carFirstPrice' and '$carSecondPrice' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
		else
		{
			$sql="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carPrice\" between '$carFirstPrice' and '$carSecondPrice' and \"carYear\" between '$carFirstYear' and '$carSecondYear' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
	}
	
	$dbquery=pg_query($sql);
	
	$resultRows=pg_num_rows($dbquery);
	$i=1;
	echo "<div id=\"divImageResult\">";
  	echo "<div id=\"divNumResult\"><div id=\"divNumResultText\"><span>ผลการค้นหาทั้งหมด : $resultRows รายการ</span></div></div>";
	while($rs=pg_fetch_assoc($dbquery))
	{
		$id=$rs['carSellID'];
		$carBrand=$rs['carBrand'];
		$carModel=$rs['carModel'];
		$carType=$rs['carType'];
		$carYear=$rs['carYear'];
		$gasType=$rs['gasType'];
		$gasSystem=$rs['gasSystem'];
		$gasTankSize=$rs['gasTankSize'];
		$liveProvince=$rs['liveProvince'];
		$postDetail=$rs['postDetail'];
		$carColor=$rs['carColor'];
		$carPrice=$rs['carPrice'];
		
		$sql1="select * from \"TrCarBrand\" where \"brandID\"='$carBrand'";
		$dbquery1=pg_query($sql1);
		$rs1=pg_fetch_assoc($dbquery1);
		$brandName=$rs1['brandName'];
		
		$sql2="select * from \"TrCarModel\" where \"modelID\"='$carModel'";
		$dbquery2=pg_query($sql2);
		$rs2=pg_fetch_assoc($dbquery2);
		$modelName=$rs2['modelName'];
		
		echo"<div id=\"divEachResult$i\" class=\"divEachResult\" onClick=\"window.open('detailSellcar.php?id=$id','_blank');\" onmouseover=\"addClass(id);\" onmouseout=\"removeClass(id);\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"680\">";
        echo"<tr>";
        echo"<td rowspan=\"6\" valign=\"top\">";
        echo"<div id=\"divImageExam\">";
        echo"<div id=\"divExamText\">ภาพตัวอย่าง</div>";
        echo"<div id=\"divExamImage\"><img src=\"taxi/00049756.jpg\" id=\"taxiPreview\" /></div>";
        echo"</div>";
        echo"</td>";
        echo"<td></td>";
        echo"<td></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td colspan=\"2\" id=\"tdPostSellText\">$postDetail</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ยี่ห้อ : </span><span>$brandName</span><span> รุ่น : </span><span>$modelName</span><span> สี : </span><span>$carColor</span></td>";
        echo"<td rowspan=\"4\" valign=\"top\" id=\"tdPricePerMonth\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\">";
        echo"<tr>";
        echo"<td class=\"postSellDetail1\"><span>ผ่อน : </span><span>$carPrice บาท/เดือน</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail2\"><span>ติดต่อ : </span><span>บริษัท ไทยเอซ จำกัด</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail2\"><span>เบอร์โทรศัพท์ : </span><span>0-2944-2000</span></td>";
        echo"</tr>";
        echo"</table>";
        echo"</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ประเภท : </span><span>$carType</span><span> ปีที่ผลิต : </span><span>$carYear</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ประเภทแก๊ส : </span><span>$gasType</span><span> ระบบแก๊ส : </span><span>$gasSystem</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ขนาดถังแก๊ส : </span><span>$gasTankSize</span><span> จังหวัด : </span><span>$liveProvince</span></td>";
        echo"</tr>";
        echo"</table>";
		echo"</div>";
		$i++;
	}
	echo"</div>";
	echo $sql;
?>
<style type="text/css">
@charset "UTF-8";
body {
	margin: 0px;
	padding: 0px;
}
#divImageResult {
	width: 680px;
}
#divNumResult {
	height: 35px;
	width: 250px;
	border: 1px solid #ccc;
	background-color: #eee;
	padding-top: 3px;
	padding-bottom: 1px;
	padding-left: 2px;
	float: right;
	padding-right: 2px;
}
#divNumResultText {
	background-color: #fff;
	border: 1px dotted #ddd;
	text-align: left;
	vertical-align: middle;
	height: 31px;
	width: 246px;
}
#divNumResultText span {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 15px;
	line-height: 31px;
	font-weight: bold;
	color: #555;
	text-decoration: none;
	padding-left: 5px;
}
.divEachResult.activeResult {
	background-color:transparent;
	background-image:-moz-linear-gradient(center top, rgb(255,255,255) 0px, rgb(245,245,245) 100%);
	background-repeat:repeat;
	background-attachment:scroll;
	background-position:0% 0%;
	background-clip:border-box;
	background-origin:padding-box;
	background-size:auto auto;
	cursor:pointer;
}
.divEachResult {
	float:left;
	padding-top:12px;
	padding-right:9px;
	padding-bottom:10px;
	padding-left:2px;
	padding-left-rtl-source:physical;
	padding-left-rtl-source:physical;
	padding-right-rtl-source:physical;
	padding-right-rtl-source:physical;
	width::670px;
	border-bottom-width:1px;
	border-bottom-style:dotted;
	border-bottom-color:rgb(170,170,170);
}
#divEachResult {
	margin-top: 20px;
}
#divExamText {
	background-image: url(images/camera.png);
	background-repeat: no-repeat;
	background-position: left top;
	height: 25px;
	width: 150px;
	text-decoration: none;
	padding-left: 20px;
	font-size: 13px;
	font-weight: bold;
	color: #333;
}
#taxiPreview {
	padding: 2px;
	border: 1px solid #aaa;
}
#tdPostSellText {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 15px;
	color: #0064c8;
	text-decoration: none;
	font-weight: bold;
}
.postSellDetail {
	font-family: Tahoma, arial, Helvetica,sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #333;
	text-decoration: none;
}
.postSellDetail1 {
	font-family: Tahoma, arial, Helvetica,sans-serif;
	font-size: 15px;
	font-weight: bold;
	color: #f89c00;
	text-decoration: none;
	padding-bottom: 15px;
}
.postSellDetail2 {
	font-family: Tahoma, arial, Helvetica,sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #333;
	text-decoration: none;
	padding-bottom: 5px;
}
#tdPricePerMonth {
	padding-left: 5px;
}
</style>

<div id="divImageResult">
  <div id="divNumResult"><div id="divNumResultText"><span>ผลการค้นหาทั้งหมด : 1 รายการ</span></div></div>
    <div id="divEachResult" class="divEachResult" onClick="window.open('detailSellcar.php?id=','_blank');" onmouseover="addClass(divEachResult$i);" onmouseout="removeClass(divEachResult$i);">
        <table border="0" cellpadding="0" cellspacing="3" width="680">
            <tr>
                <td rowspan="6" valign="top">
           	    <div id="divImageExam">
                    	<div id="divExamText">ภาพตัวอย่าง</div>
                  <div id="divExamImage"><img src="taxi/00049756.jpg" id="taxiPreview" /></div>
                    </div>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" id="tdPostSellText">ขายรถแท็กซี่มือสองสภาพใหม่ ไม่เคยเกิดอุบัติเหตุ สนใจติดต่อบริษัทไทยเอซ</td>
            </tr>
            <tr>
              <td class="postSellDetail"><span>ยี่ห้อ : </span><span>TOYOTA</span><span> รุ่น : </span><span>VX-111</span><span> สี : </span><span>ฟ้า</span></td>
                <td rowspan="4" align="right" valign="top" id="tdPricePerMonth">
                	<p id="tdPricePerMonth">&nbsp;</p>
                	<table border="0" cellpadding="0" cellspacing="3">
                    	<tr>
                        	<td class="postSellDetail1"><span>ผ่อน : </span><span>10,000 บาท/เดือน</span></td>
                        </tr>
                        <tr>
                        	<td class="postSellDetail"><span>ติดต่อ : </span><span>บริษัท ไทยเอซ จำกัด</span></td>
                        </tr>
                        <tr>
                        	<td class="postSellDetail"><span>เบอร์โทรศัพท์ : </span><span>0-2944-2000</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="postSellDetail"><span>ประเภท : </span><span>มือสอง</span><span> ปีที่ผลิต : </span><span>2005</span></td>
            </tr>
            <tr>
                <td class="postSellDetail"><span>ประเภทแก๊ส : </span><span>NGV</span><span> ระบบแก๊ส : </span><span>ระบบฉีด</span></td>
            </tr>
            <tr>
                <td class="postSellDetail"><span>ขนาดถังแก๊ส : </span><span>57 ลิตร</span><span> จังหวัด : </span><span>กรุงเทพมหานคร</span></td>
            </tr>
        </table>
	</div>
</div>