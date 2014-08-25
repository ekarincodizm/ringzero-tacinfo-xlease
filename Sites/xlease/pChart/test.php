<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
			
 /* Create and populate the pData object */
 $MyData = new pData();

 $MyData->setAxisName(0,"ยอดสินเชื่อ (หมื่นบาท)");
 $MyData->addPoints(array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(1500,800,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw a background */
 $Settings = array("R"=>190, "G"=>213, "B"=>107, "Dash"=>1, "DashR"=>210, "DashG"=>223, "DashB"=>127); 
 $myPicture->drawFilledRectangle(0,0,1499,799,$Settings); 

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,1499,799,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));
 $myPicture->drawText(750,25,"รายงานสินเชื่อประจำปี พ.ศ.2555",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area (magin x, magin x, chart width,chart height) */
 $myPicture->setGraphArea(150,50,1450,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 $myPicture->drawLegend(1400,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
?>
