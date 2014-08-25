<?php
include("../config/config.php");
$q = $_GET["q"];

$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา

$sql = "select * from  \"VSearchCus\"  
		where (\"full_name\" like '%$q%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

if($nrows==0) // ไม่พบลูกค้าที่ต้องการค้นหา
{
	$name="ไม่พบข้อมูล";
	$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
	echo "<li onselect=\"this.setText('$name'); \">$display_name</li>";
}
else
{
	while($row = pg_fetch_array( $results ))
	{
		$id = $row["CusID"]; // ฟิลที่ต้องการส่งค่ากลับ
		$name =trim($row["full_name"]);
		
		// ป้องกันเครื่องหมาย '
		$name = str_replace("'", "\'",$name);
		// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
		$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
		echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
	}
}
?>