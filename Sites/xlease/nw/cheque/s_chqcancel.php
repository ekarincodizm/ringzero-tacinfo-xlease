<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select \"chqpayID\",a.\"BAccount\",\"chequeNum\",\"cusPay\",\"moneyPay\",\"typeName\" from cheque_pay a
left join \"BankInt\" b on a.\"BAccount\"=b.\"BAccount\"
left join cheque_typepay c on a.\"typePay\"=c.\"typePay\"
WHERE \"chequeNum\" LIKE '%$term%' and \"appStatus\"='1' and \"statusPay\"='TRUE'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$chqpayID=$res_name["chqpayID"];
    $BAccount=$res_name["BAccount"];
	$chequeNum=$res_name["chequeNum"];
    $cusPay=$res_name["cusPay"];
    $moneyPay=number_format($res_name["moneyPay"],2);
	$typeName=$res_name["typeName"];
    
    $dt['value'] = $chqpayID."#เลขที่เช็ค".$chequeNum;
    $dt['label'] = "เลขที่เช็ค {$chequeNum},ประเภท{$typeName}, สั่งจ่าย{$cusPay}, จำนวน {$moneyPay} บาท";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
