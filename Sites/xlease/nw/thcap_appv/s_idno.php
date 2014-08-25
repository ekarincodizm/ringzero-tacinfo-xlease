<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql=pg_query("select * from public.\"thcap_contract\" WHERE \"contractID\" LIKE '%$term%' ");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql))
{
	$contractID = $result["contractID"];
	$conDate = $result["conDate"];
	$conStartDate = $result["conStartDate"];
	$conRepeatDueDay = $result["conRepeatDueDay"];
	$conLoanAmt = $result["conLoanAmt"];
	$conMinPay = $result["conMinPay"];
	
	$t1=$res["contractID"];
	$t2=$res["conDate"];
	$t3=$res["conLoanAmt"];
	$t4=$res["conMinPay"];
    
    $dt['value'] = $t1;
    $dt['label'] = "เลขที่สัญญา:{$t1} วันที่ทำสัญญา:{$t2} ยอดกู้:{$t3} ยอดจ่ายขั้นต่ำ/เดือน:{$t4}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>