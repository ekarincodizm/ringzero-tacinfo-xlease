<?php
	$res_contractid=pg_escape_string($_POST['contractid']);
	//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
	$contractid_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/'; 
	$contractid_replace = "yes";	
	// ที่ replace ได้							
	$res_contractid = preg_replace($contractid_format,$contractid_replace,$res_contractid);
	if($res_contractid =="yes"){
		echo 0;
	}
	else{
		echo 1;		
	}
?>