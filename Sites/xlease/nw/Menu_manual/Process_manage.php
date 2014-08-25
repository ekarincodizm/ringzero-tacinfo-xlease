<?php
session_start();
include('../../config/config.php');

$user_id = $_SESSION["av_iduser"];
$recmenu = $_POST['recmenu'];
$nameheader = $_POST['nameheader'];
$detail = $_POST['editor1'];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$chkalert = $_POST['chkalert'];

$typecheck = $_POST['hdtype'];
$status = 0;

if($chkalert == "1"){
	$alertshow = 'TRUE';
}else{
	$alertshow = 'FALSE';
}

//$detail = str_replacein($detail1);
pg_query("BEGIN");

if($typecheck == 'insert'){


	$sql = "INSERT INTO f_menu_manual(id_menu, recheader,id_user, revision_num, recdetail, rec_date, appstatus,show_login) VALUES ('$recmenu','$nameheader','$user_id', '0', '$detail', '$datenow', '0',$alertshow)";
	if($sqlre = pg_query($sql)){}else{$status++;}	

}else if($typecheck == 'edit'){
	$recmenuid = $_POST['hdrecidmenu'];
		$sqlchk = pg_query("SELECT * FROM f_menu_manual where recmenuid = '$recmenuid' and appstatus = '0' ");
		$rowshk = pg_num_rows($sqlchk);
	if($rowshk > 0){
		
		$sql = "UPDATE f_menu_manual
							SET  id_menu='$recmenu', id_user='$user_id', revision_num=revision_num+1, recdetail='$detail', 
							rec_date='$datenow', recheader='$nameheader', show_login=$alertshow
							WHERE recmenuid='$recmenuid'";
		if($sqlre = pg_query($sql)){}else{$status++;}
		
	}else{
		$sql = "INSERT INTO f_menu_manual(id_menu, recheader,id_user, revision_num, recdetail, rec_date, appstatus,show_login) 
		VALUES ('$recmenu','$nameheader','$user_id', '0', '$detail', '$datenow', '0',$alertshow)";
		if($sqlre = pg_query($sql)){}else{$status++;}	
	}


}


if($status == 0){
	pg_query('COMMIT');
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=Manage_rec.php\">";
	echo "<script type='text/javascript'>alert('Save done')</script>";
	
}else{
	pg_query('ROLLBACK');
	echo "<script type='text/javascript'>alert('Error')</script>";
	echo "$sql<p>";
	echo "<center>กรุณาแจ้งผู้ดูแลระบบ</center>";

}	
?>
