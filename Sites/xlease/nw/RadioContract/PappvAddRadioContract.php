<?php
session_start();
include("../../config/config.php");

$id_user=$_SESSION["av_iduser"];
$logs_any_time_close = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$ContractStatus3 = $_POST["ContractStatus"];
$Remask = $_POST["Remask"];
$COID4 = $_POST["COID3"];

if($Remask==""){$Remask="NULL";} else{$Remask="'$Remask'";}

//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$COID4' and \"ContractStatus\" in('1','8')");
$num_check=pg_num_rows($qry_check);
if($num_check > 0){
	$rescheck=pg_fetch_array($qry_check);
	$check_status=trim($rescheck["ContractStatus"]);
	if($check_status =="1"){
		echo "รายการนี้ได้รับการอนุมัติไปแล้ว";
		echo "<meta http-equiv='refresh' content='2; URL=appvAddRadioContract.php'>";
	}else if($check_status =="8"){
		echo "รายการนี้ถูกยกเลิกไปแล้ว";
		echo "<meta http-equiv='refresh' content='2; URL=appvAddRadioContract.php'>";
	}
}else{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้

pg_query("BEGIN");
$status = 0;

if($ContractStatus3=="1") // ถ้าเลือกอนุมัติ
{
	$sql=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$COID4'");
	while($resultB=pg_fetch_array($sql)){
				$COID2=$resultB["COID"];
				$RadioNum2=$resultB["RadioNum"];
				$RadioCar2=$resultB["RadioCar"];
				$RadioRelationID2=$resultB["RadioRelationID"];
				$ContractStatus2=$resultB["ContractStatus"];
				$ContractCtl2=$resultB["ContractCtl"];
				$ContractDesc2=$resultB["ContractDesc"];
				$DoerID2=$resultB["DoerID"];
				$DoerStamp2=$resultB["DoerStamp"];
				$AppvID2=$resultB["AppvID"];
				$AppvStamp2=$resultB["AppvStamp"];
				$AuditID2=$resultB["AuditID"];
				$AuditStamp2=$resultB["AuditStamp"];
				$AppvRemask2=$resultB["AppvRemask"];
				}
				
	if($ContractCtl2==""){$ContractCtl2="NULL";} else{$ContractCtl2="'$ContractCtl2'";}
	if($AppvStamp2==""){$AppvStamp2="NULL";} else{$AppvStamp2="'$AppvStamp2'";}
	if($AuditStamp2==""){$AuditStamp2="NULL";} else{$AuditStamp2="'$AuditStamp2'";}
	if($ContractDesc2==""){$ContractDesc2="NULL";} else{$ContractDesc2="'$ContractDesc2'";}
	if($AppvID2==""){$AppvID2="NULL";} else{$AppvID2="'$AppvID2'";}
	if($AuditID2==""){$AuditID2="NULL";} else{$AuditID2="'$AuditID2'";}
	if($AppvRemask2==""){$AppvRemask2="NULL";} else{$AppvRemask2="'$AppvRemask2'";}
	
	$in_sql_9="insert into public.\"RadioContract_Bin\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$RadioNum2','$RadioCar2','$RadioRelationID2','$ContractStatus2',$ContractCtl2,$ContractDesc2,'$DoerID2','$DoerStamp2',$AppvID2,$AppvStamp2,$AuditID2,$AuditStamp2,$AppvRemask2)";
	if($result9=pg_query($in_sql_9))
	{}
	else
	{
		$status++;
	}
	
	$update_sql="update public.\"RadioContract\" set \"ContractStatus\"='1' , \"AppvID\"='$id_user' , \"AppvStamp\"='$logs_any_time_close' , \"AppvRemask\"=$Remask  where \"COID\"='$COID4'";
	if($result2=pg_query($update_sql))
	{}
	else
	{
		$status++;
	}
	
}

if($ContractStatus3=="8") // ถ้าเลือกยกเลิก
{
	$sql=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$COID4'");
	while($resultB=pg_fetch_array($sql)){
				$COID2=$resultB["COID"];
				$RadioNum2=$resultB["RadioNum"];
				$RadioCar2=$resultB["RadioCar"];
				$RadioRelationID2=$resultB["RadioRelationID"];
				$ContractStatus2=$resultB["ContractStatus"];
				$ContractCtl2=$resultB["ContractCtl"];
				$ContractDesc2=$resultB["ContractDesc"];
				$DoerID2=$resultB["DoerID"];
				$DoerStamp2=$resultB["DoerStamp"];
				$AppvID2=$resultB["AppvID"];
				$AppvStamp2=$resultB["AppvStamp"];
				$AuditID2=$resultB["AuditID"];
				$AuditStamp2=$resultB["AuditStamp"];
				$AppvRemask2=$resultB["AppvRemask"];
				}
				
	if($ContractCtl2==""){$ContractCtl2="NULL";} else{$ContractCtl2="'$ContractCtl2'";}
	if($AppvStamp2==""){$AppvStamp2="NULL";} else{$AppvStamp2="'$AppvStamp2'";}
	if($AuditStamp2==""){$AuditStamp2="NULL";} else{$AuditStamp2="'$AuditStamp2'";}
	if($ContractDesc2==""){$ContractDesc2="NULL";} else{$ContractDesc2="'$ContractDesc2'";}
	if($AppvID2==""){$AppvID2="NULL";} else{$AppvID2="'$AppvID2'";}
	if($AuditID2==""){$AuditID2="NULL";} else{$AuditID2="'$AuditID2'";}
	if($AppvRemask2==""){$AppvRemask2="NULL";} else{$AppvRemask2="'$AppvRemask2'";}
	
	$in_sql_9="insert into public.\"RadioContract_Bin\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$RadioNum2','$RadioCar2','$RadioRelationID2','$ContractStatus2',$ContractCtl2,$ContractDesc2,'$DoerID2','$DoerStamp2',$AppvID2,$AppvStamp2,$AuditID2,$AuditStamp2,$AppvRemask2)";
	if($result9=pg_query($in_sql_9))
	{}
	else
	{
		$status++;
	}
	
	$update_sql="update public.\"RadioContract\" set \"ContractStatus\"='8' , \"AppvID\" = '$id_user' , \"AppvStamp\" = '$logs_any_time_close' , \"AppvRemask\" = $Remask  where \"COID\"='$COID4'";
	if($result2=pg_query($update_sql))
	{}
	else
	{
		$status++;
	}
	
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) อนุมัติสัญญาวิทยุ (ลูกค้านอก)', '$logs_any_time_close')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>บันทึกสมบูรณ์ </h2></center>";
	echo "<form method=\"post\" name=\"form1\" action=\"appvAddRadioContract.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"appvAddRadioContract.php\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}

}
?>