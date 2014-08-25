<?php

require_once("../../../core/core_functions.php");


$min_pay = $_POST["cmort_minpay"];
$int_normal = $_POST["intNormal"];

$MinimumInsDate=$_POST["MinimumInsDate"];
list($dd,$mm,$yy)=split("/",$MinimumInsDate);//แปลงวันที่
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
$credit = str_replace(',','',$credit);//แปลงตัวเลข
$min_pay = str_replace(',','',$min_pay);
//$minpay = $minpay-$minpay*(2/100) ;

$r = 1+(($int_normal/36500)*(30.4167));
//echo $r;
$length = log($min_pay/(($credit*(1-$r))+$min_pay),$r);
 //
//$minpay = $minpay+($minpay*(2/100));

// echo $length." ".$credit." ".$min_pay." ".$intNormalC;
//$minpay = $minpay+($minpay*(2/100));
	
//echo $intNormalC;

$length = number_format($length) ;
$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
//echo $last." ".$length;
while($last>0){ //ถ้าไม่ติดลบ +เดือนอีก1เดือน จากเดิม แล้วคำนวนใหม่
		
		$length = $length+1;
		$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date);
		//echo $last." ".$length;
		
}

$last= $last*(-1);

if($last>$min_pay){
	echo "<font color=red>กรุณากรอกจำนวนเงินขั้นต่ำใหม่ !!!</font>";
}else{
//echo 

$last = number_format($last) ;

echo "ส่วนเกินงวดสุดท้ายประมาณ ".$last." บาท";
	}
	}
?>