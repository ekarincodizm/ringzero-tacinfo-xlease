<?php
include("../../config/config.php");
$ConID = $_POST['ConID'];

$sql_nub1 = pg_query("SELECT * FROM thcap_contract where \"contractID\" = '$ConID'");
$rowchk1 = pg_num_rows($sql_nub1);

echo $rowchk1;
?>