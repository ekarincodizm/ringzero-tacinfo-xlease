<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"VSearchCusCorp\" where \"full_name\" like '%$term%' or \"IDCARD\" like '%$term%' ");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$CusID=trim($res_name["CusID"]);
	$IDCARD=trim($res_name["IDCARD"]);
	$fullname=trim($res_name["full_name"]);
    
    $dt['value'] = "$fullname";
    $dt['label'] = "{$CusID} : {$fullname} : {$IDCARD}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>