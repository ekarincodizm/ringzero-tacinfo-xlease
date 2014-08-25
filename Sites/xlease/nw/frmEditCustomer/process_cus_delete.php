<?php
session_start();
include("../../config/config.php");

$idno = pg_escape_string($_GET["idno"]);
$CusState = pg_escape_string($_GET["CusState"]);
$method = pg_escape_string($_GET["method"]);
pg_query("BEGIN WORK");
$status = 0;


if($method=="cancel"){
	//กรณียกเลิกรายการ
	$delCon = "delete from \"ContactCus_Temp\" where \"IDNO\" = '$idno' and \"statusApp\" = '9'";
	if($result=pg_query($delCon)){
	}else{
		$error1=$result;
		$status++;
	}	
	if($status == 0){
		pg_query("COMMIT");
		echo "<center><h2>ยกเลิกข้อมูลเรียบร้อยแล้ว</h2></center>";
		echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_edit_cus.php?idnog=$idno&status=\">"; 
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>ยกเลิกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";	
		echo "<br>$error1";
	}
}else{
	//ค้นหาข้อมูลในตาราง  ContactCus_Temp ว่ามีหรือยัง
	$qrytemp=pg_query("select * from \"ContactCus_Temp\" where \"IDNO\"='$idno' and \"statusApp\"='9'");
	$numtemp=pg_num_rows($qrytemp);
	if($numtemp==0){ //ยังไม่มีข้อมูลในตารางนี้
		//add ข้อมูลลงในตารางนี้ก่อน
		$qrycon=pg_query("select * from \"ContactCus\" where \"IDNO\"='$idno' order by \"CusState\"");
		while($rescon=pg_fetch_array($qrycon)){
			$CusState1=$rescon["CusState"];
			$CusID=$rescon["CusID"];
				
			$instemp="INSERT INTO \"ContactCus_Temp\"(
			   \"IDNO\", \"CusState\", \"CusID\", \"statusApp\")
				VALUES ('$idno', '$CusState1','$CusID', '9');";
			if($resins=pg_query($instemp)){
			}else{
				$status++;
			}
		}
	}

	//ลบ CusID ที่ต้องการออกจากตาราง ContactCus_Temp
	$delCon = "delete from \"ContactCus_Temp\" where \"IDNO\" = '$idno' and \"CusState\" = '$CusState'";
	if($result=pg_query($delCon)){
	}else{
		$error1=$result;
		$status++;
	}	

	$state = 1;
	//ดึง CusID ที่เหลือจากตาราง  ContactCus_Temp โดยที่ไม่ใช่ผู้เช่าซือออกมา เพื่อนำมาเรียงลำดับผู้ค้ำใหม่
	$query_con = pg_query("select * from \"ContactCus_Temp\" where \"IDNO\" = '$idno' and \"CusState\" <> '0' and \"statusApp\"='9' order by \"CusState\"");
	while($resccus=pg_fetch_array($query_con)){
		$cusID1 = $resccus["CusID"];
			
		$upcon = "update \"ContactCus_Temp\" set \"CusState\" = '$state' where \"IDNO\" = '$idno' and \"CusID\" ='$cusID1'";
		if($resultup=pg_query($upcon)){
		}else{
			$error2=$resultup;
			$status++;
		}
		
		$state++;
	}
	
	if($status == 0){
		pg_query("COMMIT");
		echo "<center><h2>ลบข้อมูลเรียบร้อยแล้ว</h2></center>";
		echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_edit_cus.php?idnog=$idno&status=9\">"; 
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2>ลบข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";	
		echo "<br>$error1<br>$error2";
	}
}


?>