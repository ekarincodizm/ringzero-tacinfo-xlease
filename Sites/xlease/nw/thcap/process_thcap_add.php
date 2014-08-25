<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$tpID = pg_escape_string($_POST["tpID"]);
$tpCompanyID = pg_escape_string($_POST["tpCompanyID"]);
$tpConType = pg_escape_string($_POST["tpConType"]);
$tpDesc = pg_escape_string($_POST["tpDesc"]);
$tpFullDesc = pg_escape_string($_POST["tpFullDesc"]);
$ableB = pg_escape_string($_POST["ableB"]);
$ableDiscount = pg_escape_string($_POST["ableDiscount"]);
$ableWaive = pg_escape_string($_POST["ableWaive"]);
$ableVAT = pg_escape_string($_POST["ableVAT"]);
$ableWHT = pg_escape_string($_POST["ableWHT"]);

//By Por
$ableSkip = pg_escape_string($_POST["ableSkip"]);
$ablePartial = pg_escape_string($_POST["ablePartial"]);
$curWHTRate = pg_escape_string($_POST["curWHTRate"]);if($curWHTRate==""){ $curWHTRate3="null";}else{$curWHTRate3="'$curWHTRate'"; }
$isServices = pg_escape_string($_POST["isServices"]);
$tpSort = checknull(pg_escape_string($_POST["tpSort"]));
$tpType= pg_escape_string($_POST["tpType"]);
$tpRanking= pg_escape_string($_POST["tpRanking"]);

$curSBTRate= checknull(pg_escape_string($_POST["curSBTRate"]));
$isLockedVat= pg_escape_string($_POST["isLockedVat"]);
$ableInvoice= pg_escape_string($_POST["ableInvoice"]);
$curLTRate= checknull(pg_escape_string($_POST["curLTRate"]));

if($ableSkip!=""){$ableSkip=1;} else{$ableSkip=0;}
if($ablePartial!=""){$ablePartial=1;} else{$ablePartial=0;}
//End By Por

//By Boz (เลียนแบบข้างบน)
	$whoSeen = pg_escape_string($_POST["whoSeen"]); //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType = trim(pg_escape_string($_POST["tpRefType"])); //รูปแบบ Ref
	$isSubsti = pg_escape_string($_POST["isSubsti"]); //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing = checknull(pg_escape_string($_POST["isLeasing"]));

if($ableB!=""){$ableB=1;} else{$ableB=0;}
if($ableDiscount!=""){$ableDiscount=1;} else{$ableDiscount=0;}
if($ableWaive!=""){$ableWaive=1;} else{$ableWaive=0;}
if($ableVAT!=""){$ableVAT=1;} else{$ableVAT=0;}
if($ableWHT!=""){$ableWHT=1;} else{$ableWHT=0;}
if($isLockedVat!=""){$isLockedVat=1;} else{$isLockedVat=0;}
if($ableInvoice!=""){$ableInvoice=1;} else{$ableInvoice=0;}
pg_query("BEGIN WORK");
$status = 0;

$in_sql="	INSERT INTO account.\"thcap_typePay\" 
							(	\"tpID\",
								\"tpCompanyID\",
								\"tpConType\",
								\"tpDesc\",
								\"tpFullDesc\",
								\"ableB\",
								\"ableDiscount\",
								\"ableWaive\",
								\"ableVAT\",
								\"ableWHT\",
								\"ableSkip\",
								\"ablePartial\",
								\"curWHTRate\",
								\"isServices\",
								\"tpSort\",
								\"tpType\",
								\"tpRanking\",
								\"whoSeen\",
								\"tpRefType\",
								\"isSubsti\",
								\"isLeasing\",
								\"curSBTRate\",
								\"isLockedVat\",
								\"ableInvoice\",
								\"curLTRate\"
							) 
					VALUES(		'$tpID',
								'$tpCompanyID',
								'$tpConType',
								'$tpDesc',
								'$tpFullDesc',
								'$ableB',
								'$ableDiscount',
								'$ableWaive',
								'$ableVAT',
								'$ableWHT',
								'$ableSkip',
								'$ablePartial',
								$curWHTRate3,
								'$isServices',
								$tpSort,
								'$tpType',
								'$tpRanking',
								'$whoSeen',
								'$tpRefType',
								'$isSubsti',
								$isLeasing,
								$curSBTRate,
								'$isLockedVat',
								'$ableInvoice',
								$curLTRate
						 )";

if($result=pg_query($in_sql))
{}
else
{
	$status++;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) เพิ่มประเภทค่าใช้จ่าย', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>บันทึกสมบูรณ์</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<center><input type=\"submit\" value=\"ตกลง\" onclick=\"location.href='frm_thcap_show.php'\" /></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_thcap_add.php\">";
	echo "<input type=\"hidden\" name=\"tpID2\" value=\"$tpID\">";
	echo "<input type=\"hidden\" name=\"tpCompanyID2\" value=\"$tpCompanyID\">";
	echo "<input type=\"hidden\" name=\"tpConType2\" value=\"$tpConType\">";
	echo "<input type=\"hidden\" name=\"tpDesc2\" value=\"$tpDesc\">";
	echo "<input type=\"hidden\" name=\"tpFullDesc2\" value=\"$tpFullDesc\">";
	echo "<input type=\"hidden\" name=\"ableB2\" value=\"$ableB\">";
	echo "<input type=\"hidden\" name=\"ableDiscount2\" value=\"$ableDiscount\">";
	echo "<input type=\"hidden\" name=\"ableWaive2\" value=\"$ableWaive\">";
	echo "<input type=\"hidden\" name=\"ableVAT2\" value=\"$ableVAT\">";
	echo "<input type=\"hidden\" name=\"ableWHT2\" value=\"$ableWHT\">";
	echo "<input type=\"hidden\" name=\"tpSort2\" value=\"$tpSort\">";
	echo "<input type=\"hidden\" name=\"ableSkip2\" value=\"$ableSkip\">";
	echo "<input type=\"hidden\" name=\"ablePartial2\" value=\"$ablablePartialeWHT\">";
	echo "<input type=\"hidden\" name=\"curWHTRate2\" value=\"$curWHTRate\">";
	echo "<input type=\"hidden\" name=\"isServices2\" value=\"$isServices\">";
	echo "<input type=\"hidden\" name=\"tpType2\" value=\"$tpType\">";
	echo "<input type=\"hidden\" name=\"tpRanking2\" value=\"$tpRanking\">";
	echo "<input type=\"hidden\" name=\"whoSeen2\" value=\"$whoSeen\">";
	echo "<input type=\"hidden\" name=\"tpRefType2\" value=\"$tpRefType\">";
	echo "<input type=\"hidden\" name=\"isSubsti2\" value=\"$isSubsti\">";
	echo "<input type=\"hidden\" name=\"isLeasing2\" value=\"$isLeasing\">";
	echo "<input type=\"hidden\" name=\"curSBTRate2\" value=\"$curSBTRate\">";
	echo "<input type=\"hidden\" name=\"isLockedVat2\" value=\"$isLockedVat\">";
	echo "<input type=\"hidden\" name=\"ableInvoice2\" value=\"$ableInvoice\">";
	echo "<input type=\"hidden\" name=\"curLTRate2\" value=\"$curLTRate\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}

?>