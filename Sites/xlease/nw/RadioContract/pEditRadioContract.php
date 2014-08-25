<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$coid = $_POST["coid2"];
$radionum = $_POST["radionum"];
$radiocar = $_POST["radiocar"];
$cusid_new = $_POST["s_idno"];
$cusid_old = $_POST["cusid"];
$radioralationid = $_POST["radioralationid"];

$sql2= pg_query("select * from public.\"GroupCus\" ");
$numrow=pg_num_rows($sql2);
$numrow2 = $numrow + 1;
$gid = "G".core_generate_frontzero($numrow2, 10);

$id_user=$_SESSION["av_iduser"];
$logs_any_time_close = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

/*echo $coid."<br>";
echo $radionum."<br>";
echo $radiocar."<br>";
echo "old".$cusid_old."<br>";
echo "new".$cusid_new."<br>";
echo $radioralationid."<br>";
echo $numrow."<br>";
echo $numrow2."<br>";
echo $gid;
echo "<br><br>";

echo $id_user;
echo "<br>";
echo $logs_any_time_close;*/


pg_query("BEGIN");
$status = 0;

if($cusid_old != $cusid_new)
{
	$in_sql="insert into public.\"GroupCus\" (\"GroupCusID\",\"GStatus\",\"GType\") values ('$gid','ACTIVE','RadioContract')";
	if($result=pg_query($in_sql))
	{}
	else
	{
		$status++;
	}
	
	$update_sql="update public.\"GroupCus\" set \"GStatus\"='BIN' where \"GroupCusID\"='$radioralationid'";
	if($result2=pg_query($update_sql))
	{}
	else
	{
		$status++;
	}
	
	$A_sql=pg_query("select * from public.\"GroupCus_Active\" where \"GroupCusID\" = '$radioralationid'");
	while($resultA=pg_fetch_array($A_sql)){
				$A_GroupCusID=$resultA["GroupCusID"];
				$A_CusState=$resultA["CusState"];
				$A_CusID=$resultA["CusID"];
				}
	$in_sql_Bin="insert into public.\"GroupCus_Bin\" (\"GroupCusID\",\"CusState\",\"CusID\") values ('$A_GroupCusID','$A_CusState','$A_CusID')";
	if($result3=pg_query($in_sql_Bin))
	{}
	else
	{
		$status++;
	}
	
	$delete_sql="delete from public.\"GroupCus_Active\" where \"GroupCusID\" = '$radioralationid'";
	if($result4=pg_query($delete_sql))
	{}
	else
	{
		$status++;
	}
	
	$in_sql_Active="insert into public.\"GroupCus_Active\" (\"GroupCusID\",\"CusState\",\"CusID\") values ('$gid','0','$cusid_new')";
	if($result5=pg_query($in_sql_Active))
	{}
	else
	{
		$status++;
	}
	
	$C_sql=pg_query("select * from public.\"RadioContract\" where \"RadioRelationID\" = '$radioralationid'");
	while($resultB=pg_fetch_array($C_sql)){
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
	
	/*$update_RadioContract="update public.\"RadioContract\" set \"RadioRelationID\"='$gid' where \"RadioRelationID\"='$radioralationid'";
	if($resultR=pg_query($update_RadioContract))
	{}
	else
	{
		$status++;
	}*/
	
	$sql_de_R2="delete from public.\"RadioContract\" where \"RadioRelationID\" = '$radioralationid'";
	if($resultD2=pg_query($sql_de_R2))
	{}
	else
	{
		$status++;
	}
	
	$in_sql_R2="insert into public.\"RadioContract\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$radionum','$radiocar','$gid','$ContractStatus2',$ContractCtl2,$ContractDesc2,'$id_user','$logs_any_time_close',$AppvID2,$AppvStamp2,$AuditID2,$AuditStamp2,$AppvRemask2)";
	if($resultR2=pg_query($in_sql_R2))
	{}
	else
	{
		$status++;
	}
	
}

else
{
	$B_sql=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$coid'");
	while($resultB=pg_fetch_array($B_sql)){
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
				
	/*echo "<br><br>";
	echo "COID2 = ".$COID2."<br>";
	echo "RadioNum2 = ".$RadioNum2."<br>";
	echo "RadioCar2 = ".$RadioCar2."<br>";
	echo "RadioRelationID2 = ".$RadioRelationID2."<br>";
	echo "ContractStatus2 = ".$ContractStatus2."<br>";
	echo "ContractCtl2 = ".$ContractCtl2."<br>";
	echo "ContractDesc2 = ".$ContractDesc2."<br>";
	echo "DoerID2 = ".$DoerID2."<br>";
	echo "DoerStamp2 = ".$DoerStamp2."<br>";
	echo "AppvID2 = ".$AppvID2."<br>";
	echo "AppvStamp2 = ".$AppvStamp2."<br>";
	echo "AuditID2 = ".$AuditID2."<br>";
	echo "AuditStamp2 = ".$AuditStamp2."<br>";
	echo "AppvRemask2 = ".$AppvRemask2."<br><br>";
	echo "delete from public.\"RadioContract\" where \"COID\" = '$coid'";
	echo "<br><br>";
	echo "insert into public.\"RadioContract_Bin\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$RadioNum2','$RadioCar2','$RadioRelationID2','$ContractStatus2','$ContractCtl2','$ContractDesc2','$DoerID2','$DoerStamp2','$AppvID2','$AppvStamp2','$AuditID2','$AuditStamp2','$AppvRemask2')";
	echo "<br><br>";*/
	
	/*$in_sql_9="insert into public.\"RadioContract_Bin\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$RadioNum2','$RadioCar2','$RadioRelationID2','$ContractStatus2',$ContractCtl2,'$ContractDesc2','$DoerID2','$DoerStamp2','$AppvID2',$AppvStamp2,'$AuditID2',$AuditStamp2,'$AppvRemask2')";
	if($result9=pg_query($in_sql_9))
	{}
	else
	{
		$status++;
	}*/
	
	$in_sql_9="insert into public.\"RadioContract_Bin\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"ContractCtl\",\"ContractDesc\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"AppvRemask\") values ('$COID2','$RadioNum2','$RadioCar2','$RadioRelationID2','$ContractStatus2',$ContractCtl2,$ContractDesc2,'$DoerID2','$DoerStamp2',$AppvID2,$AppvStamp2,$AuditID2,$AuditStamp2,$AppvRemask2)";
	if($result9=pg_query($in_sql_9))
	{}
	else
	{
		$status++;
	}
	
	$delete_rc="delete from public.\"RadioContract\" where \"COID\" = '$coid'";
	if($result7=pg_query($delete_rc))
	{}
	else
	{
		$status++;
	}
	
	$in_sql_8="insert into public.\"RadioContract\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"DoerID\",\"DoerStamp\") values ('$coid','$radionum','$radiocar','$RadioRelationID2','0','$id_user','$logs_any_time_close')";
	if($result8=pg_query($in_sql_8))
	{}
	else
	{
		$status++;
	}
	
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขสัญญาวิทยุ (ลูกค้านอก)', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>บันทึกสมบูรณ์ (สัญญาวิทยุ=".$coid." รหัสวิทยุ=".$radionum." ทะเบียนรถ=".$radiocar." เจ้าของวิทยุ=".$cusid_new.") GropCusID=".$gid."</h2></center>";
	echo "<form method=\"post\" name=\"form1\" action=\"sEditRadioContract.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"sEditRadioContract.php\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>