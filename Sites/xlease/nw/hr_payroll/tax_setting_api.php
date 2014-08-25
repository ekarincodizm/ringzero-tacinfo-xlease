<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$set_tax_id =$_POST['set_tax_id'];
 $arradd = json_decode(stripcslashes($_POST["arradd"]));
$cmd =$_POST['cmd'];
pg_query("BEGIN WORK");
$status= 0;



if($cmd=="add" || $cmd=="edit"){
	
	if($cmd=="add"){
		$id_log = 1 ;
	}else $id_log = 2 ;

    $qry = "delete from \"hr_payroll_tax\" where id = '$set_tax_id' ";
        if(!$res=@pg_query($qry)){
            $txt_error[] = "Delete hr_payroll_tax ไม่สำเร็จ $qry";
            $status++;
        }
		
foreach($arradd as $key => $value){
        $tax_begin = $value->tax_begin;
        $tax_end = $value->tax_end;
        $tax_rate = $value->tax_rate;
        $tax_percent = $value->tax_percent;
        $tax_step = $value->tax_step;
		$tax_max = $value->tax_max;

      //  if(empty($tax_begin) or empty($tax_end) or empty($tax_rate) or empty($tax_percent) or empty($tax_step) or empty($tax_max)){
			
           // continue;
      //  }

		    $qry = "INSERT INTO \"hr_payroll_tax\" (tax_begin,tax_end,tax_rate,tax_percent,tax_step,tax_max,id) VALUES ('$tax_begin','$tax_end','$tax_rate','$tax_percent','$tax_step','$tax_max','$set_tax_id')";
        if(!$res=@pg_query($qry)){
            $txt_error[] = "INSERT hr_payroll_tax ไม่สำเร็จ $qry";
            $status++;
        }
    }
			$sqlaction = pg_query("INSERT INTO \"hr_log\"(
            id_ref, column_ref, table_ref, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$set_tax_id', 'id', 'hr_payroll_tax', '$id_log',
            '$datelog', '$user_id')");

			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		

					
}


		
		
				else if($cmd=="del"){//ลบ


 $query = "delete from \"hr_payroll_tax\" where id = '$set_tax_id' ";
$sql_query = pg_query($query);	

	 if($sql_query){	
		}else{
			$status=$status+1;
		}
		$sqlaction = pg_query("INSERT INTO \"hr_log\"(
            id_ref, column_ref, table_ref, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$set_tax_id', 'id', 'hr_payroll_tax', '3',
            '$datelog', '$user_id')");

			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		
	
		
		
		
}						
		

	 if($status == 0){   
		pg_query("COMMIT");
	
		 $data['success'] = true;
       
	}else{
		pg_query("ROLLBACK");
		$data['success'] = false;
        $data['message'] = "ไม่สามารถบันทึกได้! $txt_error[0]";
	}
	echo json_encode($data);
?>