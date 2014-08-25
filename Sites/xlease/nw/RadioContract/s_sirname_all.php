<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select \"RadioContract\".\"COID\" , \"RadioContract\".\"RadioNum\" , \"RadioContract\".\"RadioCar\" , \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" from public.\"RadioContract\" , public.\"GroupCus_Active\" , public.\"Fa1\" where \"Fa1\".\"A_SIRNAME\" like '%$term%' and \"RadioContract\".\"RadioRelationID\" = \"GroupCus_Active\".\"GroupCusID\" and \"GroupCus_Active\".\"CusID\" = \"Fa1\".\"CusID\" order by \"RadioContract\".\"COID\" ASC ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$t1=$res_name["COID"];
	$t2=$res_name["RadioNum"];
	$t3=$res_name["RadioCar"];
	$t4=$res_name["A_FIRNAME"];
	$t5=$res_name["A_NAME"];
	$t6=$res_name["A_SIRNAME"];
    
    /*if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }*/
    
    $dt['value'] = $t1;
    $dt['label'] = "สัญญาวิทยุ:{$t1} รหัสวิทยุ:{$t2} ทะเบียนรถ:{$t3} , {$t4}{$t5} {$t6}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>