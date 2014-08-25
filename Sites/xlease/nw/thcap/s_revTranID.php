<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"revTranID\" FROM finance.thcap_receive_transfer_log where \"revTranID\" LIKE '%$term%' group by \"revTranID\" order by \"revTranID\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $revTranID = trim($res_name["revTranID"]);
	
	$name = str_replace("'", "\'"," ".$revTranID);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
    
	$dt['value'] = $revTranID;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
