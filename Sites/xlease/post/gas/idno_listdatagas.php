<?php
session_start();
    include("../../config/config.php");
	$id_no=$_GET["names"];
 	$sql_select=pg_query("select A.*,B.*,C.* from  \"Fp\"  A
	                      LEFT OUTER JOIN \"FGas\" B ON B.\"GasID\"=A.asset_id
	                      LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=A.\"CusID\"
	                     where (A.\"IDNO\" like '%$id_no%') and (A.asset_id like 'GAS%') OR (B.car_regis like '%$id_no%') OR (B.carnum like '%$id_no%') OR  (C.\"A_NAME\" like '%$id_no%') LIMIT(10)   ");
	
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
	  
	  
	  echo trim($result[IDNO]).$slock." ทะเบียน ".trim($result[car_regis])." ชื่อ ".trim($result[A_NAME])." เลขตัวถัง ".trim($result[carnum])." เลขถังแก๊ส ".trim($result[gas_number]).",";  
     }
   }
	
?>