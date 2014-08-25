<?php   
 /* CAT:Spline chart */
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");
include("../config/config.php");

$SelectChart = $_POST["SelectChart"];
$month = $_POST["month"];
$SelectWhat = $_POST["SelectWhat"];

$SelectChartB = $_POST["SelectChartB"];
$year1 = $_POST["year1"];
$year2 = $_POST["year2"];
$year3 = $_POST["year3"];
$year4 = $_POST["year4"];


if($SelectChart == "a1" && $SelectChartB != "2") // ถ้าเลือกแบบดูข้อมูลรวมในแต่ละเดือน และเปรียบเทียบข้อมูลย้อนหลัง
{
$month=$_POST["month"];
$SelectChartB=$_POST["SelectChartB"];
$toyear = $SelectChartB-1;
if($SelectChartB==3)
{
	$year=$_POST["year1"];
}
elseif($SelectChartB==5)
{
	$year=$_POST["year2"];
}
			
 /* Create and populate the pData object */
$MyData = new pData();  

//------------------------------------------------------------------------------------------
$y_row=0;
for($y=$year-$toyear;$y<=$year;$y++)
{
	$y_row++;
for($r=1;$r<=12;$r++)
{
	$sum_month = 0;
	if($r < 10){
		$month="0".$r;
	}else{
		$month=$r;
	}
//............................
for($i=1;$i<=31;$i++){
	if($i < 10){
		$day="0".$i;
	}else{
		$day=$i;
	}
	$sum_j = 0;
//.............................
$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$y') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}
if($numrow == 0){$sum_j = 0;}
$sum_month += $sum_j;

}
//------------------------------------------------------------------------------------------------------
//if($numrow == 0){$sum_month = 0;}
$y_th = $y+543;
if($SelectWhat == "w2"){$y_th=$y_th." ค่าใช้จ่าย";}
	$MyData->addPoints($sum_month,"$y_th");
	if($y_row==1)
	{
		$MyData->setPalette("$y_th",array("R"=>0,"G"=>0,"B"=>255));
	}
	elseif($y_row==2)
	{
		$MyData->setPalette("$y_th",array("R"=>255,"G"=>0,"B"=>0));
	}
	elseif($y_row==3)
	{
		$MyData->setPalette("$y_th",array("R"=>28,"G"=>28,"B"=>28));
	}
	elseif($y_row==4)
	{
		$MyData->setPalette("$y_th",array("R"=>139,"G"=>0,"B"=>139));
	}
	elseif($y_row==5)
	{
		$MyData->setPalette("$y_th",array("R"=>139,"G"=>139,"B"=>122));
	}
}
}

//nnnnnnnnnnnnnn   ถ้าเลือกให้แสดงยอดสินเชื่อที่ปล่อยด้วย
if($SelectWhat == "w2")
{
	$x_row = 0;
	for($i=1;$i<=12;$i++)
	{
		if($i < 10){
			$month="0".$i;
		}else{
			$month=$i;
		}
		$j=1;
		//for($y=$year1;$y<=$year2;$y++){
		for($y=$year-$toyear;$y<=$year;$y++)
		{
			$x_row++;
			$txty=$y+543;
			$txty=$txty." สินเชื่อที่ปล่อย";
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
					$allsum1.$j=$sumbegin;
				}
			}
			$MyData->addPoints($allsum1.$j,$txty);
			if($x_row==1)
			{
				$MyData->setPalette("$txty",array("R"=>0,"G"=>238,"B"=>238));
			}
			if($x_row==2)
			{
				$MyData->setPalette("$txty",array("R"=>71,"G"=>60,"B"=>139));
			}
			if($x_row==3)
			{
				$MyData->setPalette("$txty",array("R"=>112,"G"=>128,"B"=>144));
			}
			if($x_row==4)
			{
				$MyData->setPalette("$txty",array("R"=>255,"G"=>255,"B"=>0));
			}
			if($x_row==5)
			{
				$MyData->setPalette("$txty",array("R"=>46,"G"=>139,"B"=>78));
			}
			$j++;
		}
	}
}
//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn

 $MyData->setAxisName(0,"ยอดค่าใช้จ่ายและยอดสินเชื่อที่ปล่อย");
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
 $yold = $year - $SelectChartB + 1;
 $year_th = $year+543;
 $yold_th = $yold+543;
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));
 if($SelectWhat == "w2"){$myPicture->drawText(750,25,"รายงานค่าใช้จ่ายและยอดสินเชื่อที่ปล่อยระหว่างปี พ.ศ.$yold_th ถึงปี พ.ศ.$year_th",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}
 else{$myPicture->drawText(750,35,"รายงานค่าใช้จ่ายระหว่างปี พ.ศ.$yold_th ถึงปี พ.ศ.$year_th",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(100,50,1450,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 if($SelectWhat == "w2"){$myPicture->drawLegend(100,38,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}
 else{$myPicture->drawLegend(1200,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
 } // จบเลือกแบบดูข้อมูลรวมในแต่ละเดือน และเปรียบเทียบข้อมูลย้อนหลัง
 
 
 
 // dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
 
 
 
if($SelectChart == "a1" && $SelectChartB == "2") // ถ้าเลือกแบบดูข้อมูลรวมในแต่ละเดือน และเปรียบเทียบข้อมูลระหว่างปีหนึ่งกับอีกปีหนึ่ง
{
$year=$_POST["SelectChart"];
$year2=$year+543;

$y1=$_POST["year3"];
$y2=$_POST["year4"];
$y1_th = $y1+543;
$y2_th = $y2+543;

$month=$_POST["month"];
			
 /* Create and populate the pData object */
$MyData = new pData();  

//------------------------------------------------------------------------------------------
for($r=1;$r<=12;$r++)
{
	$sum_month = 0;
	$sum_month2 = 0;
	if($r < 10){
		$month="0".$r;
	}else{
		$month=$r;
	}
//............................
for($i=1;$i<=31;$i++){
	if($i < 10){
		//$month="0".$i;
		$day="0".$i;
	}else{
		//$month=$i;
		$day=$i;
	}
	$sum_j = 0;
//.............................
$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$y1') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if(empty($chq_acc_no)){
    $chq_acc_no_text = "เงินสด";
}else{
    $chq_acc_no_text = "เช็ค";
}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}
if($numrow == 0){$sum_j = 0;}
$sum_month += $sum_j;

}

//222222222222222222222222222222222222222222222222222222222222222

	$vc_id = "";
    $vc_detail = "";
    $do_date = "";
    $job_id = "";
    $cash_amt = "";
    $approve_id = "";
    $chq_acc_no = "";
    $chque_no = "";
    $autoid_abh = "";

for($i=1;$i<=31;$i++){
	if($i < 10){
		//$month="0".$i;
		$day="0".$i;
	}else{
		//$month=$i;
		$day=$i;
	}
	$sum_j = 0;
//.............................
$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$y2') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if(empty($chq_acc_no)){
    $chq_acc_no_text = "เงินสด";
}else{
    $chq_acc_no_text = "เช็ค";
}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}
if($numrow == 0){$sum_j = 0;}
$sum_month2 += $sum_j;
}

//222222222222222222222222222222222222222222222222222222222222222
//------------------------------------------------------------------------------------------------------
	$MyData->addPoints($sum_month,"$y1_th ค่าใช้จ่าย");
	$MyData->addPoints($sum_month2,"$y2_th ค่าใช้จ่าย");
	$MyData->setPalette("$y1_th ค่าใช้จ่าย",array("R"=>0,"G"=>0,"B"=>255));
	$MyData->setPalette("$y2_th ค่าใช้จ่าย",array("R"=>255,"G"=>0,"B"=>0));
}

//nnnnnnnnnnnnnnnn ถ้าเลือกแบบให้แสดงยอดสินเชื่อที่ปล่อยด้วย
if($SelectWhat == "w2")
{
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
		$y1_1=$y1+543;
		$y1_1=$y1_1." ยอดสินเชื่อที่ปล่อย";
		while($result=pg_fetch_array($query)){
			$beginx =$result["sumbeginx"]; 								
			$sumbegin = $sumbegin+$beginx; 	
			$allsum1=$sumbegin;
			//$allsum1=$sumbegin/10000;
		}
	
		$query2=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
		where (EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$y2')");
		$sumbegin=0;
		$allsum2=0;
		$y2_1=$y2+543;
		$y2_1=$y2_1." ยอดสินเชื่อที่ปล่อย";
		while($result2=pg_fetch_array($query2)){
			$beginx =$result2["sumbeginx"]; 								
			$sumbegin = $sumbegin+$beginx; 	
			$allsum2=$sumbegin;
			//$allsum2=$sumbegin/10000;
		}
		$MyData->addPoints($allsum1,"$y1_1");
		$MyData->addPoints($allsum2,"$y2_1");
		$MyData->setPalette("$y1_1",array("R"=>255,"G"=>255,"B"=>0));
		$MyData->setPalette("$y2_1",array("R"=>54,"G"=>54,"B"=>54));
	}
}
//nnnnnnnnnnnnnnnnn จบ ถ้าเลือกแบบให้แสดงยอดสินเชื่อที่ปล่อยด้วย

 $MyData->setAxisName(0,"ยอดค่าใช้จ่ายและยอดสินเชื่อที่ปล่อย");
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
 if($SelectWhat == "w2"){$myPicture->drawText(750,25,"รายงานค่าใช้จ่ายและยอดสินเชื่อที่ปล่อยระหว่างปี พ.ศ.$y1_th กับปี พ.ศ. $y2_th",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}
 else{ $myPicture->drawText(750,35,"รายงานค่าใช้จ่ายระหว่างปี พ.ศ.$y1_th กับปี พ.ศ. $y2_th",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(100,50,1450,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 if($SelectWhat == "w2"){$myPicture->drawLegend(900,38,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}
 else{$myPicture->drawLegend(1400,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
 } // จบการเลือกแบบดูข้อมูลรวมในแต่ละเดือน และเปรียบเทียบข้อมูลระหว่างปีหนึ่งกับอีกปีหนึ่ง
 
 
 //ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
 
 
 if($SelectChart == "a2" && $SelectChartB != "2") // ถ้าเลือกแบบดูข้อมูลแต่ละวันในเดือน และเปรียบเทียบข้อมูลย้อนหลัง
 {
 $SelectChartB=$_POST["SelectChartB"];
$toyear = $SelectChartB-1;
if($SelectChartB==3)
{
	$year=$_POST["year1"];
}
elseif($SelectChartB==5)
{
	$year=$_POST["year2"];
}
$month=$_POST["month"];
if($month=="01"){$month_th = "มกราคม";}
elseif($month=="02"){$month_th = "กุมภาพันธ์";}
elseif($month=="03"){$month_th = "มีนาคม";}
elseif($month=="04"){$month_th = "เมษายน";}
elseif($month=="05"){$month_th = "พฤษภาคม";}
elseif($month=="06"){$month_th = "มิถุนายน";}
elseif($month=="07"){$month_th = "กรกฎาคม";}
elseif($month=="08"){$month_th = "สิงหาคม";}
elseif($month=="09"){$month_th = "กันยายน";}
elseif($month=="10"){$month_th = "ตุลาคม";}
elseif($month=="11"){$month_th = "พฤศจิกายน";}
elseif($month=="12"){$month_th = "ธันวาคม";}
			
 /* Create and populate the pData object */
$MyData = new pData();  

//------------------------------------------------------------------------------------------
//............................
$y_row=0;
for($y=$year-$toyear;$y<=$year;$y++)
{
	$y_row++;
for($i=1;$i<=31;$i++){
	if($i < 10){
		//$month="0".$i;
		$day="0".$i;
	}else{
		//$month=$i;
		$day=$i;
	}
	$sum_j = 0;
//.............................
$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$y') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}
//------------------------------------------------------------------------------------------------------

if($numrow == 0){$sum_j = 0;}
$y_th = $y+543;
$y_th = $y_th." ค่าใช้จ่าย";
	$MyData->addPoints($sum_j,"$y_th");
	if($y_row==1)
	{
		$MyData->setPalette("$y_th",array("R"=>0,"G"=>0,"B"=>255));
	}
	elseif($y_row==2)
	{
		$MyData->setPalette("$y_th",array("R"=>255,"G"=>0,"B"=>0));
	}
	elseif($y_row==3)
	{
		$MyData->setPalette("$y_th",array("R"=>28,"G"=>28,"B"=>28));
	}
	elseif($y_row==4)
	{
		$MyData->setPalette("$y_th",array("R"=>139,"G"=>0,"B"=>139));
	}
	elseif($y_row==5)
	{
		$MyData->setPalette("$y_th",array("R"=>139,"G"=>139,"B"=>122));
	}
}
}

//nnnnnnnnnnnnnn   ถ้าเลือกให้แสดงยอดสินเชื่อที่ปล่อยด้วย
if($SelectWhat == "w2")
{
	$x_row = 0;
	for($d=1;$d<=31;$d++){
	if($d < 10){
		//$month="0".$i;
		$day="0".$d;
	}else{
		//$month=$i;
		$day=$d;
	}
		$j=1;
		//for($y=$year1;$y<=$year2;$y++){
		for($y=$year-$toyear;$y<=$year;$y++)
		{
			$x_row++;
			$txty=$y+543;
			$txty=$txty." สินเชื่อที่ปล่อย";
			$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
			where (EXTRACT(DAY FROM \"P_STDATE\")='$day' AND EXTRACT(MONTH FROM \"P_STDATE\") ='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
			$numrow=pg_num_rows($query);
			$sumbegin=0;
			$allsum1.$j=0;
			if($numrow==0){
				$allsum1.$j=0;
			}else{
				while($result=pg_fetch_array($query)){
					$beginx =$result["sumbeginx"];							
					$sumbegin = $sumbegin+$beginx; 	
					$allsum1.$j=$sumbegin;
				}
			}
			$MyData->addPoints($allsum1.$j,$txty);
			if($x_row==1)
			{
				$MyData->setPalette("$txty",array("R"=>0,"G"=>238,"B"=>238));
			}
			if($x_row==2)
			{
				$MyData->setPalette("$txty",array("R"=>71,"G"=>60,"B"=>139));
			}
			if($x_row==3)
			{
				$MyData->setPalette("$txty",array("R"=>112,"G"=>128,"B"=>144));
			}
			if($x_row==4)
			{
				$MyData->setPalette("$txty",array("R"=>255,"G"=>255,"B"=>0));
			}
			if($x_row==5)
			{
				$MyData->setPalette("$txty",array("R"=>46,"G"=>139,"B"=>78));
			}
			$j++;
		}
	}
}
//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn

 $MyData->setAxisName(0,"ยอดค่าใช้จ่ายและยอดสินเชื่อที่ปล่อย");
 $MyData->addPoints(array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"),"Labels");
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
 $yold = $year - $SelectChartB + 1;
 $year_th = $year+543;
 $yold_th = $yold+543;
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>11));
 if($SelectWhat == "w2"){$myPicture->drawText(750,25,"รายงานค่าใช้จ่ายและยอดสินเชื่อที่ปล่อยประจำเดือน $month_th ของปี พ.ศ.$yold_th ถึง พ.ศ.$year_th",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}
 else{$myPicture->drawText(750,35,"รายงานค่าใช้จ่ายประจำเดือน $month_th ของปี พ.ศ.$yold_th ถึง พ.ศ.$year_th",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(100,50,1450,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 if($SelectWhat == "w2"){$myPicture->drawLegend(100,38,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}
 else{$myPicture->drawLegend(1200,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
 } // จบการเลือกแบบดูข้อมูลแต่ละวันในเดือน และเปรียบเทียบข้อมูลย้อนหลัง
 
 
 //dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
 
 
 
 if($SelectChart == "a2" && $SelectChartB == "2") // ถ้าเลือกแบบดูข้อมูลแต่ละวันในเดือน และเปรียบเทียบข้อมูลระหว่างปีหนึ่งกับอีกปีหนึ่ง
 {
 $year=$_POST["SelectChart"];
$year2=$year+543;
$month=$_POST["month"];

if($month=="01"){$month_th = "มกราคม";}
elseif($month=="02"){$month_th = "กุมภาพันธ์";}
elseif($month=="03"){$month_th = "มีนาคม";}
elseif($month=="04"){$month_th = "เมษายน";}
elseif($month=="05"){$month_th = "พฤษภาคม";}
elseif($month=="06"){$month_th = "มิถุนายน";}
elseif($month=="07"){$month_th = "กรกฎาคม";}
elseif($month=="08"){$month_th = "สิงหาคม";}
elseif($month=="09"){$month_th = "กันยายน";}
elseif($month=="10"){$month_th = "ตุลาคม";}
elseif($month=="11"){$month_th = "พฤศจิกายน";}
elseif($month=="12"){$month_th = "ธันวาคม";}

$SelectChartB=$_POST["SelectChartB"];
$year3=$_POST["year3"];
$year4=$_POST["year4"];
	
 /* Create and populate the pData object */
$MyData = new pData();  

//------------------------------------------------------------------------------------------
//............................
for($i=1;$i<=31;$i++){
	if($i < 10){
		//$month="0".$i;
		$day="0".$i;
	}else{
		//$month=$i;
		$day=$i;
	}
	$sum_j = 0;
	$sum_j2 = 0;
//.............................
$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$year3') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if(empty($chq_acc_no)){
    $chq_acc_no_text = "เงินสด";
}else{
    $chq_acc_no_text = "เช็ค";
}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

//22222222222222222222222222222222222222222

$j = 0;
$qry=pg_query("select * from account.tal_voucher WHERE \"receipt_id\" is not null AND \"autoid_abh\" is null AND (EXTRACT(DAY FROM \"do_date\")='$day' AND EXTRACT(MONTH FROM \"do_date\")='$month' AND EXTRACT(YEAR FROM \"do_date\")='$year4') ORDER BY \"job_id\",\"vc_id\" ASC");
$numrow2=pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $j++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    $autoid_abh = $res["autoid_abh"];

if($j > 1){
    
if($old_job == $job_id){
}else{
    
    $sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j2 += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

    $all_sum_sum += $sum_sum;

    $sum_sum = 0;
    $sum_sub1_plus = 0;
    $sum_sub1_lob = 0;
}

}

if( empty($autoid_abh) ){
    $autoid_abh_text = "ยังไม่ลงบัญชี";
}else{
    $autoid_abh_text = "$autoid_abh";
}

$Amount = 0;

if(empty($chq_acc_no)){
    if($cash_amt >= 0){
        $sum_sub1_plus+=$cash_amt;
    }else{
        $sum_sub1_lob+=$cash_amt;
    }
    $sum_all1+=$cash_amt;
}else{
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $Amount = $res_chq["Amount"];
    }
    $sum_sub2+=$Amount;
    $sum_all2+=$Amount;
}

$begin_jobid = $job_id;
$begin_chq_acc_no = $chq_acc_no_text;
$begin_vc_id = $vc_id;
$begin_do_date = $do_date;
$begin_vc_detail = $vc_detail;
$begin_autoid_abh = $autoid_abh_text;
$begin_cash_amt = $cash_amt;
$begin_Amount = $Amount;

$begin_chq_acc_no2 = $chq_acc_no;
$begin_chque_no2 = $chque_no;

$old_job = $job_id;
}

//แสดงรายการสุดท้าย
$sum_sum = $sum_sub1_plus+$sum_sub1_lob;

if($sum_sum >= 0){
    $sum_j2 += $sum_sum;
}else{
    $sum_r += $sum_sum;
}

//22222222222222222222222222222222222222222
//------------------------------------------------------------------------------------------------------
if($numrow == 0){$sum_j = 0;}
if($numrow2 == 0){$sum_j2 = 0;}
$year3_th = $year3+543;
$year4_th = $year4+543;
	$MyData->addPoints($sum_j,"$year3_th ค่าใช้จ่าย");
	$MyData->addPoints($sum_j2,"$year4_th ค่าใช้จ่าย");
	$MyData->setPalette("$year3_th ค่าใช้จ่าย",array("R"=>0,"G"=>0,"B"=>255));
	$MyData->setPalette("$year4_th ค่าใช้จ่าย",array("R"=>255,"G"=>0,"B"=>0));
}

//nnnnnnnnnnnnnnnn ถ้าเลือกแบบให้แสดงยอดสินเชื่อที่ปล่อยด้วย
if($SelectWhat == "w2")
{
		
	for($d=1;$d<=31;$d++){
	if($d < 10){
		//$month="0".$i;
		$day="0".$d;
	}else{
		//$month=$i;
		$day=$d;
	}
	
		$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
		where (EXTRACT(DAY FROM \"P_STDATE\")='$day' AND EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$year3')");
		$sumbegin=0;
		$allsum1=0;	
		$y1_1=$year3+543;
		$y1_1=$y1_1." ยอดสินเชื่อที่ปล่อย";
		while($result=pg_fetch_array($query)){
			$beginx =$result["sumbeginx"]; 								
			$sumbegin = $sumbegin+$beginx; 	
			$allsum1=$sumbegin;
			//$allsum1=$sumbegin/10000;
		}
	
		$query2=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
		where (EXTRACT(DAY FROM \"P_STDATE\")='$day' AND EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$year4')");
		$sumbegin=0;
		$allsum2=0;
		$y2_1=$year4+543;
		$y2_1=$y2_1." ยอดสินเชื่อที่ปล่อย";
		while($result2=pg_fetch_array($query2)){
			$beginx =$result2["sumbeginx"]; 								
			$sumbegin = $sumbegin+$beginx; 	
			$allsum2=$sumbegin;
			//$allsum2=$sumbegin/10000;
		}
		$MyData->addPoints($allsum1,"$y1_1");
		$MyData->addPoints($allsum2,"$y2_1");
		$MyData->setPalette("$y1_1",array("R"=>255,"G"=>255,"B"=>0));
		$MyData->setPalette("$y2_1",array("R"=>54,"G"=>54,"B"=>54));
	}
}
//nnnnnnnnnnnnnnnnn จบ ถ้าเลือกแบบให้แสดงยอดสินเชื่อที่ปล่อยด้วย

 $MyData->setAxisName(0,"ยอดค่าใช้จ่ายและยอดสินเชื่อที่ปล่อย");
 $MyData->addPoints(array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"),"Labels");
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
 if($SelectWhat == "w2"){$myPicture->drawText(750,25,"รายงานค่าใช้จ่ายและยอดสินเชื่อที่ปล่อยประจำเดือน $month_th ระหว่างปี พ.ศ.$year3_th กับปี พ.ศ.$year4_th",array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}
 else{$myPicture->drawText(750,35,"รายงานค่าใช้จ่ายประจำเดือน $month_th ระหว่างปี พ.ศ.$year3_th กับปี พ.ศ.$year4_th",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));}

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"fonts/tahoma.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(100,50,1450,760);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawSplineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 if($SelectWhat == "w2"){$myPicture->drawLegend(900,38,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}
 else{$myPicture->drawLegend(1400,16,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));}


 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawSplineChart.simple.png");
 } // จบการเลือกแบบดูข้อมูลแต่ละวันในเดือน และเปรียบเทียบข้อมูลระหว่างปีหนึ่งกับอีกปีหนึ่ง