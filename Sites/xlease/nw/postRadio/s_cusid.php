<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"COID\",\"RadioNum\",\"RadioCar\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",c.\"CusID\" FROM \"RadioContract\" a
left join \"GroupCus\" b on a.\"RadioRelationID\"=b.\"GroupCusID\"
left join \"GroupCus_Active\" c on b.\"GroupCusID\"=c.\"GroupCusID\"
left join \"Fa1\" d on c.\"CusID\"=d.\"CusID\"
where a.\"ContractStatus\"='1' and (\"COID\" LIKE '%$term%' OR \"RadioNum\" LIKE '%$term%' OR \"RadioCar\" LIKE '$term') ORDER BY \"COID\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $COID=trim($res_name["COID"]);
    $RadioNum=trim($res_name["RadioNum"]);
    $RadioCar=trim($res_name["RadioCar"]);
	$CusID=trim($res_name["CusID"]);
	$cusname=trim($res_name["A_FIRNAME"]).trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);
    
    $dt['value'] = $COID."#".$RadioNum."#".$RadioCar."#".$CusID;
    $dt['label'] = "{$COID}, {$RadioNum}, {$RadioCar}, {$cusname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
