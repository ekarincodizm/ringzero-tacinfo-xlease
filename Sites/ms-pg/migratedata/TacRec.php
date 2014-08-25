<?php
set_time_limit (0); 
ini_set("memory_limit","1024M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacRec ",$conn); 
while($res_fc = mssql_fetch_array($sql_fc)){
	$RecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecNO"]));
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$RecDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecDate"]));
	$RecValue=trim($res_fc["RecValue"]);
	$RPay=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RPay"]));
	$RCQBank=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQBank"]));
	
	$RCQBranch=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQBranch"]));
	$RCQBranch = strtr($RCQBranch, "'", "*" );
	
	$RCQID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQID"]));
	$RCQDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQDate"]));
	$DepositID=trim($res_fc["DepositID"]);
	$RIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RIDUser"]));
	$PrintDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["PrintDate"]));
	$RecCancel=trim($res_fc["RecCancel"]);
	$RecCancelUserID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecCancelUserID"]));
	$CancelDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelDate"]));
	$CancelRemark=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelRemark"]));
	
	$query = pg_query("select * from taxiacc.\"TacRec\" where \"RecNO\" = '$RecNO'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		$ins="insert into taxiacc.\"TacRec\" (\"RecNO\",\"CusID\",\"RecDate\",\"RecValue\",\"RPay\",\"RCQBank\",\"RCQBranch\",\"RCQID\",\"RCQDate\",\"DepositID\",\"RIDUser\",\"PrintDate\",\"RecCancel\",\"RecCancelUserID\",\"CancelDate\",\"CancelRemark\") values 
		('$RecNO','$CusID','$RecDate','$RecValue','$RPay','$RCQBank','$RCQBranch','$RCQID','$RCQDate','$DepositID','$RIDUser','$PrintDate','$RecCancel','$RecCancelUserID','$CancelDate','$CancelRemark')";
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

