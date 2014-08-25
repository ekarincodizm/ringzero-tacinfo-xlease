<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"UNContact\" WHERE \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $full_name=$res_name["full_name"];
    $IDNO=$res_name["IDNO"];
    
    $dt['value'] = "$IDNO|$full_name";
    $dt['label'] = "{$IDNO} | {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
