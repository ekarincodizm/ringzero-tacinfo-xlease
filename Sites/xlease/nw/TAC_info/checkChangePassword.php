<?php
	session_start();
	include("config.php");
	$oldPassword=$_POST['tbxOldPassword'];
	$newPassword=$_POST['tbxNewPassword'];
	$username=$_SESSION['username'];
	$sql="select * from \"TrMember\" where \"Username\"='$username' and \"Password\"='$oldPassword'";
	$dbquery=pg_query($sql);
	$rows=pg_num_rows($dbquery);
	if($rows==0)
	{
		echo"คุณกรอกรหัสผ่านไม่ถูกต้อง";
	}
	else
	{
		$sql="update \"TrMember\" set \"Password\"='".md5($newPassword)."' where \"Username\"='$username'";
		pg_query($sql);
		echo"เปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
	}
?>