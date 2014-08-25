<?php
session_start();
include("../../config/config.php");
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$method=$_POST["method"];
$vtid=$_POST["vtid"]; //รหัสใหม่ที่แก้ไข
$vtidold=$_POST["vtidold"]; //รหัสเก่าก่อนการแก้ไข
$voucher_type_name=$_POST["voucher_type_name"];
$voucher_type_desc=$_POST["voucher_type_desc"];
$voucher_type_status=$_POST["voucher_type_status"];

pg_query("BEGIN WORK");
$status = 0;
	
//นำรหัสใหม่ที่กรอกมาค้นหาว่าข้อมูลซ้ำหรือไม่
$query=pg_query("select * from Account.\"nw_voucher_type\" where \"vtid\"='$vtid'");
$numrows=pg_num_rows($query);

if($method == "add"){
	//กรณี insert แล้วข้อมูลซ้ำให้กลับไปกรอกใหม่
	if($numrows > 0){
		echo "<meta http-equiv='refresh' content='0; URL=frm_IndexAdd.php?showtext=1'>";
	}else{
		$in_sql="insert into Account.\"nw_voucher_type\" (\"vtid\",\"voucher_type_name\",\"voucher_type_desc\",\"voucher_type_status\") values ('$vtid','$voucher_type_name','$voucher_type_desc','$voucher_type_status')";
		if($result=pg_query($in_sql)){
		}else{
			$status++;
		}
		if($status == 0){
			
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่มการตั้งค่า Voucher', '$datelog')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<center><h2>บันทึกประเภทค่าใช้จ่ายเรียบร้อยแล้ว</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
		}else{
			pg_query("ROLLBACK");
			echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
		}
	}
	
}else if($method=="edit"){	
	//กรณีที่กรอกข้อมูลแล้วไปซ้ำกับรหัสที่มีอยู่แล้ว
	if($numrows > 0){
		if($res=pg_fetch_array($query)){
		$vtid_compare=$res["vtid"];
		}
		//กรณีข้อมูลที่ซ้ำไม่ใช่ข้อมูลเดิมให้ส่งค่ากลับไป
		if($vtidold != $vtid_compare){	
			echo "<meta http-equiv='refresh' content='0; URL=frm_IndexAdd.php?showtext=1&vtid=$vtidold&method=edit'>";
		}else{
			$update="update Account.\"nw_voucher_type\" set 
								\"vtid\"='$vtid',
								\"voucher_type_name\"='$voucher_type_name',
								\"voucher_type_desc\"='$voucher_type_desc',
								\"voucher_type_status\"='$voucher_type_status' where \"vtid\"='$vtidold'";
			if($res_up=pg_query($update)){
			}else{
				$status++;
			}
			$updatevtid="update Account.\"voucher_details\" set \"vtid\"='$vtid' where \"vtid\"='$vtidold'";
			if($res_upvtid=pg_query($updatevtid)){
			}else{
				$status++;
			}
			
			if($status == 0){
			
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขการตั้งค่า Voucher', '$datelog')");
				//ACTIONLOG---
				pg_query("COMMIT");
				echo "<center><h2>แก้ไขประเภทค่าใช้จ่ายเรียบร้อยแล้ว</h2></center>";
				echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
			}else{
				pg_query("ROLLBACK");
				echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
				echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?vtid=$vtidold&method=edit'>";
			}		
		}
	}else{
		$update="update Account.\"nw_voucher_type\" set 
								\"vtid\"='$vtid',
								\"voucher_type_name\"='$voucher_type_name',
								\"voucher_type_desc\"='$voucher_type_desc',
								\"voucher_type_status\"='$voucher_type_status' where \"vtid\"='$vtidold'";
		if($res_up=pg_query($update)){
		}else{
			$status++;
		}
		$updatevtid="update Account.\"voucher_details\" set \"vtid\"='$vtid' where \"vtid\"='$vtidold'";
		if($res_upvtid=pg_query($updatevtid)){
		}else{
			$status++;
		}
		
		if($status == 0){
				
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขการตั้งค่า Voucher', '$datelog')");
				//ACTIONLOG---
			pg_query("COMMIT");
			echo "<center><h2>แก้ไขประเภทค่าใช้จ่ายเรียบร้อยแล้ว</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
		}else{
			pg_query("ROLLBACK");
			echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?vtid=$vtidold&method=edit'>";
		}
	}
	

}


