<?php 
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$addUser=$_SESSION["av_iduser"];
$addStamp=nowDateTime();
$method=$_POST['update'];
$contractID	= $_POST['contractID']; //เลขที่สัญญา

if($method==""){ //ถ้าเป็นค่า ว่าง แสดงว่า มาจาก show_approve.php
	$sendform="showapprove"; 
	if(isset($_POST["btn1"])){		
		$method='approve';//กดอนุมัติ
	}else if(isset($_POST["btn2"])){
		$method='noapp';//กดไม่อนุมัติ
	}
}

pg_query("BEGIN WORK");
$status=0;	

if($method=="approve" || $method=="noapp"){ //กรณีเป็นในส่วนอนุมัติ		
	$result=checknull($_POST["result"]);
	
	//ตรวจสอบว่าเลขที่สัญญานี้มีการรออนุมัติหรือยัง
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='2'");
	
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ายังมีรายการรออนุมัติอยู่
		if($method=="approve"){
			$appStatus=1;
			
			//update ข้อมูลในตาราง thcap_ContactCus
			$upcon="UPDATE \"thcap_ContactCus\" a SET ranking=tableb.\"ranking_New\"
			FROM
			(SELECT \"contractID\",\"ranking_New\",\"CusID\" FROM \"thcap_ContactCus_Temp\" where \"appStatus\"='2') tableb
			WHERE a.\"contractID\"=tableb.\"contractID\" and a.\"CusID\"=tableb.\"CusID\" and a.\"contractID\"='$contractID'";
			if($resupcon=pg_query($upcon)){
			}else{
				$status++;
			}
		}else if($method=="noapp"){
			$appStatus=0;
		}
		
		$up="UPDATE \"thcap_ContactCus_Temp\"
			SET \"appStatus\"='$appStatus', \"appUser\"='$addUser', \"appStamp\"='$addStamp',\"result\"=$result
			WHERE \"contractID\"='$contractID' and \"appStatus\"='2'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}	
	}else{
		$status=-1;
	}
}else if($method == "update"){
	$array	= $_POST['arr']; //ผู้กู้หลัก
	$array1	= $_POST['arr1']; //ผู้กู้ร่วม
	$array2	= $_POST['arr2']; //ผู้ค้ำ
	
	//ตรวจสอบว่าเลขที่สัญญานี้มีการรออนุมัติหรือยัง
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='2'");
	if(pg_num_rows($qrychkapp)>0){
		echo "<div style=\"padding:50px;text-align:center;\"><h2>รายการนี้กำลังรออนุมัติอยู่ จะสามารถทำรายการได้หลังจากอนุมัติแล้ว</h2></div>";
		exit();
	}

	//ตรวจสอบว่าเลขที่สัญญานี้มีการบันทึกก่อนหน้านี้หรือยัง
	$qrychk=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='3'");
	$numrows=pg_num_rows($qrychk);
	
	//#############################ผู้กู้หลัก
	$count=1;
	if(sizeof($array)>0){
		foreach ($array as $idval) {			
			if($numrows>0){ //ถ้าพบว่ามีข้อมูลแล้วให้ update ข้อมูล
				$up="UPDATE \"thcap_ContactCus_Temp\"
				SET \"ranking_New\"='$count', \"addUser\"='$addUser', \"addStamp\"='$addStamp'
				WHERE \"contractID\"='$contractID' and \"CusID\"='$idval'";
				if($resup=pg_query($up)){
				}else{
					$status++;
				}	
			}else{ //ถ้ายังไม่มีให้ insert
				$ins="INSERT INTO \"thcap_ContactCus_Temp\"(
						\"contractID\", \"CusState\", \"CusID\", ranking,\"ranking_New\", \"addUser\", \"addStamp\",\"appStatus\")
				select \"contractID\",\"CusState\", \"CusID\", ranking,'$count','$addUser', '$addStamp', '3' 
				from \"thcap_ContactCus\"
				where \"contractID\"='$contractID' and \"CusID\"='$idval'";
				
				if($resins=pg_query($ins)){
				}else{
					$status++;
				}
			}
			$count++;	
		}
	}
	
	//######################################ผู้กู้ร่วม
	$count=1;
	if(sizeof($array1)>0){
		foreach ($array1 as $idval1) {
			if($numrows>0){ //ถ้าพบว่ามีข้อมูลแล้วให้ update ข้อมูล
				$up="UPDATE \"thcap_ContactCus_Temp\"
				SET \"ranking_New\"='$count', \"addUser\"='$addUser', \"addStamp\"='$addStamp'
				WHERE \"contractID\"='$contractID' and \"CusID\"='$idval1'";
				if($resup=pg_query($up)){
				}else{
					$status++;
				}
			
			}else{ //ถ้ายังไม่มีให้ insert
				$ins="INSERT INTO \"thcap_ContactCus_Temp\"(
						\"contractID\", \"CusState\", \"CusID\", ranking,\"ranking_New\", \"addUser\", \"addStamp\",\"appStatus\")
				select \"contractID\",\"CusState\", \"CusID\", ranking,'$count','$addUser', '$addStamp', '3' 
				from \"thcap_ContactCus\"
				where \"contractID\"='$contractID' and \"CusID\"='$idval1'";
				if($resins=pg_query($ins)){
				}else{
					$status++;
				}
			}
			$count++;	
		}
	}
	
	//###########################################ผู้ค้ำ
	$count=1;
	if(sizeof($array2)>0){
		foreach ($array2 as $idval2) {
			if($numrows>0){ //ถ้าพบว่ามีข้อมูลแล้วให้ update ข้อมูล
				$up="UPDATE \"thcap_ContactCus_Temp\"
				SET \"ranking_New\"='$count', \"addUser\"='$addUser', \"addStamp\"='$addStamp'
				WHERE \"contractID\"='$contractID' and \"CusID\"='$idval2'";
				if($resup=pg_query($up)){
				}else{
					$status++;
				}
			
			}else{ //ถ้ายังไม่มีให้ insert
				$ins="INSERT INTO \"thcap_ContactCus_Temp\"(
						\"contractID\", \"CusState\", \"CusID\", ranking,\"ranking_New\", \"addUser\", \"addStamp\",\"appStatus\")
				select \"contractID\",\"CusState\", \"CusID\", ranking,'$count','$addUser', '$addStamp', '3' 
				from \"thcap_ContactCus\"
				where \"contractID\"='$contractID' and \"CusID\"='$idval2'";
				if($resins=pg_query($ins)){
				}else{
					$status++;
				}
			}
			$count++;	
		}
	}	
}else if($method == "confirm"){ //กรณียืนยันการเปลี่ยนแปลงข้อมูล
	//ตรวจสอบว่าเลขที่สัญญานี้มีการรออนุมัติหรือยัง
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='2'");
	if(pg_num_rows($qrychkapp)>0){
		echo "<div style=\"padding:50px;text-align:center;\"><h2>รายการนี้กำลังรออนุมัติอยู่ จะสามารถทำรายการได้หลังจากอนุมัติแล้ว</h2></div>";
		exit();
	}
	
	//ตรวจสอบว่าเลขที่สัญญานี้มีการเปลี่ยนแปลงหรือไม่
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='3'");
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ามีการเปลี่ยนแปลงให้ update เป็นรออนุมัติ
		$up="UPDATE \"thcap_ContactCus_Temp\"
		SET \"appStatus\"='2', \"addUser\"='$addUser', \"addStamp\"='$addStamp'
		WHERE \"contractID\"='$contractID' and \"appStatus\"='3'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}else{
		$status=-1;
	}
}else if($method == "clear"){ //กรณียกเลิกการเปลี่ยนแปลงข้อมูล
	//ตรวจสอบว่าเลขที่สัญญานี้มีการรออนุมัติหรือยัง
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='2'");
	if(pg_num_rows($qrychkapp)>0){
		echo "<div style=\"padding:50px;text-align:center;\"><h2>รายการนี้กำลังรออนุมัติอยู่ จะสามารถทำรายการได้หลังจากอนุมัติแล้ว</h2></div>";
		exit();
	}

	//ตรวจสอบว่าเลขที่สัญญานี้มีการเปลี่ยนแปลงหรือไม่
	$qrychkapp=pg_query("select * from \"thcap_ContactCus_Temp\" where \"contractID\"='$contractID' and \"appStatus\"='3'");
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ามีการเปลี่ยนแปลงให้ update เป็นรออนุมัติ
		$del="DELETE FROM \"thcap_ContactCus_Temp\" WHERE \"contractID\"='$contractID' and \"appStatus\"='3'";
		if($resdel=pg_query($del)){
		}else{
			$status++;
		}
	}else{
		$status=-1;
	}
}

if($status==-1){
	pg_query("ROLLBACK");
	if($sendform=="showapprove"){ 
		$script= '<script language=javascript>';
		$script.= " alert('ไม่พบรายการอนุมัติ อาจได้รับอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ');
					opener.location.reload(true);
					self.close();";	
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 2;
	}
}else if($status==0){
	pg_query("COMMIT");
	if($method == "update"){
		echo "<div style=\"padding:50px;text-align:center;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";
	}else{
		if($sendform=="showapprove"){ 
		$script= '<script language=javascript>';
			if($method=='approve'){
				$script.= " alert('อนุมัติเรียบร้อยแล้ว');
				opener.location.reload(true);
				self.close();";	
			}
			else{
				$script.= " alert('ไม่อนุมัติเรียบร้อยแล้ว');
				opener.location.reload(true);
				self.close();";	
			}
			$script.= '</script>';
			echo $script;
		}
		else{
			echo 1;
		}
	}
}else{
	pg_query("ROLLBACK");
	if($method == "update"){
		echo "<div style=\"padding:50px;text-align:center;\"><h2>ผิดพลาด กรุณาติดต่อฝ่าย IT</h2></div>";
	}else{
		if($sendform=="showapprove"){ 
			$script= '<script language=javascript>';
			$script.= " alert('ผิดพลาดไม่สามารถอนุมัติได้ ');
					opener.location.reload(true);
					self.close();";	
			$script.= '</script>';
			echo $script;
		}
		else{
			echo 3;
		}
	}
}


?>