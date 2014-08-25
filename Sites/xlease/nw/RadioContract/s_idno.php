<?php
include("../../config/config.php");
$term = $_GET['term'];

//$qry_name=pg_query("select * from \"VContact\" WHERE \"C_REGIS\" LIKE '%$term%' OR \"C_CARNAME\" LIKE '%$term%' OR \"IDNO\" LIKE '%$term%' ORDER BY \"IDNO\" ASC");
$qry_name=pg_query("select * from public.\"VSearchCus\" where \"CusID\" like '%$term%' or \"full_name\" like '%$term%' order by \"CusID\" ASC"); 
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$t1=trim($res_name["CusID"]);
	/*$t2=$res_name["A_NAME"];
	$t3=$res_name["A_SIRNAME"];
	$t4=$res_name["A_FIRNAME"];*/
	$full_name=$res_name["full_name"];
    
    /*if($asset_type == 1){
        $regis = $C_REGIS;
    }else{
        $regis = $car_regis;
    }*/
    
    $dt['value'] = $t1;
    $dt['label'] = "{$t1}, $full_name ";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>
