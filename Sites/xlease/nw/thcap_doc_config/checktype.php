<?php
include("../../config/config.php");

$term = trim($_POST['type']);
$conType = trim($_POST["contype"]);

$sql = pg_query("SELECT * FROM \"thcap_contract_doc_config\" where \"doc_conTypeName\" = '$conType' and \"doc_docName\" = '$term' ");						 
$row = pg_num_rows($sql);

	if($row==0||empty($row)){
	$qr = pg_query("SELECT \"doc_docName\" FROM \"thcap_contract_doc_config_temp\" where \"doc_conTypeName\" = '$conType' and \"doc_docName\" = '$term' and \"doc_status_appv\" = '2'");
	$row1 = pg_num_rows($qr);
		if($row1==0||empty($row1))
		{
			echo "YES";
		}
		else
		{
			echo "Dup";
		}
	}else if($row!=0){
		echo "NO";
	}
?>