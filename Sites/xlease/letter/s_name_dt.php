<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select distinct(A.\"IDNO\"),C.\"full_name\",E.\"C_REGIS\",F.\"car_regis\" from letter.\"SendDetail\" A
left join letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"
left join \"VSearchCus\" C on B.\"CusID\" = C.\"CusID\"
left join \"Fp\" D on A.\"IDNO\" = D.\"IDNO\"
left join \"VCarregistemp\" E on D.\"IDNO\"=E.\"IDNO\"
left join \"FGas\" F on D.\"asset_id\"=F.\"GasID\"
WHERE A.\"IDNO\" LIKE '%$term%' OR C.\"full_name\" LIKE '%$term%' OR E.\"C_REGIS\" LIKE '%$term%' OR F.\"car_regis\" LIKE '%$term%'");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $auto_id=$res_name["auto_id"];
	$address_id = $res_name["address_id"];
    $IDNO=$res_name["IDNO"];
	$name=$res_name["full_name"];
    $C_REGIS=$res_name["C_REGIS"]; if($C_REGIS=="") $C_REGIS="ไม่พบทะเบียนรถ";

    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO},ผู้เช่าซื้อ:{$name}, {$C_REGIS}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
