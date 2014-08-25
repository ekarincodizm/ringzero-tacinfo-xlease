<?php
include("../../config/config.php");
$debtid = $_POST['debtid'];
$date = date("Y-m-d H:i:s");
$doer = $_SESSION['av_iduser'];
$qr = pg_query("insert into \"thcap_print_debt_invoice_log\"(\"thcap_debt_invoice_id\",\"doer\",\"do_time\") values('$debtid','$doer','$date')");
if($qr)
{
	echo 1;
}
else
{
	echo 0;
}
?>