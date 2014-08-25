<?php
session_start();
    include("../config/config.php");
	$id_no=pg_escape_string($_GET["names"]);
 
 
    
	$sql_select=pg_query("select DISTINCT B.\"R_Receipt\",C.\"O_RECEIPT\" from  \"Fr\" B 
	                      LEFT OUTER JOIN \"FOtherpay\" C
						  where (B.\"R_Receipt\" like '%$id_no%') OR(C.\"O_RECEIPT\" like '%$id_no%')   ");
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