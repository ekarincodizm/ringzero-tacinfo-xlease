<?php
include("../../config/config.php");

$fdep_id = $_POST['fdep_id']; // ฝ่าย
$select_dep_id = $_POST['select_dep_id']; // รายการที่จะเลือก

echo "<option value=\"\">---เลือก---</option>";

$qr = pg_query("select * from \"department\" where \"fdep_id\" = '$fdep_id' order by \"dep_name\" asc");
while($rs = pg_fetch_array($qr))
{
	$dep_id = $rs['dep_id'];
	$dep_name = $rs['dep_name'];
	
	if($dep_id == $select_dep_id)
	{
		echo "<option value=\"$dep_id\" selected>$dep_name</option>";
	}
	else
	{
		echo "<option value=\"$dep_id\">$dep_name</option>";
	}
}
?>