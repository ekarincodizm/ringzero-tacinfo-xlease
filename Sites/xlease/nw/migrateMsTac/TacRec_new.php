<?php
set_time_limit (0); 

ini_set("memory_limit","1024M"); 
include("config/config.php");

//pg_query("BEGIN WORK");

$status = 0;
$sql_fc=mssql_query("select  RecNO, RIDUser,RPay
,CONVERT(varchar(4), YEAR(RecDate)) + '-' + CONVERT(varchar(2), MONTH(RecDate)) + '-' + CONVERT(varchar(2), DAY(RecDate))  AS RecDate,
CONVERT(varchar(4), YEAR(PrintDate)) + '-' + CONVERT(varchar(2), MONTH(PrintDate)) + '-' + CONVERT(varchar(2), DAY(PrintDate))  AS PrintDate
 from TacRec where RecCancel = 'false' ",$conn); 
while($res_fc = mssql_fetch_array($sql_fc)){
	$RecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecNO"]));
	//$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$RecDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecDate"]));
	//$RecValue=trim($res_fc["RecValue"]);
	$RPay=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RPay"]));
	//$RCQBank=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQBank"]));
	
	//$RCQBranch=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQBranch"]));
	//$RCQBranch = strtr($RCQBranch, "'", "*" );
	
	//$RCQID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQID"]));
	//$RCQDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RCQDate"]));
	//$DepositID=trim($res_fc["DepositID"]);
	$RIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RIDUser"]));
	$PrintDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["PrintDate"]));
	//$RecCancel=trim($res_fc["RecCancel"]);
	//$RecCancelUserID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecCancelUserID"]));
	//$CancelDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelDate"]));
	//$CancelRemark=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelRemark"]));

		$ins="insert into \"Receipts\" (\"r_receipt\",\"r_date\",\"money_way\",\"money_type\",\"prndate\",\"cancel\",\"memo\",\"user_id\") values 
		('$RecNO','$RecDate','OC','CA','$PrintDate','false',NULL,'$RIDUser')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	
}
//รอบ 2 ใหญ่

$sql_fc1=pg_query("select r_receipt from \"Receipts\" "); 

//รอบแรก หา r_receipt
$tem_inv = null;
while($res_fc1 = pg_fetch_array($sql_fc1)){

	$r_receipt=trim($res_fc1["r_receipt"]);//radio_no
	
	$sql_fc=mssql_query("select InvNo from TacInvoice where RecNO = '$r_receipt' ",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){//รอบ 2 dtl


	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));
	
	if($tem_inv !=$InvNo){
	
	$ins="INSERT INTO \"ReceiptDtl\" (\"inv_no\",\"r_receipt\") values 
    ('$InvNo','$r_receipt')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
		
		$tem_inv = $InvNo;
}

		
}

		}
		
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

