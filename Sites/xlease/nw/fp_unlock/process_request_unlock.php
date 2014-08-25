<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$id_no = pg_escape_string($_GET["idnoget"]);
$sslock = pg_escape_string($_GET["stalock"]);
$fscarnum = pg_escape_string($_GET["fcarnum"]);
$fscusid = pg_escape_string($_GET["fcusnum"]);
$n_asid = pg_escape_string($_GET["fass_id"]);
$doerRemark = pg_escape_string($_GET["doerRemark"]);

$doerRemark = str_replace("<br>","\n",$doerRemark);
$doerRemark = checknull($doerRemark);
	
pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่า มีการรออนุมัติอยู่หรือไม่
$qry_chk = pg_query("select * from \"Fp_unlock\" where \"IDNO\" = '$id_no' and \"appvStatus\" = '9' ");
$row_chk = pg_num_rows($qry_chk);

if($row_chk > 0)
{
	$status++;
	$error = "สัญญา $id_no อยู่ระหว่างรอการอนุมัติ";
}

if($status == 0)
{
	$in_fp = "insert into \"Fp_unlock\"(\"IDNO\", \"doerID\", \"doerStamp\", \"doerRemark\", \"appvStatus\") values('$id_no', '$user_id', '$add_date', $doerRemark, '9') "; 
	if($result_unlock=pg_query($in_fp))
	{
		$stat_Fp = "OK update at Fn ".$in_fp;
		$res_up = "ขอปลด Lock ข้อมูล $idno แล้ว";
	}
	else
	{
		$stat_Fp = "error update  Fn Re ".$in_fp;
		$status++;
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', 'ขอปลดล็อกสัญญาเช่าซื้อ ', '$add_date')");
	//ACTIONLOG---
	
	echo $res_up." บันทึกข้อมูลเรียบร้อย";
}
else
{
	pg_query("ROLLBACK");
	echo "มีข้อผิดพลาดในการบันทึก $error";
}
?>