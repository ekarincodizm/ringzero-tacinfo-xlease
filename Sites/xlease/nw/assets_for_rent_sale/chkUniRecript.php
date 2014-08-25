<?php
include("../../config/config.php");

$seller = $_POST["seller"];
list($CusID,$CusName) = explode('#',$seller); // รหัสผู้ขาย และชื่อผู้ขาย
$receiptNumber = $_POST["receiptNumber"]; // เลขที่ใบเสร็จ
$qrychkRecript = pg_query("select \"receiptNumber\" from \"thcap_asset_biz\" where \"corpID\" = '$CusID' and \"receiptNumber\" = '$receiptNumber' and \"ActiveStatus\" = '1'
							union
							select \"receiptNumber\" from \"thcap_asset_biz_temp\" where \"corpID\" = '$CusID' and \"receiptNumber\" = '$receiptNumber' and \"Approved\" is null");
$numrows = pg_num_rows($qrychkRecript);

if($numrows > 0)
{
	echo "duplicateReceipt";
}
else
{
	echo "NOduplicate";
}
?>