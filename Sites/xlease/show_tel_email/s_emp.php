<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);
$qry_name=pg_query("select * from \"Vfuser\" where \"fullname\" like '%$term%' and resign_date is null and \"user_group\" <>'AD'"); //ยกเว้น แผนก AD

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$id_user=trim($res_name["id_user"]);
	$fullname=trim($res_name["fullname"]);
    
    $dt['value'] = "$id_user".'#'."$fullname";
    $dt['label'] = "{$id_user} # {$fullname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>