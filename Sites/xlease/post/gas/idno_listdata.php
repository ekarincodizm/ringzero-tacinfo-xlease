<?php
session_start();
    include("../../config/config.php");
	$id_no=$_GET["names"];
 
	$sql_select=pg_query("select * from  \"Fp\" where \"IDNO\" like '%$id_no%' order by \"IDNO\" ");
	
    while ($result=pg_fetch_array($sql_select))
    {	
	 echo "$result[IDNO],";  
    }
	
?>