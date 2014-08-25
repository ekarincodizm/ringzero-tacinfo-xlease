<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"VSearchCus\".\"CusID\" , \"VSearchCus\".\"full_name\" , \"Fn\".\"N_IDCARD\" from \"VSearchCus\" , \"Fn\" where \"VSearchCus\".\"CusID\" = \"Fn\".\"CusID\" and (\"VSearchCus\".\"full_name\" like  '%$term%' or \"Fn\".\"N_IDCARD\" like '%$term%') LIMIT(20)");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $CusID=trim($res_name["CusID"]);
    $full_name=$res_name["full_name"];
    $N_IDCARD=$res_name["N_IDCARD"];



    
    if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }
    
    $dt['value'] = $full_name."#".$CusID;
    $dt['label'] = "{$CusID}, {$full_name} {$N_IDCARD} ";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
