<?php
include("../config/config.php");

$username = $_SESSION['app_username'];
$passwd = $_POST['first_passwd'];
$fullname = $_POST['fullname'];
$email = $_POST['Email'];
$telephone = $_POST['telephone'];
$mobile = $_POST['mobile'];
$date = date("Y-m-d H:i:s");;

$q = "update $schema.\"app_member\" set \"app_fullname\"='$fullname', \"app_email\"='$email', \"app_telephone\"='$telephone', \"app_mobile\"='$mobile', \"regis_date\"='$date'";
if($passwd!="")
{
	$passwd = md5($seed.$passwd);
	$q.=", \"app_passwd\"='$passwd'";
}
$q.=" where \"app_usr\"='$username'";
if(pg_query($q))
{
	echo 1;
}
else
{
	echo 0;
}
?>