<?php 
session_start();
include("../../../../../config/config.php");

$addUser=$_SESSION["av_iduser"];
$addStamp=nowDateTime();

$method=$_POST['method'];
$id	= $_POST['id']; //id ของรายการที่ต้องการยกเลิก
if($method==""){
	$sendfrom="showappv"; //ส่งมาจาก หน้า show_approve.php
	if(isset($_POST["btn1"])){
		$method='approve';//อนุมัติ
	}else{
		$method='noapp';//ไม่อนุมัติ
	}
}
	
pg_query("BEGIN WORK");
$status=0;	

if($method=="approve" || $method=="noapp"){ //กรณีเป็นในส่วนอนุมัติ		
	$deleteid=$_POST["deleteid"];
	
	//ตรวจสอบว่ารายการนี้มีการรออนุมัติยกเลิกหรือไม่
	$qrychkapp=pg_query("select * from \"ta_join_main_delete_temp\" where \"deleteid\"='$deleteid' and \"appStatus\"='2'");
	if(pg_num_rows($qrychkapp)==0){ //แสดงว่ารายการได้อนุมัติไปก่อนหน้านี้แล้ว
		$status=-1;
	}
	
	//ตรวจสอบว่ารายการนี้มีการยกเลิกก่อนหน้านี้หรือไม่ ถ้า deleted=1 คือยกเลิกแล้ว
	$qrychk=pg_query("select * from \"ta_join_main\" where \"id\"='$id' and \"deleted\"='1'");
	if(pg_num_rows($qrychk)>0){  //แสดงว่าถูกยกเลิกไปแล้ว
		$status=-2;
	}
	
	if($status==0){ //กรณีสามารถอนุมัติได้ปกติ
		if($method=="approve"){
			$appStatus=1;
			
			//update ข้อมูลในตาราง ta_join_main
			$upcon="UPDATE \"ta_join_main\"  SET deleted='1'
			WHERE \"id\"='$id'";
			if($resupcon=pg_query($upcon)){
			}else{
				$status++;
			}
		}else if($method=="noapp"){
			$appStatus=0;
		}
		
		$up="UPDATE \"ta_join_main_delete_temp\"
			SET \"appStatus\"='$appStatus', \"appUser\"='$addUser', \"appStamp\"='$addStamp'
			WHERE \"deleteid\"='$deleteid' and \"appStatus\"='2'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}	
	}
}else if($method == "request"){ //กรณีขอยกเลิกรายการ
	$result=$_POST['result'];
	
	//ตรวจสอบว่ารายการนี้มีการรออนุมัติยกเลิกหรือไม่
	$qrychkapp=pg_query("select * from \"ta_join_main_delete_temp\" where \"id\"='$id' and \"appStatus\"='2'");
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ากำลังรออนุมัติยกเลิกอยู่
		$status=-1;
	}

	//ตรวจสอบว่ารายการนี้มีการยกเลิกก่อนหน้านี้หรือไม่ ถ้า deleted=1 คือยกเลิกแล้ว
	$qrychk=pg_query("select * from \"ta_join_main\" where \"id\"='$id' and \"deleted\"='1'");
	if(pg_num_rows($qrychk)>0){  //แสดงว่าถูกยกเลิกไปแล้ว
		$status=-2;
	}
	
	//#############################ทำการบันทึกข้อมูลกรณีที่สามารถบันทึกได้
	if($status==0){
		$ins="INSERT INTO ta_join_main_delete_temp(
            id, \"userRequest\", \"userStamp\",\"appStatus\", resultdelete) VALUES 
			('$id', '$addUser', '$addStamp', '2', '$result')";
		if($resins=pg_query($ins)){
		}else{
			$status++;
		}
	}
}
if($status==-1){
	pg_query("ROLLBACK");
	if($sendfrom=="showappv"){
		$script= '<script language=javascript>';
		$script.= " alert('รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 1; //กรณีมีรายการรออนุมัติอยู่ (ตอนขออนุมติยกเลิก)
	}
}else if($status==-2){
	pg_query("ROLLBACK");
	if($sendfrom=="showappv"){
		$script= '<script language=javascript>';
		$script.= " alert('รายการนี้ได้รับการยกเลิกไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 2; //กรณีรายการถูกยกเลิกแล้ว(ตอนขออนุมติยกเลิก)
	}
}else if($status==0){
	pg_query("COMMIT");
	if($sendfrom=="showappv"){
		$script= '<script language=javascript>';
		if($method=='approve'){
			$script.= " alert('บันทึกการอนุมัติยกเลิกเรียบร้อยแล้ว');";}
		else{
			$script.= " alert('บันทึกการไม่อนุมัติเรียบร้อยแล้ว');";}		
		$script.= "	opener.location.reload(true);";
		$script.= "	self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 3; //กรณีบันทึกสำเร็จ
	}
}else{
	pg_query("ROLLBACK");
	if($sendfrom=="showappv"){
		$script= '<script language=javascript>';
		$script.= " alert('ผิดพลาดไม่สามารถขอยกเลิกรายการนี้ได้ ');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 4; //กรณีข้อมูลผิดพลาด
	}
}
?>