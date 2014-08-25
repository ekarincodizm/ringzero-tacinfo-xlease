<?php
include("../../config/config.php");
$q = $_GET["q"];
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา
$sql = "select A.*,B.*,C.* from  \"Fp\"  A
	                      LEFT OUTER JOIN \"VCarregistemp\" B ON B.\"IDNO\"=A.\"IDNO\"
	                      LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=A.\"CusID\"
	                     where (A.\"IDNO\" like '%$q%') AND (A.asset_id like 'GAS%') OR (B.\"C_REGIS\" like '%$q%') OR (B.\"C_CARNUM\" like '%$q%') OR  (C.\"A_NAME\" like '%$q%') LIMIT 10  ";
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
			$name =trim($row["A_NAME"]);
			$surname =trim($row["A_SIRNAME"]);
			$fir_name=trim($row["A_FIRNAME"]);
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
			  $qry_gas=pg_query("select \"GasID\",car_regis from \"FGas\" where \"GasID\"='$ass_id' ");
			  $resgas=pg_fetch_array($qry_gas);
			  $regis=$resgas["car_regis"];
			   $article="[GAS";
			} 
			// ป้องกันเครื่องหมาย '
			$name = str_replace("'", "\'",$article." ".$id.$slock."]"." / ".$regis." / ".$fir_name.$name."  ".$surname." / เลขตัวถัง ".$carn);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
		 }
     }
?>