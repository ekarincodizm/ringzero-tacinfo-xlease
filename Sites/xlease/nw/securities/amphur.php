<?php
session_start();
include("../../config/config.php");

$proID = $_GET['proID'];
if($proID==""){

	echo "------";
}else{

if($proID == '01'){
	$proID = '64';
}else if($proID == '02'){
	$proID = '1';
}else if($proID == '03'){
	$proID = '56';
}else if($proID == '04'){
	$proID = '34';
}else if($proID == '05'){
	$proID = '49';
}else if($proID == '06'){
	$proID = '28';
}else if($proID == '07'){
	$proID = '13';
}else if($proID == '08'){
	$proID = '15';
}else if($proID == '09'){
	$proID = '11';
}else if($proID == '10'){
	$proID = '9';
}else if($proID == '11'){
	$proID = '25';
}else if($proID == '12'){
	$proID = '69';
}else if($proID == '13'){
	$proID = '72';
}else if($proID == '14'){
	$proID = '14';
}else if($proID == '15'){
	$proID = '50';
}else if($proID == '16'){
	$proID = '17';
}else if($proID == '17'){
	$proID = '58';
}else if($proID == '18'){
	$proID = '36';
}else if($proID == '19'){
	$proID = '19';
}else if($proID == '20'){
	$proID = '63';
}else if($proID == '21'){
	$proID = '47';
}else if($proID == '22'){
	$proID = '3';
}else if($proID == '23'){
	$proID = '76';
}else if($proID == '24'){
	$proID = '43';
}else if($proID == '25'){
	$proID = '97';
}else if($proID == '26'){
	$proID = '20';
}else if($proID == '27'){
	$proID = '4';
}else if($proID == '28'){
	$proID = '62';
}else if($proID == '29'){
	$proID = '16';
}else if($proID == '30'){
	$proID = '74';
}else if($proID == '31'){
	$proID = '5';
}else if($proID == '32'){
	$proID = '44';
}else if($proID == '33'){
	$proID = '65';
}else if($proID == '34'){
	$proID = '73';
}else if($proID == '35'){
	$proID = '53';
}else if($proID == '36'){
	$proID = '52';
}else if($proID == '37'){
	$proID = '66';
}else if($proID == '38'){
	$proID = '32';
}else if($proID == '39'){
	$proID = '37';
}else if($proID == '40'){
	$proID = '75';
}else if($proID == '41'){
	$proID = '24';
}else if($proID == '42'){
	$proID = '68';
}else if($proID == '43'){
	$proID = '12';
}else if($proID == '44'){
	$proID = '55';
}else if($proID == '45'){
	$proID = '33';
}else if($proID == '46'){
	$proID = '7';
}else if($proID == '47'){
	$proID = '40';
}else if($proID == '48'){
	$proID = '39';
}else if($proID == '49'){
	$proID = '22';
}else if($proID == '50'){
	$proID = '35';
}else if($proID == '51'){
	$proID = '70';
}else if($proID == '52'){
	$proID = '71';
}else if($proID == '53'){
	$proID = '2';
}else if($proID == '54'){
	$proID = '60';
}else if($proID == '55'){
	$proID = '59';
}else if($proID == '56'){
	$proID = '10';
}else if($proID == '57'){
	$proID = '18';
}else if($proID == '58'){
	$proID = '8';
}else if($proID == '59'){
	$proID = '57';
}else if($proID == '60'){
	$proID = '67';
}else if($proID == '61'){
	$proID = '21';
}else if($proID == '62'){
	$proID = '51';
}else if($proID == '63'){
	$proID = '31';
}else if($proID == '64'){
	$proID = '27';
}else if($proID == '65'){
	$proID = '26';
}else if($proID == '66'){
	$proID = '29';
}else if($proID == '67'){
	$proID = '41';
}else if($proID == '68'){
	$proID = '48';
}else if($proID == '69'){
	$proID = '23';
}else if($proID == '70'){
	$proID = '6';
}else if($proID == '71'){
	$proID = '45';
}else if($proID == '72'){
	$proID = '38';
}else if($proID == '73'){
	$proID = '61';
}else if($proID == '74'){
	$proID = '54';
}else if($proID == '75'){
	$proID = '30';
}else if($proID == '76'){
	$proID = '42';
}else if($proID == '77'){
	$proID = '46';
}

 ?>

 <select name="amphur" id="amphur" onchange="caldis()">
 <option value=""> -- อำเภอ/เขต-- </option>
<?php		
														
													
	$objQuery = pg_query("SELECT \"AMPHUR_ID\",\"AMPHUR_NAME\" FROM amphur where \"PROVINCE_ID\" = '$proID' ");
	while($objResuut = pg_fetch_array($objQuery))
	{ 
		$amid=$objResuut["AMPHUR_ID"];
		$amname=$objResuut["AMPHUR_NAME"];
		
		echo "<option value=\"$amid\">$amname</option>";
	}		


?>	</select>
 
 <?php } ?>
 
 
 
 
 
 
 
 
 
 
 