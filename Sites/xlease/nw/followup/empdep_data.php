<?php
session_start();
include("../../config/config.php");

$DEPID = pg_escape_string($_GET['depID']);
$i = 0;
	
	
	
	$objQuery = pg_query("SELECT * FROM \"fuser\" where \"user_dep\" = '$DEPID' order by \"user_dep\"");
	$row = pg_num_rows($objQuery);
	if(empty($row)){
	echo "--- ไม่มีพนักงาน ----";
	}else{
	while($objResuut = pg_fetch_array($objQuery))
	{ 
		
		$b=$objResuut["id_user"];
		$c=$objResuut["fname"];
		$d=$objResuut["lname"];
		
		
		
		echo "<input type=\"checkbox\" name=\"CH[]\" id=\"CH[$i]\" value=\"$b\">$c $d";
		echo "<p>";
	
		
		$i++;
	}		
}


?>