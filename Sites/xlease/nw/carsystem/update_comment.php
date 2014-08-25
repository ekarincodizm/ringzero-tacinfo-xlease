<?php
include("../../config/config.php");
$imgid=$_POST['imgid'];
$value=$_POST['value'];
$sql="update carsystem.\"productImage\" set \"imageComment\"='$value' where \"imageID\"='$imgid'";
if(pg_query($sql))
{
	echo 1;
}
else
{
	echo 0;
}
?>