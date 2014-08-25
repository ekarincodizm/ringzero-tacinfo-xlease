<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$condition=pg_escape_string($_GET['condition']);

if($condition==1){
	$qry_name=pg_query("SELECT distinct\"contractID\" FROM thcap_v_taxinvoice_details where \"contractID\" LIKE '%$term%' order by \"contractID\"");
}else{
	$qry_name=pg_query("SELECT distinct\"taxinvoiceID\" FROM thcap_v_taxinvoice_details where \"taxinvoiceID\" LIKE '%$term%' order by \"taxinvoiceID\"");
}
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $receiptID = trim($res_name["taxinvoiceID"]);
	if($receiptID==""){
		$id=$res_name["contractID"];
	}else{
		$id=$res_name["taxinvoiceID"];
	}
	

    $dt['value'] = $id;
    $dt['label'] = "{$id}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
