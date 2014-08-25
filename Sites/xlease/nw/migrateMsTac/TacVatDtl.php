<?php
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select DISTINCT InvNo,VatNO from TacInvoice where InvCancel='False' and VatNO is not null  AND VatNO != '' ",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));

	$VatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatNO"]));


/*$query = pg_query("select inv_no from \"VatDtl\" where \"v_receipt\" = '$VatNO' AND \"inv_no\" = '$InvNo' ");//เช็คว่าซ้ำหรือไม่
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/
		
	$ins="INSERT INTO \"VatDtl\" (\"inv_no\",\"v_receipt\") values 
    ('$InvNo','$VatNO')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	//}
	
	
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

