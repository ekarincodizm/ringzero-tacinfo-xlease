<?php   
 /* CAT:Line chart */

 /* pChart library inclusions */
 include("../../pChart/class/pData.class.php");
 include("../../pChart/class/pDraw.class.php");
 include("../../pChart/class/pImage.class.php");
 include("../../config/config.php");
 
 
 $MyData = new pData();
  
 $m1 = $_GET['month'];
 $year = $_GET['year'];
 $playback = $_GET['playback'];
 $type = $_GET['type'];

$contype = $_GET['contypee'];
$contype = explode("@",$contype);
sizeof($contype);

for($con = 0;$con < sizeof($contype) ; $con++){

	if($contype[$con] != ""){	
		if($contypeqry == ""){
			$contypeqry = "\"conType\" = '$contype[$con]' ";
		}else{
			$contypeqry = $contypeqry."OR \"conType\" = '$contype[$con]' ";
		}		
	}

}

if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
}
 
$strSQL = "SELECT \"gen_numDaysInMonth\"($m1,$year) ";
$objQuery = pg_query($strSQL);
$re1= pg_fetch_array($objQuery);
list($day)=$re1;

$date = $year."-".$m1."-"."01";
$datedes = $year."-".$m1."-".$day;

	if($playback != ''){		
		$stop = $playback;
	}else{
		echo "<script>alert(' Error ')</script>";
		exit();
	}

	for($i=1;$i<=$stop;$i++){
	
			if($type=='a1'){	
				$sum = 0;
				$txt = 'ยอดสินเชื่อ';
			
					$strSQL = "
								SELECT  sum(tba.\"conLoanAmt\") as \"conLoanAmt\"
								FROM	(	
											SELECT 	sum(\"conLoanAmt\") as \"conLoanAmt\" 
											FROM 	\"thcap_mg_contract\" 
											WHERE  	(\"conDate\" Between '$date' AND  '$datedes') $contypeqry
											UNION
											SELECT 	sum(\"conFinanceAmount\") as \"conLoanAmt\" 
											FROM 	\"thcap_lease_contract\" 
											WHERE  	(\"conDate\" Between '$date' AND  '$datedes') $contypeqry
										) tba	
								
							  ";
					$objQuery = pg_query($strSQL);
					$nrows = pg_num_rows($objQuery);
				
					list($sum) = pg_fetch_array($objQuery);			
					$MyData->addPoints($sum,"ยอดสินเชื่อ");		
		
			}else if($type == 'a2'){
				$txt = 'จำนวนสัญญา';
					$strSQL = "
								SELECT 	\"contractID\",\"conType\" 
								FROM 	\"thcap_mg_contract\" 
								WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
								
								UNION
								
								SELECT 	\"contractID\",\"conType\" 
								FROM 	\"thcap_lease_contract\" 
								WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
								
								ORDER BY \"contractID\"	
							  ";
					$objQuery = pg_query($strSQL);
					$nrows = pg_num_rows($objQuery);
		
						
					$MyData->addPoints($nrows,"จำนวนสัญญา");	
					
			}else if($type == 'a3'){
				$txt = 'ยอดสินเชื่อเฉลี่ยต่อจำนวนสัญญา';
					$strSQL = "	
								SELECT \"contractID\",\"conLoanAmt\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
								FROM \"thcap_mg_contract\" 
								WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
								
								UNION
								
								SELECT \"contractID\",\"conFinanceAmount\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
								FROM \"thcap_lease_contract\" 
								WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
								
								ORDER BY \"contractID\"
								";
					$objQuery = pg_query($strSQL);
					$nrows = pg_num_rows($objQuery);
		

					$sum1 = 0;
						
						while($result=pg_fetch_array($objQuery)){
		 								
						$sum1 +=  $result["conLoanAmt"];
				
						}
						if($nrows ==0 ){}else{
						$sum2 = $sum1/$nrows;
						
						$sum2 = (int)$sum2;
						}
						
					$MyData->addPoints($sum2,"ยอดเฉลี่ย");	
			
			}
			
		list($year,$m2,$day)=explode('-',$date);
		$year = substr($year,2);
			if($m2=="01"){
				$txtmonth="01"."/".$year;
			}else if($m2=="02"){
				$txtmonth="02"."/".$year;
			}else if($m2=="03"){
				$txtmonth="03"."/".$year;
			}else if($m2=="04"){
				$txtmonth="04"."/".$year;
			}else if($m2=="05"){
				$txtmonth="05"."/".$year;
			}else if($m2=="06"){
				$txtmonth="06"."/".$year;
			}else if($m2=="07"){
				$txtmonth="07"."/".$year;
			}else if($m2=="08"){
				$txtmonth="08"."/".$year;
			}else if($m2=="09"){
				$txtmonth="09"."/".$year;
			}else if($m2=="10"){
				$txtmonth="10"."/".$year;
			}else if($m2=="11"){
				$txtmonth="11"."/".$year;
			}else if($m2=="12"){
				$txtmonth="12"."/".$year;
			}
		 $MyData->addPoints(array($txtmonth),"Labels");
		 
		 $date = date("Y-m-d", strtotime("-1 month", strtotime($date)));
			list($year2,$m2,$d2) = explode("-",$date);
			$strSQL = "SELECT \"gen_numDaysInMonth\"($m2,$year2) ";
			$objQuery = pg_query($strSQL);
			$re1= pg_fetch_array($objQuery);
			list($day2)=$re1;				
			$datedes = $year2."-".$m2."-".$day2;
	}	


 /* Create and populate the pData object */

if($type == 'a2'){
 $MyData->setAxisName(0,"จำนวนสัญญา");
}else{
 $MyData->setAxisName(0,"สกุลเงิน บาท ไทย");
}
 $MyData->setSerieDescription("Labels","เดือน");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(1550,600,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw the background */
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,1550,600,$Settings);

 /* Overlay with a gradient */
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,1550,600,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,1550,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,1550,599,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../../pChart/fonts/tahoma.ttf","FontSize"=>8,"R"=>255,"G"=>255,"B"=>255));
 $myPicture->drawText(10,16,"$txt",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../../pChart/fonts/tahoma.ttf","FontSize"=>9,"R"=>5,"G"=>0,"B"=>0));

 /* Define the chart area */
 $myPicture->setGraphArea(90,50,1500,550);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Enable shadow computing */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the line chart */
 $myPicture->drawLineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 $myPicture->drawLegend(1400,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawLineChart.plots.png");
?>
