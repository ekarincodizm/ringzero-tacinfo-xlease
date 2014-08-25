<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$a1[] = "";
//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("select \"accBookID\",\"accBookName\", \"accBookserial\" FROM account.\"V_all_accBook\" where \"accBookID\"like '%$term%' or \"accBookName\" like '%$term%' ");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$a1[] = $res["accBookID"]; // เลขที่สัญญา
		$t1=$res["accBookID"]; // เลขที่สมุดบัญชี
		$t2=$res["accBookName"]; // ชื่อสมุดบัญชี
		$t3=$res["accBookserial"]; // รหัสสมุดบัญชี

		$txtLable = "<font color=\"#000000\">$t1: $t2</font>";

		$dt['value'] = $t3."# ".$t1.": ".$t2;
		$dt['label'] = $txtLable;
		$matches[] = $dt;
					
	}				
}
if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>