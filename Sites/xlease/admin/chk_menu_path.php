<?php
include("../config/config.php");

$menu_path = pg_escape_string($_POST['menu_path']);

$q = "select \"name_menu\" from \"f_menu\" where \"path_menu\"='$menu_path'";
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
