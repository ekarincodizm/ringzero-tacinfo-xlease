<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$id = $_POST['id'];
$st =$_POST[st];
$memo =$_POST['memo'];

/*
$sql_query=pg_query("select count(id_att) WHERE id_att = '$id' ");
$nub=pg_num_rows($sql_query); 
	if($nub>0)*/
	if($memo=="")$memo_sql ="NULL";
	else $memo_sql = "'$memo'";	

	if($st=='0'){	
		$sqlaction = pg_query("INSERT INTO \"LogsTimeAttApprove\"(id_att, approved1, approver1_id,app1_dt,memo) VALUES ('$id','t','$user_id','$datelog' ,$memo_sql)");
		
		}
		
			else if($st=='1' ||$st=='2' )	{ //แก้ไข

$query =	"UPDATE \"LogsTimeAttApprove\"  SET ";
					
					if($st=='1'){
					$query .=	"approved2 = 't' ,approver2_id='$user_id',app2_dt  = '$datelog'";
					}else if($st=='2'){
					$query .=	"non_app = 't' ,non_approver_id='$user_id',non_app_dt  = '$datelog'";
						
					}
					if($memo!="")$query .=	",memo = '$memo' ";
					
					$query .=	" where id_att ='$id' ";
	
		$sql_query = pg_query($query);		
}
		
							else if($st=='4')	{ //แก้ไข

$sqlaction = pg_query("INSERT INTO \"LogsTimeAttApprove\"(id_att, approved1, approver1_id,app1_dt, approved2, approver2_id,app2_dt,memo) VALUES ('$id','t','$user_id','$datelog','t','$user_id','$datelog',$memo_sql)");


}
					else if($st=='5')	{ //แก้ไข

$query =	"UPDATE \"LogsTimeAttApprove\"  SET approved2 = 't' ,approver2_id='$user_id',app2_dt  = '$datelog'  ";
if($memo!="")$query .=	",memo = '$memo' ";

			$query .=	" where id_att ='$id' ";

		$sql_query = pg_query($query);		
}else if($st=='6'){	//ไม่อนุมัติ ครั้งที่ 1
		$sqlaction = pg_query("INSERT INTO \"LogsTimeAttApprove\"(id_att, non_app, non_approver_id,non_app_dt,memo) VALUES ('$id','t','$user_id','$datelog' ,$memo_sql)");
		
		}
		
		
	     
?>