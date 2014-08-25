<?php
include('../config/config.php');

$id_tranpay = pg_escape_string($_POST["id_tranpay"]);
$note = pg_escape_string($_POST["note"]);

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();
?>

<script language="JavaScript" type="text/javascript">
function mainReload()
{
	opener.location.reload(true);
	self.close();
}
</script>

<?php
pg_query("BEGIN WORK");
$status = 0;

$qry_requestDel = "INSERT INTO \"TranPay_Request_Cancel\"(\"id_tranpay\", \"doerID\", \"doerStamp\", \"reason\", \"Approved\")
							values('$id_tranpay', '$id_user', '$logs_any_time', '$note', '9')";
if($resultD=pg_query($qry_requestDel)){}else{$status++;}

if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<br><center><h2><font color=\"#0000FF\">บันทึกสมบูรณ์</font></h2></center>";
	echo "<br><br><center><input type=\"button\" value=\"ตกลง\" onClick=\"mainReload();\"><center>";
}
else
{
	pg_query("ROLLBACK");
	
	echo "<br><center><h2><font color=\"#FF0000\">บันทึกผิดพลาด!!</font></h2></center>";
	echo "<br><br><center><input type=\"button\" value=\"ตกลง\" onClick=\"mainReload();\"><center>";
}
?>