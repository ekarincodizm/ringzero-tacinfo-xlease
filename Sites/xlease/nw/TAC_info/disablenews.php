<?php
	session_start();
	include("config.php");
	$id=$_GET['id'];
	$operator=$_GET['operation'];
	if($operator=="disable")
	{
		if(pg_query("update \"Main_News\" set \"disabled\"='y' where \"NewsID\"='$id'"))
		{
			header("Location:editAllNews.php");	
		}
		else
		{
			echo"<center>";
			echo"<b>";
			echo"การดำเนินการล้มเหลว";
			echo"</b>";
			echo"</center>";
		}
	}
	else if($operator=="enable")
	{
		if(pg_query("update \"Main_News\" set \"disabled\"='n' where \"NewsID\"='$id'"))
		{
			header("Location:editAllNews.php");	
		}
		else
		{
			echo"<center>";
			echo"<b>";
			echo"การดำเนินการล้มเหลว";
			echo"</b>";
			echo"</center>";
		}
	}
?>