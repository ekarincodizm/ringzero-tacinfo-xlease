<?php
include("../../config/config.php");
include("../function/checknull.php");

$abh_autoid = pg_escape_string($_POST["abh_autoid"]);
$remark = pg_escape_string($_POST["remark"]);

$id_user = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime();
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่ามีการเพิ่มข้อมูล whitelist แล้วหรือยัง
$qry_chk = pg_query("select * from thcap_check_acc_debit_credit_amt_data_whitelist where \"abh_autoid\" = '$abh_autoid' ");
$row_chk = pg_num_rows($qry_chk);
if($row_chk > 0)
{
	$status++;
	$error = "มีการเพิ่มรายการนี้เข้าไปใน whitelist ก่อนหน้านี้แล้ว";
}

//----- เพิ่ม whitelist
if($status == 0)
{
	$qry_whitelist = "insert into thcap_check_acc_debit_credit_amt_data_whitelist(\"abh_autoid\", \"remark\", \"doerID\", \"doerStamp\")
						values('$abh_autoid', '$remark', '$id_user', '$nowDateTime'); ";

	if($result_whitelist = pg_query($qry_whitelist)){
	}else{
		$status++;
	}
}
//----- จบการเพิ่ม whitelist

// ตรวจสอบความสอดคล้องของยอดเงิน Debit / Credit ทางบัญชี ใหม่
if($status == 0)
{
	$qry_thcap_check_acc_debit_credit_amt = "select thcap_check_acc_debit_credit_amt();";
	if($result_thcap_check_acc_debit_credit_amt = pg_query($qry_thcap_check_acc_debit_credit_amt)){
	}else{
		$status++;
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h1><font color=\"#0000FF\">เพิ่ม whitelist สำเร็จ</font></h1></center>";
	echo "<br><center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h1><font color=\"#FF0000\">เกิดข้อผิดพลาด!!</font></h1></center>";
	if($error != ""){echo "<br><center><font color=\"#FF0000\">$error</font></center><br>";}
	echo "<br><center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>