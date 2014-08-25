<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacRInvoice ",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$RInvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvNO"]));
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));
	$InvFixDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvFixDate"]));
	$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$RInvDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvDate"]));
	$RDiscount=trim($res_fc["RDiscount"]);	
	$RRecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RRecNO"]));
	$RInvAmount=trim($res_fc["RInvAmount"]);
	$RVatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RVatNO"]));
	$RInvVat=trim($res_fc["RInvVat"]);
	$RInvIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvIDUser"]));
	$RInvRemark=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvRemark"]));
	
	$query = pg_query("select * from taxiacc.\"TacRInvoice\" where \"RInvNO\" = '$RInvNO' and \"InvNo\" = '$InvNo' and \"InvFixDate\" = '$InvFixDate' and \"InvType\" = '$InvType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacRInvoice\" (\"RInvNO\",\"InvNo\",\"InvFixDate\",\"InvType\",\"RInvDate\",\"RDiscount\",\"RRecNO\",\"RInvAmount\",\"RVatNO\",\"RInvVat\",\"RInvIDUser\",\"RInvRemark\") values 
		('$RInvNO','$InvNo','$InvFixDate','$InvType','$RInvDate','$RDiscount','$RRecNO','$RInvAmount','$RVatNO','$RInvVat','$RInvIDUser','$RInvRemark')";
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

