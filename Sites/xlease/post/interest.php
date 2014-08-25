<?php
include("../config/config.php");

$brand = $_POST['brand'];
$time = $_POST['time'];
$down = $_POST['down'];
//$period = $_POST['period'];

if($period == "fail"){

		echo "กรุณาเลือกค่างวดก่อนครับ";
}else{
	
	$objQuery = pg_query("select  \"interest\" from \"Fp_package\" where  \"numtest\" = '$brand' and \"month_payment\" = '$time' and \"down_payment\" = '$down' ");
	$objResuut = pg_fetch_array($objQuery);


	echo $interest=trim($objResuut['interest']);
		

} ?>
	
		
	
