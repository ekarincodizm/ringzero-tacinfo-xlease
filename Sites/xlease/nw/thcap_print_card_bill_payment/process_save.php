<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$user_id = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$CusFullName = pg_escape_string($_POST["CusFullName"]);
$contractID = pg_escape_string($_POST["contractID"]);
$minPayment = pg_escape_string($_POST["minPayment"]);
$firstDueDate = pg_escape_string($_POST["firstDueDate"]);
$payDay = pg_escape_string($_POST["payDay"]);
$note = pg_escape_string($_POST["note"]); // หมายเหตุ (แสดงใน Card Bill Payment)
$doerNote = pg_escape_string($_POST["doerNote"]); // หมายเหตุ ในการทำรายการ / หมายเหตุรายละเอียดของสัญญา

$CusFullName = checknull($CusFullName);
$contractID = checknull($contractID);
$minPayment = checknull($minPayment);
$firstDueDate = checknull($firstDueDate);
$payDay = checknull($payDay);
$note = checknull($note);
$doerNote = checknull($doerNote);

?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function updateOpener() {
window.opener.document.forms[0].updatelistbox.click();
window.close();
}

function popU(U,N,T) {
	newWindow = window.open(U, N, T);
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

$sql_add = "insert into \"thcap_print_card_bill_payment\"(\"CusFullName\", \"contractID\", \"minPayment\", \"firstDueDate\", \"payDay\", \"note\", \"doerID\", \"doerStamp\", \"doerNote\", \"appvStatus\")
			values($CusFullName, $contractID, $minPayment, $firstDueDate, $payDay, $note, '$user_id', '$add_date', $doerNote, '9')
			returning \"autoID\" ";
if($result_add = pg_query($sql_add))
{
	$autoID = pg_fetch_result($result_add,0);
}
else
{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) พิมพ์ Card Bill Payment', '$add_date')");
	//ACTIONLOG---
	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<form method=\"post\" name=\"form1\" action=\"frm_Index.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\" style=\"cursor:pointer;\" /></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">ผิดพลาด!!</font></h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_Index.php\">";
	echo "<center><input type=\"submit\" value=\"กลับ\" style=\"cursor:pointer;\" /></center></form>";
}
?>