<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_voucher=pg_query("select * from v_thcap_temp_voucher_details_journal where \"voucherID\" like '%$term%' LIMIT(20)");

$numrows = pg_num_rows($qry_voucher);
if($numrows>0){
	while($res_name=pg_fetch_array($qry_voucher)){
		$voucherID=trim($res_name["voucherID"]);
  
		$dt['value'] = $voucherID;
		$dt['label'] = "{$voucherID}";
		$matches[] = $dt;
	}
}else{
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
