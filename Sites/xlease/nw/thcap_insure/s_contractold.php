<?php
include("../../config/config.php");
$term = $_GET['term'];

//ค้นจากประกันภัยที่มีเลขที่กรมธรรม์อยู่แล้ว
$qry_name=pg_query("select * from \"thcap_insure_main\" a
left join thcap_insure_temp b on a.\"auto_tempID\"=b.\"auto_id\"
where (\"ContractID\" like '%$term%' or a.\"insureNum\" like '%$term%')");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $contractID=$res_name["ContractID"];
	$insureNum=$res_name["insureNum"]; if($insureNum==""){ $insureNum="-"; }
    
    $name = str_replace("'", "\'"," ".$contractID.""." /เลขที่กรมธรรม์ ".$insureNum);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
	
	$dt['value'] = $contractID;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
