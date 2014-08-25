<?php
session_start();
include("../../config/config.php");

$amphurID = $_GET['ampID'];

if($amphurID==""){

	echo "------";
}else{
?>

 <select name="district" id="district" onchange="">
  <option value=""> -- ตำบล/แขวง -- </option>
<?php		
														
													
	$objQuery = pg_query("SELECT \"DISTRICT_NAME\" FROM district where \"AMPHUR_ID\" = '$amphurID' ");
	while($objResuut = pg_fetch_array($objQuery))
	{ 
		$disname=$objResuut["DISTRICT_NAME"];
		
		echo "<option value=\"$disname\">$disname</option>";
	}		


?>	</select>
 <?php } ?>
 
 
 
 
 
 
 
 
 
 
 
 