<?php ?>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
session_start();
include("../config/config.php");
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime();

$v_id=pg_escape_string($_POST["s_id"]);

pg_query("BEGIN WORK");
$status=0;

for($i=0;$i<count($_POST["i_menu"]);$i++) 
{
	$ii_menu=pg_escape_string($_POST["i_menu"][$i]);
	$se_st=pg_escape_string($_POST["a_st"][$i]);
 
	echo $v_id." ".$ii_menu." ".$se_st."<br>";
 
	//ตรวจสอบว่ามีการบันทึกในตารางนี้หรือยัง
	$querysts=pg_query("select * from nw_changemenu where id_user='$v_id' and id_menu='$ii_menu' and \"statusApprove\"='0' and \"statusOKapprove\"='FALSE'");
	$numrowsts=pg_num_rows($querysts);
	if($numrowsts>0){ //แสดงว่ามีการรออนุมัติจากเมนูขอเปลี่ยนแปลงสิทธิ์อยู่
		echo "มีบางรายการรออนุมัติขอเปลี่ยนแปลงสิทธิ์การทำงานอยู่ กรุณาตรวจสอบ";
		$status++;
		break;
	}else{ //กรณีไม่รออนุมัติสามารถ update ได้ตามปกติ
		//ดึงข้อมูลเก่าขึ้นมาเพื่อตรวจสอบว่าค่า status เดิมคืออะไร
		$querystatus=pg_query("select * from f_usermenu where id_user='$v_id' and id_menu='$ii_menu'");
		if($res_status=pg_fetch_array($querystatus)){
			$sts_old=$res_status["status"];
			if($sts_old=='t'){
				$sts_old="TRUE";
			}else{
				$sts_old="FALSE";
			}
		}
 
		//ถ้าสถานะไม่เหมือนกันแสดงว่ามีการเปลี่ยนแปลงให้ add ในตาราง nw_changemenu
		if($sts_old != $se_st){
			//ตรวจสอบว่ามีการอนุมัติแล้วรอกดรับทราบหรือไม่
			$querysts=pg_query("select * from nw_changemenu where id_user='$v_id' and id_menu='$ii_menu' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'");
			$numrowsts=pg_num_rows($querysts);
			if($numrowsts==0){
				$ins="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
											values ('$ii_menu','$v_id','$se_st','$id_user','$currentdate','$id_user','$currentdate','2','FALSE')";
				if($resins=pg_query($ins)){
				}else{
					$status++;
				}
			}else{
				//update สถานะให้เป็นข้อมูลล่าสุด
				$up="update \"nw_changemenu\" set \"status\"='$se_st',\"add_user\"='$id_user',\"add_date\"='$currentdate',\"approve_user\"='$id_user',\"approve_date\"='$currentdate'
				where \"id_menu\"='$ii_menu' and \"id_user\"='$v_id' and \"statusApprove\"='2' and \"statusOKapprove\"='FALSE'";
				if($resup=pg_query($up)){
				}else{
					$status++;
				}
			}
			//update log ว่ามีการเปลี่ยนแปลง
			$uplog="INSERT INTO nw_changemenu_log(
			id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
			app_user, app_stamp)
			VALUES ('$ii_menu', '$v_id', '2', '$se_st', 'TRUE', 
					'$id_user', '$currentdate')";
			if($ins=pg_query($uplog)){
			}else{
				$status++;
			}
		}
		
		$sql="update f_usermenu set status='$se_st' where id_user='$v_id' AND id_menu='$ii_menu'";
		if($db_query=pg_query($sql)){
		}else{ 
			$status++; 
		}
	}
}
if($status==0){
	pg_query("COMMIT");
	echo "บันทึกเรียบร้อยแล้ว";
}else{
	pg_query("ROLLBACK");
}
echo "<input type=\"button\" value=\"กลับไปรายการหลัก\" onclick=\"parent.location='menu_manage.php'\"/>";
?>