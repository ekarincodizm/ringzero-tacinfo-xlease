<?php
session_start();
    include("../config/config.php");
	$id_no=pg_escape_string($_GET["names"]);
 
 
    
	$sql_select=pg_query("select DISTINCT \"R_Receipt\" from  \"Fr\" where \"R_Receipt\" like '%$id_no%'  ");
	$nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล ".",";
	}
	else
	{	
     while ($result=pg_fetch_array($sql_select))
     {	
	  echo trim($result["R_Receipt"]).",";  
     }
   }
	
?>