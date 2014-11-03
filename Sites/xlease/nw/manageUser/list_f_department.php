<?php
include("../../config/config.php");

$organizeID = $_POST['organizeID']; // บริษัท
$select_fdep_id = $_POST['select_fdep_id']; // รายการที่จะเลือก

echo "<option value=\"\">---เลือก---</option>";

$qr = pg_query("select * from \"f_department\" where \"organizeID\" = '$organizeID' and \"fstatus\" = TRUE order by \"fdep_name\" asc");
while($rs = pg_fetch_array($qr))
{
	$fdep_id = $rs['fdep_id'];
	$fdep_name = $rs['fdep_name'];
	
	if($fdep_id == $select_fdep_id)
	{
		echo "<option value=\"$fdep_id\" selected>$fdep_name</option>";
	}
	else
	{
		echo "<option value=\"$fdep_id\">$fdep_name</option>";
	}
}
?>