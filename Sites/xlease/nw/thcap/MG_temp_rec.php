<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("../../config/config.php"); 
pg_query("BEGIN WORK");
$status = 0;


$sql_fc=pg_query("select a.\"debtID\",b.\"typePayID\", b.\"typePayRefValue\", c.\"tpDesc\", c.\"tpFullDesc\" from thcap_v_receipt_otherpay a ,thcap_temp_otherpay_debt b ,account.\"thcap_typePay\" c
where a.\"debtID\"=b.\"debtID\"  and  b.\"typePayID\" = c.\"tpID\" order by a.\"debtID\" "); 
 

while($res_fc = pg_fetch_array($sql_fc)){
	$debtID =trim($res_fc["debtID"]);
	$typePayID =trim($res_fc["typePayID"]);
	$typePayRefValue =trim($res_fc["typePayRefValue"]);
	$tpDesc =trim($res_fc["tpDesc"]);
	$tpFullDesc =trim($res_fc["tpFullDesc"]);

		$ins="update \"thcap_temp_receipt_otherpay\" 
		SET \"typePayID\"='$typePayID', \"typePayRefValue\"='$typePayRefValue', \"tpDesc\"='$tpDesc', \"tpFullDesc\"='$tpFullDesc'
		
		where \"debtID\" = '$debtID'  ";
		//echo $ins."<br>";
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

