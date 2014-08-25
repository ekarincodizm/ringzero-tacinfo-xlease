<?php
include("../../config/config.php");

$term = $_GET['term'];

$sql="select distinct \"fullname\",\"nickname\",\"id_user\" from public.\"Vmenu_log_adv\" where \"id_menu\" like '%$term%' or \"name_menu\" like '%$term%' or \"id_user\" like '%$term%' or \"fullname\" like '%$term%' or \"nickname\" like '%$term%' or \"user_group\" like '%$term%' or \"dep_name\" like '%$term%' or cast(\"menu_date\" as character varying) like '%$term%'";
$dbquery=pg_query($sql);
$rows=pg_num_rows($dbquery);
if($rows==0)
{
	$id_user="";
	$fullname= "ไม่พบข้อมูล";
	$nickname= "ไม่พบข้อมูล";
	$dt['value'] = $id_user;
	$dt['label'] = "{$fullname} : {$nickname}";
	$matches[] = $dt;
}
else
{
	while($result=pg_fetch_assoc($dbquery))
	{
		$id_user=$result['id_user'];
		$fullname=$result['fullname'];
		$nickname=$result['nickname'];
		
		$dt['value'] = $id_user;
		$dt['label'] = "ชื่อเล่น : {$nickname} || ชื่อจริง : {$fullname}";
		$matches[] = $dt;
	}
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
