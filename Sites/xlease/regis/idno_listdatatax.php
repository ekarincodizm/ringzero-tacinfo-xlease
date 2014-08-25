<?php
session_start();
    include("../config/config.php");
	$id_no=$_GET["names"];
 
 
    
	$sql_select=pg_query("select A.*,B.*,C.* from  \"Fp\"  A
	                      LEFT OUTER JOIN \"VCarregistemp\" B ON B.\"IDNO\"=A.\"IDNO\"
	                      LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=A.\"CusID\"
	                     where (A.\"IDNO\" like '%$id_no%') OR (B.\"C_REGIS\" like '%$id_no%') OR (B.\"C_CARNUM\" like '%$id_no%') OR  (C.\"A_NAME\" like '%$id_no%')  ");
	
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
	  
	  
	  echo trim($result[IDNO]).$slock." ทะเบียน ".$result[C_REGIS]." ชื่อ ".$result[A_NAME]." เลขตัวถัง ".$result[C_CARNUM].",";  
     }
   }
	
?>