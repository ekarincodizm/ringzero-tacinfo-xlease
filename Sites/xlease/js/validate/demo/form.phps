<?php
// wait a second to simulate a some latency
usleep(500000);
$user = pg_escape_string($_REQUEST['user']);
$pw = pg_escape_string($_REQUEST['password']);
if($user && $pw && $pw == "foobar")
	echo "Hi $user, welcome back.";
else
	echo "Your password is wrong (must be foobar).";
?>