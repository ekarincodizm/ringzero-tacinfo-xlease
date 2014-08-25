<?php
session_start();
    include("../config/config.php");
	$id_no=pg_escape_string($_GET["names"]);
 
 
    
	$sql_select=pg_query("select * from  \"VCarregistemp\" 
	                     where (\"IDNO\" like '%$id_no%') OR (\"C_REGIS\" like '%$id_no%') LIMIT(10) ");
	
    $nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล ".",";
	}
	else
	{	
     while ($result=pg_fetch_array($sql_select))
     {	
	  if($result["LockContact"]=='t')
	  {
	    $slock=" xxx Locked xxx ";
	  }
	  else
	  {
	    $slock="";
	  }
	  
	  
	  echo trim($result[IDNO]).$slock." ทะเบียน ".$result[C_REGIS].",";  
     }
   }
	
?>