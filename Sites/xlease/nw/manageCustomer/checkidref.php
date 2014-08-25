<?php
include("../../config/config.php");


$term = trim($_POST['id']);
$term = str_replace("-","",$term);
$term = str_replace(" ","",$term);
if($term == null){
	
	echo "null";

}else{
	$sql = "SELECT \"N_CARDREF\" FROM \"Fn\" where \"N_CARDREF\" = '$term'";
			
	$results=pg_query($sql);						 
	$row = pg_num_rows($results);
	$re = pg_fetch_array($results);


	if($row == 0 || empty($row)){

	echo "YES";

	}else{
	echo "No";
	}
}
?>