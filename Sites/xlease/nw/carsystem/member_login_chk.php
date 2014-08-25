<?php
session_start();
include '../../config/config.php'; //เรียกใช้ไฟล์ config
$username = $_POST['username'];
$password = md5($_POST['password']);
$remember=$_POST['remember'];
$sql = "select * from carsystem.\"members\" where \"username\" = '$username' and \"password\" = '$password'";
$dbquery = pg_query($sql);
$result=pg_fetch_assoc($dbquery);
$num = pg_num_rows($dbquery);
if($num>0)
{
	if($result['status']==1)
	{
		echo 2;
	}
	else
	{
    	echo 1;
		session_register(username);
		session_register(showname);
		$_SESSION['username']=$username;
		$_SESSION['showname']=$result['showname'];
		if($remember!=""&&$remember!="undefined")
		{
			setcookie("carSystemUsername",$username,time()+3600*24*2);
		}
		else if(isset($_COOKIE['carSystemUsername']))
		{
			setcookie("carSystemUsername");
		}
	}
}
else
{
    echo 0;
}
?>