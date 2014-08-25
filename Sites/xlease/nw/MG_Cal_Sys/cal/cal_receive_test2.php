<?php


 


require_once("../../../core/core_functions.php");



//$show_con = $_REQUEST["show_con"];


$length = $_POST["cmort_length"];
$length = str_replace(',','',$length);
$int_normal = $_POST["intNormal"];


if(!is_numeric($length))
{
echo "กรุณากรอกตัวเลข" ;	
}else{



$credit = $_POST["credit"];
$credit = str_replace(',','',$credit);
if(!is_numeric($credit))
{
echo "กรุณากรอกตัวเลข" ;	
}
	else{

//$month = 1 ;


/*
$day = 30*$month;
$int_pay = ($credit*$intNormal*$day)/365 ;

$credit_net = $minpay-$int_pay ;
$next_month = $credit-$credit_net;
*/

/*-----------------------------------------------------------------

$intNormalC = (($int_normal/2)/12) ;
$int = (($intNormalC*$credit)/100)*$length;

$total = $int+$credit ;

$min_pay = round(($total/$length)) ;


$cmort_start=$_POST["cmort_start"];
list($dd,$mm,$yy)=split("/",$cmort_start);
$start_date = $yy."-".$mm."-".$dd ;
$pay_date=$_POST['pdate'];




$min_pay2 =round(($min_pay*(1/100)));
$min_pay = $min_pay+$min_pay2;
$last = func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay);
$p=0.1;
while($last > 0){

	$min_pay2 =($min_pay*($p/100));
	$min_pay = $min_pay+$min_pay2;
	$min_pay  = number_format($min_pay) ;
	$min_pay = str_replace(',','',$min_pay);
	$last = func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay);
	//วนลูปอีกรอบ
	
	//echo $min_pay." 2 " ;
	
	//$p = $p+0.1;
	
}
$p=0.01;

while($last < (-0.1*$min_pay)){
	//$p = $p-0.1;
	$min_pay2 =($min_pay*($p/100));
	$min_pay = $min_pay-$min_pay2;
	$min_pay  = number_format($min_pay) ;
	$min_pay = str_replace(',','',$min_pay);
	$last = func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay);
}


-----------------------------------------------*/
/*
	$p=0.1;
	while($last>0){
		//echo 111;
		
		$min_pay2 =round(($min_pay*($p/100)));
	$min_pay = $min_pay+$min_pay2;
	$last = func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay);
		
		$p = $p+0.025;
		
	}
*/
//$min_pay = $min_pay+$min_pay*($p/100);
	//echo "$min_pay";

//$ratio = ($last/$min_pay)*100*-1;
//$min_pay = $min_pay+$min_pay*($p/100);

$MinimumInsDate=$_POST["MinimumInsDate"];
//$MinimumInsDate="15/05/2010";
list($dd,$mm,$yy)=split("/",$MinimumInsDate);
$yy = $yy-543;
$start_date = $yy."-".$mm."-".$dd ;
$start =  MKTIME(0,0,0,$mm, $dd, $yy) ;
$pay_date=$_POST['pdate'];
$first_pay_date_m = $_POST['first_pay_date_m'];
$first_pay_date_y = $_POST['first_pay_date_y'];

$first_pay_date = $first_pay_date_y."-".$first_pay_date_m."-".$pay_date ;

//$pay_date=15;
$last =  MKTIME(0,0,0,$mm+$length, $pay_date, $yy) ;

$date = $last-$start;
$date = round(($date/60/60/24),4);
$date = $date/$length;
//echo $date."<br>";
$r = 1+(($int_normal/36500)*($date));
$min_pay =  round($credit*(pow($r,$length)*(1-$r))/(1-pow($r,$length)),2);
$min_pay2 = $min_pay;
$p = 0.1 ;  // %
$min_pay = $min_pay+($min_pay*($p/100));

$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
while($last > 0){
	
	$p=$p+0.1;
	$min_pay = $min_pay2+($min_pay2*($p/100));

$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
}
//$last = floor($last*(-1)/10)*10 ;
$min_pay2 = floor($min_pay/10)*10 ;
$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
if($last>0){
	$min_pay2 = ceil($min_pay/10)*10 ;	
	$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
}
if($last>0){
	$min_pay2 = (ceil($min_pay/10)*10)+10 ;	
	$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
}
$last2 =$last*(-1);
	$last = number_format($last*(-1)) ;
	//$last = number_format($last) ;
//echo func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay);
if($last2>$min_pay2)echo "กรุณากรอก ระยะเวลาในการจ่ายคืนสินเชื่อใหม่ ";
else
echo "ส่วนเกินงวดสุดท้ายประมาณ $last บาท ";

	}
}
	  /*
		if($show_con=="contacts_responder"){
			//update $_GET[cus_id])  column cpro_main
			
			echo " <select name=\"show_key\" id=\"show_key\" >
      <option selected=\"selected\" value=\"show_key\">[แสดงเฉพาะผู้ที่คีย์ข้อมูล]</option>
      <option 
                          value=\"show_0\">[แสดงเฉพาะผู้ที่ไม่คีย์ข้อมูล]</option>
  
    </select>";
		
		
		}
	     */
?>