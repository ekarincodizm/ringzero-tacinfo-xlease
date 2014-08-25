<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"VContact\" WHERE \"full_name\" LIKE '%$term%'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $CusID=$res_name["CusID"];
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
    
    $dt['value'] = $CusID;
    $dt['label'] = "{$CusID}, {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
