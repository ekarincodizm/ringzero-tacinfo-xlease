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
	echo "<script type='text/javascript'>alert('กรุณาเลือก รายการที่จะอนุมัติ !')</script>";
	
}else{
pg_query('BEGIN');
	for($i=0;$i<sizeof($id);$i++){
		
		$sqlcheck = pg_query("SELECT \"reiden_pk\" FROM \"Re_indentity_cus_temp\" where \"reiden_pk\" = '$id[$i]' and    app_status='1'");
		$num_ctemp=pg_num_rows($sqlcheck);
		$show=0;
		if($num_ctemp=0){ $show=1;}
		else{
			$sql1 = "UPDATE \"Re_indentity_cus_temp\" SET app_status='2', app_user='$iduser', app_date='$date' WHERE reiden_pk = '$id[$i]'";		
			if($query1 = pg_query($sql1)){}else{ $status++; }
		
		
				$sqlselect = pg_query("SELECT * FROM \"Re_indentity_cus_temp\" where \"reiden_pk\" = '$id[$i]'");
				$reselect = pg_fetch_array($sqlselect);
				$cusid = trim($reselect['CusID']);
				$idcard = trim($reselect['identity_new']);
		
			$sql2 = "UPDATE \"Fn\" SET \"N_IDCARD\"='$idcard' WHERE \"CusID\" = '$cusid'";
			if($query2 = pg_query($sql2)){}else{ $status++; }
			//update ข้อมูลใน Customer_Temp ว่าได้ approve แล้ว  โดยให้มีการตรวจสอนก่อนว่า ได้ approve แล้ว หรือยัง
			$sqlcheck_update = pg_query("SELECT \"CustempID\" FROM \"Customer_Temp\" where \"CusID\" = '$cusid' and statusapp='2'");
			$num_update=pg_num_rows($sqlcheck_update);
			$re_check_update = pg_fetch_array($sqlcheck_update);
			$custempid=$re_check_update["CustempID"];
			$show=0;
			if($num_update==0){$show=1;}
			else{
				$update="update \"Customer_Temp\" set 
				\"app_user\"='$iduser', 
				\"app_date\"='$date', 
				statusapp='1'
				where \"CustempID\" = '$custempid'";
				if($result=pg_query($update)){
				}else{
					$status++;
				}
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
		echo "<script type='text/javascript'>alert('อนุมัติสำเร็จ !!')</script>";
		}
	}else{
		pg_query('ROLLBACK');
		// echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
		echo "<script type='text/javascript'>alert('ไม่สามารถอนุมัติได้ !')</script>";
		echo $sql1."<p>".$sql2;
	
	}
}
?>