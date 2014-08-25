<?php
include("../../config/config.php");
$q = $_GET["q"];
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา
$sql = "select A.\"IDNO\",C.\"full_name\",B.\"car_regis\",B.\"carnum\",A.\"LockContact\" from  \"Fp\"  A
inner JOIN \"FGas\" B ON A.\"asset_id\"=B.\"GasID\"
LEFT JOIN \"VSearchCus\" C ON A.\"CusID\"=C.\"CusID\"
where (A.\"IDNO\" like '%$q%') AND (A.asset_id like 'GAS%') OR (B.\"car_regis\" like '%$q%') OR (B.\"carnum\" like '%$q%') OR  (C.\"full_name\" like '%$q%') LIMIT 15  ";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);
if($nrows==0)
	{
	  $name="ไม่พบข้อมูล";
	  $display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
	  echo "<li onselect=\"this.setText('$name'); \">$display_name</li>";
	 
	}
	else
	{						 
						 
		
		while($row = pg_fetch_array( $results )) 
		{
			$id = $row["IDNO"]; // ฟิลที่ต้องการส่งค่ากลับ
			$name =trim($row["full_name"]);
			$carn=trim($row["carnum"]);
			
			if($row["LockContact"]=='t')
			  {
				$slock=" x Locked x ";
			  }
			  else
			  {
				$slock="";
			  }
			
			 $regis=trim($row["car_regis"]);
			 $article="[GAS";

			
			// ป้องกันเครื่องหมาย '
			$name = str_replace("'", "\'",$article." ".$id.$slock."]"." / ".$regis." / ".$name." / เลขตัวถัง ".$carn);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
		 }
     }
?>