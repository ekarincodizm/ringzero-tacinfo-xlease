<?php
session_start();
include("../../config/config.php");

$term = $_GET['term'];

$sql = pg_query("SELECT title, day, month, year FROM v_calendar_events_all WHERE flag = '1' AND title LIKE '%$term%' ");

$num_rows = pg_num_rows($sql);

while($res=pg_fetch_array($sql))
{
	if($res["month"] <10){
		$format_month = "0".$res["month"];
	}else{
		$format_month = $res["month"];
	}
			
	$events_date = 
	$t1 = $res["title"];
	$t2 = $res["year"]."-".$format_month."-".$res["day"];
	
    $dt['value'] = $t1;
    $dt['label'] = "ชื่อเรื่อง:{$t1} วันที่นัดหมาย:{$t2} ";
    $matches[] = $dt;
}

if($num_rows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);

?>