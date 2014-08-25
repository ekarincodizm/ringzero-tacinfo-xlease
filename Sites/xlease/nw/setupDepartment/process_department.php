<?php
session_start();
include("../../config/config.php");
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$method=$_POST["method"];
$fdep_id=$_POST["fdep_id"]; //รหัสใหม่ที่แก้ไข
$fdep_idold=$_POST["fdep_idold"]; //รหัสเก่าก่อนการแก้ไข
$fdep_name=$_POST["fdep_name"];
$fstatus=$_POST["fstatus"];

$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;
	
//นำรหัสใหม่ที่กรอกมาค้นหาว่าข้อมูลซ้ำหรือไม่
$query=pg_query("select * from \"f_department\" where \"fdep_id\"='$fdep_id'");
$numrows=pg_num_rows($query);

if($method == "add"){
	//กรณี insert แล้วข้อมูลซ้ำให้กลับไปกรอกใหม่
	if($numrows > 0){
		echo "<meta http-equiv='refresh' content='0; URL=frm_IndexAdd.php?showtext=1'>";
	}else{
		$in_sql="insert into \"f_department\" (\"fdep_id\",\"fdep_name\",\"fstatus\") values ('$fdep_id','$fdep_name','$fstatus')";
		if($result=pg_query($in_sql)){
		}else{
			$status++;
		}
		if($status == 0){
			//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มฝ่าย', '$add_date')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<center><h2>บันทึกฝ่ายหรือกลุ่มผู้ใช้เรียบร้อยแล้ว</h2></center>";
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
		$fdep_id_compare=$res["fdep_id"];
		}
		//กรณีข้อมูลที่ซ้ำไม่ใช่ข้อมูลเดิมให้ส่งค่ากลับไป
		if($fdep_idold != $fdep_id_compare){	
			echo "<meta http-equiv='refresh' content='0; URL=frm_IndexAdd.php?showtext=1&fdep_id=$fdep_idold&method=edit'>";
		}else{
			$update="update \"f_department\" set 
								\"fdep_id\"='$fdep_id',
								\"fdep_name\"='$fdep_name',
								\"fstatus\"='$fstatus' where \"fdep_id\"='$fdep_idold'";
			if($res_up=pg_query($update)){
			}else{
				$status++;
			}
			$updatedep="update \"fuser\" set \"user_dep\"='$fdep_id' where \"user_dep\"='$fdep_idold'";
			if($res_dep=pg_query($updatedep)){
			}else{
				$status++;
			}
			if($status == 0){
				//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) แก้ไขฝ่าย', '$add_date')");
			//ACTIONLOG---
				pg_query("COMMIT");
				echo "<center><h2>แก้ไขฝ่ายหรือกลุ่มผู้ใช้เรียบร้อยแล้ว</h2></center>";
				echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
			}else{
				pg_query("ROLLBACK");
				echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
				echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?fdep_id=$fdep_idold&method=edit'>";
			}		
		}
	}else{
		$update="update \"f_department\" set 
								\"fdep_id\"='$fdep_id',
								\"fdep_name\"='$fdep_name',
								\"fstatus\"='$fstatus' where \"fdep_id\"='$fdep_idold'";
		if($res_up=pg_query($update)){
		}else{
			$status++;
		}
		$updatedep="update \"fuser\" set \"user_dep\"='$fdep_id' where \"user_dep\"='$fdep_idold'";
		if($res_dep=pg_query($updatedep)){
		}else{
			$status++;
		}
		if($status == 0){
			pg_query("COMMIT");
			echo "<center><h2>แก้ไขประเภทค่าใช้จ่ายเรียบร้อยแล้ว</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
		}else{
			pg_query("ROLLBACK");
			echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?fdep_id=$fdep_idold&method=edit'>";
		}
	}
	

}


