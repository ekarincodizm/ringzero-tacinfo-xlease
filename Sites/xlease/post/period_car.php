<?php
session_start();
include("../config/config.php");

$brand = $_POST['brand'];
$time = $_POST['time'];
$down = $_POST['down'];

if($brand == "" || $time == "" || $down== "" ){

	echo "กรุณาเลือกรุ่นรถยนต์ เงินดาวน์ จำนวนงวด ก่อนครับ!55 ";
}else{

	
	$objQuery = pg_query("select  \"period\" from \"Fp_package\" where \"numtest\" = '$brand' and \"month_payment\" = '$time' and \"down_payment\" = '$down' ");
	$row = pg_num_rows($objQuery);
	if($row == 0 ){
	echo "กรุณาเลือกรุ่นรถยนต์ เงินดาวน์ จำนวนงวด ก่อนครับ!";
	}else{
	while($objResuut = pg_fetch_array($objQuery)){
	
	echo $period = trim($objResuut["period"]);
	?>	
	
	<?PHP }	

	} 
}
 ?>
	
		
	
