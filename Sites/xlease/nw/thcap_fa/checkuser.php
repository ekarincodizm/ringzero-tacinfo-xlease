<?php
include("../../config/config.php");
$term =pg_escape_string($_POST['id']);
$term = trim($term);
list($CusID,$nname) = explode('#',$term);

$qry=pg_query("SELECT \"CusID\" FROM \"VSearchCusCorp\" WHERE \"CusID\"='$CusID'");
 echo $num=pg_num_rows($qry);
?>