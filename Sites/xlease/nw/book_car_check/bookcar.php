<?php
include("../../config/config.php"); 

$term = pg_escape_string($_GET['term']);

$sql_select=pg_query("select \"IDNO\",\"C_REGIS\",\"bookcarID\",\"cusname\",\"date\" from \"book_car_check\" where \"IDNO\" like '%$term%' OR \"C_REGIS\" like '%$term%'  OR \"cusname\" like '%$term%' order by \"IDNO\" DESC");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
	$bookid = trim($res_cn["bookcarID"]);
    $IDNO = trim($res_cn["IDNO"]);
    $full_name = trim($res_cn["cusname"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
	$date = trim($res_cn["date"]);
	

	$dt['value'] = $bookid."#".$IDNO."#".$full_name."#".$C_REGIS;
	$dt['label'] = "{$IDNO} : {$full_name} : {$C_REGIS} : {$date}";
	$matches[] = $dt;		
}
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>