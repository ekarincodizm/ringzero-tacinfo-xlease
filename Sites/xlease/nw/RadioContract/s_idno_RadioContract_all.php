<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("select \"RadioContract\".\"COID\" , \"RadioContract\".\"RadioNum\" , \"RadioContract\".\"RadioCar\" , \"VSearchCus\".\"full_name\" from public.\"RadioContract\" , public.\"GroupCus_Active\" , public.\"VSearchCus\" where \"RadioContract\".\"RadioRelationID\" = \"GroupCus_Active\".\"GroupCusID\" and \"GroupCus_Active\".\"CusID\" = \"VSearchCus\".\"CusID\" and (\"RadioContract\".\"COID\" like '%$term%' or \"RadioContract\".\"RadioNum\" like '%$term%' or \"RadioContract\".\"RadioCar\" like '%$term%' or \"VSearchCus\".\"full_name\" like '%$term%') order by \"RadioContract\".\"COID\" ASC ");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql)){
	$t1=$res["COID"];
	$t2=$res["RadioNum"];
	$t3=$res["RadioCar"];
	$t4=$res["full_name"];
    
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