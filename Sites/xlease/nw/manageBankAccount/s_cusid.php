<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from  \"VSearchCusCorp\" where \"full_name\" like  '%$term%'");
$numrows = pg_num_rows($qry_name);

while($res_search=pg_fetch_array($qry_name)){
		$CusID=trim($res_search["CusID"]);
		$full_name=trim($res_search["full_name"]);
		
		$dt['value'] = $CusID."#".$full_name;
		$dt['label'] = "{$CusID}, {$full_name}";
		$matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?> 
