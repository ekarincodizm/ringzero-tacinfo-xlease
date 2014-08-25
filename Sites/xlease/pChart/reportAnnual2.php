<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$y1=$_POST["y1"];
$y1_1=$y1+543;

$y2=$_POST["y2"];
$y2_1=$y2+543;
			
 /* Create and populate the pData object */
$MyData = new pData(); 

for($i=1;$i<=12;$i++){
	if($i < 10){
		$month="0".$i;
	}else{
		$month=$i;
	}
	$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
	where (EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$y1')");
	$sumbegin=0;
	$allsum1=0;	
	while($result=pg_fetch_array($query)){
		$beginx =$result["sumbeginx"]; 								
		$sumbegin = $sumbegin+$beginx; 	
		$allsum1=$sumbegin/10000;
	}
	
	$query2=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
	where (EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$y2')");
	$sumbegin=0;
	$allsum2=0;	
	while($result2=pg_fetch_array($query2)){
		$beginx =$result2["sumbeginx"]; 								
		$sumbegin = $sumbegin+$beginx; 	
		$allsum2=$sumbegin/10000;
	}
	$MyData->addPoints($allsum1,"$y1_1");
	$MyData->addPoints($allsum2,"$y2_1");
	$MyData->setPalette("$y1_1",array("R"=>220,"G"=>60,"B"=>20));
	$MyData->setPalette("$y2_1",array("R"=>84,"G"=>73,"B"=>216));
}


 $MyData->setAxisName(0,"ยอดสินเชื่อ (หมื่นบาท)");
 $MyData->addPoints(array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

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
 $myPicture->drawText(750,25,"เปรียบเทียบรายงานสินเชื่อประจำปี พ.ศ.$y1_1 และ พ.ศ.$y2_1",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

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
