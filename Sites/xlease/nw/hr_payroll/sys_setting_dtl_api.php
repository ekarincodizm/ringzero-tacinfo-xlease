<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$set_group_id = $_REQUEST['set_group_id'];
$cmd =$_POST['cmd'];
pg_query("BEGIN WORK");
$status= 0;
					$current_social_rate = $_REQUEST['current_social_rate'];
					$tax_exp_deduct_percent = $_REQUEST['tax_exp_deduct_percent'];
					$tax_exp_deduct_max = $_REQUEST['tax_exp_deduct_max'];
	
				    $tax_private_deductible = $_REQUEST['tax_private_deductible'];
				    $maternity_leave_salary = $_REQUEST['maternity_leave_salary'];
			
					

if($cmd=="add"){


		$sqlaction = pg_query("INSERT INTO \"hr_payroll_setting\"(set_name, set_value, set_seq,set_group_id) 
		VALUES ('current_social_rate', '$current_social_rate', '1', '$set_group_id')");
		
		$sqlaction = pg_query("INSERT INTO \"hr_payroll_setting\"(set_name, set_value, set_seq,set_group_id) 
		VALUES ('tax_exp_deduct_percent', '$tax_exp_deduct_percent', '2', '$set_group_id')");
		
		$sqlaction = pg_query("INSERT INTO \"hr_payroll_setting\"(set_name, set_value, set_seq,set_group_id) 
		VALUES ('tax_exp_deduct_max', '$tax_exp_deduct_max', '3', '$set_group_id')");
		
		$sqlaction = pg_query("INSERT INTO \"hr_payroll_setting\"(set_name, set_value, set_seq,set_group_id) 
		VALUES ('tax_private_deductible', '$tax_private_deductible', '4', '$set_group_id')");
		
		$sqlaction = pg_query("INSERT INTO \"hr_payroll_setting\"(set_name, set_value, set_seq,set_group_id) 
		VALUES ('maternity_leave_salary', '$maternity_leave_salary', '5', '$set_group_id')");
		
				 if($sqlaction){	
		}else{
			$status=$status+1;
		}

		

		$sqlaction = pg_query("INSERT INTO \"hr_log\"(
            id_ref, column_ref, table_ref, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$set_group_id', 'set_group_id', 'hr_payroll_setting', '1',
            '$datelog', '$user_id')");


			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		
	
}
			else if($cmd=="edit"){
			

$query =	"UPDATE \"hr_payroll_setting\"  SET set_value='$current_social_rate' where set_name ='current_social_rate' and set_group_id= '$set_group_id'  ";
$sql_query = pg_query($query);	

$query =	"UPDATE \"hr_payroll_setting\"  SET set_value='$tax_exp_deduct_percent' where set_name ='tax_exp_deduct_percent' and set_group_id= '$set_group_id'  ";
$sql_query = pg_query($query);	

$query =	"UPDATE \"hr_payroll_setting\"  SET set_value='$tax_exp_deduct_max' where set_name ='tax_exp_deduct_max' and set_group_id= '$set_group_id'  ";
$sql_query = pg_query($query);	

$query =	"UPDATE \"hr_payroll_setting\"  SET set_value='$tax_private_deductible' where set_name ='tax_private_deductible' and set_group_id= '$set_group_id'  ";
$sql_query = pg_query($query);	

$query =	"UPDATE \"hr_payroll_setting\"  SET set_value='$maternity_leave_salary' where set_name ='maternity_leave_salary' and set_group_id= '$set_group_id'  ";
$sql_query = pg_query($query);	

	 if($sql_query){	
		}else{
			$status=$status+1;
		}

$sqlaction = pg_query("INSERT INTO \"hr_log\"(
            id_ref, column_ref, table_ref, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$set_group_id', 'set_group_id', 'hr_payroll_setting', '2',
            '$datelog', '$user_id')");

			
		 if($sqlaction){	
		}else{
			$status=$status+1;
		}
}
		
				else if($cmd=="del"){//ลบ


$query =	"delete from \"hr_payroll_setting\" where set_group_id ='$set_group_id' ";
$sql_query = pg_query($query);	

	 if($sql_query){	
		}else{
			$status=$status+1;
		}
		$sqlaction = pg_query("INSERT INTO \"hr_log\"(
            id_ref, column_ref, table_ref, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$set_group_id', 'set_group_id', 'hr_payroll_setting', '3',
            '$datelog', '$user_id')");

			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		
	
		
		
		
}						
		

	 if($status == 0){   
		pg_query("COMMIT");
		echo "0";
	}else{
		pg_query("ROLLBACK");
		echo $status;
	}
?>