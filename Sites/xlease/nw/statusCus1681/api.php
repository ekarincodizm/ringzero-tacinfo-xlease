<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$makerID = $_SESSION["av_iduser"];
$makerStamp = Date('Y-m-d H:i:s');
$payment = json_decode(stripcslashes($_POST["payment"]));
$tacID = $_POST["cusid"]; //เลขที่สัญญา
$method=$_POST["method"]; 
$tacXlsRecID=$_POST["tacXlsRecID"]; //เลขที่ใบเสร็จนวมินทร์
if($tacXlsRecID==""){
	$tacXlsRecID=$_POST["tacXlsRecID_J"]; //เลขที่ใบเสร็จจรัญ
}
if(isset($_POST["appv"])){
		$statusApp='1';//อนุมัติ
}else if(isset($_POST["unappv"])){
		$statusApp='0';//ไม่อนุมัติ
}



pg_query("BEGIN WORK");
$status = 0;

if($method=="edit"){
	$tacTempDate=$_POST["tacTempDate"];
	$tacOldRecID=checknull($_POST["tacOldRecID"]);
	$tacID_Old=$_POST["tacID_Old"];
	$tacXlsRecID_Old=$_POST["tacXlsRecID_Old"];
	$checkdel=$_POST["checkdel"];
	
	if($checkdel==1){ //กรณีขอลบข้อมูล
		$in_sql="INSERT INTO \"tacReceiveTemp_waitedit\"(
			\"tacID\", \"tacXlsRecID\", \"tacTempDate\",\"tacMoney\", \"tacMonth\" ,
			\"tacOldRecID\", req_user, req_stamp,\"statusApp\",\"tacID_Old\",\"tacXlsRecID_Old\")
		SELECT \"tacID\", \"tacXlsRecID\", \"tacTempDate\", \"tacMoney\", \"tacMonth\",\"tacOldRecID\", '$makerID', '$makerStamp', '3',\"tacID\",\"tacXlsRecID\"
		FROM \"tacReceiveTemp\" WHERE \"tacID\"='$tacID_Old' and \"tacXlsRecID\"='$tacXlsRecID_Old'";
		if($result2=pg_query($in_sql)){	
		}else{
			$status++;
		}
	}else{
		foreach($payment as $key => $value){
			$a1 = $value->paymoney;
			$a2 = $value->month;
			$a3 = $value->yearpay;
			
			
			if( empty($a1) or empty($a2) or empty($a3)){
				continue;
			}   
		   
			$tacMonth=$a3."-".$a2."-01";
			
			$in_sql="INSERT INTO \"tacReceiveTemp_waitedit\"(
				\"tacID\", \"tacXlsRecID\", \"tacTempDate\", 
				\"tacOldRecID\", \"tacMoney\", \"tacMonth\", req_user, req_stamp,\"statusApp\",\"tacID_Old\",\"tacXlsRecID_Old\")
				VALUES ('$tacID', '$tacXlsRecID', '$tacTempDate', 
				$tacOldRecID, '$a1', '$tacMonth', '$makerID', '$makerStamp', '2','$tacID_Old','$tacXlsRecID_Old')";
			if($result2=pg_query($in_sql)){	
			}else{
				$status++;
			}
		}
	}
}else if($method=="approve"){
		//$statusApp=$_POST["statusApp"];
		$tacID_Old=$_POST["tacID_Old"];
		$tacXlsRecID_Old=$_POST["tacXlsRecID_Old"];		
		$statusApp_old=$_POST["statusApp_now"];
		
		//หา serial หลัก (ค่าแรก) ของตาราง tacReceiveTemp_waitedit 
		$qrymaster=pg_query("SELECT min(\"auto_id\") FROM \"tacReceiveTemp_waitedit\" WHERE \"tacID\"='$tacID' AND  \"tacXlsRecID\"='$tacXlsRecID' AND \"tacID_Old\"='$tacID_Old'
		AND \"tacXlsRecID_Old\"='$tacXlsRecID_Old' AND \"statusApp\" IN ('2','3')");
		list($minautoid)=pg_fetch_array($qrymaster);
			
		//update ตาราง Log ให้เก็บข้อมูลเก่าเป็นประวัติ
		$inslog="INSERT INTO \"tacReceiveTemp_Log\"(
        \"tacID\", \"tacXlsRecID\", \"tacMoney\", \"tacMonth\", \"tacOldRecID\", 
        \"tacTempDate\", \"makerID\", \"makerStamp\",\"masterID\")
		SELECT \"tacID\", \"tacXlsRecID\", \"tacMoney\", \"tacMonth\", \"tacOldRecID\", 
        \"tacTempDate\", \"makerID\", \"makerStamp\",'$minautoid' FROM \"tacReceiveTemp\" WHERE \"tacID\"='$tacID_Old' AND  \"tacXlsRecID\"='$tacXlsRecID_Old'";

		if($reslog=pg_query($inslog)){
		}else{
			$status++;
		}
		
		if($statusApp=="1"){ //กรณีอนุมัติ ให้ลบข้อมูลเก่าแล้ว insert ข้อมูลใหม่แทนที่
			//ลบข้ือมูลเก่าจากตาราง tacReceiveTemp ออกเพื่อเก็บข้อมูลใหม่ที่อนุมัติแทน
			$delold="DELETE FROM \"tacReceiveTemp\" WHERE \"tacID\"='$tacID_Old' AND  \"tacXlsRecID\"='$tacXlsRecID_Old'";
			if($resdel=pg_query($delold)){
			}else{
				$status++;
			}
			
			if($statusApp_old!="3"){ //ให้ insert ใหม่เฉพาะกรณีไม่ได้ขอลบข้อมูลเท่านั้น
				$inslog="INSERT INTO \"tacReceiveTemp\"(
				\"tacID\", \"tacXlsRecID\", \"tacMoney\", \"tacMonth\", \"tacOldRecID\", 
				\"tacTempDate\", \"makerID\", \"makerStamp\")
				SELECT \"tacID\", \"tacXlsRecID\", \"tacMoney\", \"tacMonth\", \"tacOldRecID\", 
				\"tacTempDate\", \"req_user\", \"req_stamp\" FROM \"tacReceiveTemp_waitedit\" WHERE \"tacID\"='$tacID' AND  \"tacXlsRecID\"='$tacXlsRecID' AND \"tacID_Old\"='$tacID_Old'
				AND \"tacXlsRecID_Old\"='$tacXlsRecID_Old' AND \"statusApp\"='2'";

				if($reslog=pg_query($inslog)){
				}else{
					$status++;
				}
			}
		}
		
		
		//update ข้อมูลว่าอนุมัติหรือไม่อนุมัติ
		$up="UPDATE \"tacReceiveTemp_waitedit\"
		SET \"statusApp\"='$statusApp', app_user='$makerID', app_stamp='$makerStamp'
		WHERE \"tacID\"='$tacID' AND  \"tacXlsRecID\"='$tacXlsRecID' AND \"tacID_Old\"='$tacID_Old' AND \"tacXlsRecID_Old\"='$tacXlsRecID_Old' AND \"statusApp\" IN ('2','3')";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}	
}else{
	$tacxlsrecid1 = $_POST["tacxlsrecid1"];
	$tacxlsrecid2 = $_POST["tacxlsrecid2"];
	$tacxlsrecid3 = $_POST["tacxlsrecid3"];
		
	if($tacxlsrecid1 != ""){
		$tacXlsRecID=$tacxlsrecid1;
	}else if($tacxlsrecid2 != ""){
		$tacXlsRecID=$tacxlsrecid2;
	}else{
		$tacXlsRecID=$tacxlsrecid3;
	}

	$tacTempDate1 = $_POST["tactempdate"];
	$tacxlsdate=$_POST["tacxlsdate"];
	$tacxlsjrdate=$_POST["tacxlsjrdate"];

	if($tacxlsrecid1 != ""){
		if($tacxlsdate==""){
			$tacTempDate="NULL";
		}else{
			$tacTempDate="'$tacxlsdate'";
		}
	}else if($tacxlsrecid2 != ""){
		if($tacTempDate1==""){
			$tacTempDate="NULL";
		}else{
			$tacTempDate="'$tacTempDate1'";
		}
	}else{
		if($tacxlsjrdate==""){
			$tacTempDate="NULL";
		}else{
			$tacTempDate="'$tacxlsjrdate'";
		}
	}

	$tacOldRecID1 = $_POST["tacoldrecid"];
	if($tacOldRecID1==""){
		$tacOldRecID="NULL";
	}else{
		$tacOldRecID="'$tacOldRecID1'";
	}
		
	foreach($payment as $key => $value){
		$a1 = $value->paymoney;
		$a2 = $value->month;
		$a3 = $value->yearpay;
		
		if($a2 == 0){
		  if( empty($a1) or empty($a3) ){
			 continue;
		  }
		}else{
		   if( empty($a1) or empty($a2) or empty($a3) ){
			  continue;
		   }   
	   }
		
		$a1=trim($a1);	
		$tacMonth=$a3."-".$a2."-01";
		$in_sql="insert into \"tacReceiveTemp\" (\"tacID\",\"tacXlsRecID\",\"tacMoney\",\"tacMonth\",\"tacOldRecID\",\"tacTempDate\",\"makerID\",\"makerStamp\") values  ('$tacID','$tacXlsRecID',$a1,'$tacMonth',$tacOldRecID,$tacTempDate,'$makerID','$makerStamp')";
		if($result2=pg_query($in_sql)){	
		}else{
			$status++;
		}
	}
}
    
if($status == 0){
	if($method=="edit"){
		$txtlog="(TAL) แก้ไขรับชำระแทนชั่วคราว 1681";
	}else if($method=="approve"){
		$txtlog="(TAL) อนุมัติชำระแทนชั่วคราว 1681";
	}else{
		$txtlog="(TAL) รับชำระแทนชั่วคราว 1681";
	}
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$makerID', '$txtlog', '$makerStamp')");
	//ACTIONLOG---
	pg_query("COMMIT");
	if($method=="approve"){
		$script= '<script language=javascript>';
		$script.= " alert('บันทึกรายการเรียบร้อย');    
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
	echo "1";}
}else{
	pg_query("ROLLBACK");
	if($method=="approve"){
		$script= '<script language=javascript>';
		$script.= " alert('ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง');    
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{echo "2";}
}
?>