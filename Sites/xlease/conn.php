<?php

//$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");

session_start();
include("./config/config.php");

//if(!session_is_registered("uid")){ @session_register("uid"); $_SESSION['uid'] = "null"; }
//if(!session_is_registered("ugroup")){ @session_register("ugroup"); $_SESSION['ugroup'] = "null"; }

@ini_set('display_errors', '1');

?>