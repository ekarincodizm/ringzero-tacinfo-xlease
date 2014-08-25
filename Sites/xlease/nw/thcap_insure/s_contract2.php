<?php
include("../../config/config.php");
//ใ้ช้สำหรับเพิ่มกรมธรรม์ กรณีได้เลขกรมธรรม์มาแล้ว
$term = $_GET['term'];

//ต้องค้นจากเลขที่สัญญาที่มีกรมธรรม์แล้วเท่านั้น
$qry_name=pg_query("select a.\"ContractID\" from \"thcap_insure_temp\" a
left join \"thcap_insure_main\" b on a.\"auto_id\"=b.\"auto_tempID\"
where (a.\"ContractID\" like '%$term%' or a.\"insureNum\" like '%$term%') 
and (b.\"Active\" = 'TRUE' or b.\"Active\" is null)
and a.\"statusApprove\"='1' and a.\"statusInsure\" <> '2' group by a.\"ContractID\"");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $contractID=$res_name["ContractID"];
	$insureNum=$res_name["insureNum"]; if($insureNum==""){ $insureNum="-"; }
    
    $name = str_replace("'", "\'"," ".$contractID."");
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
