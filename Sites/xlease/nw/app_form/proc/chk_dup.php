<?php
include('../config/config.php');

$data = pg_escape_string($_POST['data']);
$type = pg_escape_string($_POST['type']);

switch($type)
{
	case "user":
		$q = "select * from $schema.\"app_member\" where \"app_usr\"='$data'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row==0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		break;
	case "name":
		$q = "select * from $schema.\"app_member\" where \"app_fullname\"='$data'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row==0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		break;
	case "email":
		$q = "select * from $schema.\"app_member\" where \"app_email\"='$data'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row==0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		break;
	case "tel":
		$q = "select * from $schema.\"app_member\" where \"app_telephone\"='$data'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row==0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		break;
	case "mobile":
		$q = "select * from $schema.\"app_member\" where \"app_mobile\"='$data'";
		$qr = pg_query($q);
		$row = pg_num_rows($qr);
		if($row==0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		break;
}
?>