<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacDocNo",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$DocType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DocType"]));
	$DocNum=trim($res_fc["DocNum"]);
	
	$query = pg_query("select * from taxiacc.\"TacDocNo\" where \"DocType\" = '$DocType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacDocNo\" (\"DocType\",\"DocNum\") values ('$DocType','$DocNum')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
		}
	}	
}
if($status == 0){
    pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

