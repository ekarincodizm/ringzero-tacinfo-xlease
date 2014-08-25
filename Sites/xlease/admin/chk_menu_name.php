<?php
include("../config/config.php");

$menu_name = pg_escape_string($_POST['menu_name']);

$q = "select \"name_menu\" from \"f_menu\" where \"name_menu\"='$menu_name'";
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