<?php
/*pg sql */

	$conn_string = "host=172.16.2.5  port=5432 dbname=devxleasenw user=dev	password=nextstep";
	$db_connect = pg_connect($conn_string) or die("can't connect");


/* mssql */
   $dbserver="172.16.2.5";
   $db_name="Taxiacc_test";
   $dbusername="taxiuser1";
   $dbpassword="99910";
   $conn=mssql_connect($dbserver,$dbusername,$dbpassword) or die("can not connect db");
   $s=mssql_select_db($db_name) or die("Can't select database");
   echo "connect ms success";
?>
