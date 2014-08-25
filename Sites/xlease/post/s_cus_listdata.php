<?php
session_start();
include("../config/config.php");
$term=trim($_GET["term"]);

$sql_select=pg_query("select \"VSearchCus\".\"CusID\" , \"VSearchCus\".\"full_name\" , \"Fn\".\"N_IDCARD\" from \"VSearchCus\" , \"Fn\" where \"VSearchCus\".\"CusID\" = \"Fn\".\"CusID\" and (\"VSearchCus\".\"full_name\" like  '%$term%' or \"Fn\".\"N_IDCARD\" like '%$term%')");
$numrows = pg_num_rows($sql_select);
if($numrows > 0){
    while ($result=pg_fetch_array($sql_select))
    {	
		$CusID = trim($result["CusID"]); // รหัสลูกค้า
		$full_name=trim($result["full_name"]); // ชื่อลูกค้า
		$N_IDCARD=trim($result["N_IDCARD"]); // รหัสบัตรประชาชน
		
		$name = str_replace("'", "\'"," ".$CusID.""." ".$full_name.""." ".$N_IDCARD);
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
		
		$dt['value'] = $CusID."#".$full_name."#".$N_IDCARD;
		$dt['label'] = $display_name;
		$matches[] = $dt;
    }
}
if($matches==""){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?> 
