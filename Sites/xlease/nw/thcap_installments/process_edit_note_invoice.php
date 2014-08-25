<?php
session_start();
include("../../config/config.php");
include('../function/checknull.php');

$contractID = pg_escape_string($_POST["contractID"]);
$noteDatail = pg_escape_string(_POST["noteDatail"]);
$radio1 = pg_escape_string($_POST["radio1"]);
$user_id = $_SESSION["av_iduser"];
$add_date = nowDateTime();
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function updateOpener() {
window.opener.document.forms[0].updatelistbox.click();
window.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

$noteDatail = checknull($noteDatail);

$sql_add = "insert into public.\"thcap_contract_note\"(\"contractID\", \"noteType\", \"noteDetail\", \"doerID\", \"doerStamp\", \"appvID\", \"appvStamp\", \"Approved\")
			values('$contractID', '1', $noteDatail, '$user_id', '$add_date', '000', '$add_date', 'TRUE') ";
if($result_add = pg_query($sql_add))
{}
else
{
	$status++;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) แก้ไขหมายเหตุการวางบิล/ใบแจ้งหนี้', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	
	$noteDatail = str_replace("'","",$noteDatail);
	
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_edit_note_invoice.php\">";
	echo "<input type=\"hidden\" name=\"noteDatail\" value=\"$noteDatail\">";
	echo "<input type=\"hidden\" name=\"contractID\" value=\"$contractID\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>