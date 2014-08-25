<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("SELECT b.\"full_name\" as \"userSalebill\", c.\"full_name\" as \"userDebtor\", \"numberInvoice\",\"prebillIDMaster\"
FROM thcap_fa_prebill a
LEFT JOIN \"VSearchCusCorp\" b on a.\"userSalebill\"=b.\"CusID\"
LEFT JOIN \"VSearchCusCorp\" c on a.\"userDebtor\"=c.\"CusID\"
WHERE \"numberInvoice\" like '%$term%' OR b.\"full_name\" like '%$term%' OR c.\"full_name\" like '%$term%' 
group by b.\"full_name\", c.\"full_name\", \"numberInvoice\",\"prebillIDMaster\"");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
	$prebillIDMaster = trim($res_name["prebillIDMaster"]);
	$numberInvoice = trim($res_name["numberInvoice"]);
	$userSalebill = trim($res_name["userSalebill"]);
	$userDebtor = trim($res_name["userDebtor"]);

	$name = str_replace("'", "\'",$prebillIDMaster."#".$numberInvoice."#ผู้ขายบิล: ".$userSalebill."#ลูกหนี้: ".$userDebtor);
    $dt['value'] = $name;
    $dt['label'] = $name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
