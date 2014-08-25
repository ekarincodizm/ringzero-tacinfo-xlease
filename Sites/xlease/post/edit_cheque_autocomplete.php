<?php
include("../config/config.php");

$term = $_GET['term'];

$qry_name=pg_query("select * from \"VDetailCheque\" WHERE \"ChequeNo\" LIKE '%$term%' ORDER BY \"ChequeNo\",\"PostID\" ASC ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $ChequeNo=$res_name["ChequeNo"];
    $PostID=$res_name["PostID"];
    $BankName=$res_name["BankName"];
    $IsPass=$res_name["IsPass"];
    
    //if($IsPass == "t"){ $IsPass = "TRUE"; }else{ $IsPass = "FALSE"; }

    $dt['value'] = $ChequeNo."#".$PostID;
    $dt['label'] = "{$ChequeNo}, {$PostID}, {$BankName}, {$IsPass}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>