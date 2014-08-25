<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"UNContact\" WHERE (\"IDNO\" LIKE '%$term%' AND \"IDNO\" NOT LIKE '00_%') ORDER BY \"IDNO\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $C_REGIS=$res_name["C_REGIS"];
    
    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}, {$full_name} {$C_REGIS}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
