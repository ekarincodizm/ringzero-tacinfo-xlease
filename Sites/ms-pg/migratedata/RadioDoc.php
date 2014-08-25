<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from RadioDoc",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$DocID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DocID"]));
	$PrintTIme=trim($res_fc["PrintTIme"]);
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioID"]));
	$DocDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DocDate"]));
	$FUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["FUser"]));
	$PrintDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["PrintDate"]));
	
	$query = pg_query("select * from taxiacc.\"RadioDoc\" where \"DocID\" = '$DocID' and \"PrintTIme\" = '$PrintTIme'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"RadioDoc\" (\"DocID\",\"PrintTIme\",\"CusID\",\"RadioID\",\"DocDate\",\"FUser\",\"PrintDate\") values 
		('$DocID','$PrintTIme','$CusID','$RadioID','$DocDate','$FUser','$PrintDate')";
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

