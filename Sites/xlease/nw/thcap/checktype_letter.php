<?php
include("../../config/config.php");

$detailref = pg_escape_string($_POST['detailref']);
$typeletter = pg_escape_string($_POST["typeletter"]);

$detailref = trim($detailref);
$typeletter = trim($typeletter);

if($typeletter=="1"){
	$sql = pg_query("SELECT \"receiptID\" FROM \"thcap_temp_receipt_details\" where \"receiptID\" = '$detailref' ");
	$row = pg_num_rows($sql);
}else if($typeletter=="12"||$typeletter=="13"){
	$sql = pg_query("SELECT \"invoiceID\" FROM \"thcap_temp_invoice_details\" where \"invoiceID\" = '$detailref' ");
	$row = pg_num_rows($sql);
} else if($typeletter=="14"){
	$sql = pg_query("SELECT \"taxinvoiceID\" FROM \"thcap_temp_taxinvoice_details\" where \"taxinvoiceID\" = '$detailref'");
	$row = pg_num_rows($sql);
} else {
	$row = 1;
}

		
	if($row==0||empty($row)){
		echo "F";
	}else {
		echo "T";
	}
?>