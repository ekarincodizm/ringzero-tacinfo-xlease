<?php
session_start();
    include("../../config/config.php");
	$namess=$_GET["names"];
 
	$sql_select=pg_query("select * from  \"Fa1\" where (\"A_NAME\" like '%$namess%' ) OR (\"A_SIRNAME\" like '%$namess%') order by \"A_NAME\" LIMIT(15)  ");
	
	$nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล".",";
	}
	else
	{	
    while ($result=pg_fetch_array($sql_select))
     {	
	
	echo trim($result["CusID"])." ".trim($result["A_FIRNAME"])." ".trim($result["A_NAME"])." ".trim($result["A_SIRNAME"]).",";  
	 //$a=array('s1','s2');
	 
	 //echo $a;
	
     }
    }	 
?> 