<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT carid, car_license, \"C_CARNUM\",\"C_MARNUM\" FROM ta_join_main a
LEFT JOIN \"Fc\" b on a.\"carid\"=b.\"CarID\" 
where car_license_seq='0' and deleted='0' and cancel='0' and (\"car_license\" like '%$term%' or \"C_CARNUM\" like '%$term%')");
$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    list($carid,$carregis,$carnum,$marnum)=$res_name;

    $name = str_replace("'", "\'"," ".$carregis.""." / ".$carnum.""." / ".$marnum);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $carid."#".$carregis;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
