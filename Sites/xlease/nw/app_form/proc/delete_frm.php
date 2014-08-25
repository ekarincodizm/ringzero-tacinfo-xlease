<?php
include("../config/config.php");
$id = $_POST['id'];
$q = "update $schema.\"reply\" set \"state\"='7' where \"replyID\"='$id'";
$qr = pg_query($q);
if($qr)
{
	echo 1;
}
else
{
	echo 0;
}
?>