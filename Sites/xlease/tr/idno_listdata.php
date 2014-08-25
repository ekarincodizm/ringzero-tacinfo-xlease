<?php
session_start();
    include("../config/config.php");
	$id_no=$_GET["q"];
  
    
	$sql_select=pg_query("select * from  \"VCarregistemp\" 
	                     where (\"IDNO\" like '%$id_no%') OR (\"C_REGIS\" like '%$id_no%') OR (\"C_CARNUM\" like '%$id_no%') OR  (\"full_name\" like '%$id_no%')  ");
	
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
	  
	  
	  echo trim($result[IDNO]).$slock." ทะเบียน ".trim($result[C_REGIS])." ชื่อ ".trim($result[full_name])." เลขตัวถัง ".trim($result[C_CARNUM]).",";  
     }
   }
	
?>