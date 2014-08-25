<?php
session_start();
include("../../config/config.php");

$COMID = pg_escape_string($_GET['COMID']);
$i = 1;
	
	
	
	$objQuery = pg_query("SELECT * FROM \"fu_empcontact\" where \"comID\" = '$COMID' order by \"empconID\"");
	while($objResuut = pg_fetch_array($objQuery))
	{ 
		
		$b=$objResuut["empconID"];
		$c=$objResuut["empcon_name"];
		$d=$objResuut["empcon_email"];
		
		
		
		echo "<input type=\"checkbox\" name=\"CH[]\" id=\"CH[$i]\" value=\"$b\">$c : $d";
		echo "<p>";
	
		
		$i++;
	}		



?>