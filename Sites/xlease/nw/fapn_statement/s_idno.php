<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("select a.\"contractID\",\"thcap_fullname\",\"N_IDCARD\",\"N_CARDREF\",relation from \"vthcap_contract_creditline\" a
left join \"vthcap_ContactCus_detail\" b on a.\"contractID\"=b.\"contractID\"
WHERE a.\"contractID\" LIKE '%$term%' or \"thcap_fullname\" LIKE '%$term%' or \"N_IDCARD\" LIKE '%$term%' or \"N_CARDREF\" LIKE '%$term%' ");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql))
{
	$t1=$res["contractID"]; // เลขที่สัญญา
	$t2=$res["thcap_fullname"]; // ชื่อเต็ม
	$t3=$res["N_IDCARD"]; // บัตรประชาชน
	$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
	$t5=$res["relation"]; // ความสัมพันธ์กับสัญญา
    
	$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 ชื่อ:$t2($t5)บัตรประชาชน:$t3 บัตรอื่นๆ:$t4</font>";

    $dt['value'] = $t1;
    $dt['label'] = $txtLable;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>