<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$TypeID=$_POST["TypeID"];
$year1=$_POST["year1"];
$y1=$year1+543;
$year2=$_POST["year2"];
$y2=$year2+543;

$query_type=pg_query("select \"TName\" from \"TypePay\" where \"TypeID\"='$TypeID'");
$res_type=pg_fetch_array($query_type);
$TName=$res_type["TName"];
	
 /* Create and populate the pData object */
$MyData = new pData(); 
for($y=$year1;$y<=$year2;$y++){
	$txty=$y+543;
	$query=pg_query("select sum(\"O_MONEY\") as money2 from \"FOtherpay\" where \"O_Type\"='$TypeID' and  EXTRACT(YEAR FROM \"O_DATE\")='$y'");
	$numrow=pg_num_rows($query);

	$money2=0;
	while($result=pg_fetch_array($query)){
		$money =$result["money2"];							
		$money2=$money/100000;
	}
				
	$MyData->addPoints($money2,"รวมรายได้");
	$MyData->addPoints($txty,"Absissa");

	$MyData->setPalette("รวมรายได้",array("R"=>220,"G"=>60,"B"=>20));
}
$MyData->setAbscissa("Absissa");
$MyData->setAxisName(0,"รวมรายได้ (แสนบาท)");

 /* Create the pChart object */
 $myPicture = new pImage(1500,800,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw a background */
 $Settings = array("R"=>190, "G"=>213, "B"=>107, "Dash"=>1, "DashR"=>210, "DashG"=>223, "DashB"=>127); 
 $myPicture->drawFilledRectangle(0,0,1499,799,$Settings); 

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,1499,799,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));
 $myPicture->drawText(750,35,"รายงานสรุปรายได้อื่นๆ ($TName) ตั้งแต่พ.ศ.$y1-$y2",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(60,50,1475,760);

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
