<?php
/*pg sql tac*/

	$conn_string = "host=172.16.2.251  port=5432 dbname=devtac_app user=tac	password=@3nextstep";
	$db_connect = pg_connect($conn_string) or die("can't connect");


/* mssql tac*/
   $dbserver="172.16.2.5";
 $db_name="Taxiacc";
	$dbusername="dbb";
	$dbpassword="K863'[4o";
   $conn=mssql_connect($dbserver,$dbusername,$dbpassword) or die("can not connect db");
   $s=mssql_select_db($db_name) or die("Can't select database");
  // echo "connect ms success";
?>
