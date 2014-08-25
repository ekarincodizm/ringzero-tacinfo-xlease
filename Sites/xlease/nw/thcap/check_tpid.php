<?php
include("../../config/config.php");

$term = trim(pg_escape_string($_POST['tpid']));

$sql = pg_query("SELECT \"tpID\" FROM account.\"thcap_typePay\" where \"tpID\" = '$term'");						 
$row = pg_num_rows($sql);

	if($row>0){
		echo "F";
	}else {
		echo "T";
	}
?>