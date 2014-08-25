<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from BankName",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$BankID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["BankID"]));
	$BankName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["BankName"]));
	
	$query = pg_query("select * from taxiacc.\"BankName\" where \"BankID\" = '$BankID'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"BankName\" (\"BankID\",\"BankName\") values ('$BankID','$BankName')";
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

