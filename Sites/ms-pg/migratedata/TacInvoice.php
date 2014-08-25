<?php
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacInvoice",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));
	$InvFixDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvFixDate"]));
	$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$CusID=trim($res_fc["CusID"]);
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioID"]));
	$Description=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Description"]));
	$InvDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvDate"]));
	$PriceUnit=trim($res_fc["PriceUnit"]);
	$NumUnit=trim($res_fc["NumUnit"]);
	$RecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecNO"]));
	$VatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatNO"]));
	$NeedVAT=trim($res_fc["NeedVAT"]);
	$Discount=trim($res_fc["Discount"]);
	$InvAmountExVAT=trim($res_fc["InvAmountExVAT"]);
	$InvCancel=trim($res_fc["InvCancel"]);
	$RInvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvNO"]));
	$InvIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvIDUser"]));
	$CancelInvDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelInvDate"]));
	$CancelIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelIDUser"]));
	$OldInvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["OldInvNO"]));
	$BadDebt=trim($res_fc["BadDebt"]);
	
	$query = pg_query("select * from taxiacc.\"TacInvoice\" where \"InvNo\" = '$InvNo' and \"InvFixDate\" = '$InvFixDate' and \"InvType\" = '$InvType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacInvoice\" (\"InvNo\",\"InvFixDate\",\"InvType\",\"CusID\",\"RadioID\",\"Description\",\"InvDate\",\"PriceUnit\",\"NumUnit\",
		\"RecNO\",\"VatNO\",\"NeedVAT\",\"Discount\",\"InvAmountExVAT\",\"InvCancel\",\"RInvNO\",\"InvIDUser\",\"CancelInvDate\",\"CancelIDUser\",\"OldInvNO\",\"BadDebt\") 
		values 
		('$InvNo','$InvFixDate','$InvType','$CusID','$RadioID','$Description','$InvDate','$PriceUnit','$NumUnit',
		'$RecNO','$VatNO','$NeedVAT','$Discount','$InvAmountExVAT','$InvCancel','$RInvNO','$InvIDUser','$CancelInvDate','$CancelIDUser','$OldInvNO','$BadDebt')";
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

