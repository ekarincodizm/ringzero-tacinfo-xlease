<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"IDNO\",\"full_name\" from \"VContact\" WHERE \"full_name\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=trim($res_name["IDNO"]);
    $full_name=trim($res_name["full_name"]);
    
    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}, {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
