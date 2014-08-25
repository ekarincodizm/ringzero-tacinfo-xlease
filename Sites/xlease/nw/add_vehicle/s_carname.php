<?php
include("../../config/config.php"); 

$term = $_GET['term'];

$sql_select=pg_query("select distinct(trim(\"C_CARNAME\")) as carname from \"Fc\" where LOWER(\"C_CARNAME\") like '%$term%' OR UPPER(\"C_CARNAME\") like '%$term%'
OR \"C_CARNAME\" like '%$term%' ");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $C_CARNAME = $res_cn["carname"];
    

	$dt['value'] = $C_CARNAME;
	$dt['label'] = "<font color=\"red\">$C_CARNAME</font>";
	$matches[] = $dt;		
}
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>