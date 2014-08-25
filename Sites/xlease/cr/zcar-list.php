<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"C_REGIS\",\"IDNO\",\"full_name\" from \"UNContact\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $C_REGIS=trim($res_name["C_REGIS"]);
    $IDNO=trim($res_name["IDNO"]);
    $full_name=trim($res_name["full_name"]);
    
    $dt['value'] = $C_REGIS."|".$IDNO."|".$full_name;
    $dt['label'] = "{$C_REGIS} | {$IDNO} | {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
