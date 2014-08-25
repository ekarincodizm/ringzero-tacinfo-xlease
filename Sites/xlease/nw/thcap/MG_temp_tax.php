<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("../../config/config.php"); 
pg_query("BEGIN WORK");
$status = 0;

	
$sql_fc=pg_query("select \"debtID\",\"typePayID\", \"typePayRefValue\", \"tpDesc\", \"tpFullDesc\" from \"thcap_v_receipt_otherpay\" "); 
while($res_fc = pg_fetch_array($sql_fc)){
	$debtID =trim($res_fc["debtID"]);
	$typePayID =trim($res_fc["typePayID"]);
	$typePayRefValue =trim($res_fc["typePayRefValue"]);
	$tpDesc =trim($res_fc["tpDesc"]);
	$tpFullDesc =trim($res_fc["tpFullDesc"]);

		$ins="update \"thcap_temp_taxinvoice_otherpay\" 
		SET \"typePayID\"='$typePayID', \"typePayRefValue\"='$typePayRefValue', \"tpDesc\"='$tpDesc', \"tpFullDesc\"='$tpFullDesc'
		
		where \"debtID\" = '$debtID'  ";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}

}

	

	
//}
if($status == 0){
 pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

