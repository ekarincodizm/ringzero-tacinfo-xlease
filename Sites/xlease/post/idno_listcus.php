<?php
session_start();
header ("Content-type: text/html; charset=utf-8");
    include("../config/config.php");
	$id_no=$_GET["names"];
 
 
    
	$sql_select=pg_query("select A.*,B.* from  \"Fa1\"  A
	                      LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\"
	                     where (A.\"A_NAME\" like '%$id_no%') LIMIT(15) ORDER BY  A.\"A_NAME\"  ");
	
    $nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล ".",";
	}
	else
	{	
      while($result=pg_fetch_array($sql_select))
	  {
	  echo trim($result["CusID"])." ".trim($result["A_NAME"])." ".trim($result["A_SIRNAME"]).",";  
	  }
     }
 
?>