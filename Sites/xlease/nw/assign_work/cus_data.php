<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"CusID\",\"full_name\" from \"VSearchCus\" where \"CusID\" like '%$term%' or \"full_name\" like '%$term%' LIMIT(20)");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $CusID=trim($res_name["CusID"]);
    $full_name=$res_name["full_name"];

    if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }
    
    $dt['value'] = $CusID."#".$full_name;
    $dt['label'] = "{$CusID}, {$full_name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
