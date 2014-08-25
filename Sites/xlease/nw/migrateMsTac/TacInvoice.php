<?php
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select InvNo,  InvType, CusID, RadioID,  NeedVAT, InvAmountExVAT, InvCancel, InvIDUser,
                     CONVERT(varchar(4), YEAR(InvDate)) + '-' + CONVERT(varchar(2), MONTH(InvDate)) 
                      + '-' + CONVERT(varchar(2), DAY(InvDate))  AS InvDate,CONVERT(varchar(4), YEAR(InvFixDate)) + '-' + CONVERT(varchar(2), MONTH(InvFixDate)) 
                      + '-' + CONVERT(varchar(2), DAY(InvFixDate))  AS InvFixDate from TacInvoice",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));
	$InvFixDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvFixDate"]));
	$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$CusID=trim($res_fc["CusID"]);
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioID"]));
	//$Description=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Description"]));
	$InvDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvDate"]));
	//$PriceUnit=trim($res_fc["PriceUnit"]);
	//$NumUnit=trim($res_fc["NumUnit"]);
	//$RecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecNO"]));
	//$VatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatNO"]));
	$NeedVAT=trim($res_fc["NeedVAT"]);
	//$Discount=trim($res_fc["Discount"]);
	$InvAmountExVAT=trim($res_fc["InvAmountExVAT"]);
	$InvCancel=trim($res_fc["InvCancel"]);
	//$RInvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RInvNO"]));
	$InvIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvIDUser"]));
	//$CancelInvDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelInvDate"]));
	//$CancelIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CancelIDUser"]));
	//$OldInvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["OldInvNO"]));
	//$BadDebt=trim($res_fc["BadDebt"]);
	
	/*$query = pg_query("select * from taxiacc.\"TacInvoice\" where \"InvNo\" = '$InvNo' and \"InvFixDate\" = '$InvFixDate' and \"InvType\" = '$InvType'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/
	/*	$ins="INSERT INTO \"Invoices\" (\"inv_no\",\"cus_id\",\"idno\",\"inv_date\",\"prn_date\",\"branch_out\",\"status\",\"user_id\") values 
    ('$InvNo','$CusID','$idno','$InvDate','$nowdate','$_SESSION[ta_officeid]','OCCR','$_SESSION[ta_iduser]')";
	
	$in_qry="INSERT INTO \"InvoiceDetails\" (\"inv_no\",\"service_id\",\"due_date\",\"amount\",\"vat\") values 
        ('$teminvoice','$service_id','$contr_start_date','$first_due_price','$vat')";*/
		
				
		
		
		
		$query = pg_query("select inv_no from \"Invoices\" where \"inv_no\" = '$InvNo' ");//เช็คว่าซ้ำหรือไม่
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		
		$query2 = pg_query("select cus_id from \"Contracts\" where \"idno\" = '$CusID' "); //ดึงค่า Radio IDใน PG

				if($res_r2 = pg_fetch_array($query2)){
				$cus_id=trim($res_r2["cus_id"]);
				//echo "Rad_ID = ".$Rad_ID ;
				}
		
		
	$ins="INSERT INTO \"Invoices\" (\"inv_no\",\"idno\",\"cus_id\",\"inv_date\",\"cancel\",\"user_id\") values 
    ('$InvNo','$CusID','$cus_id','$InvDate','$InvCancel','$InvIDUser')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	
	}
	//$query = pg_query("select * from \"InvoiceDetails\" where \"inv_no\" = '$InvNo' ");
	//$num_row = pg_num_rows($query);
	//if($num_row == 0){
	
		if($NeedVAT){//เช็คvat
			$VAT = $InvAmountExVAT*7/100;
		}else{
			$VAT = 0;
			
		}



		if($InvType=="REN") $service_id="S001";
		else if($InvType=="REC") $service_id="S004";
		else if($InvType=="REG") $service_id="S005";
		else if($InvType=="TRR") $service_id="S006";
		else if($InvType=="TRF") $service_id="S007";
		else if($InvType=="FAR") $service_id="S008";

		else if($InvType=="SAD") $service_id="P001";
		else if($InvType=="SAG") $service_id="P002";
		else if($InvType=="SAR") $service_id="P003";
		else if($InvType=="SCT") $service_id="P004";
		else if($InvType=="SDT") $service_id="P005";
		else if($InvType=="SAA") $service_id="P007";
		
		else if($InvType=="OTR") $service_id="S101";
		else if($InvType=="OTV") $service_id="S102";
		else $service_id="";

/*
		    $qry = pg_query("SELECT * FROM \"ProductService\" where constant_var = '$InvType' ");
    if( $res = pg_fetch_array($qry) ){
        $service_id = $res['service_id'];
       // $name = $res['name'];
    }
		
	*/
	$ins2="INSERT INTO \"InvoiceDetails\" (\"inv_no\",\"service_id\",\"due_date\",\"amount\",\"vat\",\"cancel\") values 
        ('$InvNo','$service_id','$InvFixDate','$InvAmountExVAT','$VAT','$InvCancel')";
		
	
		if($res_inss2=pg_query($ins2)){	
		}else{
			$status=$status+1;
			echo $ins2;
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

