<?php
include("../../config/config.php");
$term = $_GET['term'];
$a1[] = "";
$Criteria = pg_escape_string($_GET["criteria"]);

//Query จากตารางที่ insert ข้อมูล โดยใช้ฟังก์ชัน 
	$sql=pg_query("select * from thcap_installment_search where \"TIS_Default\" like '%$term%'");
	$numrows = pg_num_rows($sql);
		if($numrows > 0){
			while($res=pg_fetch_array($sql))
			{
			$TIS_Default = $res["TIS_Default"]; // เลขที่สัญญา
			$str=explode("#",$TIS_Default);
			list($string,$contractID)=explode(":",$str[1]);
			$dt['value'] = trim($contractID);
			$dt['label'] = trim($TIS_Default);
			$matches[] = $dt;
					
			}				
		} 

if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>