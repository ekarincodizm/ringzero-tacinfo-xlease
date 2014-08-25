<?php
	session_start();
	include("../../config/config.php");
	$id=$_GET['id'];
	$dir1="uploads/croped/".$id;
	$dir2="uploads/full/".$id;
	$dir3="uploads/thumnails/".$id;
	$sql1="delete from carsystem.\"TrPostSell\" where \"carSellID\"='$id'";
	$sql2="delete from carsystem.\"productImage\" where \"postID\"='$id'";
	if(pg_query($sql1)&&pg_query($sql2))
	{
		foreach(glob($dir1."/".'*.*') as $v){
			unlink($v);
		}
		rmdir($dir1);
		foreach(glob($dir2."/".'*.*') as $v){
			unlink($v);
		}
		rmdir($dir2);
		foreach(glob($dir3."/".'*.*') as $v){
			unlink($v);
		}
		rmdir($dir3);
		echo "<script type=\"text/javascript\">";
		echo "window.location.href='showproduct.php';";
		echo "</script>";
	}
?>