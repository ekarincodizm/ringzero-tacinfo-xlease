<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
    
    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}, {$dd_C_REGIS} {$dd_C_CARNUM} {$dd_C_COLOR}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
