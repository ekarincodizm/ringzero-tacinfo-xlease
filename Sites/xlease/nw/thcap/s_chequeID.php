<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"revChqID\",\"bankChqNo\",\"revChqToCCID\",\"bankChqAmt\" FROM finance.\"V_thcap_receive_cheque_chqManage\" 
where  (\"bankChqNo\" like '%$term%' OR \"revChqToCCID\" like '%$term%' OR \"bankChqAmt\"::text like '%$term%') and \"revChqStatus\"='6'
and \"bankRevResult\"in('1','2')");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $revChqID=$res_name["revChqID"];
	$bankChqNo = trim($res_name["bankChqNo"]);
	$revChqToCCID = trim($res_name["revChqToCCID"]);
	$bankChqAmt = trim($res_name["bankChqAmt"]);
	
	$name = str_replace("'", "\'"," ".$revChqID.""." / ".$bankChqNo.""." / ".$revChqToCCID."(จำนวนเงิน ".number_format($bankChqAmt,2).")");
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

    $dt['value'] = $revChqID;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}
	
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
