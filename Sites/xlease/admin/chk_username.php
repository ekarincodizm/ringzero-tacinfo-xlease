<?php
include("../config/config.php");

$username = pg_escape_string($_POST['username']);

$q = "select \"id_user\" from \"fuser\" where \"username\"='$username'";
$qr = pg_query($q);

$row = pg_num_rows($qr);

if($row==0)
{
	echo 1;
}
else
{
	echo 2;
}
?>