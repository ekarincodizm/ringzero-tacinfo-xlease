<?php
include("../../config/config.php");
	$res_contractid=pg_escape_string($_POST['contractid']);	
	$qry_main = pg_query("SELECT \"contractID\" FROM \"thcap_contract\" WHERE  \"contractID\"='$res_contractid'");
	$numrows = pg_num_rows($qry_main);
	if($numrows ==0){
		echo 0;
	}
	else{
		echo 1;		
	}
?>