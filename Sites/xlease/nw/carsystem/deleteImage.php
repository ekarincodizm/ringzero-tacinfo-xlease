<?php
	session_start();
	include("../../config/config.php");
	$path=$_GET['path'];
	$id=$_SESSION['postID'];
	if(unlink($path))
	{
		$sql = "delete carsystem.\"productImage\" where \"postID\"='$id' and \"imageName\"='$path'";
		pg_query($sql);
		echo"<script type=\"text/javascript\">";
		echo"window.location.href='upload.php';";
		echo"</script>";
	}
?>