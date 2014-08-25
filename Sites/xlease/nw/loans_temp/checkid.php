<?php
include("../../config/config.php");

$term = trim($_POST['id']);

$sql = "SELECT \"contractID\" FROM \"thcap_contract\" where  \"contractID\" = '$term'";
		
$results=pg_query($sql);						 
$row = pg_num_rows($results);
$re = pg_fetch_array($results);


if($row == 0 || empty($row))
{
	$qr = pg_query("SELECT \"contractID\" FROM \"thcap_contract_temp\" where  \"contractID\" = '$term' and \"Approved\" is null");
	$row1 = pg_num_rows($qr);
	if($row1==0||empty($row1))
	{
		echo "YES";
	}
	else
	{
		echo "Dup";
	}
}else{
echo "No";
}
?>