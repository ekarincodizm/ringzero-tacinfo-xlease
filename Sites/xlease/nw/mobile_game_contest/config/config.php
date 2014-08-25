<?PHP
session_start();
//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');

//use for function mb_substr()
mb_internal_encoding('utf-8');

$seed = "xKMlCua6xT78zXQQ3b5Lw6JifFD4bZn5AoDF2sf";	//important

// Database Connection (Postgres)
$conn_string = "host=172.16.2.251 port=5432 dbname=ext_tacinfo user=dev password=nextstep";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");

function chk_null($data){
	if($data=="")
	{
		$chk_data = "null";
	}
	else
	{
		$chk_data = "'".$data."'";
	}
	
	return $chk_data;
}

