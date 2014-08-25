<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
	// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
	function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
		global $e_page;
		global $querystr;
		$urlfile="Sellcar.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
			echo "<a  href='$urlfile?type=order&s_page=$pPrev&querystr=".$querystr."' class='naviPN'>Prev</a>";
		}
		for($i=$total_start_p;$i<$total_end_p;$i++){  
			$nClass=($chk_page==$i)?"class='selectPage'":"";
			if($e_page*$i<$total){
			echo "<a href='$urlfile?type=order&s_page=$i&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
			}
		}		
		if($chk_page<$total_p-1){
			echo "<a href='$urlfile?type=order&s_page=$pNext&querystr=".$querystr."'  class='naviPN'>Next</a>";
		}
	}   
	?>
	<?php
	//////////////////////////////////////// เริ่มต้น ส่วนเนื้อหาที่จะนำไปใช้ในไฟล์ ที่เรียกใช้ด้วย ajax
	?>
	
	<?php
	if($carFirstPrice=="" || $carSecondPrice=="")
	{
		if($carFirstYear=="" || $carSecondYear=="")
		{
			$q="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
		else
		{
			$q="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carYear\" between '$carFirstYear' and '$carSecondYear' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
	}
	else
	{
		if($carFirstYear=="" || $carSecondYear=="")
		{
			$q="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carPrice\" between '$carFirstPrice' and '$carSecondPrice' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
		else
		{
			$q="select * from \"TrPostSell\" where \"carBrand\" like '%$carBrand%' and \"carModel\" like'%$carModel%' and \"carType\" like '%$carType%' and \"gasType\" like '%$carGasType%' and \"gasSystem\" like '%$carGasSystem%' and \"liveProvince\" like '%$carProvince%' and \"carColor\" like '%$carColor%' and \"carPrice\" between '$carFirstPrice' and '$carSecondPrice' and \"carYear\" between '$carFirstYear' and '$carSecondYear' and (\"carBrand\" like '%$wordSearch%' or \"carModel\" like '%$wordSearch%' or \"carType\" like '%$wordSearch%' or \"carYear\" like '%$wordSearch%' or \"gasType\" like '%$wordSearch%' or \"gasSystem\" like '%$wordSearch%' or \"gasTankSize\" like '%$wordSearch%' or \"liveProvince\" like '%$wordSearch%' or \"postDetail\" like '%$wordSearch%' or \"carColor\" like '%$wordSearch%' or \"carPrice\" like '%$wordSearch%')";
		}
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
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" width=\"680\">";
        echo"<tr>";
        echo"<td></td>";
        echo"<td rowspan=\"4\" align=\"right\" valign=\"top\" id=\"tdPricePerMonth\">";
        echo"<table border=\"0\" cellpadding=\"0\" cellspacing=\"3\">";
        echo"<tr>";
        echo"<td class=\"postSellDetail1\"><span>ผ่อน : </span><span>$carPrice บาท/เดือน</span></td>";
        echo"</tr>";
        echo"</table>";
        echo"</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td id=\"tdPostSellText\">$postDetail</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ยี่ห้อ : </span><span class=\"highlightInfo\">$brandName</span><span> รุ่น : </span><span class=\"highlightInfo\">$modelName</span><span> สี : </span><span class=\"highlightInfo\">$carColor</span></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class=\"postSellDetail\"><span>ติดต่อ : </span><span class=\"highlightInfo1\">บริษัท ไทยเอซ ลิสซิ่ง จำกัด</span><span> เบอร์โทรศัพท์ : </span><span class=\"highlightInfo1\">0-2944-2000</span></td>";
        echo"</tr>";
        echo"</table>";
		echo"</div>";
		$i++;
	}
	echo"</div>";
	//echo $sql;
?>
<?php if($total>0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" width="710" id="tbNavigate">
    <tr>
    <td align="center" valign="middle" id="navigate">
    <div class="browse_page">
     <?php   
     // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
      page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);    
      ?>
    </div>
    </td>
    </tr>
    </table>
    </div>
<?php } ?>
<style type="text/css">
@charset "UTF-8";
body {
	margin: 0px;
	padding: 0px;
}
#divImageResult {
	width: 680px;
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
</style>

<!--<div id="divImageResult">
  <div id="divNumResult"><div id="divNumResultText"><span>ผลการค้นหาทั้งหมด : 1 รายการ</span></div></div>
    <div id="divEachResult" class="divEachResult" onClick="window.open('detailSellcar.php?id=','_blank');" onmouseover="addClass(id);" onmouseout="removeClass(id);">
        <table border="0" cellpadding="0" cellspacing="5" width="680">
            <tr>
              <td></td>
                <td rowspan="4" align="right" valign="top" id="tdPricePerMonth">
                	<table border="0" cellpadding="0" cellspacing="3">
               		<tr>
                        	<td class="postSellDetail1"><span>ผ่อน : </span><span>10,000 บาท/เดือน</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td id="tdPostSellText">ขายรถแท็กซี่มือสองสภาพใหม่ ไม่เคยเกิดอุบัติเหตุ สนใจติดต่อบริษัทไทยเอซ</td>
            </tr>
            <tr>
                <td class="postSellDetail"><span>ยี่ห้อ : </span><span>TOYOTA</span><span> รุ่น : </span><span>VX-111</span><span> สี : </span><span>ฟ้า</span></td>
            </tr>
            <tr>
                <td class="postSellDetail"><span>ติดต่อ : </span><span>บริษัท ไทยเอซ จำกัด</span><span> เบอร์โทรศัพท์ : </span><span>0-2944-2000</span></td>
            </tr>
        </table>
	</div>
</div>-->