<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\" WHERE (\"contractID\" LIKE '%$term%' or \"thcap_fullname\" LIKE '%$term%' or \"N_IDCARD\" LIKE '%$term%' or \"N_CARDREF\" LIKE '%$term%')
and \"contractID\" in (select \"revChqToCCID\" from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqStatus\" in('2','8'))");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql))
{
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$thcap_fullname = $result["thcap_fullname"]; // ชื่อเต็ม
	$N_IDCARD = $result["N_IDCARD"]; // บัตรประชาชน
	$N_CARDREF = $result["N_CARDREF"]; // บัตรอื่นๆ
	
	$t1=$res["contractID"]; // เลขที่สัญญา
	$t2=$res["thcap_fullname"]; // ชื่อเต็ม
	$t3=$res["N_IDCARD"]; // บัตรประชาชน
	$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
    
    $dt['value'] = $t1;
    $dt['label'] = "เลขที่สัญญา:{$t1} ชื่อ:{$t2} บัตรประชาชน:{$t3} บัตรอื่นๆ:{$t4}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>