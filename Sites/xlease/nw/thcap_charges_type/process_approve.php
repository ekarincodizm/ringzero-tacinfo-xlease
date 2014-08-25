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
$qry_chk_number = pg_query("select \"appvStatus1\", \"appvStatus2\" from account.\"thcap_typePay_temp\" where \"tpAutoID\" = '$id' ");
$appvStatus1 = pg_result($qry_chk_number,0);
$appvStatus2 = pg_result($qry_chk_number,1);
if($appvStatus1 == "9" && $appvStatus2 == "9"){$n = "1";}
elseif($appvStatus1 != "9" && $appvStatus2 == "9"){$n = "2";}
else{$status++;}

if($status == 0)
{
	if($stapp == "0")
	{ // ถ้าไม่อนุมัติ
		$up="UPDATE account.\"thcap_typePay_temp\" SET \"appvID$n\" = '$app_user', \"appvStamp$n\" = '$app_date', \"appvStatus$n\" = '0' where \"tpAutoID\" = '$id' ";
		if($res=pg_query($up)){
		}else{
			$status++;
		}
	}
	elseif($stapp == "1")
	{ // ถ้าอนุมัติรายการ
		$up="UPDATE account.\"thcap_typePay_temp\" SET \"appvID$n\" = '$app_user', \"appvStamp$n\" = '$app_date', \"appvStatus$n\" = '1' where \"tpAutoID\" = '$id' ";
		if($res=pg_query($up)){
		}else{
			$status++;
		}
		
		// ถ้าอนุมัติเป็นคนที่ 2 ให้ insert/update ข้อมูลจริงเลย
		if($n == "2")
		{
			$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_temp\" where \"tpAutoID\" = '$id' ");
			while($res_name=pg_fetch_array($qry_name))
			{
				$tpID = $res_name["tpID"];
				$tpCompanyID = $res_name["tpCompanyID"];
				$tpConType = $res_name["tpConType"];  
				$tpDesc = $res_name["tpDesc"];
				$tpFullDesc = $res_name["tpFullDesc"];
				$ableB = $res_name["ableB"];
				$ableDiscount = $res_name["ableDiscount"];
				$ableWaive = $res_name["ableWaive"];
				$ableVAT = $res_name["ableVAT"];
				$ableWHT = $res_name["ableWHT"];
				$tpBasis = $res_name["tpBasis"];
				$tpAccrual = $res_name["tpAccrual"];
				$tpAmortize = $res_name["tpAmortize"];
				
				//By Por
				$ableSkip = $res_name["ableSkip"];
				$ablePartial = $res_name["ablePartial"];
				$curWHTRate = $res_name["curWHTRate"];
				$isServices = $res_name["isServices"];
				$tpSort = $res_name["tpSort"];
				$tpType = trim($res_name["tpType"]); // เงื่อนไขในการเก็บ
				$tpRanking = $res_name["tpRanking"];
				//End By Por
				
				//By Boz (เลียนแบบข้างบน)
				$whoSeen = $res_name["whoSeen"]; //ALL-เปิดให้เห็นทุกส่วนงาน
				$tpRefType = trim($res_name["tpRefType"]); //รูปแบบ Ref
				$isSubsti = $res_name["isSubsti"]; //substitutional - รับแทน เช่น รับแทนค่าประกัน
				$isLeasing = $res_name["isLeasing"];
				//End By Boz
					
				$curSBTRate= $res_name["curSBTRate"];
				$isLockedVat= $res_name["isLockedVat"];
				$ableInvoice= $res_name["ableInvoice"];
				$curLTRate= $res_name["curLTRate"];
			}
			
			if($curWHTRate==""){ 
				$curWHTRate3="null";
			}else{
				$curWHTRate3="'$curWHTRate'"; 
			}
			$tpSort = checknull($tpSort);
			
			$curSBTRate = checknull($curSBTRate);
			$curLTRate = checknull($curLTRate);
			$isLeasing = checknull($isLeasing);
			
			$tpBasis = checknull($tpBasis);
			$tpAccrual = checknull($tpAccrual);
			$tpAmortize = checknull($tpAmortize);
			
			// จัดการประเภทค่าใช้จ่าย
			$qry_chk_row = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$tpID' ");
			$chk_row = pg_num_rows($qry_chk_row);
			if($chk_row > 0)
			{ // ถ้าเป็นการแก้ไขรายการ
				$in_sql="UPDATE account.\"thcap_typePay\"
						SET	\"tpCompanyID\" = '$tpCompanyID', 
							\"tpConType\" = '$tpConType', 
							\"tpDesc\" = '$tpDesc', 
							\"tpFullDesc\" = '$tpFullDesc' , 
							\"ableB\" = '$ableB',
							\"ableDiscount\" = '$ableDiscount', 
							\"ableWaive\" = '$ableWaive',
							\"ableVAT\" = '$ableVAT', 
							\"ableWHT\" = '$ableWHT',
							\"ableSkip\"='$ableSkip',
							\"ablePartial\"='$ablePartial',
							\"curWHTRate\"=$curWHTRate3,
							\"isServices\"='$isServices',
							\"tpSort\"=$tpSort,
							\"tpType\"='$tpType',
							\"tpRanking\"='$tpRanking',
							\"whoSeen\" = '$whoSeen',
							\"tpRefType\" = '$tpRefType',
							\"isSubsti\" = '$isSubsti',
							\"isLeasing\" =  $isLeasing,
							\"curSBTRate\" = $curSBTRate,
							\"isLockedVat\" = '$isLockedVat',
							\"ableInvoice\"= '$ableInvoice',
							\"curLTRate\" = $curLTRate
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
				$in_sql="INSERT INTO account.\"thcap_typePay\"(\"tpID\", \"tpCompanyID\", \"tpConType\", \"tpDesc\", \"tpFullDesc\", \"ableB\", \"ableDiscount\", \"ableWaive\",
							\"ableVAT\", \"ableWHT\", \"ableSkip\", \"ablePartial\", \"curWHTRate\", \"isServices\", \"tpSort\", \"tpType\", \"tpRanking\", \"whoSeen\", \"tpRefType\",
							\"isSubsti\", \"isLeasing\", \"curSBTRate\", \"isLockedVat\", \"ableInvoice\", \"curLTRate\")
						VALUES('$tpID', '$tpCompanyID', '$tpConType', '$tpDesc', '$tpFullDesc', '$ableB', '$ableDiscount', '$ableWaive', '$ableVAT', '$ableWHT', '$ableSkip',
							'$ablePartial', $curWHTRate3, '$isServices', $tpSort, '$tpType', '$tpRanking', '$whoSeen', '$tpRefType', '$isSubsti', $isLeasing, $curSBTRate, '$isLockedVat',
							'$ableInvoice', $curLTRate)";
				if($result=pg_query($in_sql))
				{}
				else
				{
					$status++;
				}
			}
			
			// จัดการความสัมพันธ์ทางบัญชี
			$qry_chk_row = pg_query("select * from account.\"thcap_typePay_acc\" where \"tpID\" = '$tpID' ");
			$chk_row = pg_num_rows($qry_chk_row);
			if($chk_row > 0)
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
					echo "$in_sql";
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
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(THCAP) อนุมัติจัดการประเภทค่าใช้จ่าย', '$app_date')");
	//ACTIONLOG---
	
	echo 1;
}
else
{
	pg_query("ROLLBACK");
	echo 2;
}
?>