<?php
error_reporting(E_ERROR);
session_start();

$project['name'] = "Report Thaiace";

$host = "172.16.2.251";
$port = 5432;
$db = "devxleasenw80";
$user = "postgres";
$pass = "nextstep";

//$conn = "host=$host port=$port dbname=$db user=$user password=$pass sslmode=require";
$conn = "host=$host port=$port dbname=$db user=$user password=$pass";
$db_connect = pg_connect($conn) or die("CAN'T CONNECT DB $db !");
?>