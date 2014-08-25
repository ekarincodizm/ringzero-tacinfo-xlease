<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$tpID = pg_escape_string($_POST["tpID"]);
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

pg_query("BEGIN WORK");
$status = 0;

	$in_sql = "insert into account.\"thcap_typePay_acc_temp\"(\"tpID\", \"tpBasis\", \"tpAccrual\", \"tpAmortize\", \"doerID\", \"doerStamp\", \"appvStatus1\", \"appvStatus2\")
				values('$tpID', $tpBasis, $tpAccrual, $tpAmortize, '$user_id', '$add_date', '9', '9')";

if($result=pg_query($in_sql))
{}
else
{
	$status++;
	echo $in_sql;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) แก้ไขประเภทค่าใช้จ่าย', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>การแก้ไขสมบูรณ์</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"location.href='frm_thcap_show.php'\" /> </center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>การแก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_thcap_showtab.php\">";
	echo "<input type=\"hidden\" name=\"tpID2\" value=\"$tpID\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	//echo $in_sql;
}

?>