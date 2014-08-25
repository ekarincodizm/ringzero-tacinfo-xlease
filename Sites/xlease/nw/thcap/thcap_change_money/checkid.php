<?php
include("../../../config/config.php");

$term = trim($_POST['id']);

$sql = "SELECT \"contractID\" FROM \"thcap_contract\" where  \"contractID\" = '$term'";
		
$results=pg_query($sql);						 
$row = pg_num_rows($results);
$re = pg_fetch_array($results);


if($row == 0 || empty($row))
{
		echo "YES#$term";	
}else{
	echo "No#$term";
}
?>