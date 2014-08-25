<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$year1 = $_POST["year1"];
$year1_1=$year1+543;

$year2 = $_POST["year2"];
$year2_1=$year2+543;
	
/* Create and populate the pData object */
$MyData = new pData(); 

for($i=1;$i<=12;$i++){
	if($i < 10){
		$month="0".$i;
	}else{
		$month=$i;
	}
	$j=1;
	for($y=$year1;$y<=$year2;$y++){
		$txty=$y+543;
		$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
		where ((EXTRACT(MONTH FROM \"P_STDATE\") ='$month') AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
		$numrow=pg_num_rows($query);
		$sumbegin=0;
		$allsum1.$j=0;
		if($numrow==0){
			$allsum1.$j=0;
		}else{
			while($result=pg_fetch_array($query)){
				$beginx =$result["sumbeginx"];							
				$sumbegin = $sumbegin+$beginx; 	
				$allsum1.$j=$sumbegin/100000;
					
			}
		}
		$MyData->addPoints($allsum1.$j,$txty);
		$MyData->setSerieDescription($allsum1.$j,$txty);
		$MyData->setSerieOnAxis($allsum1.$j,0);
		$j++;
	}
}


/*$MyData->setAxisName(0,"ยอดสินเชื่อ (แสนบาท)");*/
$MyData->addPoints(array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."),"Absissa");
$MyData->setAbscissa("Absissa");

$MyData->setAxisPosition(0,AXIS_POSITION_LEFT);
$MyData->setAxisName(0,"ยอดสินเชื่อ (แสนบาท)");
$MyData->setAxisUnit(0,"");

 /* Create the pChart object */
 $myPicture = new pImage(1500,800,$MyData);
 $Settings = array("R"=>170, "G"=>183, "B"=>73, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,1500,800,$Settings);

$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1500,800,DIRECTION_VERTICAL,$Settings);

$myPicture->drawRectangle(0,0,1499,799,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>0));
 
/* Write the chart title */ 
$myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>16));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>112, "G"=>112, "B"=>112);
 $myPicture->drawText(750,35,"รายงานสินเชื่อในช่วงปี พ.ศ.$year1_1-$year2_1 (เดือนมกราคม - ธันวาคม)",$TextSettings);
 
$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(50,50,1475,760);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/tahoma.ttf","FontSize"=>11));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

 $Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/tahoma.ttf", "FontSize"=>11, "Margin"=>6, "Alpha"=>10, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(1100,16,$Config);

$myPicture->stroke();
?>
