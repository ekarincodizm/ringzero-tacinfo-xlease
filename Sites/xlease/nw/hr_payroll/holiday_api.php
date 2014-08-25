<?php
session_start();

include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$id = $_POST['id'];
$cmd =$_POST['cmd'];


					$pub_holiday = $_REQUEST['pub_holiday'];
					$desc = $_REQUEST['desc'];
					
if($cmd=="add"){//เพิ่ม
$qry_fr=pg_query("SELECT pub_holiday FROM \"hr_public_holiday\" where pub_holiday='$pub_holiday' ");	
			$nub=pg_num_rows($qry_fr);

if($nub==0){

		$sqlaction = pg_query("INSERT INTO \"hr_public_holiday\"(pub_holiday, \"desc\",create_date, create_by) 
		VALUES ('$pub_holiday', '$desc', '$datelog', '$user_id')");
		}else{
	$status=$status+1;
	$error = "มีวันที่ี้ $pub_holiday แล้ว!!";
	
}
	
}
			else if($cmd=="edit"){//แก้ไข
			

$query =	"UPDATE \"hr_public_holiday\"  SET pub_holiday='$pub_holiday', \"desc\"='$desc', mo_date='$datelog', mo_by='$user_id' where pub_id ='$id' ";

		$sql_query = pg_query($query);		
}
		
				else if($cmd=="del"){//ลบ
			

$query =	"delete from \"hr_public_holiday\"  where pub_id ='$id' ";


		$sql_query = pg_query($query);		
}						
		
	     echo $error;
?>