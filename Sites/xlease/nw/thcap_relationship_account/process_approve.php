<?php
include("../../config/config.php");
include("../function/checknull.php");

$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$id = pg_escape_string($_POST["id"]); 
$stapp = pg_escape_string($_POST["stapp"]);

pg_query("BEGIN WORK");
$status = 0;

// หาว่าเป็นคนอนุมัติคนที่เท่าไหร่
$qry_chk_number = pg_query("select \"appvStatus1\", \"appvStatus2\" from account.\"thcap_typePay_acc_temp\" where \"autoID\" = '$id' ");
$appvStatus1 = pg_result($qry_chk_number,0);
$appvStatus2 = pg_result($qry_chk_number,1);
if($appvStatus1 == "9" && $appvStatus2 == "9"){$n = "1";}
elseif($appvStatus1 != "9" && $appvStatus2 == "9"){$n = "2";}
else{$status++;}

if($status == 0)
{
	if($stapp == "0")
	{ // ถ้าไม่อนุมัติ
		$up="UPDATE account.\"thcap_typePay_acc_temp\" SET \"appvID$n\" = '$app_user', \"appvStamp$n\" = '$app_date', \"appvStatus$n\" = '0' where \"autoID\" = '$id' ";
		if($res=pg_query($up)){
		}else{
			$status++;
		}
	}
	elseif($stapp == "1")
	{ // ถ้าอนุมัติรายการ
		$up="UPDATE account.\"thcap_typePay_acc_temp\" SET \"appvID$n\" = '$app_user', \"appvStamp$n\" = '$app_date', \"appvStatus$n\" = '1' where \"autoID\" = '$id' ";
		if($res=pg_query($up)){
		}else{
			$status++;
		}
		
		// ถ้าอนุมัติเป็นคนที่ 2 ให้ insert/update ข้อมูลจริงเลย
		if($n == "2")
		{
			$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_acc_temp\" where \"autoID\" = '$id' ");
			while($res_name=pg_fetch_array($qry_name))
			{
				$tpID = $res_name["tpID"];
				$tpBasis = $res_name["tpBasis"];
				$tpAccrual = $res_name["tpAccrual"];
				$tpAmortize = $res_name["tpAmortize"];
				
				$tpBasis = checknull($tpBasis);
				$tpAccrual = checknull($tpAccrual);
				$tpAmortize = checknull($tpAmortize);
			}
			
			// หาว่าเป็นการแก้ไขหรือเพิ่มใหม่
			$qry_chk_row = pg_query("select * from account.\"thcap_typePay_acc_temp\" where \"tpID\" = '$tpID' ");
			$chk_row = pg_num_rows($qry_chk_row);
			if($chk_row > 0)
			{
				$addOrEdit = "U"; // แก้ไขรายการ
			}
			else
			{
				$addOrEdit = "N"; // เพิ่มรายการใหม่
			}
			
			if($addOrEdit == "U")
			{ // ถ้าเป็นการแก้ไขรายการ
				$in_sql="UPDATE account.\"thcap_typePay_acc\"
						SET	\"tpBasis\" = $tpBasis, 
							\"tpAccrual\" = $tpAccrual, 
							\"tpAmortize\" = $tpAmortize
					WHERE \"tpID\" = '$tpID' ";
				if($result=pg_query($in_sql))
				{}
				else
				{
					$status++;
				}
			}
			else
			{
				$in_sql="INSERT INTO account.\"thcap_typePay_acc\"(\"tpID\", \"tpBasis\", \"tpAccrual\", \"tpAmortize\") VALUES('$tpID', $tpBasis, $tpAccrual, $tpAmortize)";
				if($result=pg_query($in_sql))
				{}
				else
				{
					$status++;
				}
			}
		}
	}
	else
	{
		$status++;
	}
}
	
if($status == 0)
{
	pg_query("COMMIT");
	echo 1;
}
else
{
	pg_query("ROLLBACK");
	echo 2;
}
?>