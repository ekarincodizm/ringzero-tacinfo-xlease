<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"UNContact\" WHERE (\"C_REGIS\" LIKE '%$term%' AND \"IDNO\" NOT LIKE '00_%') ORDER BY \"C_REGIS\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $C_REGIS=$res_name["C_REGIS"];
    
    $dt['value'] = $C_REGIS;
    $dt['label'] = "{$C_REGIS}, {$IDNO} {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
