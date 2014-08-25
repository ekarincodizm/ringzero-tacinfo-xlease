<?php
include("../../config/config.php");
$id = pg_escape_string($_POST['id_user']);

$qry_name=pg_query("select \"id_user\" from \"Vfuser\" where \"id_user\" = '$id'");
$numrows = pg_num_rows($qry_name);
if($numrows >0){
    echo '1';//มีข้อมูล
}
else{
	echo '2';//ไม่มีข้อมูล
}

?>
