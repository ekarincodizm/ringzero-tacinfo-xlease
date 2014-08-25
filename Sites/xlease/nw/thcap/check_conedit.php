<?php
include("../../config/config.php");

$CONID = $_POST["CONID"];
$qry_chk = pg_query("SELECT \"contractID\" FROM \"thcap_contract\" where \"contractID\" = '$CONID'");
$row_chk = pg_num_rows($qry_chk );
if($row_chk > 0){
	echo "yes";
}else{
	echo "no";
}

?>