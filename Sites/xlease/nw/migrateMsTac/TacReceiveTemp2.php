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
$sql_fc=pg_query("select distinct(\"tacXlsRecID\"),\"tacTempDate\" from \"tacReceiveTemp\" "); 

//รอบแรก
while($res_fc = pg_fetch_array($sql_fc)){


	$tacXlsRecID=trim($res_fc["tacXlsRecID"]);

	$tacTempDate=trim($res_fc["tacTempDate"]);	
	
	
	

$sql_fc2=pg_query("select \"makerID\" from \"tacReceiveTemp\" where \"tacXlsRecID\" ='$tacXlsRecID' and \"tacTempDate\"='$tacTempDate' "); 

//รอบแรก
if($res_fc2 = pg_fetch_array($sql_fc2)){
$makerID=trim($res_fc2["makerID"]);
}

include("config/config.php"); //tac
//$conn_string = "host=172.16.2.251  port=5432 dbname=devtac_app user=tac	password=@3nextstep";
	//$db_connect = pg_connect($conn_string) or die("can't connect");
	


		$ins="insert into \"Receipts\" (\"r_receipt\",\"r_date\",\"money_way\",\"money_type\",\"prndate\",\"cancel\",\"memo\",\"user_id\") values 
		('$tacXlsRecID','$tacTempDate','OC','CA','$tacTempDate','false',NULL,'$makerID')";
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

if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

