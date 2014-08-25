<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("select * from public.\"vthcap_ContactCus_detail\"
				WHERE (\"contractID\" LIKE '%$term%' or \"thcap_fullname\" LIKE '%$term%' or \"N_IDCARD\" LIKE '%$term%' or \"N_CARDREF\" LIKE '%$term%')
						and \"contractID\" in(select \"contractID\" from public.\"thcap_mg_contract\") ");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql))
{	
	$t1=$res["contractID"]; // เลขที่สัญญา
	$t2=$res["thcap_fullname"]; // ชื่อเต็ม
	$t3=$res["N_IDCARD"]; // บัตรประชาชน
	$t4=$res["N_CARDREF"]; // บัตรอื่นๆ
	
	$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$t1' ");
	while($res_chkStatus = pg_fetch_array($qry_chkStatus))
	{
		$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
	}
	
	if($conStatus == "11")
	{
		$txtLable = "<font color=\"#CCCCCC\">เลขที่สัญญา:$t1 ชื่อ:$t2 บัตรประชาชน:$t3 บัตรอื่นๆ:$t4(ปิดบัญชีแล้ว)</font>";
	}
	else
	{
		$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1 ชื่อ:$t2 บัตรประชาชน:$t3 บัตรอื่นๆ:$t4</font>";
	}
    
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