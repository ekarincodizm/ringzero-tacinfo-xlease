<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("select a.\"id_user\" , a.\"fullname\" , a.\"user_group\" , b.\"dep_name\",a.\"nickname\" from \"Vfuser\" a , \"department\" b WHERE a.\"user_group\" = b.\"dep_id\" AND (a.\"fullname\" LIKE '%$term%' OR a.\"nickname\" LIKE '%$term%' OR b.\"dep_name\"LIKE '%$term%') ");
$numrows = pg_num_rows($sql);
while($result=pg_fetch_array($sql))
{
	$id_user = $result["id_user"]; // รหัสพนักงาน
	$fullname = $result["fullname"]; // ชื่อเต็มพนักงาน
	$user_group = $result["user_group"]; // รหัสกลุ่มพนักงาน
	$dep_name = $result["dep_name"]; // ชื่อกลุ่มพนักงาน
	$nickname = $result["nickname"]; // ชื่อกลุ่มพนักงาน
    
    $dt['value'] = $id_user." ".$fullname." แผนก:".$dep_name;
    $dt['label'] = "{$fullname} ({$nickname}) แผนก:{$dep_name} รหัสพนักงาน:{$id_user}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>