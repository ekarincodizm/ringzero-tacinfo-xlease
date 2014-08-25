<?php
session_start();
require('../../config/config.php');
require('../../thaipdfclass.php');
//-===================================================================================================-
//			Function ใช้ในการคำนวณ...
//-===================================================================================================-
function func_time_next_month($date, $day)
{
	// ตรวจสอบว่า วันเริ่มทำสัญญา กับ วันที่ต้องจ่ายทุกๆ เดือนตรงกันหรือไม่
	$date_year = $date[0].$date[1].$date[2].$date[3];
	$date_month = $date[5].$date[6];
	$date_day = $date[8].$date[9];
	
	// ถ้าปี ค.ศ. ที่หารด้วย 4 ลงตัว เดือน กุมภาพันธ์ จะมี 29 วัน
	if(($date_year%4)==0 && $date_month=="01" && $day>=29) // เดือนหน้าของ มกราคม ที่เป็น ปีที่มี กุมภา 29 วัน
		$day = 29;
	else if(($date_year%4)!=0 && $date_month=="01" && $day>=29)
		$day = 28;
	
	// ถ้า กำหนดเป็นวันที่ 31 เดือนที่มี 30 วันให้เหลือ แค่ 30 พอ (มีนา>เมษา; พฤษภา>มิถุนา; สิงหา>กันยา; ตุลา>พฤศจิกา)
	if($day == 31 && ($date_month=="03" || $date_month=="05" || $date_month=="08" || $date_month=="10"))
		$day = 30;
	
	// หลบวันในกรณีใช้ day = 0 อิงจากวันที่ ของวันที่ใส่ $date เป็นหลัก
	if($day==0 && $date_day=="31" && ($date_month=="03" || $date_month=="05" || $date_month=="08" || $date_month=="10"))
		$day = 30;
	else if(($date_year%4)==0 && $date_month=="01" && $day==0 && $date_day>=29)
		$day = 29;
	else if(($date_year%4)!=0 && $date_month=="01" && $day==0 && $date_day>=29)
		$day = 28;
	else if($day == 0)
		$day = $date_day;

	if($date_month == "01") $next_month = "02";
	else if($date_month == "02") $next_month = "03";
	else if($date_month == "03") $next_month = "04";
	else if($date_month == "04") $next_month = "05";
	else if($date_month == "05") $next_month = "06";
	else if($date_month == "06") $next_month = "07";
	else if($date_month == "07") $next_month = "08";
	else if($date_month == "08") $next_month = "09";
	else if($date_month == "09") $next_month = "10";
	else if($date_month == "10") $next_month = "11";
	else if($date_month == "11") $next_month = "12";
	else if($date_month == "12") {$next_month = "01"; $date_year += 1;}

	if($date_day == $day) // ถ้าต้องให้ใช้เดือนถัดไปได้เลย ในวันที่เดิม หรือ $day=0
		return $date_year."-".$next_month."-".$date_day;
	else{
		return $date_year."-".$next_month."-".$day;
	}
}
function func_time_datediff($date1, $date2, $bt) // count number of days between date 1 to date 2
{
	$date1_year = $date1[0].$date1[1].$date1[2].$date1[3];
	$date1_month = $date1[5].$date1[6];
	$date1_day = $date1[8].$date1[9];

	$date2_year = $date2[0].$date2[1].$date2[2].$date2[3];
	$date2_month = $date2[5].$date2[6];
	$date2_day = $date2[8].$date2[9];
	
	$first_date = MKTIME(12,0,0,$date1_month,$date1_day,$date1_year);
	$second_date = MKTIME(12,0,0,$date2_month,$date2_day,$date2_year);
	
	if($bt == 1){
		$LIMIT = $first_date-$second_date;
	}else{
		$LIMIT = $second_date-$first_date;
	}	
	return FLOOR($LIMIT/60/60/24);
}

function func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date){
	$f=1;//เช็คว่าเป็นครั้งแรกหรือไม่	
	while($length > 0){ // Loop การจ่ายตรง due ตรงจำนวน แต่ละเดือนจนครบกำหนด 
		if($f==1){//เช็คว่าเป็นครั้งแรกหรือไม่ 1= เป็นครั้งแรก	
			$f=0; //setค่าให้เป็น 0 เพื่อออกจาก เงื่อนไข
			$qryint = pg_query("select \"cal_interestTypeB\"('$credit','$int_normal','$start_date','$first_pay_date')");
			$start_date = $first_pay_date;	
		}else{ //f !+1 ไม่เป็นครั้งแรก
			$start_date = func_time_next_month($start_date, $pay_date);		
			$qryint = pg_query("select \"cal_interestTypeB\"('$credit','$int_normal','$start_later','$start_date')");				
		}
		list($interest_month) = pg_fetch_array($qryint);
		$credit = $credit - ($min_pay - $interest_month);
		$length--;		
		$start_later = $start_date;				
	} 
	return $credit;
}



//-===================================================================================================-
//			กำหนดและรับค่าตัวแปร
//-===================================================================================================-	
$capital_start=$_GET['capital']; //จำนวนเงินต้น
$interest = $_GET['interest']; //อัตราดอกเบี้ย
$time = $_GET['month']; //ระยะเวลา (เดือน)
$datestart = $_GET['datestart']; //วันที่เริ่มจ่าย		
$datestartcon = $_GET['datestartcon'];	 //วันที่ทำสัญญา
$payday = $_GET['payday'];	 //จ่ายทุกวันที่	
$paytype = $_GET['paytype'];	 //ประเภทการหา	
$interest_cal = $interest;
$userid = $_SESSION["av_iduser"]; //รหัสผู้ใช้งาน
$timestamp = date("Y-m-d H:i:s"); //วันเวลาที่ใช้งาน
		
//หาชื่อผู้ใช้
$qry_username = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$userid'");
list($fullname_doer) = pg_fetch_array($qry_username);
	
$interest_show = number_format($interest,2);
$capital_show = number_format($capital_start,2);
$period_show = number_format($period,2);

$payother = $_GET["payother"]; //ค่าใช้จ่ายอื่นๆ
$percentpayother = $_GET["percentpayother"]; //% ค่าใช้จ่าย

//หา COST { COST = ["จำนวนเงินต้น" x ("% ค่าใช้จ่าย"/100)] + "ค่าใช้จ่ายอื่นๆ"  }
$COST = round(($capital_start*($percentpayother/100)),2) + $payother;
$COST_SHOW = number_format($COST,2);

//หา NET { NET = "จำนวนเงินต้น" - COST }
$NET = $capital_start - $COST;
$NET_SHOW = number_format($NET,2);
	
//- หายอดผ่อนขั้นต่ำ *********************************************************************************************************************************************************************************************************
$length = $_GET["month"]; //ระยะเวลา (เดือน)
$first_pay_date = $_GET['datestart']; //วันที่เริ่มจ่าย		
$MinimumInsDate = $_GET["datestartcon"]; //วันที่ทำสัญญา
$int_normal = $_GET["interest"]; //อัตราดอกเบี้ย
$credit = $_GET["capital"]; //จำนวนเงินต้น
list($pay_year,$pay_month,$pay_date) = explode("-",$first_pay_date); //ตัดเอาวันที่จ่ายของทุกเดือน
list($yy,$mm,$dd) = explode("-",$MinimumInsDate); //ตัดเอาวันที่ทำสัญญา

$start =  MKTIME(0,0,0,$mm, $dd, $yy);
$start_date = $yy."-".$mm."-".$dd;

$last =  MKTIME(0,0,0,$mm+$length, $payday, $yy);
$date1 = $last-$start;
$date1 = round(($date1/60/60/24),4);
$date1 = $date1/$length;
$r = 1+(($int_normal/36500)*($date1));
			
$min_pay =  round($credit*(pow($r,$length)*(1-$r))/(1-pow($r,$length)),2);
$min_pay2 = $min_pay;
$p = 0.1 ;  // %minpay ที่เพิ่ม
$min_pay = $min_pay+$min_pay*($p/100);
$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);

while($last > 0){			
	$p=$p+0.1;
	$min_pay = $min_pay2+($min_pay2*($p/100));
	 $last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);
}
			
$min_pay2 = floor($min_pay/10)*10 ;
$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$first_pay_date);

$halfminpay = ($min_pay2/2)*(-1);
$valueminus = (-5);
$valueplus = 5;
if($last < $halfminpay OR $last > 0){
	while($stop != 1){
		if($last < $halfminpay AND $last < 0){
			$valueminus = $valueminus - (-5);
			$min_pay2 = (ceil($min_pay/10)*10)-$valueminus;	
		}else if($last > 0 AND $last > $halfminpay ){
			$valueplus = $valueplus + 5;
			$min_pay2 = (ceil($min_pay/10)*10)+$valueplus;	
		}else{
			$stop = 1;
		}		
		$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$first_pay_date);	
	}
}	

$period = $min_pay2;
$period_show = number_format($min_pay2,2);
//- จบการหายอดผ่อนขั้นต่ำ *********************************************************************************************************************************************************************************************************

//-===================================================================================================-
//			PDF
//-===================================================================================================-
class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',10);
        $this->SetXY(10,10); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(195,4,$buss_name,0,'R',0);
    }
 
}				

// $pdf=new ThaiPDF();
$pdf=new PDF('P' ,'mm','a4');
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->AliasNbPages( 'tp' );
$pdf->PageNo();
$pdf->Image("images/water_line.png",20,50,180); 
		
$pdf->SetXY(0,10);
$pdf->SetFont('AngsanaNew','B',16);	
$head=iconv('UTF-8','windows-874'," คำนวณยอดผ่อนชำระ");
$pdf->MultiCell(210,3,$head,0,'C',0);
$pdf->Ln();	
	
$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(4,17);
$head1=iconv('UTF-8','windows-874',"ผู้พิมพ์: ".$fullname_doer." วันเวลาที่พิมพ์: ".$timestamp);
$pdf->MultiCell(70,0,$head1,0,'L',0);
	
$pdf->SetXY(4,21);
$head1=iconv('UTF-8','windows-874',"COST: ".$COST_SHOW."    NET: ".$NET_SHOW);
$pdf->MultiCell(70,0,$head1,0,'L',0);
	
$pdf->SetFont('AngsanaNew','B',8);
$pdf->SetXY(4,24.5);
$head3=iconv('UTF-8','windows-874',"หมายเหตุ ");
$pdf->MultiCell(15,0,$head3,0,'L',0);	

$pdf->SetTextColor(255,0,0);
$pdf->SetXY(14,24.5);
$head3=iconv('UTF-8','windows-874',"ตารางนี้ใช้เป็นตัวอย่างในการคำนวณเท่านั้น ตารางที่ถูกต้องขึ้นอยู่กับการผ่อนชำระของลูกค้า");
$pdf->MultiCell(210,0,$head3,0,'L',0);
	
$pdf->SetFont('AngsanaNew','',9);	
$pdf->SetTextColor(0,0,0);	
$pdf->SetXY(138,28);
$head1=iconv('UTF-8','windows-874',"จำนวนเงินต้น : ".$capital_show." บาท ");
$pdf->MultiCell(70,0,$head1,0,'R',0);

$pdf->SetXY(95,28);
$head3=iconv('UTF-8','windows-874',"ค่างวด : ".$period_show." บาท");
$pdf->MultiCell(70,0,$head3,0,'R',0);

$pdf->SetXY(65,28);
$head3=iconv('UTF-8','windows-874',"จำนวนงวด : ".$time." เดือน");
$pdf->MultiCell(70,0,$head3,0,'R',0);	

$pdf->SetXY(40,28);
$head2=iconv('UTF-8','windows-874',"ดอกเบี้ย : ".$interest_show." %");
$pdf->MultiCell(65,0,$head2,0,'R',0);
	
$pdf->SetXY(30,28);
$head3=iconv('UTF-8','windows-874',"ชำระทุกวันที่ : $payday");
$pdf->MultiCell(60,0,$head3,0,'L',0);

$pdf->SetXY(52,28);
$head3=iconv('UTF-8','windows-874',"ชำระงวดแรก : $first_pay_date");
$pdf->MultiCell(30,0,$head3,0,'L',0);

$pdf->SetXY(4,28);
$head3=iconv('UTF-8','windows-874',"ทำสัญญา : $datestartcon");
$pdf->MultiCell(25,0,$head3,0,'L',0);
		
$X = 5;
$Y = 30;

$header=array('งวด','วันที่ชำระ','% ดอกเบี้ย','จำนวนเงินที่จ่าย','จำนวนวัน','ดอกเบี้ยที่เกิดขึ้นในรอบชำระครั้งนี้','ยอดดอกเบี้ยคงเหลือรอบก่อนยกมา','จำนวนหักเงินต้น','ยอดเงินต้นคงเหลือ ณ วันที่จ่าย','ยอดดอกเบี้ยคงเหลือ ณ วันที่จ่าย');
		
	
//Colors, line width and bold font
$pdf->SetFillColor(135,206,235);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128,0,0);
$pdf->SetLineWidth(.1);
$pdf->SetFont('AngsanaNew','',8);

//Header
$w=array(6,13,13,15,10,30,30,25,30,30);
$pdf->SetXY($X,$Y);
for($i=0;$i<count($header);$i++){
	$pdf->Cell($w[$i],6,iconv('UTF-8','windows-874',$header[$i]),1,0,'C',true);
}
$pdf->Ln();

//Color and font restoration
$pdf->SetFillColor(248,248,245);
$pdf->SetTextColor(0);
$pdf->SetFont('');

//Data
$fill=false;
		
$motnhlist = array('02','04','06','09','11');
$Y = $Y+3;
$rows = 1;
$interest_balance_old_show = '--';

for($z=1;$z<=$time;$z++){	
	if($rows == 80){	
		$Y = $Y+3;
		$pdf->SetXY($X,$Y);
		$pdf->Cell(array_sum($w),0,'','T');
		$pdf->AddPage();		
		$pdf->Image("images/water_line.png",20,50,180); 

		$pdf->SetXY(0,10);
		$pdf->SetFont('AngsanaNew','B',16);	
		$head=iconv('UTF-8','windows-874'," คำนวณยอดผ่อนชำระ");
		$pdf->MultiCell(210,3,$head,0,'C',0);
		$pdf->Ln();	
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(4,17);
		$head1=iconv('UTF-8','windows-874',"ผู้พิมพ์: ".$fullname_doer." วันเวลาที่พิมพ์: ".$timestamp);
		$pdf->MultiCell(70,0,$head1,0,'L',0);
				
		$pdf->SetXY(4,21);
		$head1=iconv('UTF-8','windows-874',"COST: ".$COST_SHOW."    NET: ".$NET_SHOW);
		$pdf->MultiCell(70,0,$head1,0,'L',0);
				
		$pdf->SetFont('AngsanaNew','B',8);
		$pdf->SetXY(4,24.5);
		$head3=iconv('UTF-8','windows-874',"หมายเหตุ ");
		$pdf->MultiCell(15,0,$head3,0,'L',0);	
		
		$pdf->SetTextColor(255,0,0);
		$pdf->SetXY(14,24.5);
		$head3=iconv('UTF-8','windows-874',"ตารางนี้ใช้เป็นตัวอย่างในการคำนวณเท่านั้น ตารางที่ถูกต้องขึ้นอยู่กับการผ่อนชำระของลูกค้า");
		$pdf->MultiCell(210,0,$head3,0,'L',0);
				
		$pdf->SetFont('AngsanaNew','',9);	
		$pdf->SetTextColor(0,0,0);	
		$pdf->SetXY(138,28);
		$head1=iconv('UTF-8','windows-874',"จำนวนเงินต้น : ".$capital_show." บาท ");
		$pdf->MultiCell(70,0,$head1,0,'R',0);
		
		$pdf->SetXY(95,28);
		$head3=iconv('UTF-8','windows-874',"ค่างวด : ".$period_show." บาท");
		$pdf->MultiCell(70,0,$head3,0,'R',0);
		
		$pdf->SetXY(65,28);
		$head3=iconv('UTF-8','windows-874',"จำนวนงวด : ".$time." เดือน");
		$pdf->MultiCell(70,0,$head3,0,'R',0);	
				
		$pdf->SetXY(40,28);
		$head2=iconv('UTF-8','windows-874',"ดอกเบี้ย : ".$interest_show." %");
		$pdf->MultiCell(65,0,$head2,0,'R',0);
		
		$pdf->SetXY(30,28);
		$head3=iconv('UTF-8','windows-874',"ชำระทุกวันที่ : $payday");
		$pdf->MultiCell(60,0,$head3,0,'L',0);
		
		$pdf->SetXY(52,28);
		$head3=iconv('UTF-8','windows-874',"ชำระงวดแรก : $first_pay_date");
		$pdf->MultiCell(30,0,$head3,0,'L',0);
		
		$pdf->SetXY(4,28);
		$head3=iconv('UTF-8','windows-874',"ทำสัญญา : $datestartcon");
		$pdf->MultiCell(25,0,$head3,0,'L',0);
				
				
		$X = 5;
		$Y = 30;
				
		$header=array('งวด','วันที่ชำระ','% ดอกเบี้ย','จำนวนเงินที่จ่าย','จำนวนวัน','ดอกเบี้ยที่เกิดขึ้นในรอบชำระครั้งนี้','ยอดดอกเบี้ยคงเหลือรอบก่อนยกมา','จำนวนหักเงินต้น','ยอดเงินต้นคงเหลือ ณ วันที่จ่าย','ยอดดอกเบี้ยคงเหลือ ณ วันที่จ่าย');
					
				
		//Colors, line width and bold font
		$pdf->SetFillColor(135,206,235);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.1);
		$pdf->SetFont('AngsanaNew','',8);
		
		//Header
		$w=array(6,13,13,15,10,30,30,25,30,30);
		$pdf->SetXY($X,$Y);
		for($i=0;$i<count($header);$i++){
			$pdf->Cell($w[$i],6,iconv('UTF-8','windows-874',$header[$i]),1,0,'C',true);
		}
		$pdf->Ln();
		
		//Color and font restoration
		$pdf->SetFillColor(248,248,245);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		
		//Data
		$fill=false;
					
		$motnhlist = array('02','04','06','09','11');
		$Y = $Y+3;
		$rows = 1;		
	}
							
	//แยกหาวัน
	list($sy,$sm,$sd) = explode("-",$datestart);
	if(in_array($sm,$motnhlist)){
		if($sd < $payday){
			$sd  = $payday;
		}
	}
												
	if($z == 1){
		//หางวดที่ต้องจ่ายถัดไป 
		$nextdue = func_time_next_month($datestart,$payday);
		//หาระยะห่างของวัน
		$day = func_time_datediff($datestart,$datestartcon,1);
		//คำนวณ จำนวนดอกเบี้ยที่ต้องจ่าย							
		$qryint = pg_query("select \"cal_interestTypeB\"('$capital_start','$interest_cal','$datestartcon','$datestart')");		
	}else{
		//หางวดที่ต้องจ่ายถัดไป 
		$nextdue = func_time_next_month($datestart,$sd);	
		//หาระยะห่างของวัน
		$day = func_time_datediff($datestart,$datelater,1);
		//คำนวณ จำนวนดอกเบี้ยที่ต้องจ่าย							
		$qryint = pg_query("select \"cal_interestTypeB\"('$capital_start','$interest_cal','$datelater','$datestart')");			
	}
						
	//คำนวณ จำนวนดอกเบี้ยที่ต้องจ่าย
	unset($interest_pay);
	list($interest_pay) = pg_fetch_array($qryint);
	$interest_pay_show = number_format($interest_pay,2);
	$interest_pay = $interest_pay + $interest_balance;									
														
	//คำนวณ จำนวนหักเงินต้น										
	if($interest_pay<0){
		$capital_pay_show = $period_show;								
	}else{
		if($interest_pay > $period){	
			$capital_pay = 0;
		}else{
			$capital_pay = $period-$interest_pay;
		}
		$capital_pay_show = number_format($capital_pay,2);
	}
									
	//คำนวณ ยอดเงินต้นคงเหลือ ณ วันที่จ่าย
	$capital_start = $capital_start-$capital_pay;
	$capital_start_show = number_format($capital_start,2);
														
	//คำนวณ ยอดดอกเบี้ยคงเหลือ ณ วันที่จ่าย	
	unset($interest_balance);
	if($interest_pay>$period){
		$interest_balance = $interest_pay-$period;
		$interest_balance_show = number_format($interest_balance,2);
		
	}else{
		$interest_balance_show = '---';	
	}						
		
	$data=array($z,$datestart,$interest_show,$period_show,$day,$interest_pay_show,$interest_balance_old_show,$capital_pay_show,$capital_start_show,$interest_balance_show);
			
	$Y = $Y+3;
	$pdf->SetXY($X,$Y);
	$pdf->Cell($w[0],3,$data[0],'LR',0,'C',$fill);
	$pdf->Cell($w[1],3,$data[1],'LR',0,'C',$fill);
	$pdf->Cell($w[2],3,$data[2],'LR',0,'C',$fill);
	$pdf->Cell($w[3],3,$data[3],'LR',0,'C',$fill);
	$pdf->Cell($w[4],3,$data[4],'LR',0,'C',$fill);
	$pdf->Cell($w[5],3,$data[5],'LR',0,'C',$fill);
	$pdf->Cell($w[6],3,$data[6],'LR',0,'C',$fill);
	$pdf->Cell($w[7],3,$data[7],'LR',0,'C',$fill);
	$pdf->Cell($w[8],3,$data[8],'LR',0,'C',$fill);
	$pdf->Cell($w[9],3,$data[9],'LR',0,'C',$fill);
	$pdf->Ln(0);
	// $fill=!$fill;
	$rows++;
			
	$datelater = $datestart;
	$datestart = $nextdue;
	$interest_balance_old_show = $interest_balance_show;
	
	if($capital_start <= 0){
		$z = $time+1;
	}		
} //end for

$Y = $Y+3;
$pdf->SetXY($X,$Y);
$pdf->Cell(array_sum($w),0,'','T');
				
$pdf->Output();
?>


