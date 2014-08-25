<?php
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$MyData = new pData();

$month = $_POST["month"];
$txtcon = $_POST["txtcon"];
$txtmonth = $_POST["txtmonth"];
$year = $_POST["year"];
$conmonth = $_POST["conmonth"];
if($conmonth != ""){ $conmonth="AND (EXTRACT(MONTH FROM a.\"startDate\")='$conmonth')";}
$query=pg_query("select a.\"id_user\",b.\"fullname\",count(a.\"IDNO\") as numidno,sum(c.\"P_BEGIN\") as sumbeginx from \"nw_startDateFp\" a
		left join \"Vfuser\" b on a.\"id_user\" = b.\"id_user\"
		left join \"Fp\" c on a.\"IDNO\" = c.\"IDNO\" where EXTRACT(YEAR FROM a.\"startDate\")='$year' $conmonth
		group by a.\"id_user\",b.\"fullname\" order by a.\"id_user\"");

$numrows=pg_num_rows($query);
$sumidno=0;
$sumbegin=0;

while($result=pg_fetch_array($query)){
	$beginx =$result["sumbeginx"];
	$beginx=number_format($beginx/100000,2);
	$fullname=$result["fullname"];
	$name=explode(" ",$fullname);
	$MyData->addPoints($beginx,"ยอดสินเชื่อ");
	
	$MyData->addPoints($name[0],"Absissa");
	
}
$MyData->setSerieShape("ยอดสินเชื่อ",SERIE_SHAPE_FILLEDTRIANGLE); 
$MyData->setSerieWeight("ยอดสินเชื่อ",2); //size of point
$MyData->setAxisName(0,"ยอดสินเชื่อ(แสนบาท)"); //ชื่อแกน Y

$MyData->setAbscissa("Absissa");

/* Create the pChart object */
 $myPicture = new pImage(1500,800,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,1499,799,array("R"=>0,"G"=>0,"B"=>0)); //boder color
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));
 $myPicture->drawText(750,25,"รายงานการขายของพนักงาน $txtcon$txtmonth ค.ศ. $year",array("FontSize"=>18,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>8));

 /* Define the chart area */
 $myPicture->setGraphArea(60,50,1475,760); //x,y,width,height of chart

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>50,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
$myPicture->Antialias = TRUE;
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

 /* Draw the line chart */
$Config = array("DisplayValues"=>1, "PlotSize"=>3, "PlotBorder"=>1, "BorderSize"=>2);
$myPicture->drawPlotChart($Config);

 /* Write the chart legend */
 $myPicture->drawLegend(1400,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); //คำอธิบายมุมขวา

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawPlotChart.simple.png");
?>