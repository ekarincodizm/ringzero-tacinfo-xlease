<?php
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select *,CONVERT(varchar(4), YEAR(InvDate)) + '-' + CONVERT(varchar(2), MONTH(InvDate)) 
                      + '-' + CONVERT(varchar(2), DAY(InvDate))  AS InvDate from TacInvoice",$conn);
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
	
	/*$query = pg_query("select * from taxiacc.\"TacInvoice\" where \"InvNo\" = '$InvNo' and \"InvFixDate\" = '$InvFixDate' and \"InvType\" = '$InvType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/
	/*	$ins="INSERT INTO \"Invoices\" (\"inv_no\",\"cus_id\",\"idno\",\"inv_date\",\"prn_date\",\"branch_out\",\"status\",\"user_id\") values 
    ('$InvNo','$CusID','$idno','$InvDate','$nowdate','$_SESSION[ta_officeid]','OCCR','$_SESSION[ta_iduser]')";
	
	$in_qry="INSERT INTO \"InvoiceDetails\" (\"inv_no\",\"service_id\",\"due_date\",\"amount\",\"vat\") values 
        ('$teminvoice','$service_id','$contr_start_date','$first_due_price','$vat')";*/
		
	$ins="INSERT INTO \"Invoices2\" (\"inv_no\",\"cus_id\",\"inv_date\") values 
    ('$InvNo','$CusID','$InvDate')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	$ins="INSERT INTO \"InvoiceDetails2\" (\"inv_no\",\"amount\") values 
        ('$InvNo','$PriceUnit')";
		
	
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	}
//}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

