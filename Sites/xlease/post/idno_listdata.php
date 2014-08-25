<?php
session_start();
    include("../config/config.php");
	$id_no = pg_escape_string($_GET["names"]);
  
    
	$sql_select=pg_query("select A.\"LockContact\",A.\"IDNO\",B.\"C_REGIS\",\"A_NAME\",\"C_CARNUM\" from  \"Fp\"  A
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
	  
	  
	  echo trim($result[IDNO]).$slock." ทะเบียน ".trim($result[C_REGIS])." ชื่อ ".trim($result[A_NAME])." เลขตัวถัง ".trim($result[C_CARNUM]).",";  
     }
   }
	
?>