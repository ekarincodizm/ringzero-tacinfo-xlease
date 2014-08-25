<?php
/*pg sql xlease*/

	$conn_string = "host=172.16.2.251  port=5432 dbname=devxleasenw12 user=dev	password=nextstep";
	$db_connect = pg_connect($conn_string) or die("can't connect");


?>
