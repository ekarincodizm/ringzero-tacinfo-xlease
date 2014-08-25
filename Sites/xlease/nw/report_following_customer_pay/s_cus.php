<?php
include("../../config/config.php");
$term =  pg_escape_string($_GET['term']);

$term=strtr($term, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$term=ereg_replace('[[:space:]]+', '', trim($term)); //ตัดช่องว่างออก

$qry_name=pg_query("select a.\"full_name\",a.\"CusID\",b.\"N_IDCARD\"  from \"VSearchCus\" a 
left join \"Fn\" b on a.\"CusID\"=b.\"CusID\" 
where replace(replace(a.\"full_name\",' ',''),'-','') like '%$term%' or replace(replace(b.\"N_IDCARD\",' ',''),'-','') like '%$term%' OR a.\"CusID\" like '%$term%'");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);
	$N_IDCARD = trim($res_name["N_IDCARD"]);
	
    $dt['value'] = $CusID."#".$name."#".$N_IDCARD;
    $dt['label'] = "{$CusID}, {$name}, {$N_IDCARD}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>