<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select a.\"CusID\",\"full_name\",\"N_IDCARD\",\"N_CARDREF\" from \"VSearchCus\" a
LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"
WHERE a.\"full_name\" like '%$term%' OR a.\"CusID\" like '%$term%' OR b.\"N_IDCARD\" like '%$term%' OR b.\"N_CARDREF\" like '%$term%' group by a.\"CusID\",a.\"full_name\",b.\"N_IDCARD\",b.\"N_CARDREF\"");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);
	$N_IDCARD = trim($res_name["N_IDCARD"]);
	if($N_IDCARD == ""){
		$N_IDCARD = trim($res_name["N_CARDREF"]);
	}
	
	$name = str_replace("'", "\'",$CusID."#".$name."#".$N_IDCARD);
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
