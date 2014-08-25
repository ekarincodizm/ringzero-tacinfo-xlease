<?PHP
// Database Connection (Postgres)
$conn_string = "host=172.16.2.5 port=5432 dbname=trmember user=dev password=nextstep";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
?>