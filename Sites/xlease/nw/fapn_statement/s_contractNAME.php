<?php
include("../../config/config.php");
$term = $_GET['term'];

$db1="ta_mortgage_datastore";

$qry_name = pg_query("select \"thcap_fullname\",a.\"contractID\" from \"vthcap_contract_creditline\" a  
left join \"vthcap_ContactCus_detail\" b on a.\"contractID\"=b.\"contractID\"
where \"thcap_fullname\" like '%$term%' ");
$numrows=pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$cusname = $res_name["thcap_fullname"];
	$contract_loans_code = $res_name["contractID"];
    
    $dt['value'] = $contract_loans_code;
    $dt['label'] = "$cusname, $contract_loans_code";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>