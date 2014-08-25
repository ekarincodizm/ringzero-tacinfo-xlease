<?php
include("../config/config.php");

$id_menu = pg_escape_string($_POST['menuid']);

$q = "select \"id_menu\" from \"f_menu\" where \"id_menu\"='$id_menu'";
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