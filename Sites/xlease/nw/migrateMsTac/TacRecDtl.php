<?php
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select DISTINCT InvNo,RecNO from TacInvoice where InvCancel='False' and RecNO is not null AND RecNO != '' ",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));

	$RecNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RecNO"]));

	
/*$query = pg_query("select inv_no from \"ReceiptDtl\" where \"r_receipt\" = '$RecNO' AND \"inv_no\" = '$InvNo' ");//เช็คว่าซ้ำหรือไม่
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/
		
	$ins="INSERT INTO \"ReceiptDtl\" (\"inv_no\",\"r_receipt\") values 
    ('$InvNo','$RecNO')";
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

