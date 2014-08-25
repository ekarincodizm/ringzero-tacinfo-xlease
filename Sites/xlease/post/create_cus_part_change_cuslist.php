<?php
include("../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"UNContact\" WHERE (\"full_name\" LIKE '%$term%') ORDER BY \"full_name\" ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["CusID"];
    $IDNO=$res_name["IDNO"];
    $full_name=trim($res_name["full_name"]);

    $dt['value'] = $CusID;
    $dt['label'] = "{$CusID}, {$IDNO} {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
