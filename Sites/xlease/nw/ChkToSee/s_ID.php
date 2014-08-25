<?php
include("../../config/config.php");
$term = $_GET['term'];

//$qry_name=pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" like '%$term%' order by \"contractID\" ASC");
/*$qry_name=pg_query("SELECT * FROM \"VContact\" 
					WHERE (\"IDNO\" like '%$term%') OR (\"C_REGIS\" like '%$term%') OR (\"car_regis\" like '%$term%') OR (\"C_CARNUM\" like '%$term%') OR (\"full_name\" like '%$term%') 
					OR (\"TranIDRef1\" like '%$term%') OR (\"TranIDRef2\" like '%$term%') ORDER BY \"IDNO\" LIMIT 100");
$numrows = pg_num_rows($qry_name);
while($res_cn = pg_fetch_array($qry_name)){
	$IDNO = trim($res_cn["IDNO"]);
    $asset_type = trim($res_cn["asset_type"]);
    $full_name = trim($res_cn["full_name"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
    $TranIDRef1 = trim($res_cn["TranIDRef1"]);
    $TranIDRef2 = trim($res_cn["TranIDRef2"]);
    
    if($asset_type == 1){
        $regis = trim($res_cn["C_REGIS"]);
    }else{
        $regis = trim($res_cn["car_regis"]);
    }
    
    $dt['value'] = $IDNO;
    $dt['label'] = "$IDNO : $regis - $full_name - $C_CARNUM - $TranIDRef1 - $TranIDRef2";
    $matches[] = $dt;
} */
$sql=pg_query("select * from tal_installment_search where \"tal_default\" like '%$term%' limit 100");
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

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>