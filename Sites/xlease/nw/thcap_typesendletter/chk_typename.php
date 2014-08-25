<?php
include("../../config/config.php");
	$res_name=pg_escape_string($_POST['name']);		
	$qrysendName=pg_query("SELECT  \"auto_id\" FROM thcap_letter_head where \"sendName\"='$res_name'  ");	
	$numrows = pg_num_rows($qrysendName);
	if($numrows ==0){
		echo 0;
	}
	else{
		echo 1;		
	}
?>