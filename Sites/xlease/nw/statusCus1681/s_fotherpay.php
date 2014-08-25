<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"FOtherpay\" where (\"O_Type\"='165' OR \"O_Type\"='307') and (\"O_DATE\" > '2012-01-01') and (\"O_RECEIPT\" LIKE '%$term%')
and \"O_RECEIPT\" NOT IN (select \"tacXlsRecID\" from \"tacReceiveTemp\")");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){	
	$dt['value'] = $res_name["O_RECEIPT"];
	$dt['label'] = "{$res_name["O_RECEIPT"]} : {$res_name["IDNO"]}";
	$matches[] = $dt;

}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
