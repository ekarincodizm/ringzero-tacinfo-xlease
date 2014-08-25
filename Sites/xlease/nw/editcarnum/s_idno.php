<?php
include("../../config/config.php"); 

$term = $_GET['term'];

/*$sql_select=pg_query("select \"IDNO\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\",\"C_CARNUM\",carnum from \"VContact\" 
WHERE \"full_name\" like '%$term%' or \"IDNO\" like '%$term%' or \"C_REGIS\" like '%$term%' or \"car_regis\" like '%$term%' 
or \"C_CARNUM\" like '%$term%' or carnum like '%$term%' ");
$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $full_name = trim($res_cn["full_name"]);
    $C_REGIS = trim($res_cn["C_REGIS"]);
	$car_regis = trim($res_cn["car_regis"]);
	$C_CARNUM = trim($res_cn["C_CARNUM"]);
	$carnum = trim($res_cn["carnum"]);
	
	$name = str_replace("'", "\'"," ".$IDNO.""." : ".$full_name.""." : ".$C_REGIS.$car_regis." : ".$C_CARNUM.$carnum);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

	$dt['value'] = $IDNO;
	$dt['label'] = $display_name;
	$matches[] = $dt;		
} */
$sql=pg_query("select * from tal_installment_search where \"tal_default\" like '%$term%'");
	$numrows = pg_num_rows($sql);
		if($numrows > 0){
			while($res=pg_fetch_array($sql))
			{
			$tal_Default = $res["tal_default"]; 
			$IDNO = explode(":",$tal_Default);
			$dt['value'] = trim($IDNO[0]);
			$dt['label'] = trim($tal_Default);
			$matches[] = $dt;
					
			}				
		} 
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>