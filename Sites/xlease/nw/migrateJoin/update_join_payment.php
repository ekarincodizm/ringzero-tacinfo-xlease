<?php
$excel = $_REQUEST[excel];

set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$num_add = 0;
?>
<?php if($excel==1)header("Content-Type: application/vnd.ms-excel");
if($excel==1)header('Content-Disposition: attachment; filename="join_main_ck.xls"'); ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />

</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>


<br>

    <fieldset><legend>
    <h3> Update PayType ใน ta_join_payment , ta_join_payment_bin</h3>
    </legend>   

<?php		

$num_add=0;
$query = "SELECT pay_type,ta_join_payment_id,contract_id,amount,deleted FROM $mysql_db_select.ta_join_payment ";
		
				$sql_query = mysql_query($query);
while($sql_row = mysql_fetch_array($sql_query))
				{			
					
				
				//$car_license = trim($sql_row['car_license']);
				$amount = $sql_row['amount'];
				$deleted = $sql_row['deleted'];
				$ta_join_payment_id = $sql_row['ta_join_payment_id'];
				$contract_id = trim($sql_row['contract_id']);
				$pay_type = $sql_row['pay_type'];
					
					//update
					$ins="update \"ta_join_payment\" set pay_type = '$pay_type' where ta_join_payment_id='$ta_join_payment_id' and idno='$contract_id' and amount='$amount' and deleted= '$deleted' ";
		
						if($res_inss=pg_query($ins)){	
					$num_add++;	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}
					
					
				}


if($status == 0){
   pg_query("COMMIT");
   echo "<br>ปรับปรุงข้อมูลจำนวน $num_add Record เรียบร้อยแล้ว<br>";
}else{
  pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ ";
}


$num_add=0;
$query = "SELECT pay_type,car_license,ta_join_payment_id,contract_id,amount,deleted FROM $mysql_db_select.ta_join_payment_bin ";
		
				$sql_query = mysql_query($query);
while($sql_row = mysql_fetch_array($sql_query))
				{			
					$deleted = $sql_row['deleted'];
				$amount = $sql_row['amount'];
				$car_license = trim($sql_row['car_license']);
				$ta_join_payment_id = $sql_row['ta_join_payment_id'];
				$contract_id = trim($sql_row['contract_id']);
				$pay_type = $sql_row['pay_type'];
					
					//update
					$ins="update \"ta_join_payment_bin\" set pay_type = '$pay_type' where ta_join_payment_id='$ta_join_payment_id' and  car_license='$car_license' and idno='$contract_id' and amount='$amount' and deleted= '$deleted'  ";
		
						if($res_inss=pg_query($ins)){	
					$num_add++;	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}
					
					
				}


if($status == 0){
   pg_query("COMMIT");
   echo "<br>Bin ปรับปรุงข้อมูลจำนวน $num_add Record เรียบร้อยแล้ว<br>";
}else{
  pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ ";
}
?>
</fieldset>

</BODY>

</HTML>

