<?php
include("../../config/config.php");
$id = $_POST['id'];
$type = $_POST['type'];
if($type=="q")
{
	if(pg_query("delete from \"qaQuestion\" where \"questionID\"='$id'"))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
else if($type=="a")
{
	if(pg_query("delete from \"qaAnswer\" where \"answerID\"='$id'"))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
?>