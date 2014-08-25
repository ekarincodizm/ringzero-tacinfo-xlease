<?php
include("../config/config.php"); 

$term = $_GET['term'];

//ตรวจสอบว่าค่าที่ส่งมาเป็นตัวเลขหรือไม่
/*if(is_numeric($term) ) {
	$sql_select=pg_query("
							SELECT a.\"IDNO\",a.\"asset_type\",a.\"full_name\",a.\"C_CARNUM\",a.\"TranIDRef1\",a.\"TranIDRef2\",a.\"C_REGIS\",a.\"car_regis\",b.asset_id 
							FROM \"VContact\" a join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
							WHERE 	(a.\"IDNO\" like '%$term%') OR 
									(a.\"C_REGIS\" like '%$term%') OR 
									(a.\"car_regis\" like '%$term%') OR 
									(a.\"C_CARNUM\" like '%$term%') OR 
									(a.\"TranIDRef1\" like '%$term%') OR 
									(a.\"TranIDRef2\" like '%$term%') 
							ORDER BY a.\"IDNO\"			
						");
} else {
	$sql_select=pg_query("
							SELECT a.\"IDNO\",a.\"asset_type\",a.\"full_name\",a.\"C_CARNUM\",a.\"TranIDRef1\",a.\"TranIDRef2\",a.\"C_REGIS\",a.\"car_regis\",b.asset_id 
							FROM \"VContact\" a join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
							WHERE 	(a.\"IDNO\" like '%$term%') OR 
									(a.\"C_REGIS\" like '%$term%') OR 
									(a.\"car_regis\" like '%$term%') OR 
									(a.\"C_CARNUM\" like '%$term%') OR 
									(a.\"full_name\" like '%$term%')
							ORDER BY a.\"C_REGIS\"
						");
}



$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $asset_type = trim($res_cn["asset_type"]);
    $full_name = trim($res_cn["full_name"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
    $TranIDRef1 = trim($res_cn["TranIDRef1"]);
    $TranIDRef2 = trim($res_cn["TranIDRef2"]);
    $carid = trim($res_cn["asset_id"]);
    if($asset_type == 1){
        $regis = trim($res_cn["C_REGIS"]);
    }else{
        $regis = trim($res_cn["car_regis"]);
    }

	$dt['value'] = "$IDNO : $regis - $full_name - $C_CARNUM - $TranIDRef1 - $TranIDRef2 - $carid";
    $dt['label'] = "{$IDNO} : {$regis} - {$full_name} - {$C_CARNUM} - {$TranIDRef1} - {$TranIDRef2} - {$carid}";
    $matches[] = $dt;
}*/
$sql=pg_query("select \"tal_default\" from tal_installment_search where \"tal_default\" like '%$term%'");
	$numrows = pg_num_rows($sql);
		if($numrows > 0){
			while($res=pg_fetch_array($sql))
			{
			$tal_Default = $res["tal_default"]; 
			$dt['value'] = trim($tal_Default);
			$dt['label'] = trim($tal_Default);
			$matches[] = $dt;
					
			}				
		} 
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0);
print json_encode($matches);
?>