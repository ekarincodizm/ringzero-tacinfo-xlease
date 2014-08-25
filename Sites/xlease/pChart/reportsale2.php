<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$year=$_POST["year"];
$conmonth="AND(EXTRACT(MONTH FROM a.\"startDate\") BETWEEN '01' AND '12')";
$txtcon="ประจำเดือนมกราคม-ธันวาคม";
		
 /* Create and populate the pData object */
$MyData = new pData();  

$qryuser=pg_query("select a.\"id_user\",b.\"fname\" from \"nw_startDateFp\" a
	left join \"fuser\" b on a.\"id_user\" = b.\"id_user\"
	where EXTRACT(YEAR FROM a.\"startDate\")='$year'
	group by a.\"id_user\",b.\"fname\" order by a.\"id_user\"");

while($resuser=pg_fetch_array($qryuser)){
	list($id_user,$fname)=$resuser;
	for($i=1;$i<=12;$i++){
		if($i < 10){
			$month="0".$i;
		}else{
			$month=$i;
		}
		$query=pg_query("select a.\"id_user\",b.\"fname\",count(a.\"IDNO\") as numidno,sum(c.\"P_BEGINX\") as sumbeginx from \"nw_startDateFp\" a
		left join \"fuser\" b on a.\"id_user\" = b.\"id_user\"
		left join \"Fp\" c on a.\"IDNO\" = c.\"IDNO\" where (EXTRACT(MONTH FROM a.\"startDate\")='$month' AND EXTRACT(YEAR FROM a.\"startDate\")='$year') AND a.\"id_user\"='$id_user'
		group by a.\"id_user\",b.\"fname\" order by a.\"id_user\"");
		
		$sumbegin=0;
		$allsum1=0;	
		while($result=pg_fetch_array($query)){
			$beginx =$result["sumbeginx"]; 								
			$sumbegin = $sumbegin+$beginx; 	
			$fname = $result["fname"];
			$allsum1=number_format($sumbegin/100000,2);
			//$allsum2=number_format($allsum1,2);
		}
		$MyData->addPoints($allsum1,"$fname");
		//$MyData->setPalette("$fname",0);
		
		$MyData->setSerieDescription($allsum1,$fname);
		$MyData->setSerieOnAxis($allsum1,0);
	}
	
}


 $MyData->setAxisName(0,"ยอดสินเชื่อที่ปล่อย (แสนบาท)");
 $MyData->addPoints(array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(1500,800,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw a background */
 $Settings = array("R"=>234, "G"=>241, "B"=>207, "Dash"=>1, "DashR"=>210, "DashG"=>223, "DashB"=>127); 
 $myPicture->drawFilledRectangle(0,0,1499,799,$Settings); 

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,1499,799,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));
 $myPicture->drawText(750,25,"รายงานพนักงานขายแบบรวม ประจำเดือนมกราคม-ธันวาคม $year",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));

 /* Define the chart area */
 $myPicture->setGraphArea(60,50,1475,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 $myPicture->drawLegend(70,40,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
?>