<?php
include("../config/config.php");
$seed = $_SESSION["session_company_seed"];
$new_pass=pg_escape_string($_POST['new_pass']);
$new_pass = md5(md5($new_pass).$seed);
$qre_sql=pg_query("SELECT \"password\" from \"fuser\" WHERE id_user='".$_SESSION['av_iduser']."'");

$res=pg_fetch_array($qre_sql);
$password=$res["password"];
if($password != $new_pass){
	echo 0;
}
else{
	echo 1;		
}

?>