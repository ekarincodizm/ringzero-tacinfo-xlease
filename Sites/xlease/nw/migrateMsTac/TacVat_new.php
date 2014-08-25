<?php
set_time_limit (0); 
ini_set("memory_limit","1024M"); 
//error_reporting(0); 
include("config/config.php");
echo "<table border=1><tr><td>ลำดับ</td><td>v_receipt</td></tr>";
//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select VatNO, VIDUser, InvNO,
CONVERT(varchar(4), YEAR(VatDate)) + '-' + CONVERT(varchar(2), MONTH(VatDate)) + '-' + CONVERT(varchar(2), DAY(VatDate))  AS VatDate,
CONVERT(varchar(4), YEAR(VPrintDate)) + '-' + CONVERT(varchar(2), MONTH(VPrintDate)) + '-' + CONVERT(varchar(2), DAY(VPrintDate))  AS VPrintDate
 from TacVat where VCancel = 'false' order by VatNO ",$conn); 
 
 $tem_vat = null;
$tem_inv =null;

while($res_fc = mssql_fetch_array($sql_fc)){
	$VatNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatNO"]));
	//$VatFixDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatFixDate"]));
	//$InvType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvType"]));
	$InvNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNO"]));
	//$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$VatDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatDate"]));
	//$VatValue=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VatValue"]));
	$VIDUser=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VIDUser"]));
	$VPrintDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VPrintDate"]));
	//$VCancel=trim($res_fc["VCancel"]);
	//$VCancelUserID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VCancelUserID"]));
	//$VCancelDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["VCancelDate"]));
	
		
/*$query = pg_query("select v_receipt from \"Vats\" where \"v_receipt\" = '$VatNO' ");
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/

if($tem_vat!=$VatNO){
		$ins="insert into \"Vats\" (\"v_receipt\",\"v_date\",\"prndate\",\"cancel\",memo,\"user_id\") values 
		('$VatNO','$VatDate','$VPrintDate','false',NULL,'$VIDUser')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			//echo $ins;
			echo "<tr><td>$status</td><td>$VatNO &nbsp;</td></tr>";
		}
		$tem_vat=$VatNO;
		
		
		
		$ins="INSERT INTO \"VatDtl\" (\"inv_no\",\"v_receipt\") values 
    ('$InvNO','$VatNO')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
		$tem_inv=$InvNO;
		
}
else{
	
	if($tem_inv!=$InvNO){
	$ins="INSERT INTO \"VatDtl\" (\"inv_no\",\"v_receipt\") values 
    ('$InvNO','$VatNO')";
	if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
		$tem_inv=$InvNO;
	
	}
	}
		//}
		
		
	
}

		echo "</table>";
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
  //  pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

