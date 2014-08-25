<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name = pg_query("select * from public.\"th_corp\" where \"corp_regis\" like '%$term%' or \"corpName_THA\" like '%$term%'");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $corp_regis = $res_name["corp_regis"];
	$corpType = $res_name["corpType"];
	$corpName_THA = $res_name["corpName_THA"];

    $dt['value'] = $corpName_THA;
    $dt['label'] = "{$corp_regis}, {$corpType} {$corpName_THA}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
