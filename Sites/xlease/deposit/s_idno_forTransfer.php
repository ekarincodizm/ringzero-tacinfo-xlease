<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']); // ค่าที่คีย์ใน textbox
$CusID = pg_escape_string($_GET["CusID"]); // รหัสลูกค้า
$asset_id = pg_escape_string($_GET["asset_id"]); // เลขที่ตัวถัง
$idno_java = pg_escape_string($_GET["idno"]); // เลขที่สัญญาเดิม

//$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\" like '%$term%' and (\"CusID\" = '$CusID' or \"asset_id\" = '$asset_id') and \"IDNO\" <> '$idno_java' "); // ถ้าจะเอาเฉพาะสัญญาที่ถูกต้องเท่านั้น ให้ใช้ SQL นี้
$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\" like '%$term%'"); // เอาทั้งหมด
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
    $IDNO = trim($res_name["IDNO"]); // เลขที่สัญญาที่จะโอน
	
	$dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
