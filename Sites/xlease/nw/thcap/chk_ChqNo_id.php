<?php
include("../../config/config.php");

$ChqNo= pg_escape_string($_POST['ChqNo']);
$bankOutID = pg_escape_string($_POST['bankOutID']);

$q = "select \"bankChqNo\" from \"finance\".\"thcap_receive_cheque\" 
where \"bankChqNo\"='$ChqNo' and \"bankOutID\"='$bankOutID' and \"revChqStatus\" <> '4' ";
$qr = pg_query($q);
$row = pg_num_rows($qr);
if($row==0)
{
	echo 1;
}
else
{
	echo 2;
}
?>