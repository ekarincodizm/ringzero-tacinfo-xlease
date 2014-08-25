<?php
include("../../config/config.php"); 

$term = pg_escape_string($_GET['term']);

$sql_select=pg_query("select \"IDNO\",\"full_name\",\"C_REGIS\",\"car_regis\",\"CusID\" 
from \"VContact\" where \"IDNO\" like '%$term%' OR \"C_REGIS\" like '%$term%' ORDER BY \"IDNO\" ");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $full_name = trim($res_cn["full_name"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
	$car_regis = trim($res_cn["car_regis"]);
	$cusid = trim($res_cn["CusID"]);

	$dt['value'] = $IDNO."#".$full_name."#".$C_REGIS."#".$cusid;
	$dt['label'] = "{$IDNO} : {$full_name} : {$C_REGIS} {$car_regis}";
	$matches[] = $dt;		
}
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>