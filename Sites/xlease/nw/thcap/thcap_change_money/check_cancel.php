<?php
include("../../../config/config.php");

$termContract = trim($_POST['id']); // เลขที่สัญญา

$sql = "select a.* from \"thcap_temp_receipt_cancel\" a, \"thcap_temp_receipt_otherpay\" b
		where a.\"receiptID\" = b.\"receiptID\" and a.\"contractID\" = '$termContract' and a.\"approveStatus\" = '2'
		and (account.\"thcap_getHoldMoneyType\"(a.\"contractID\") = b.\"typePayID\" or account.\"thcap_getSecureMoneyType\"(a.\"contractID\") = b.\"typePayID\") ";
$query = pg_query($sql);
$rowchk = pg_num_rows($query);

echo $rowchk;
?>