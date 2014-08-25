<?php
	include("../../../config/config.php");
	echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=9\" />";
	echo "<script src=\"js/image_preview.js\" type=\"text/javascript\"></script>";
	echo "<script type=\"text/javascript\" src=\"script/jquery-1.7.2.min.js\"></script>";
	echo "<script type=\"text/javascript\">";
	echo "function addClass(stringClass)";
	echo "{";
	echo "document.getElementById(stringClass).className=\"divEachResult activeResult\";";
	echo "}";
	echo "function removeClass(stringClass1)";
	echo "{";
	echo "document.getElementById(stringClass1).className=\"divEachResult\";";
	echo "}";
	echo "</script>";
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
			echo "<a  href=\"$urlfile?type=image&s_page=$pPrev&querystr=".$querystr."\" class=\"naviPN\">ก่อนหน้า</a>";
		}
		for($i=$total_start_p;$i<$total_end_p;$i++){  
			$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
			if($e_page*$i<$total){
			echo "<a href=\"$urlfile?type=image&s_page=$i&querystr=".$querystr."\" $nClass  >".intval($i+1)."</a> ";   
			}
		}		
		if($chk_page<$total_p-1){
			echo "<a href=\"$urlfile?type=image&s_page=$pNext&querystr=".$querystr."\"  class='naviPN'>ถัดไป</a>";
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
	//echo $q;
	echo "<div id=\"divImageResult\">";
  	echo "<div id=\"divNumResultText\"><span>ผลการค้นหาทั้งหมด : $resultRows รายการ</span></div>";
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
		$picpath=$rs['thumnailsImagePath'];
		$picpath1=$rs['cropImagePath'];
		$carPrice=$rs['carprice'];
		$poster=$rs['poster'];
		$installmentMonth=$rs['installmentMonth'];
		
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
		
		$sql3="select * from carsystem.\"members\" where \"memberID\"='$poster'";
		$dbquery3=pg_query($sql3);
		$rs3=pg_fetch_assoc($dbquery3);
		$showname=$rs3['showname'];
		$mobile=$rs3['mobilephone'];
		$files = array_diff( scandir("../".$picpath), array(".", "..") );
		$files1 = array_diff( scandir("../".$picpath1), array(".", "..") );
		
		$size=sizeof($files);
		
		echo"<div id=\"divEachResult$i\" class=\"divEachResult\" onClick=\"window.open('product_detail.php?id=$id','_blank');\" onmouseover=\"addClass(id);\" onmouseout=\"removeClass(id);\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"3\" width=\"680\">";
        echo"<tr>";
        echo"<td rowspan=\"6\" valign=\"top\">";
        echo"<div id=\"divImageExam\">";
        echo"<div id=\"divExamText\">ภาพตัวอย่าง</div>";
		
		if($size!=0)
		{
        	echo"<div id=\"divExamImage\"><img src=\"".$picpath.$files[2]."\" id=\"taxiPreview\" width=\"150\" height=\"113\"  class=\"preview\" alt=\"".$picpath1.$files1[2]."\" /></div>";
		}
		else
		{
			echo"<div id=\"divExamImage\"><img src=\"images/404.png\" id=\"taxiPreview\" width=\"150\" height=\"113\"  class=\"preview\" alt=\"images/404.png\" /></div>";
		}
        echo"</div>";
        echo"</td>";
        echo"<td></td>";
        echo"<td></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td colspan=\"2\" id=\"tdPostSellText\">$postDetail</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ยี่ห้อ : </span><span class=\"highlightInfo\">$brandName $subBrandName</span><span> รุ่น : </span><span class=\"highlightInfo\">$modelName</span><span> สี : </span><span class=\"highlightInfo\">$carColor</span></td>";
        echo"<td rowspan=\"4\" valign=\"top\" id=\"tdPricePerMonth\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\">";
        echo"<tr>";
        echo"<td class=\"postSellDetail1\"><span>ผ่อน : </span><span>".number_format($carInstallment,2,".",",")." บาท/เดือน<br>";
		if($installmentMonth!=""){ echo "(".$installmentMonth." เดือน)"; }
		echo"</span></td>";
        echo"</tr>";
		if($carPrice!=0 || $carPrice!="")
		{
			echo"<tr>";
			echo"<td class=\"postSellDetail3\"><span>ราคา : </span><span>".number_format($carPrice,2,".",",")." บาท</span></td>";
			echo"</tr>";
		}
        echo"<tr>";
        echo"<td class=\"postSellDetail2\"><span>ติดต่อ : </span><span class=\"highlightInfo1\">$showname</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail2\"><span>เบอร์โทรศัพท์ : </span><span class=\"highlightInfo1\">$mobile</span></td>";
        echo"</tr>";
        echo"</table>";
        echo"</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ประเภท : </span><span class=\"highlightInfo\">$carType</span><span> ปีที่ผลิต : </span><span class=\"highlightInfo\">$carYear</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ประเภทแก๊ส : </span><span class=\"highlightInfo\">$gasType</span><span> ระบบแก๊ส : </span><span class=\"highlightInfo\">$gasSystem</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ขนาดถังแก๊ส : </span><span class=\"highlightInfo\">$gasTankSize</span><span> จังหวัด : </span><span class=\"highlightInfo\">$liveProvince</span></td>";
        echo"</tr>";
        echo"</table>";
		echo"</div>";
		$i++;
	}
	echo "<div id=\"divVSpace\"></div>";
	echo"</div>";
	//echo $sql; 
	if($total>0)
	{
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\" id=\"tbNavigate\">";
		echo "<tr>";
		echo "<td align=\"center\" valign=\"middle\" id=\"navigate\">";
		echo "<div class=\"browse_page\">"; 
		 // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
		page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);
		echo "</div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
    }
?>
<style>
pre{
	display:block;
	font:100% "Courier New", Courier, monospace;
	padding:10px;
	border:1px solid #bae2f0;
	background:#e3f4f9;	
	margin:.5em 0;
	overflow:auto;
	width:800px;
}

img{border:none;}
ul,li{
	margin:0;
	padding:0;
}
li{
	list-style:none;
	/*float:left;
	display:inline;*/
	margin-right:10px;
}



/*  */

#preview{
	position:absolute;
	display:none;
	color:#fff;
	background-attachment: scroll;
	background-image: url(../images/arrow_bg.png);
	background-repeat: no-repeat;
	background-position: right 15px;
	text-align: left;
	padding: 0px;
	}
	#preview #mainImage{
	position:relative;
	background:#333;
	color:#fff;
	padding: 5px;
	float: left;
	}
	#preview #arrowImage{
	position:relative;
	float: right;
	margin-top: 5px;
	}

/*  */
</style>