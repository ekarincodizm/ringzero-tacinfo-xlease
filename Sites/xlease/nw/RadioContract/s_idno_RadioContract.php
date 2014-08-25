<?php
include("../../config/config.php");
$term = $_GET['term'];

//$qry_name=pg_query("select * from public.\"Fa1\" where \"CusID\" like '%$term%' or \"A_NAME\" like '%$term%' or \"A_SIRNAME\" like '%$term%' order by \"CusID\" ASC");
$sql=pg_query("select \"RadioContract\".\"COID\" , \"RadioContract\".\"RadioNum\" , \"RadioContract\".\"RadioCar\" , \"VSearchCus\".\"full_name\" from public.\"RadioContract\" , public.\"GroupCus_Active\" , public.\"VSearchCus\" where \"RadioContract\".\"RadioRelationID\" = \"GroupCus_Active\".\"GroupCusID\" and \"GroupCus_Active\".\"CusID\" = \"VSearchCus\".\"CusID\" and (\"RadioContract\".\"COID\" like '%$term%' or \"RadioContract\".\"RadioNum\" like '%$term%' or \"RadioContract\".\"RadioCar\" like '%$term%' or \"VSearchCus\".\"full_name\" like '%$term%') and \"RadioContract\".\"AppvID\" is null order by \"RadioContract\".\"COID\" ASC ");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql)){
	$t1=$res["COID"];
	$t2=$res["RadioNum"];
	$t3=$res["RadioCar"];
	$t4=$res["full_name"];
    
    /*if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }*/
    
    $dt['value'] = $t1;
    $dt['label'] = "สัญญาวิทยุ:{$t1} รหัสวิทยุ:{$t2} ทะเบียนรถ:{$t3} , {$t4}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>