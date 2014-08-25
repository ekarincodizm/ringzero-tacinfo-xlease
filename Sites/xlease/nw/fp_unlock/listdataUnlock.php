<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "SELECT a.\"IDNO\", b.\"C_REGIS\", b.\"C_CARNUM\", ((btrim(d.\"A_FIRNAME\") || btrim(d.\"A_NAME\")) || ' ') || btrim(d.\"A_SIRNAME\") AS full_name, 
		a.asset_type, a.\"LockContact\",a.asset_id
		FROM \"Fp\" a
		LEFT JOIN \"VCarregistemp\" b on a.\"IDNO\" = b.\"IDNO\"
		LEFT JOIN \"Fa1\" d ON a.\"CusID\" = d.\"CusID\"
		LEFT JOIN \"FGas\" e ON a.asset_id = e.\"GasID\"
		where a.\"LockContact\" = true AND ((a.\"IDNO\" like '%$term%') OR (b.\"C_REGIS\" like '%$term%') OR (b.\"C_CARNUM\" like '%$term%') 
		OR  (((btrim(d.\"A_FIRNAME\") || btrim(d.\"A_NAME\")) || ' ') || btrim(d.\"A_SIRNAME\") like '%$term%')
		OR (e.\"car_regis\" like '%$term%') OR (e.\"carnum\" like '%$term%'))";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($row = pg_fetch_array( $results )) {
	$id = $row["IDNO"]; // ฟิลที่ต้องการส่งค่ากลับ
	$fullname =trim($row["full_name"]);
	$ass_id=trim($row["asset_id"]);
	$carn=trim($row["C_CARNUM"]);
			
	if($row["LockContact"]=='t'){
		$slock=" x Locked x ";
	}else{
		$slock="";
	}
				
	if($row["asset_type"]==1){
		$regis=trim($row["C_REGIS"]);
		$article="[CAR";
	}else{
		$qry_gas=pg_query("select \"GasID\",car_regis from \"FGas\" where \"GasID\"='$ass_id' ");
		$resgas=pg_fetch_array($qry_gas);
		$regis=$resgas["car_regis"];
		$article="[GAS";
	} 
	
	$name = str_replace("'", "\'",$article." ".$id.$slock."]"." / ".$regis." / ".$fullname." / เลขตัวถัง ".$carn);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", $name);
    
	$dt['value'] = $id."#".$fullname;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}
if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>