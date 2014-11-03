<?php
include("../../config/config.php");
$term =  pg_escape_string($_GET['term']);

$term=strtr($term, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$term=ereg_replace('[[:space:]]+', '', trim($term)); //ตัดช่องว่างออก

$qry_name=pg_query("SELECT
						\"full_name\",
						\"CusID\",
						\"IDCARD\"
					FROM
						\"VSearchCusCorp\"
					WHERE
						replace(replace(\"full_name\",' ',''),'-','') like '%$term%' OR
						replace(replace(\"IDCARD\",' ',''),'-','') like '%$term%' OR
						\"CusID\" like '%$term%'");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);
	$IDCARD = trim($res_name["IDCARD"]);
	
    $dt['value'] = $CusID."#".$name."#".$IDCARD;
    $dt['label'] = "{$CusID}, {$name}, {$IDCARD}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>