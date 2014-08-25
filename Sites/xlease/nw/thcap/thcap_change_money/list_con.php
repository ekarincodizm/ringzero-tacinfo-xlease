<?php
include("../../../config/config.php");
$term = $_GET['term'];

$sql = "SELECT \"contractID\", \"moneyType\", \"BAccount\", \"contractBalance\"
  FROM vthcap_contract_money where (\"contractID\" like '%$term%')order by \"contractID\"";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array($results)) {
	$id = $row["contractID"];
	$BAccount = trim($row["BAccount"]);
	$contractBalance = trim($row["contractBalance"]);
	
	if($contractBalance == ""){
		$contractBalance = "0.00";
	}

	$name = str_replace("'", "\'"," ".$id.""." : ".$BAccount.""." : ".$contractBalance);
	$display_name = $name;
    
	$dt['value'] = $id;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>