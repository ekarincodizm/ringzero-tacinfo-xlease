<?php
include("../../config/config.php");

$term = trim(pg_escape_string($_POST['officer']));

if(preg_match("/#/i",$term)){
	list($f_id,$f_name) = explode("#",$term);
	
	$sql = pg_query("SELECT \"id_user\" FROM fuser where \"id_user\" = '$f_id'");						 
	$row = pg_num_rows($sql);

	if($row>0){
		echo "T";
	}else {
		echo "F";
	}
	
} else {
	echo "F";
}



?>