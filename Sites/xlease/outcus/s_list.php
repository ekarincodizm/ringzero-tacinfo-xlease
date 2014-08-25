<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"UNContact\" WHERE \"full_name\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' OR \"C_REGIS\" LIKE '%$term%' ORDER BY \"IDNO\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=trim($res_name["IDNO"]);
    $full_name=trim($res_name["full_name"]);
    $C_REGIS=trim($res_name["C_REGIS"]);
    $CusID=trim($res_name["CusID"]);
    
    $dt['value'] = $IDNO."#".$full_name."#".$C_REGIS;
    $dt['label'] = "{$IDNO}, {$full_name} {$C_REGIS}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
