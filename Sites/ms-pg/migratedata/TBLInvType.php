<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TBLInvType",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$Description=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Description"]));
	$DefaultPriceExcVat=trim($res_fc["DefaultPriceExcVat"]);
	$DefaultPercen0fDiscount=trim(iconv('WINDOWS-874','UTF-8',$res_fc["DefaultPercen0fDiscount"]));
	$Priority=trim($res_fc["Priority"]);
	$NeedVat=trim($res_fc["NeedVat"]);
	$CreatedVATWInv=trim($res_fc["CreatedVATWInv"]);

	$query = pg_query("select * from taxiacc.\"TBLInvType\" where \"InvType\" = '$InvType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		if($DefaultPercen0fDiscount == ""){	
			$ins="insert into taxiacc.\"TBLInvType\" (\"InvType\",\"Description\",\"DefaultPriceExcVat\",\"DefaultPercen0fDiscount\",\"Priority\",\"NeedVat\",\"CreatedVATWInv\") values 
			('$InvType','$Description','$DefaultPriceExcVat',null,'$Priority','$NeedVat','$CreatedVATWInv')";
		}else{
			$ins="insert into taxiacc.\"TBLInvType\" (\"InvType\",\"Description\",\"DefaultPriceExcVat\",\"DefaultPercen0fDiscount\",\"Priority\",\"NeedVat\",\"CreatedVATWInv\") values 
			('$InvType','$Description','$DefaultPriceExcVat','$DefaultPercen0fDiscount','$Priority','$NeedVat','$CreatedVATWInv')";
		}
		
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

