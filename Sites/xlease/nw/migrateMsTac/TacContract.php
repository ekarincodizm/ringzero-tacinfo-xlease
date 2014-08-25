<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");
include("include/function.php");
//pg_query("BEGIN WORK");
$status = 0;


	
		$sql_fc1=pg_query("select idno from \"Contracts\" "); 
while($res_fc1 = pg_fetch_array($sql_fc1)){
	$tem_idno =trim($res_fc1["idno"]);
	
	
$sql_fc=pg_query("select serial_no,ref1 from \"StockProduct\" where contract_id = '$tem_idno' "); 
while($res_fc = pg_fetch_array($sql_fc)){
	$serial_no =trim($res_fc["serial_no"]);
	$ref1 =trim($res_fc["ref1"]);

		$ins="update \"Contracts\" set radio_serial = '$serial_no',radio_no = '$ref1' where idno = '$tem_idno'  ";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}
		//$serial_no=null;
		//$ref1 = null;
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

