<?php
$host = "172.16.2.251";
$us = "postgres";
$pw = "nextstep";
$db_tha="devxleasenw75";
$port = "5432";
$conn_tha=pg_connect("host=$host port=$port dbname=$db_tha user=$us password=$pw") or die ("Could not connect to PGSQL");
?>