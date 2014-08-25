<?php
session_start();
		session_register('length');
		session_register('min_pay');
		session_register('credit');
require_once("../../../core/core_functions.php");



//$show_con = $_REQUEST["show_con"];

$length = $_POST["cmort_length"];
$length = str_replace(',','',$length);


if($length>360){
	echo "จำนวนเดือนต้องไม่เกิน 360" ;
}else{
$int_normal = $_POST["intNormal"];
$_SESSION['length'] = number_format($length);

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
		$_SESSION['credit'] = number_format($credit,2);
/*
$intNormalC = (($int_normal/2)/12) ;
$int = (($intNormalC*$credit)/100)*$length;

$total = $int+$credit ;

$min_pay = round(($total/$length)) ;


$MinimumInsDate=$_POST["MinimumInsDate"];
list($dd,$mm,$yy)=split("/",$MinimumInsDate);
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
*/

$MinimumInsDate=$_POST["MinimumInsDate"];
//$MinimumInsDate="15/05/2010";
list($dd,$mm,$yy)=split("/",$MinimumInsDate);
$yy = $yy-543;
$start =  MKTIME(0,0,0,$mm, $dd, $yy) ;
$start_date = $yy."-".$mm."-".$dd ;
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
//echo  "r = ".$r;
$min_pay =  round($credit*(pow($r,$length)*(1-$r))/(1-pow($r,$length)),2);//

$min_pay2 = $min_pay;
$p = 0.1 ;  // %minpay ที่เพิ่ม
$min_pay = $min_pay+$min_pay*($p/100);
$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);

while($last > 0){
	
	$p=$p+0.1;
	$min_pay = $min_pay2+($min_pay2*($p/100));

$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
}
$min_pay2 = floor($min_pay/10)*10 ;
$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
if($last>0){
	$min_pay2 = ceil($min_pay/10)*10 ;	
	$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
}
if($last>0){
	$min_pay2 = (ceil($min_pay/10)*10)+10 ;	
	$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay2,$first_pay_date);
}
//echo $min_pay;
$_SESSION['min_pay'] = number_format($min_pay2);
if(($last*(-1))>$min_pay2)echo "จำนวนเดือนมากเกินไป ";
else
echo number_format($min_pay2) ;

//$credit =  $min_pay/(pow($r,$length)*(1-$r))/(1-pow($r,$length))   ;


	}
}

}
?>