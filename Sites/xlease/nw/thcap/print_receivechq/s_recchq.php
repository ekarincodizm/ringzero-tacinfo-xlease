<?php
include("../../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT distinct(\"revChqNum\"),\"revChqToCCID\",date(\"revChqDate\") as \"revChqDate\" FROM finance.\"V_thcap_receive_cheque_chqManage\"
where  \"revChqNum\" like '%$term%' OR \"revChqToCCID\" like '%$term%' OR \"revChqDate\"::text like '%$term%' order by \"revChqNum\"");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $revChqNum=$res_name["revChqNum"];
	$revChqToCCID = trim($res_name["revChqToCCID"]);
	$revChqDate = trim($res_name["revChqDate"]);
	
	$name = str_replace("'", "\'"," ".$revChqNum.""." /เลขที่สัญญา ".$revChqToCCID.""." /วันที่รับเช็ค ".$revChqDate);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

    $dt['value'] = $revChqNum;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}
	
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
