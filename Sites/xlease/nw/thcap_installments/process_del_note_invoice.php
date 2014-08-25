<?php
session_start();
include("../../config/config.php");
include('../function/checknull.php');

$noteID = $_POST["noteID"];
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

$sql_up = "update public.\"thcap_contract_note\"
			set \"appvID\" = '$user_id', \"appvStamp\" = '$add_date', \"Approved\" = 'FALSE', \"cancel\" = 'TRUE'
			where \"noteID\" = '$noteID' ";
if($result_up = pg_query($sql_up))
{}
else
{
	$status++;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) ลบหมายเหตุการวางบิล/ใบแจ้งหนี้', '$add_date')");
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
	echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
	/*echo "<form method=\"post\" name=\"form2\" action=\"frm_del_note_invoice.php\">";
	echo "<input type=\"hidden\" name=\"noteID\" value=\"$noteID\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";*/
}
?>