<?php
set_time_limit(0);
include("../../config/config.php");

pg_query("BEGIN WORK");
$status = 0;

$query=pg_query("select \"Capacity\",\"CarID\" from insure.\"InsureForce\" group by \"Capacity\",\"CarID\"");
while($result=pg_fetch_array($query)){
	$Capacity=$result["Capacity"];
	$CarID=$result["CarID"];
	$updatefc="update \"Fc\" a set \"C_CAR_CC\"='$Capacity' where a.\"CarID\"='$CarID' and a.\"C_CAR_CC\" is null";
	if($resultup=pg_query($updatefc)){
	}else{
		$error=$resultup;
		$status++;
	}
}
if($status == 0){
	pg_query("COMMIT");
	echo "Update OK";
}else{
	pg_query("ROLLBACK");
	echo $error."<br>";
}	
?>