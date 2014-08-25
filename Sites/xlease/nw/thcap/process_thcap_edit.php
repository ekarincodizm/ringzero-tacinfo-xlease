<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
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
$doerRemark = pg_escape_string($_POST["doerRemark"]); // หมายเหตุ

//By Por
$ableSkip = pg_escape_string($_POST["ableSkip"]);
$ablePartial = pg_escape_string($_POST["ablePartial"]);
$curWHTRate = pg_escape_string($_POST["curWHTRate"]); 
if($curWHTRate==""){ 
	$curWHTRate3="null";
}else{
	$curWHTRate3="'$curWHTRate'"; 
}
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
	
// ความสัมพันธ์ทางบัญชี
$tpBasis = pg_escape_string($_POST["tpBasis"]);
$tpAccrual = pg_escape_string($_POST["tpAccrual"]);
$tpAmortize = pg_escape_string($_POST["tpAmortize"]);

// แยกข้อมูลเอาแต่รหัส
$tpBasis_explode = explode("#", $tpBasis); $tpBasis = $tpBasis_explode[0];
$tpAccrual_explode = explode("#", $tpAccrual); $tpAccrual = $tpAccrual_explode[0];
$tpAmortize_explode = explode("#", $tpAmortize); $tpAmortize = $tpAmortize_explode[0];

// เช็คค่าว่าง
$tpBasis = checknull($tpBasis);
$tpAccrual = checknull($tpAccrual);
$tpAmortize = checknull($tpAmortize);

$doerRemark = checknull($doerRemark);

if($ableB!=""){$ableB=1;} else{$ableB=0;}
if($ableDiscount!=""){$ableDiscount=1;} else{$ableDiscount=0;}
if($ableWaive!=""){$ableWaive=1;} else{$ableWaive=0;}
if($ableVAT!=""){$ableVAT=1;} else{$ableVAT=0;}
if($ableWHT!=""){$ableWHT=1;} else{$ableWHT=0;}
if($isLockedVat!=""){$isLockedVat=1;} else{$isLockedVat=0;}
if($ableInvoice!=""){$ableInvoice=1;} else{$ableInvoice=0;}

pg_query("BEGIN WORK");
$status = 0;

// ตรวจสอบก่อนว่า รหัสค่าใช้จ่ายดังกล่าวอยู่ระหว่างรออนุมัติหรือไม่
$chk_sql = pg_query("select * from account.\"thcap_typePay_temp\" where \"tpID\" = '$tpID' and (\"appvStatus1\" = '9' or \"appvStatus2\" = '9') and \"appvStatus1\" <> '0' and \"appvStatus2\" <> '0' ");
$chk_row = pg_num_rows($chk_sql);
if($chk_row > 0)
{
	$status++;
	$error = "มีรหัสประเภทค่าใช้จ่าย $tpID อยู่ระหว่างรออนุมัติอยู่แล้ว";
}
else
{
	$in_sql = "insert into account.\"thcap_typePay_temp\"(\"tpID\", \"tpCompanyID\", \"tpConType\", \"tpDesc\", \"tpFullDesc\", \"ableB\", \"ableDiscount\", \"ableWaive\", \"ableVAT\",
					\"ableWHT\", \"ableSkip\", \"ablePartial\", \"curWHTRate\", \"isServices\", \"tpSort\", \"tpType\", \"tpRanking\", \"whoSeen\", \"tpRefType\", \"isSubsti\",
					\"isLeasing\", \"curSBTRate\", \"isLockedVat\", \"ableInvoice\", \"curLTRate\", \"doerID\", \"doerStamp\", \"appvStatus1\", \"appvStatus2\",
					\"tpBasis\", \"tpAccrual\", \"tpAmortize\", \"doerRemark\")
				values('$tpID', '$tpCompanyID', '$tpConType', '$tpDesc', '$tpFullDesc', '$ableB', '$ableDiscount', '$ableWaive', '$ableVAT', '$ableWHT', '$ableSkip', '$ablePartial',
					$curWHTRate3, '$isServices', $tpSort, '$tpType', '$tpRanking', '$whoSeen', '$tpRefType', '$isSubsti', $isLeasing, $curSBTRate, '$isLockedVat', '$ableInvoice',
					$curLTRate, '$user_id', '$add_date', '9', '9', $tpBasis, $tpAccrual, $tpAmortize, $doerRemark)";

	if($result=pg_query($in_sql))
	{}
	else
	{
		$status++;
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) แก้ไขประเภทค่าใช้จ่าย', '$add_date')");
	//ACTIONLOG---
	
	echo "<center><h2>บันทึกเรียบร้อย</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"location.href='frm_thcap_show.php'\" /> </center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">การบันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	if($error != ""){echo "<center><h2><font color=\"#FF0000\">$error</font></h2></center>";}
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_thcap_show.php\">";
	echo "<input type=\"hidden\" name=\"tpID2\" value=\"$tpID\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	//echo $in_sql;
}

?>