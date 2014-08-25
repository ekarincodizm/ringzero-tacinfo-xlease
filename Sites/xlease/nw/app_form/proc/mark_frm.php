<?php
include("../config/config.php");
$id = $_POST['id'];
$type = $_POST['type'];
$user = $_SESSION['app_userid'];
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

if($type=="1")
{
	$q = "update $schema.\"app_frm\" set \"form_state\"='3',\"approve_user\"='$user',\"approve_stamp\"='$date' where \"formID\"='$id'";
	$qr = pg_query($q);
	if($qr)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
else if($type=="2")
{
	$q = "update $schema.\"app_frm\" set \"form_state\"='4',\"approve_user\"='$user',\"approve_stamp\"='$date' where \"formID\"='$id'";
	$qr = pg_query($q);
	if($qr)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
else if($type=="3")
{
	$q = "update $schema.\"app_frm\" set \"accepted\"='1' where \"formID\"='$id'";
	$qr = pg_query($q);
	if($qr)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
?>