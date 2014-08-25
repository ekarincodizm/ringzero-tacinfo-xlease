<?php
session_start();
    include("../config/config.php");
	$car_name=$_GET["carname"];
 
	//$sql_select=pg_query("select * from  \"VCarregistemp\" where  (\"C_CARNUM\" like '%$car_name%') OR (\"C_REGIS\" like '%$car_name%') LIMIT(10)");	
	$sql_select=pg_query("select * from  \"Fc\" a
	inner join (select MAX(\"CarID\") as carid from \"Fc\" group by \"C_CARNUM\") b on a.\"CarID\"=b.carid
	where  (\"C_CARNUM\" like '%$car_name%') OR (\"C_REGIS\" like '%$car_name%')");	

	$nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล".",";
	}
	else
	{	
	
    while ($result=pg_fetch_array($sql_select))
    {	
	 echo  trim($result[CarID])."ทะเบียน ".trim($result[C_REGIS])."เลขตัวถัง ".trim($result[C_CARNUM]).",";  
    }
	
   }	
?>