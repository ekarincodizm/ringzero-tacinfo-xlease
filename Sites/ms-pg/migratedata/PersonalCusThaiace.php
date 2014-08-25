<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$i=1;
$sql_fc=mssql_query("select * from PersonalCusThaiace",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$IDNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["IDNO"]));
	$State=trim(iconv('WINDOWS-874','UTF-8',$res_fc["State"]));
	$FirName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["FirName"]));
	$Name=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Name"]));
	$C_Name=trim(iconv('WINDOWS-874','UTF-8',$res_fc["C_Name"]));
	$C_Regis=trim(iconv('WINDOWS-874','UTF-8',$res_fc["C_Regis"]));
	$C_Color=trim(iconv('WINDOWS-874','UTF-8',$res_fc["C_Color"]));
	$C_CarNum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["C_CarNum"]));
	$N_card=trim(iconv('WINDOWS-874','UTF-8',$res_fc["N_card"]));
	$N_IDCard=trim(iconv('WINDOWS-874','UTF-8',$res_fc["N_IDCard"]));
	$N_OT_Date=trim(iconv('WINDOWS-874','UTF-8',$res_fc["N_OT_Date"]));
	$N_ContacAdd=trim(iconv('WINDOWS-874','UTF-8',$res_fc["N_ContacAdd"]));
	
	$N_ContacAdd = strtr($N_ContacAdd, "'", "*" );
	
	$query = pg_query("select * from taxiacc.\"PersonalCusThaiace\" where \"IDNO\" = '$IDNO' and \"State\" = '$State'");
	$num_row = pg_num_rows($query);
	if($num_row == 0 ){  
		$ins="insert into taxiacc.\"PersonalCusThaiace\" 
		(\"IDNO\",\"State\",\"FirName\",\"Name\",\"C_Name\",\"C_Regis\",\"C_Color\",\"C_CarNum\",\"N_card\",\"N_IDCard\",\"N_OT_Date\",\"N_ContacAdd\") values 
		('$IDNO','$State','$FirName','$Name','$C_Name','$C_Regis','$C_Color','$C_CarNum','$N_card','$N_IDCard','$N_OT_Date','$N_ContacAdd')";
		
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

