<?php
include("../config/config.php");
$q = $_GET["q"];
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา
$sql = "select \"IDNO\",\"full_name\",\"asset_id\",\"C_CARNUM\",\"LockContact\",\"asset_type\",\"C_REGIS\",\"car_regis\",\"carnum\" from  \"VContact\" 
	    where (\"IDNO\" like '%$q%') OR (\"C_REGIS\" like '%$q%') OR (\"C_CARNUM\" like '%$q%') OR  (\"full_name\" like '%$q%') OR 
		(\"car_regis\" like '%$q%') OR (\"carnum\" like '%$q%') LIMIT 10  ";
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
			$ass_id=trim($row["asset_id"]);
			$carn=trim($row["C_CARNUM"]);
			
			if($row["LockContact"]=='t')
			  {
				$slock=" x Locked x ";
			  }
			  else
			  {
				$slock="";
			  }
			
			
			if($row["asset_type"]==1)
			{
			 $regis=trim($row["C_REGIS"]);
			 $article="[CAR";
			}
			else
			{
			  $regis=$row["car_regis"];
			  $carn=trim($row["carnum"]);
			   $article="[GAS";
			} 
			// ป้องกันเครื่องหมาย '
			$name = str_replace("'", "\'",$article." ".$id.$slock."]"." / ".$regis." / ".$name." / เลขตัวถัง ".$carn);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
		 }
     }
?>