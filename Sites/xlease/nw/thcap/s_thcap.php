<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$condition = pg_escape_string($_GET['condition']);

if($condition==1){
	$qry_name=pg_query("SELECT \"contractID\" FROM thcap_contract where \"contractID\" LIKE '%$term%' order by \"contractID\"");
}else{
	$qry_name=pg_query("SELECT \"receiptID\" as receipt FROM thcap_temp_int_201201 WHERE \"isReceiveReal\" != '0'  AND \"receiptID\" LIKE '%$term%'
						UNION
						SELECT distinct(\"receiptID\") as receipt FROM thcap_v_receipt_otherpay WHERE \"receiptID\" LIKE '%$term%'
						ORDER BY receipt");
}
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $receiptID = trim($res_name["receipt"]);
	if($receiptID==""){
		$id=$res_name["contractID"];
	}else{
		$id=$res_name["receipt"];
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
