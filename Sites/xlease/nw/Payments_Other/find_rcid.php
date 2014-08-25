<?php
include("../../config/config.php");
$term = $_GET['term'];

$sql=pg_query("	select 	distinct a.\"debtInvID\", a.\"contractID\", b.\"N_IDCARD\", a.\"thcap_fullname\" 
				from 	public.\"Vthcap_debt_invoice\" a
				left join public.\"vthcap_ContactCus_detail\" b ON a.\"CusID\" = b.\"CusID\"
				WHERE 	a.\"contractID\" LIKE '%$term%' or 
						b.\"N_IDCARD\" LIKE '%$term%' or 
						a.\"thcap_fullname\" LIKE '%$term%' or
						a.\"debtInvID\" LIKE '%$term%'
			");
$numrows = pg_num_rows($sql);
while($res=pg_fetch_array($sql))
{
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$thcap_fullname = $result["thcap_fullname"]; // ชื่อเต็ม
	$N_IDCARD = $result["N_IDCARD"]; // บัตรประชาชน
	
	$t1=$res["contractID"]; // เลขที่สัญญา
	$t2=$res["thcap_fullname"]; // ชื่อเต็ม
	$t3=$res["N_IDCARD"]; // บัตรประชาชน
	$t4=$res["debtInvID"]; // เลขที่สัญญา
    
    $dt['value'] = $t1;
    $dt['label'] = "เลขที่สัญญา:{$t1} ชื่อ:{$t2} บัตรประชาชน:{$t3} เลขที่ใบแจ้งหนี้:{$t4}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>