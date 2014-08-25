<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from VatRange",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$StartDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["StartDate"]));
	$EndDate=trim($res_fc["EndDate"]);
	$PercentVat=trim($res_fc["PercentVat"]);
	
	$query = pg_query("select * from taxiacc.\"VatRange\" where \"StartDate\" = '$StartDate' and \"EndDate\"='$EndDate'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"VatRange\" (\"StartDate\",\"EndDate\",\"PercentVat\") values ('$StartDate','$EndDate','$PercentVat')";
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

