<?php
include("../config/config.php");
$user = pg_escape_string($_POST['tbx_super_user']);
$passwd = pg_escape_string($_POST['tbx_super_passwd']);
$passwd = md5($seed.$passwd);
$q = "select * from $schema.\"app_member\" where \"app_usr\"='$user' and \"app_passwd\"='$passwd' and \"status\"<>'0'";
$qr = pg_query($q);
$rs = pg_fetch_array($qr);
$row = pg_num_rows($qr);
if($row==1)
{
	if($rs['verify_status']=="1")
	{
		session_register("app_username");
		session_register("app_userid");
		session_register("app_user_type");
		
		$_SESSION['app_username']=$user;
		$_SESSION['app_userid']=$rs['memberID'];;
		
		if($rs['user_type']=='1')
		{
			$_SESSION['app_user_type']="admin";
			echo "10";
		}
		else if($rs['user_type']=='2')
		{
			$_SESSION['app_user_type']="user";
			echo "11";
		}
	}
	else
	{
		echo "01";
	}
}
else
{
	echo "00";
}
?>