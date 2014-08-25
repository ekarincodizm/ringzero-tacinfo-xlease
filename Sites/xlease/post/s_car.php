<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"VCarregistemp\" 
WHERE \"C_REGIS\" LIKE '%$term%' OR \"C_CARNAME\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' OR \"full_name\" LIKE '%$term%' ORDER BY \"CarID\" ASC");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$IDNO=$res_name["IDNO"];
	$full_name=$res_name["full_name"];
    $CarID=$res_name["CarID"];
    $C_REGIS=$res_name["C_REGIS"];
    $C_CARNAME=$res_name["C_CARNAME"];
    $C_COLOR=$res_name["C_COLOR"];
 
    
    $dt['value'] = $CarID;
    $dt['label'] = "{$IDNO} : {$full_name} : {$CarID} : {$C_REGIS}, {$C_CARNAME}, {$C_COLOR} ";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
