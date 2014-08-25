<?php
	session_start();
	session_unregister("username");
	session_unregister("showname");
	header("Location:index.php")
?>