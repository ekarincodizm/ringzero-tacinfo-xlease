<?php
include("../../config/config.php");
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
$debtID = pg_escape_string($_POST["debtID"]); // รหัสหนี้
$insurer_id = pg_escape_string($_POST["insurer_id"]); // รหัสบริษัทประกันภัย
$policyNo = pg_escape_string($_POST["policyNo"]); // เลขที่กรมธรรม์
$payAmt = pg_escape_string($_POST["payAmt"]); // จำนวนเงินที่ชำระ
$user_id = $_SESSION["av_iduser"]; // รหัสพนักงาน
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>

<script type="text/javascript">
	function RefreshMe()
	{
		opener.location.reload(true);
		self.close();
	}

	function updateOpener()
	{
		window.opener.document.forms[0].btn00.click();
		window.close();
	}
</script>

<form name="form">
<input type="hidden" name="industry" id="industry" value="<?php echo $industry; ?>">
</form>

<?php
pg_query("BEGIN");
$status = 0;

$query_chk = pg_query("select * from public.\"thcap_pay_insurer\" where \"debtID\" = '$debtID' ");
$row_chk = pg_num_rows($query_chk);
if($row_chk > 0)
{
	$status++;
	$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
}
else
{
	$sql_add = "insert into public.\"thcap_pay_insurer\"(\"debtID\", \"insurer_id\", \"policyNo\", \"payAmt\", \"doerID\", \"doerStamp\")
				values('$debtID', '$insurer_id', '$policyNo', '$payAmt', '$user_id', '$add_date') ";
	if($result_add = pg_query($sql_add))
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
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) บันทึกจ่ายเบี้ยประกันภัย', '$add_date')");
	//ACTIONLOG---
	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:updateOpener();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">ผิดพลาด $error!!</font></h2></center>";
	echo "<form method=\"get\" name=\"form2\" action=\"popup_transection.php\">";
	echo "<input type=\"hidden\" name=\"debtID\" value=\"$debtID\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>