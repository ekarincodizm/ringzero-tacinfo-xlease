<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select * from \"VContact\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"C_CARNAME\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" ASC");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $asset_type=$res_name["asset_type"];
    $C_REGIS=$res_name["C_REGIS"];
    $car_regis=$res_name["car_regis"];
	$car_name = $res_name["C_CARNAME"];
    $C_CARNUM=$res_name["C_CARNUM"];
    $C_COLOR=$res_name["C_COLOR"];
    
    if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }
    
    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}, {$regis} {$car_name} {$C_COLOR}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
