<?php
session_start();
include("../../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$iduser = $_SESSION["av_iduser"];
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id = $_POST['changeiden'];
$status = 0;

if($id == "" || $id == null){

	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
	echo "<script type='text/javascript'>alert('กรุณาเลือก รายการที่จะปฎิเสธการอนุมัติ !')</script>";
	
}else{
pg_query('BEGIN');
	for($i=0;$i<sizeof($id);$i++){
	
		$sql1 = "UPDATE \"Re_indentity_cus_temp\" SET app_status='3', app_user='$iduser', app_date='$date' WHERE reiden_pk = '$id[$i]'";		
		if($query1 = pg_query($sql1)){}else{ $status++; }
		//update ข้อมูลใน Customer_Temp ว่าได้ approve แล้ว  โดยให้มีการตรวจสอนก่อนว่า ได้ approve แล้ว หรือยัง
			$sqlcheck_update = pg_query("SELECT \"CusID\",\"id_user\",\"date\",\"identity_new\" FROM \"Re_indentity_cus_temp\" where \"reiden_pk\" ='$id[$i]'");
			$num_update=pg_num_rows($sqlcheck_update);
			$re_check_update = pg_fetch_array($sqlcheck_update);
			$custempid=$re_check_update["CusID"];
			$id_user=$re_check_update["id_user"];
			$cdate=$re_check_update["date"];
			$cidentity_new=$re_check_update["identity_new"];
			$show=0;
			if($num_update==0){$show=1;}
			else{
				$update="update \"Customer_Temp\" set
				\"app_user\"='$iduser',
				\"app_date\"='$date',
				statusapp='0'
				where \"CusID\" = '$custempid' and statusapp='2' and add_user='$id_user' and add_date='$cdate'";
				if($result=pg_query($update)){
				}else{
					$status++;
				}
			}
	
	}
	
	if($status == 0){
		if($show==1){
			pg_query('ROLLBACK');
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('ไม่สามารถอนุมัติได้ เนื่องจากมีบางรายการได้รับการอนุมัติ ไปก่อนหน้านี้ !')</script>";
		echo $sql1."<p>".$sql2;
		}
		else{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(ALL) อนุมัติแก้ไขบัตรประชาชน', '$date')");
		//ACTIONLOG---
		pg_query('COMMIT');
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('ปฎิเสธการอนุมัติสำเร็จ !!')</script>";
		}
	}else{
		pg_query('ROLLBACK');
		// echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('ไม่สามารถปฎิเสธการอนุมัติได้ !')</script>";
		echo $sql1;
	
	}
}
?>