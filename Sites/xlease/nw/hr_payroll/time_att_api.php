<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$id = $_POST['id'];
$id_user = $_POST['id_user'];
$cmd =$_POST['cmd'];
pg_query("BEGIN WORK");
$status= 0;
					$datetime = $_REQUEST['datetime'];
					$year_now =substr($datetime,0,4);
					$date_ck =substr($datetime,0,10);
					$type_id = $_REQUEST['type_id'];
					$memo = $_REQUEST['memo'];
	
				$datetime_old = $_REQUEST['datetime_old'];
				$type_id_old = $_REQUEST['type_id_old'];
					$memo_old = $_REQUEST['memo_old'];
					
	if($memo=="")$memo_sql ="NULL";
	else $memo_sql = "'$memo'";	

if($cmd=="add"){//เพิ่ม
//ตรวจสอบว่าเคยมีช่วงเวลานี้อยู่แล้วหรือไม่
$qry_fr=pg_query("SELECT id FROM \"LogsTimeAtt$year_now\" where user_id='$id_user' and datetime::character varying LIKE '$date_ck%' and type_id = '$type_id' ");	
			$nub=pg_num_rows($qry_fr);

if($nub==0){
		$sqlaction = pg_query("INSERT INTO \"LogsTimeAtt$year_now\"(datetime, type_id, memo,s_p, user_id) 
		VALUES ('$datetime', '$type_id', $memo_sql,'1', '$id_user')");
				 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		

		//select id ref
		$qry_fr=pg_query("SELECT id FROM \"LogsTimeAtt$year_now\" where user_id='$id_user' and datetime = '$datetime' and type_id = '$type_id' ");	
			 while($sql_row4=pg_fetch_array($qry_fr)){
					$id = $sql_row4['id'];
			 }
		
		//ต้อง Insert ตาราง Approve
		$sqlaction = pg_query("INSERT INTO \"LogsTimeAttMgt\"(
            id_ref, type_id_old, datetime_old,id_user, transaction_type, 
            transaction_date, transaction_by, memo)
			VALUES ('$id', '$type_id', '$datetime', '$id_user', '1',
            '$datelog', '$user_id', $memo_sql)");


			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		}else{
	$status=$status+1;
	$error = "มีช่วงเวลานี้ ในวันที่ $date_ck แล้ว!!";
	
}
		
	
}
			else if($cmd=="edit"){//แก้ไข
			if($memo!="")$memo_sql =", memo='$memo'";	

$query =	"UPDATE \"LogsTimeAtt$year_now\"  SET datetime='$datetime', type_id='$type_id' $memo_sql , user_id='$id_user',s_p='1' where id ='$id' ";
$sql_query = pg_query($query);	

	 if($sql_query){	
		}else{
			$status=$status+1;
		}
if($memo=="")$memo_sql ="NULL";
	else $memo_sql = "'$memo'";	
$sqlaction = pg_query("INSERT INTO \"LogsTimeAttMgt\"(
            id_ref, type_id_old, datetime_old, memo_old, id_user, transaction_type, 
            transaction_date, transaction_by, memo)
			VALUES ('$id', '$type_id_old', '$datetime_old', '$memo_old', '$id_user', '2',
            '$datelog', '$user_id', $memo_sql)");

			
		 if($sqlaction){	
		}else{
			$status=$status+1;
		}
}
		
				else if($cmd=="del"){//ลบ
			

//$query =	"delete from \"hr_user_leave\"  SET deleted='1', delete_date='$datelog', delete_by='$user_id' where id ='$id' ";
//$sql_query = pg_query($query);		

$query =	"UPDATE \"LogsTimeAtt$year_now\"  SET s_p='1' where id ='$id' ";
$sql_query = pg_query($query);	

	 if($sql_query){	
		}else{
			$status=$status+1;
		}
		$sqlaction = pg_query("INSERT INTO \"LogsTimeAttMgt\"(
            id_ref, type_id_old, datetime_old, memo_old, id_user, transaction_type, 
            transaction_date, transaction_by)
			VALUES ('$id', '$type_id_old', '$datetime_old', '$memo_old', '$id_user', '3',
            '$datelog', '$user_id')");

			 if($sqlaction){	
		}else{
			$status=$status+1;
		}
		
	
		
		
		
}						
		

		
		
	 if($status == 0){   
	 			// เครียร์ค่า Approve อันเก่า ถ้ามี
		$query =	"UPDATE \"LogsTimeAttApprove\"  SET cancel = '1' where id_att ='$id' ";

		$sql_query = pg_query($query);	
		
			 if($sql_query){	
		}else{
			$status=$status+1;
		}
		pg_query("COMMIT");
		echo "0";
	}else{
		pg_query("ROLLBACK");
		echo $error;
	}
?>