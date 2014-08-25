<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacDeposit",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$DepID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DepID"]));
	$DepDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DepDate"]));
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$DepAmt=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DepAmt"]));
	$RefReceiptNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RefReceiptNO"]));
	$DepRemark=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DepRemark"]));
	$DepCancel=trim($res_fc["DepCancel"]);
	$DepCancelUserID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DepCancelUserID"]));
	
	$query = pg_query("select * from taxiacc.\"TacDeposit\" where \"DepID\" = '$DepID'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacDeposit\" (\"DepID\",\"DepDate\",\"CusID\",\"DepAmt\",\"RefReceiptNO\",\"DepRemark\",\"DepCancel\",\"DepCancelUserID\") values 
		('$DepID','$DepDate','$CusID','$DepAmt','$RefReceiptNO','$DepRemark','$DepCancel','$DepCancelUserID')";
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

