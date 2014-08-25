<?php
session_start();
include("../config/config.php");
$namess=trim($_GET["names"]);
//$sql_select=pg_query("select * from  \"VSearchCus\" where (\"full_name\" like  '%$namess%') LIMIT(25)");
$sql_select=pg_query("select \"VSearchCus\".\"CusID\" , \"VSearchCus\".\"full_name\" , \"Fn\".\"N_IDCARD\" from \"VSearchCus\" , \"Fn\" where \"VSearchCus\".\"CusID\" = \"Fn\".\"CusID\" and (\"VSearchCus\".\"full_name\" like  '%$namess%' or \"Fn\".\"N_IDCARD\" like '%$namess%')");
	
	$nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล".",";
	}
	else
	{
	//$sql_se=pg_query("select * from  \"VSearchCus\" where (\"full_name\" like  '%$namess%') LIMIT(25)");
	$sql_se=pg_query("select \"VSearchCus\".\"CusID\" , \"VSearchCus\".\"full_name\" , \"Fn\".\"N_IDCARD\" from \"VSearchCus\" , \"Fn\" where \"VSearchCus\".\"CusID\" = \"Fn\".\"CusID\" and (\"VSearchCus\".\"full_name\" like  '%$namess%' or \"Fn\".\"N_IDCARD\" like '%$namess%')");
    while ($result=pg_fetch_array($sql_se))
     {	
	  echo trim($result["CusID"])."#".trim($result["full_name"])."#".trim($result["N_IDCARD"]).",";  
     }
   }
?> 
