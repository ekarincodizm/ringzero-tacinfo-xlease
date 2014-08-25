<?php
session_start();
include("../config/config.php");
$term=$_GET["term"];
 
$sql_select=pg_query("select * from  \"Fc\" a
inner join (select MAX(\"CarID\") as carid from \"Fc\" group by \"C_CARNUM\") b on a.\"CarID\"=b.carid
where  (\"C_CARNUM\" like '%$term%') OR (\"C_REGIS\" like '%$term%')");	
$numrows = pg_num_rows($sql_select);
if($numrows > 0){
	while ($result=pg_fetch_array($sql_select))
	{	
		$CarID = trim($result["CarID"]); // รหัสรถ
		$C_REGIS=trim($result["C_REGIS"]); // ทะเบียนรถ
		$C_CARNUM=trim($result["C_CARNUM"]); // เลขตัวถัง
		
		$name = str_replace("'", "\'","".$CarID.""." ทะเบียน ".$C_REGIS.""." เลขตัวถัง ".$C_CARNUM);
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
		
		$dt['value'] = $CarID." ทะเบียน ".$C_REGIS."เลขตัวถัง ".$C_CARNUM;
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