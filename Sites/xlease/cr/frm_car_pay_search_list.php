<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("SELECT A.*,B.* FROM carregis.\"CarTaxDue\" A
INNER JOIN \"VContact\" B on A.\"IDNO\"=B.\"IDNO\" 
WHERE \"ApointmentDate\" is not null AND A.\"BookIn\"='false' AND (B.\"C_REGIS\" like '%$term%' OR B.\"car_regis\" like '%$term%') ORDER BY A.\"IDNO\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO = $res_name["IDNO"];
    $C_REGIS=trim($res_name["C_REGIS"]);
    
    $dt['value'] = $C_REGIS;
    $dt['label'] = "{$C_REGIS}, {$IDNO}";
    $matches[] = $dt;
}

if($rows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
