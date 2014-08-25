<?php 
session_start();
$user_id = $_SESSION["av_iduser"];
include("../../config/config.php");
$array	= $_POST['arrayorder'];

if ($_POST['update'] == "update"){
	
	$count = 1;
	foreach ($array as $idval) {				
	$query = pg_query("UPDATE \"f_favorite_menu\" SET \"id_menunumber\" = '$count' WHERE \"id_menu\" = '$idval' and  \"id_user\" = '$user_id'");		
	$count++;	
	}
  
}
?>