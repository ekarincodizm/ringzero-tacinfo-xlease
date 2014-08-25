<?php
include("../../config/config.php");
$q = $_GET["q"];
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง

$sql = "select * from  \"VCarregistemp\" 
where (\"LockContact\" = 'TRUE') AND ((\"IDNO\" like '%$q%') OR (\"C_REGIS\" like '%$q%') OR (\"C_CARNUM\" like '%$q%') OR  (\"full_name\" like '%$q%') ) LIMIT(5) ";
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
			$full_name =trim($row["full_name"]);
			$ass_id=trim($row["CarID"]);
			$carn=trim($row["C_CARNUM"]);
			
			if($row["LockContact"]=='t')
			  {
				$slock=": x Locked x ";
			  }
			  else
			  {
				$slock="";
			  }
			
			
			if($row["asset_type"]==1)
			{
			 $regis=trim($row["C_REGIS"]);
			}
			else
			{
			  $qry_gas=pg_query("select \"GasID\",car_regis from \"FGas\" where \"GasID\"='$ass_id' ");
			  $resgas=pg_fetch_array($qry_gas);
			  $regis=$resgas["car_regis"];
			} 
			// ป้องกันเครื่องหมาย '
			$name = str_replace("'", "\'","[".$id.$slock."]"." / ".$regis." / ".$full_name);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
		 }
     }
?>