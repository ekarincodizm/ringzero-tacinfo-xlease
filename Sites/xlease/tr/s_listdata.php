<?php
include("../config/config.php");

set_time_limit(60);

$q = $_GET["q"];
$pagesize = 10; // จำนวนรายการที่ต้องการแสดง
//$table_db="article"; // ตารางที่ต้องการค้นหา
//$find_field="arti_topic"; // ฟิลที่ต้องการค้นหา

$sql = "select a.\"IDNO\", e.\"full_name\", c.\"TranIDRef1\", c.\"TranIDRef2\", a.\"CarID\" as asset_id, a.\"C_CARNUM\", c.\"LockContact\", c.\"asset_type\", a.\"C_REGIS\"
from  \"Carregis_temp\" a
LEFT JOIN \"Fp\" c ON a.\"IDNO\"::text = c.\"IDNO\"::text
LEFT JOIN \"Fa1_FAST\" e ON c.\"CusID\"::bpchar = e.\"CusID\"::bpchar
where (a.\"IDNO\" like '%$q%') OR (a.\"C_REGIS\" like '%$q%') OR (a.\"C_CARNUM\" like '%$q%') OR  
	(e.\"full_name\" like '%$q%') OR (c.\"TranIDRef1\" like '%$q%') OR (c.\"TranIDRef2\" like '%$q%')

union

select \"IDNO\",\"full_name\",\"TranIDRef1\",\"TranIDRef2\",asset_id,\"carnum\",\"LockContact\",\"asset_type\",car_regis as \"C_REGIS\"
from \"Fp\" a
inner join \"FGas\" b on a.asset_id=b.\"GasID\"
left join \"VSearchCus\" c on a.\"CusID\"=c.\"CusID\"
where (\"IDNO\" like '%$q%') OR (\"car_regis\" like '%$q%') OR (\"carnum\" like '%$q%') OR  
	(\"full_name\" like '%$q%') OR (\"TranIDRef1\" like '%$q%') OR (\"TranIDRef2\" like '%$q%') limit 100";

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
			$ref="R1 ".trim($row["TranIDRef1"])."/ R2 ".trim($row["TranIDRef2"]);
			
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
			$name = str_replace("'", "\'",$article." ".$id.$slock."]"." / ".$regis." / ".$full_name." / Ref ".$ref);
			// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
			$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
			echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
		 }
    }
?>