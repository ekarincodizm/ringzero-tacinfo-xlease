<?php
include("../../config/config.php");

$term = trim($_POST['frname']);

$sql = pg_query("SELECT * FROM account.\"all_accFormula\" where \"af_fmname\" = '$term' ");						 
$row = pg_num_rows($sql);

		if($row==0||empty($row)){
			echo "YES";
		} else {
			echo "NO";
		}
?>