<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$y1 = $_POST["y1"];
$y1_1=$y1+543;

$y2 = $_POST["y2"];
$y2_1=$y2+543;

$m1 = $_POST["m1"];
$m2 = $_POST["m2"];

for($j=1;$j<=2;$j++){
	if($j==1){
		$month=$m1;
	}else{
		$month=$m2;
	}
	if($month=="01"){
		$txtmonth="มกราคม";
	}else if($month=="02"){
		$txtmonth="กุมภาพันธ์";
	}else if($month=="03"){
		$txtmonth="มีนาคม";
	}else if($month=="04"){
		$txtmonth="เมษายน";
	}else if($month=="05"){
		$txtmonth="พฤษภาคม";
	}else if($month=="06"){
		$txtmonth="มิถุนายน";
	}else if($month=="07"){
		$txtmonth="กรกฎาคม";
	}else if($month=="08"){
		$txtmonth="สิงหาคม";
	}else if($month=="09"){
		$txtmonth="กันยายน";
	}else if($month=="10"){
		$txtmonth="ตุลาคม";
	}else if($month=="11"){
		$txtmonth="พฤศจิกายน";
	}else if($month=="12"){
		$txtmonth="ธันวาคม";
	}
	if($j==1){
		$month1=$txtmonth;
	}else{
		$month2=$txtmonth;
	}
}		
 /* Create and populate the pData object */
$MyData = new pData(); 
for($y=$y1;$y<=$y2;$y++){
	$txty=$y+543;
	$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
	where ((EXTRACT(MONTH FROM \"P_STDATE\")between '$m1' and '$m2') AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
	$numrow=pg_num_rows($query);
	$sumbegin=0;
	$allsum1=0;
	while($result=pg_fetch_array($query)){
		$beginx =$result["sumbeginx"];							
		$sumbegin = $sumbegin+$beginx; 	
		$allsum1=$sumbegin/100000;
	}
				
	$MyData->addPoints($allsum1,"ยอดสินเชื่อ");
	$MyData->addPoints($txty,"Absissa");

	$MyData->setPalette("ยอดสินเชื่อ",array("R"=>220,"G"=>60,"B"=>20));
}
$MyData->setAbscissa("Absissa");
$MyData->setAxisName(0,"ยอดสินเชื่อ (แสนบาท)");

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
 $myPicture->drawText(750,35,"รายงานสินเชื่อในช่วงปี พ.ศ.$y1_1-$y2_1 (เดือน$month1 - $month2)",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

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
