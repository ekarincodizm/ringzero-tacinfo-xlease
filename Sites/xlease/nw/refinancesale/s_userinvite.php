<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name = pg_query("select * from refinance.\"user_invite\" A
left join \"Vfuser\" B on A.\"id_user\" = B.\"id_user\"
WHERE B.\"fullname\" LIKE '%$term%' OR A.\"id_user\" LIKE '%$term%'");		
				 
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
	$id_user=$res_name["id_user"];
	$fullname=$res_name["fullname"];
		
	$dt['value'] = $id_user;
    $dt['label'] = "{$id_user},{$fullname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>

