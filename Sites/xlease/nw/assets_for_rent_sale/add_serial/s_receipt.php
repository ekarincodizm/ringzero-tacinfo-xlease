<?php
include("../../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"receiptNumber\",\"PurchaseOrder\" FROM thcap_asset_biz where \"receiptNumber\" like '%$term%' OR \"PurchaseOrder\" like '%$term%'");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
	if($res_name["receiptNumber"] ==""){
		$receiptNumber=trim($res_name["PurchaseOrder"]);
	}else{
		$receiptNumber=trim($res_name["receiptNumber"]);
	}	

    $dt['value'] = $receiptNumber;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบใบเสร็จดังกล่าว";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
