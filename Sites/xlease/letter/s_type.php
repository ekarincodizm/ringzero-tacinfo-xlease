<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from letter.\"type_letter\" where \"type_name\" LIKE '%$term%' order by \"auto_id\" DESC");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $auto_id=$res_name["auto_id"];
    $type_name=$res_name["type_name"];
    $is_use=$res_name["is_use"];
	if($is_use == 't'){
		$text = "อนุญาตให้ใช้";
	}else{
		$text = "ไม่อนุญาตให้ใช้";
	}
    
    $dt['value'] = $auto_id;
    $dt['label'] = "{$auto_id}, {$type_name}, {$text} ";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
