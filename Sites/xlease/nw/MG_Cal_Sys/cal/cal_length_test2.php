<?php
session_start();
		session_register('length');
		session_register('min_pay');
		session_register('credit');
require_once("../../../core/core_functions.php");



$min_pay = $_POST["cmort_minpay"];
$int_normal = $_POST["intNormal"];

$MinimumInsDate=$_POST["MinimumInsDate"];
list($dd,$mm,$yy)=split("/",$MinimumInsDate);
$yy = $yy-543;
$start_date = $yy."-".$mm."-".$dd ;
$pay_date=$_POST['pdate'];
$first_pay_date_m = $_POST['first_pay_date_m'];
$first_pay_date_y = $_POST['first_pay_date_y'];

$first_pay_date = $first_pay_date_y."-".$first_pay_date_m."-".$pay_date ;

$credit = $_POST["credit"];
if($credit==''){
	echo "";}
	else{
$credit = str_replace(',','',$credit);
$min_pay = str_replace(',','',$min_pay);
$_SESSION['min_pay'] = number_format($min_pay,2);
$_SESSION['credit'] = number_format($credit,2);
//$minpay = $minpay-$minpay*(2/100) ;


//echo $date."<br>";
$r = 1+(($int_normal/36500)*(30.4167));
//echo $r;
$length = log($min_pay/(($credit*(1-$r))+$min_pay),$r);
 //
//$minpay = $minpay+($minpay*(2/100));
$length = number_format($length) ;	

//echo $intNormalC;

$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);

while($last>0){
		
		$length = $length+1;
		$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
}

if($last*(-1)>$min_pay){
	echo "";
}else{
	$_SESSION['length'] = number_format($length);
echo number_format($length) ;
//echo $last."x0000";
}
	}
?>