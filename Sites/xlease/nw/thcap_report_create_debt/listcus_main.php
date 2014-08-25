<?php
include("../../config/config.php");
$term = $_GET['term'];
/*
$sql = "select * from  \"vthcap_ContactCus_detail\"
where (\"thcap_fullname\" like '%$term%') and \"CusState\" = '0' order by \"thcap_NLname\"";
*/
//$sql = "select \"CusID\",\"full_name\" from \"VSearchCusCorp\" WHERE \"full_name\" like '%$term%'";
$sql = "select a.\"contractID\",b.\"FullName\" from \"thcap_v_otherpay_debt_realother\" a left join \"thcap_ContactCus\" b on a.\"contractID\"=b.\"contractID\" where a.\"contractID\" like '%$term%' GROUP BY a.\"contractID\",b.\"FullName\"";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["contractID"];
	$fullname =trim($row["FullName"]);
	

	$name = str_replace("'", "\'"," ".$id.""." / ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $id;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>