<?php
session_start();
include("../config/config.php");

$brand = $_GET['brand'];

if($brand==""){

echo " เลือกรุ่นของรถยนต์ด้วยครับ ";

}else{
	
	$objQuery = pg_query("select  distinct \"price_not_accessory\" from \"Fp_package\" where \"numtest\" = '$brand' ");
	$objResuut = pg_fetch_array($objQuery);
	$row = pg_num_rows($objQuery);

		
	$car_price=trim($objResuut["price_not_accessory"]);
			
?>	<input type="hidden" name="price_car" id="price_car" value="<?php echo $car_price?>" readOnly>
<span id="price_cartext" ><?php echo number_format($car_price,2) ?></span>
	<?php } ?>
		
	
