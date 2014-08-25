<?php
include("../config/config.php");
$q = pg_escape_string($_GET["q"]);
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา
$sql = "select  * from  letter.send_address 
	    where (name like '%$q%') OR (\"IDNO\" like '%$q%')";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);
if($nrows==0)
	{
	  $name="ไม่พบข้อมูลการส่งจดหมาย";
	  $display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
	  echo "<li onselect=\"this.setText('$name'); \">$display_name</li>";
	 
	}
	else
	{						 
						 
		
		while($row = pg_fetch_array( $results )) 
		{
			$id = $row["CusLetID"]; // ฟิลที่ต้องการส่งค่ากลับ
			$name_letter =trim($row["name"]);
			$idno = $row["IDNO"];
			$ads=trim($row["dtl_ads"]);
			// ป้องกันเครื่องหมาย '
			$name = str_replace("'", "\'",$idno.$id." / ".$name_letter." ".$ads);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$idno'); \">$display_name</li>";
		 }
     }
?>