<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
include('../../../config/config.php');
$status = 0 ;
pg_query("BEGIN");


$sql = "SELECT \"DISTRICT_ID\",\"DISTRICT_NAME\" FROM district where \"DISTRICT_NAME\" LIKE '%*%'";
$query = pg_query($sql);
while($re = pg_fetch_array($query)){

$id = $re['DISTRICT_ID'];
$name = $re['DISTRICT_NAME'];

$newname = str_replace("*","",$name);
$newname = trim($newname);
	$sql1 = "UPDATE district SET  \"DISTRICT_NAME\"='$newname' WHERE \"DISTRICT_ID\"='$id'";
	$query1 = pg_query($sql1);
		
		if($query1){}else{ $status++; };


}

$sql = "SELECT \"AMPHUR_ID\",\"AMPHUR_NAME\" FROM amphur where \"AMPHUR_NAME\" LIKE '%*%'";
$query = pg_query($sql);
while($re = pg_fetch_array($query)){

$id = $re['AMPHUR_ID'];
$name = $re['AMPHUR_NAME'];

$newname = str_replace("*","",$name);
$newname = trim($newname);
	$sql1 = "UPDATE amphur SET  \"AMPHUR_NAME\"='$newname' WHERE \"AMPHUR_ID\"='$id'";
	$query1 = pg_query($sql1);
		
		if($query1){ echo $sql1."<p>";  }else{ $status++; };


}

$sql = "SELECT \"DISTRICT_ID\",\"DISTRICT_NAME\" FROM district";
$query = pg_query($sql);
while($re = pg_fetch_array($query)){

$id = $re['DISTRICT_ID'];
$name = $re['DISTRICT_NAME'];

$newname = str_replace(" ","",$name);
$newname = str_replace("  ","",$name);
$newname = str_replace("เขต","",$name);
$newname = str_replace("อ.","",$name);
$newname = str_replace("จ."," ",$name);
$newname = trim($newname);
	$sql1 = "UPDATE district SET  \"DISTRICT_NAME\"='$newname' WHERE \"DISTRICT_ID\"='$id'";
	$query1 = pg_query($sql1);
		
		if($query1){}else{ $status++; };


}

$sql = "SELECT \"AMPHUR_ID\",\"AMPHUR_NAME\" FROM amphur";
$query = pg_query($sql);
while($re = pg_fetch_array($query)){

$id = $re['AMPHUR_ID'];
$name = $re['AMPHUR_NAME'];

$newname = str_replace(" ","",$name);
$newname = str_replace("  ","",$name);
$newname = str_replace("อ.","",$name);
$newname = str_replace("จ."," ",$name);
$newname = str_replace("เขต","",$name);
$newname = trim($newname);
	$sql1 = "UPDATE amphur SET  \"AMPHUR_NAME\"='$newname' WHERE \"AMPHUR_ID\"='$id'";
	$query1 = pg_query($sql1);
		
		if($query1){ echo $sql1."<p>";  }else{ $status++; };


}




if($status == 0){
	pg_query("COMMIT");
	echo "Success";
}else{
	pg_query("ROLLBACK");
	echo "Error!";

}


?>