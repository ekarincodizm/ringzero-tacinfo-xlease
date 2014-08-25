<?php
session_start();
include("../../config/config.php");


$brand = $_POST['brand'];
$check = $_POST['price'];

if($brand != ""){
			if($brand == 'fail'){

				echo null;
				exit();
			}

			if($check == 'notaccessory'){
				
				$objQuery = pg_query("select  distinct \"price_not_accessory\" from \"Fp_package\" where \"numtest\" = '$brand' ");
				$objResuut = pg_fetch_array($objQuery);
				$row = pg_num_rows($objQuery);

					if($row == 0){
						echo " Not Price..";
					}else{
						echo $car_price=trim($objResuut["price_not_accessory"]);
					}
			}else if($check == 'accessory'){
				
				$objQuery = pg_query("select  distinct \"price_accessory\" from \"Fp_package\" where \"numtest\" = '$brand' ");
				$objResuut = pg_fetch_array($objQuery);
				$row = pg_num_rows($objQuery);

					if($row == 0){
						echo " Not Price..";
					}else{
						echo $car_price=trim($objResuut["price_accessory"]);
					}
			}else if($check == 'num'){
				
						echo $brand;		
			}else {
					
						echo null;
			}
}		
?>		


	
		
	
