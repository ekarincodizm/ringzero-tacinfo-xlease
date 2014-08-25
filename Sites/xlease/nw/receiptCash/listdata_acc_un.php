<?php
include("../../config/config.php");

$term = $_GET['term'];

$qry_name=pg_query("select a.\"COID\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",a.\"RadioNum\",a.\"RadioCar\" from \"RadioContract\" a
left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" and \"CusState\"='0'
left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\" 
WHERE a.\"COID\" like '%$term%' or a.\"RadioCar\" like '%$term%' or a.\"RadioNum\" like '%$term%' and a.\"ContractStatus\"='1'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=trim($res_name["COID"]);
    $full_name=trim($res_name["A_FIRNAME"]).trim($res_name["A_NAME"])." ".trim($res_name["A_SIRNAME"]);
    $RadioNum=trim($res_name["RadioNum"]);
    $C_REGIS=trim($res_name["RadioCar"]);

    $dt['value'] = "$IDNO#$C_REGIS#$full_name#$RadioNum";
    $dt['label'] = "$IDNO $C_REGIS / $full_name / เลขวิทยุ $RadioNum";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>