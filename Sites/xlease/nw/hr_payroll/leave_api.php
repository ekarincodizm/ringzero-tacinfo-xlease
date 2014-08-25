<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$id = $_POST['id'];
$id_user = $_POST['id_user'];
$cmd =$_POST['cmd'];


					$leave_date = $_REQUEST['leave_date'];
					$leave_type = $_REQUEST['leave_type'];
					$memo = $_REQUEST['memo'];
					$leave_time_type = $_REQUEST['leave_time_type'];
					$time1 =$_REQUEST['time1']; 
					$time2 = $_REQUEST['time2'];
					$h_amt =$_REQUEST['h_amt']; 
				
					
	if($memo=="")$memo_sql ="NULL";
	else $memo_sql = "'$memo'";	

if($cmd=="add"){//เพิ่มวันลา

	if($leave_time_type=='1'){	//ลาแบบ 1 วัน
		$sqlaction = pg_query("INSERT INTO \"hr_user_leave\"(leave_date, leave_type, memo,leave_time_type, id_user, create_date, create_by) 
		VALUES ('$leave_date', '$leave_type', $memo_sql,'$leave_time_type', '$id_user', '$datelog', '$user_id')");
		
		}
		else if($leave_time_type=='0'){	//ลาแบบเป็นชั่วโมง
		$sqlaction = pg_query("INSERT INTO \"hr_user_leave\"(leave_date, leave_type, memo,leave_time_type,time1,time2,h_amt, id_user, create_date, create_by) 
		VALUES ('$leave_date', '$leave_type', $memo_sql,'$leave_time_type','$time1','$time2','$h_amt', '$id_user', '$datelog', '$user_id')");
		
		}
}
			else if($cmd=="edit"){//แก้ไขวันลา
			
if($leave_time_type=='1'){	//ลาแบบ 1 วัน
$query =	"UPDATE \"hr_user_leave\"  SET leave_date='$leave_date', leave_type='$leave_type', memo='$memo', leave_time_type='$leave_time_type', 
       mo_date='$datelog', mo_by='$user_id' where id ='$id' ";
}	else if($leave_time_type=='0'){	//ลาแบบเป็นชั่วโมง
$query =	"UPDATE \"hr_user_leave\"  SET leave_date='$leave_date', leave_type='$leave_type', memo='$memo', leave_time_type='$leave_time_type', 
       time1='$time1', time2='$time2', h_amt='$h_amt',  mo_date='$datelog', mo_by='$user_id' where id ='$id' ";

}

		$sql_query = pg_query($query);		
}
		
				else if($cmd=="del"){//ลบ
			

$query =	"UPDATE \"hr_user_leave\"  SET deleted='1', delete_date='$datelog', delete_by='$user_id' where id ='$id' ";


		$sql_query = pg_query($query);		
}						
		
	     
?>