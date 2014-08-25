<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"VContact\" WHERE \"full_name\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' OR \"TranIDRef1\" LIKE '%$term%' OR \"TranIDRef2\" LIKE '%$term%'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=trim($res_name["IDNO"]);
    $full_name=trim($res_name["full_name"]);
    $TranIDRef1=trim($res_name["TranIDRef1"]);
    $TranIDRef2=trim($res_name["TranIDRef2"]);
    
    $dt['value'] = $IDNO."#".$full_name."#".$TranIDRef1."#".$TranIDRef2;
    $dt['label'] = "{$IDNO}, {$full_name} - {$TranIDRef1} - {$TranIDRef2}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
