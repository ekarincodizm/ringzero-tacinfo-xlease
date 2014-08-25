<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacUser ",$conn); 
while($res_fc = mssql_fetch_array($sql_fc)){
	$userid=trim(iconv('WINDOWS-874','UTF-8',$res_fc["userid"]));
	$username=trim(iconv('WINDOWS-874','UTF-8',$res_fc["username"]));
	$Password=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Password"]));
	$FullName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["FullName"]));
	$Department=trim($res_fc["Department"]);
	$AllowInputPage=trim($res_fc["AllowInputPage"]);	
	$AllowAccPage=trim($res_fc["AllowAccPage"]);
	$AllowFinPage=trim($res_fc["AllowFinPage"]);
	$AllowCtlPage=trim($res_fc["AllowCtlPage"]);
	
	$query = pg_query("select * from taxiacc.\"TacUser\" where \"userid\" = '$userid'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacUser\" (\"userid\",\"username\",\"Password\",\"FullName\",\"Department\",\"AllowInputPage\",\"AllowAccPage\",\"AllowFinPage\",\"AllowCtlPage\") values 
		('$userid','$username','$Password','$FullName','$Department','$AllowInputPage','$AllowAccPage','$AllowFinPage','$AllowCtlPage')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
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

