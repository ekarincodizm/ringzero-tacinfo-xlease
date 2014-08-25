<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select 1 as type , \"contractID\" as \"sID\" FROM thcap_contract where \"contractID\" like '%$term%' 
		union
		select 2 as type , \"taxinvoiceID\" as \"sID\" FROM thcap_v_taxinvoice_otherpay where \"taxinvoiceID\" like '%$term%'
		and \"taxinvoiceID\" not in(select \"taxinvoiceID\" from \"thcap_temp_taxinvoice_cancel\" where \"approveStatus\" = '2')
		order by \"sID\" "); // 2 คือใบเสร็จปกติ , 3 คือใบเสร็จค่าอื่นๆ

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $id = trim($res_name["sID"]);

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
