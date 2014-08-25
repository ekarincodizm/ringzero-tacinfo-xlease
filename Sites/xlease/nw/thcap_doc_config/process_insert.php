<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$newDoc = pg_escape_string($_POST['newDoc']);
$conType = pg_escape_string($_POST['conType']);
$reason = pg_escape_string($_POST['reason']);
$useable = $_POST['useable'];
$doc_Ranking = pg_escape_string($_POST['doc_Ranking']);
$status=0;
		
	pg_query("Begin");
		$qry_ins = ("insert into thcap_contract_doc_config_temp (\"doc_ConfigID\",\"doc_conTypeName\",\"doc_docName\",\"doc_statusDoc\",\"doc_doerID\",\"doc_doerStamp\",doc_note,doc_status_appv,doc_count_edit,\"doc_Ranking\")
						values ('0','$conType','$newDoc','$useable','$user_id','$datenow','$reason','2','0','$doc_Ranking') ");
		
		if(pg_query($qry_ins)){
		} else {
			$status++;
		}			
	if($status == 0){
	pg_query("COMMIT");
	$alert="บันทึกข้อมูลสำเร็จแล้ว";
	}else{
	pg_query("ROLLBACK");
	$alert="บันทึกข้อมูลล้มเหลว";
	}
?>
<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<html>
	<form>
		<center>
			<H1><?php echo $alert ?></H1><br>
			<input type="submit" name="OK" value="OK" onclick="refres();">
		</center>
	</form>
</html>