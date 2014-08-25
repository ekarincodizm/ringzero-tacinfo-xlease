<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("SELECT distinct(a.\"BAccount\"),\"BCompany\",\"BName\",\"BBranch\" FROM cheque_detail a
left join \"BankInt\" b on a.\"BAccount\"=b.\"BAccount\"
WHERE (a.\"BAccount\" LIKE '%$term%' or \"BCompany\" LIKE '%$term%' or \"BName\" LIKE '%$term%' or \"BBranch\" LIKE '%$term%') and \"isChq\"='1'");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $BAccount=$res_name["BAccount"];
	$BCompany=$res_name["BCompany"];
    $BName=$res_name["BName"];
    $BBranch=$res_name["BBranch"];
    
    $dt['value'] = $BAccount;
    $dt['label'] = "{$BAccount}, {$BCompany}, ธนาคาร{$BName}, สาขา{$BBranch}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
