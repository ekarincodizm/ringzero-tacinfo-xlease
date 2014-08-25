<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");
include("include/function.php");
//pg_query("BEGIN WORK");
$status = 0;
include("config/config2.php"); //xlease
//$conn_string = "host=172.16.2.251  port=5432 dbname=devxleasenw12 user=dev	password=nextstep";
	//$db_connect = pg_connect($conn_string) or die("can't connect");
$sql_fc=pg_query("select * from \"tacReceiveTemp\" "); 

//รอบแรก
while($res_fc = pg_fetch_array($sql_fc)){

	$tacID=trim($res_fc["tacID"]);//radio_no
	$tacXlsRecID=trim($res_fc["tacXlsRecID"]);
	$tacMoney=trim($res_fc["tacMoney"]);
	$tacMonth=trim($res_fc["tacMonth"]);
	$tacOldRecID=trim($res_fc["tacOldRecID"]);
	$tacTempDate=trim($res_fc["tacTempDate"]);	
	$makerID=trim($res_fc["makerID"]);
	$makerStamp=trim($res_fc["makerStamp"]);



include("config/config.php"); //tac
//$conn_string = "host=172.16.2.251  port=5432 dbname=devtac_app user=tac	password=@3nextstep";
	//$db_connect = pg_connect($conn_string) or die("can't connect");
	
		$sql_fc1=pg_query("select cus_id from \"Contracts\" c where c.\"idno\" ='$tacID'"); 
if($res_fc1 = pg_fetch_array($sql_fc1)){
	$tem_id =trim($res_fc1["cus_id"]);
	
		}
	
	$temp_inv_n=pg_query("select gen_rec_no('$tacTempDate',9)");
            $temp_inv_no=@pg_fetch_result($temp_inv_n,0);
$ins="INSERT INTO \"Invoices\" (\"inv_no\",\"idno\",\"cus_id\",\"inv_date\",prn_date,branch_out ,status ,\"cancel\",\"user_id\",post_id ,type_rec ) values 
    ('$temp_inv_no','$tacID','$tem_id','$tacTempDate','$tacTempDate','1','OCCR','false','$makerID',NULL,'E')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	
	$ins2="INSERT INTO \"InvoiceDetails\" (\"inv_no\",\"service_id\",\"due_date\",\"amount\",\"vat\",unit ,\"cancel\") values 
        ('$temp_inv_no','S009','$tacMonth','$tacMoney','0','1','false')";
		
	
		if($res_inss2=pg_query($ins2)){	
		}else{
			$status=$status+1;
			echo $ins2;
		}
		/*
		if($tacXlsRecID!=$tacXlsRecID_old){
		$ins="insert into \"Receipts\" (\"r_receipt\",\"r_date\",\"money_way\",\"money_type\",\"prndate\",\"cancel\",\"memo\",\"user_id\") values 
		('$tacXlsRecID','$tacTempDate','OC','CA','$tacTempDate','false',NULL,'$makerID')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
		
		$tacXlsRecID_old=$tacXlsRecID;
		}
*/
				
					$ins="INSERT INTO \"ReceiptDtl\" (\"inv_no\",\"r_receipt\") values 
    ('$temp_inv_no','$tacXlsRecID')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
		
		include("config/config2.php"); //xlease

}// end while
	/*$sql_fc=pg_query("select cus_id from \"Contracts\" c where c.\"idno\" ='$idno'"); 
while($res_fc = pg_fetch_array($sql_fc)){
	$tem_id =trim($res_fc["idno"]);
	


		$ins="update \"Invoices\" set cus_id = (select cus_id from \"Contracts\" c where c.\"idno\" ='$idno') ";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}
	
}*/
//}

$sql_fc=pg_query("select * from \"tacReceiveTemp\" "); 

//รอบแรก
while($res_fc = pg_fetch_array($sql_fc)){

	$tacID=trim($res_fc["tacID"]);//radio_no
	$tacXlsRecID=trim($res_fc["tacXlsRecID"]);
	$tacMoney=trim($res_fc["tacMoney"]);
	$tacMonth=trim($res_fc["tacMonth"]);
	$tacOldRecID=trim($res_fc["tacOldRecID"]);
	$tacTempDate=trim($res_fc["tacTempDate"]);	
	$makerID=trim($res_fc["makerID"]);
	$makerStamp=trim($res_fc["makerStamp"]);
	
}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

