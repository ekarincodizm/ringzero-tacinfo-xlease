<?php
	echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=9\" />";
	echo "<script type=\"text/javascript\">";
	echo "function addClass(stringClass)";
	echo "{";
	echo "document.getElementById(stringClass).className='divEachResult activeResult';";
	echo "}";
	echo "function removeClass(stringClass1)";
	echo "{";
	echo "document.getElementById(stringClass1).className='divEachResult';";
	echo "}";
	echo "</script>";
	include("../../../config/config.php");
	$carBrand=$_GET["carBrand"];
	$carSubBrand=$_GET["carSubBrand"];
	$carModel=$_GET["carModel"];
	$carType=$_GET["carType"];
	$carGasType=$_GET["carGasType"];
	$carGasSystem=$_GET["carGasSystem"];
	$carColor=$_GET["carColor"];
	$carProvince=$_GET["carProvince"];
	$wordSearch=$_GET["wordSearch"];
	$carFirstPrice=$_GET["carFirstPrice"];
	$carSecondPrice=$_GET["carSecondPrice"];
	$carFirstYear=$_GET["carFirstYear"];
	$carSecondYear=$_GET["carSecondYear"];
	//echo $carSubBrand;
	// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
	function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
		global $e_page;
		global $querystr;
		$urlfile="showproduct.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
		$per_page=10;
		$num_per_page=floor($chk_page/$per_page);
		$total_end_p=($num_per_page+1)*$per_page;
		$total_start_p=$total_end_p-$per_page;
		$pPrev=$chk_page-1;
		$pPrev=($pPrev>=0)?$pPrev:0;
		$pNext=$chk_page+1;
		$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
		$lt_page=$total_p-4;
		if($chk_page>0){  
			echo "<a  href='$urlfile?type=order&s_page=$pPrev&querystr=".$querystr."' class='naviPN'>ก่อนหน้า</a>";
		}
		for($i=$total_start_p;$i<$total_end_p;$i++){  
			$nClass=($chk_page==$i)?"class='selectPage'":"";
			if($e_page*$i<$total){
			echo "<a href='$urlfile?type=order&s_page=$i&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
			}
		}		
		if($chk_page<$total_p-1){
			echo "<a href='$urlfile?type=order&s_page=$pNext&querystr=".$querystr."'  class='naviPN'>ถัดไป</a>";
		}
	}   
	if($wordSearch=="")
	{
		if($carFirstPrice=="" || $carSecondPrice=="")
		{
			if($carFirstYear=="" || $carSecondYear=="")
			{
				$q="select * from carsystem.\"TrPostSell\" where cast(\"carBrand\" as character varying) like '%$carBrand%' and cast(\"carSubBrand\" as character varying) like '%$carSubBrand%' and cast(\"carModel\" as character varying) like '%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%'";
			}
			else
			{
				$q="select * from carsystem.\"TrPostSell\" where cast(\"carBrand\" as character varying) like '%$carBrand%' and cast(\"carSubBrand\" as character varying) like '%$carSubBrand%' and cast(\"carModel\" as character varying) like '%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carYear\" between '$carFirstYear' and '$carSecondYear'";
			}
		}
		else
		{
			if($carFirstYear=="" || $carSecondYear=="")
			{
				$q="select * from carsystem.\"TrPostSell\" where cast(\"carBrand\" as character varying) like '%$carBrand%' and cast(\"carSubBrand\" as character varying) like '%$carSubBrand%' and cast(\"carModel\" as character varying) like '%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carInstallment\" between '$carFirstPrice' and '$carSecondPrice'";
			}
			else
			{
				$q="select * from carsystem.\"TrPostSell\" where cast(\"carBrand\" as character varying) like '%$carBrand%' and cast(\"carSubBrand\" as character varying) like '%$carSubBrand%' and cast(\"carModel\" as character varying) like '%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carInstallment\" between '$carFirstPrice' and '$carSecondPrice' and \"carYear\" between '$carFirstYear' and '$carSecondYear'";
			}
		}
	}
	else
	{
		$q="select * from carsystem.\"TrPostSell\" where \"carType\" like '%$carYear%' or \"gasType\" like '%$gasSystem%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$carColor%' or \"carInstallment\" like '%$wordSearch%'";
	}
	$q.=" order by \"carSellID\" desc";
	$qr=pg_query($q);
	$total=pg_num_rows($qr);
	$resultRows=pg_num_rows($qr);
	$e_page=10; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
	if(!isset($_GET['s_page'])){   
		$_GET['s_page']=0;   
	}else{   
		$chk_page=$_GET['s_page'];     
		$_GET['s_page']=$_GET['s_page']*$e_page;   
	}   
	$q.=" LIMIT $e_page offset ".$_GET['s_page'];
	$qr=pg_query($q);
	if(pg_num_rows($qr)>=1){   
		$plus_p=($chk_page*$e_page)+pg_num_rows($qr);   
	}else{   
		$plus_p=($chk_page*$e_page);       
	}   
	$total_p=ceil($total/$e_page);   
	$before_p=($chk_page*$e_page)+1;
	
	$i=1;
	echo "<div id=\"divImageResult\">";
  	echo "<div id=\"divNumResult\"><div id=\"divNumResultText\"><span>ผลการค้นหาทั้งหมด : $resultRows รายการ</span></div></div>";
	while($rs=pg_fetch_assoc($qr))
	{
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
		$picpath=$rs['picPath'];
		$carPrice=$rs['carprice'];
		$poster=$rs['poster'];
		
		$sql1="select * from carsystem.\"productBrand\" where \"productBrandID\"='$carBrand'";
		$dbquery1=pg_query($sql1);
		$rs1=pg_fetch_assoc($dbquery1);
		$brandName=$rs1['productBrandName'];
		
		$sql3="select * from carsystem.\"productSubBrand\" where \"productSubBrandID\"='$carSubBrand'";
		$dbquery3=pg_query($sql3);
		$rs1=pg_fetch_assoc($dbquery3);
		$subBrandName=$rs3['productSubBrandName'];
		
		$sql2="select * from carsystem.\"productModel\" where \"productModelID\"='$carModel'";
		$dbquery2=pg_query($sql2);
		$rs2=pg_fetch_assoc($dbquery2);
		$modelName=$rs2['productModelName'];
		
		$sql4="select * from carsystem.\"members\" where \"memberID\"='$poster'";
		$dbquery4=pg_query($sql4);
		$rs4=pg_fetch_assoc($dbquery4);
		$showname=$rs4['showname'];
		$mobile=$rs4['mobilephone'];
		
		echo"<div id=\"divEachResult$i\" class=\"divEachResult\" onClick=\"window.open('product_detail.php?id=$id','_blank');\" onmouseover=\"addClass(id);\" onmouseout=\"removeClass(id);\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" width=\"680\">";
        echo"<tr>";
        echo"<td></td>";
        echo"<td rowspan=\"4\" align=\"right\" valign=\"top\" id=\"tdPricePerMonth1\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"100%\">";
        echo"<tr>";
        echo"<td class=\"postSellDetail1\"><span>ผ่อน : </span><span>".number_format($carInstallment,2,".",",")." บาท/เดือน</span></td>";
        echo"</tr>";
		if($carPrice!=0 || $carPrice!="")
		{
			echo"<tr>";
			echo"<td class=\"postSellDetail3\"><span>ราคา : </span><span>".number_format($carPrice,2,".",",")." บาท</span></td>";
			echo"</tr>";
		}
        echo"</table>";
        echo"</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td id=\"tdPostSellText\">$postDetail</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ยี่ห้อ : </span><span class=\"highlightInfo\">$brandName $subBrandName</span><span> รุ่น : </span><span class=\"highlightInfo\">$modelName</span><span> สี : </span><span class=\"highlightInfo\">$carColor</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ติดต่อ : </span><span class=\"highlightInfo1\">$showname</span><span> เบอร์โทรศัพท์ : </span><span class=\"highlightInfo1\">$mobile</span></td>";
        echo"</tr>";
        echo"</table>";
		echo"</div>";
		$i++;
	}
	echo"</div>";
	//echo $sql;
	if($total>0){
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\" id=\"tbNavigate\">";
    echo "<tr>";
    echo "<td align=\"center\" valign=\"middle\" id=\"navigate\">";
    echo "<div class=\"browse_page\">";
    page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);    
    echo "</div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";
}
?>
<style type="text/css">
@charset "UTF-8";
body {
	margin: 0px;
	padding: 0px;
}
#divImageResult {
	width: 700px;
}
#divNumResultText {
	float: right;
}
#divNumResultText span {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
	font-weight: normal;
	color: #555;
	text-decoration: none;
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
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f5f5f5)";
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
	height: 20px;
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
	vertical-align: top;
	width: 200px;
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
.highlightInfo {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #0064b8;
	text-decoration: none;
}
.highlightInfo1 {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #cc0000;
	text-decoration: none;
}
#contentHolder{
	background-color:#FFFFFF;
	border:2px solid #FFFFFF;
	margin:15px;
	color:#444444;
	padding:5px;
	overflow:auto;
}
</style>