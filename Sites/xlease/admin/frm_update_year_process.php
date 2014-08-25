<?php
include("../config/config.php");

$schemas = pg_escape_string($_POST["schemas"]);
$table = pg_escape_string($_POST["table"]);
$column = pg_escape_string($_POST["column"]);

if($schemas == "public" || $schemas == ""){
	$schemas2 = "";
}else{
	$schemas2 = "$schemas.";
}
	$query = pg_query("select \"$column\" from $schemas2\"$table\"");
//	pg_query("BEGIN WORK");
	$status = 0;
	while($result_name=pg_fetch_array($query)){
		$year_old = $result_name["$column"];
		$year = substr($year_old,0,4);
		$month = substr($year_old,5,2);
		$day = substr($year_old,8,2);
		
		if($year >= 2400){
			$year2 = $year - 543;
		}else{
			$year2 = $year;
		}
		$year_new = $year2."-".$month."-".$day;
		
	$update = "update $schemas2\"$table\" set \"$column\" = '$year_new' where \"$column\" = '$year_old'";
	if(!$result_up=pg_query($update)){
		$status++;
		echo "ERROR: $year_old</br>";
	}
	else{
		echo "$year_old -> $year_new </br>";
	}
} //end while
echo "มี Error ทั้งหมด:  $status รายการ";

//	if($status == 0){
//		pg_query("COMMIT");
//		echo "update ข้อมูลเรียบร้อยแล้ว";
//	}else{
//		pg_query("ROLLBACK");
//	}
echo "<input type=\"button\" value=\"back\" onclick=\"window.location='frm_update_year.php'\">";
?>