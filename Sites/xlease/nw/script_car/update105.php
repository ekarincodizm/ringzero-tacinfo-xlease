<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

pg_query("BEGIN WORK");
$status = 0;


$update = "update carregis.\"CarTaxDue\" set \"CusAmt\" = '300' where \"TypeDep\"='105'";
if($result_up=pg_query($update)){
}else{
	$status += 1;
}	
		
if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";

}else{
	pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
