<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"UNContact\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"C_CARNUM\" LIKE '%$term%' OR \"full_name\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' ORDER BY \"asset_id\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $CarID=$res_name["asset_id"];
    $C_REGIS=$res_name["C_REGIS"];
    $C_CARNUM=$res_name["C_CARNUM"];
    $full_name=$res_name["full_name"];
    $IDNO=$res_name["IDNO"];
    
    $dt['value'] = "$CarID|$C_REGIS|$full_name|$IDNO|$C_CARNUM";
    $dt['label'] = "{$CarID} | {$C_REGIS} | {$IDNO} | {$full_name} | {$C_CARNUM}";
    $matches[] = $dt;
}
/*
$qry_name=pg_query("select * from \"Fc\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"C_CARNUM\" LIKE '%$term%' ORDER BY \"CarID\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $CarID=$res_name["CarID"];
    $C_REGIS=$res_name["C_REGIS"];
    $C_CARNUM=$res_name["C_CARNUM"];
    
    $dt['value'] = "$CarID|$C_REGIS|$C_CARNUM";
    $dt['label'] = "{$CarID}, {$C_REGIS} {$C_CARNUM}";
    $matches[] = $dt;
}
*/
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
