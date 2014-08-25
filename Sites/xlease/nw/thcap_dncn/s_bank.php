<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT * FROM \"BankProfile\" WHERE \"bankName\" like '%$term%' OR UPPER(\"bankID\") like '%$term%' 
OR LOWER(\"bankID\") like '%$term%'
ORDER BY \"sort\",\"bankName\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $bankID=$res_name["bankID"];
	$bankName = trim($res_name["bankName"]);
	
	$name = str_replace("'", "\'"," ".$bankID.""." # ".$bankName);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

    $dt['value'] = $bankID."#".$bankName;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}
	
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
