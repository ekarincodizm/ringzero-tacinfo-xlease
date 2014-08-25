<?php
include("./../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"titleName\" from \"nw_title\" WHERE \"titleName\" like '%$term%'");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $titleName=$res_name["titleName"];
	
	$dt['value'] = $titleName;
    $dt['label'] = "{$titleName}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
