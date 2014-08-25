<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_POST['contractID']);
$autoID = pg_escape_string($_POST['waive_auto_id']);
$reason = pg_escape_string($_POST['reason']);
$status=0;
$resResult = checknull($reason);
pg_query("Begin");

$qry_ins="insert into thcap_contract_waive_fa_chqguaranteed (\"contractID\",\"statusCon\",waive_add_user,waive_add_stamp,waive_reason) 
						values ('$contractID','2','$user_id','$datenow',$resResult) ";
						
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
<html>
	<form action="frm_Request.php" method="post">
		<center>
			<H1><?php echo $alert ?></H1><br>
			<input type="submit" name="OK" value="OK" onclick="refres();">
		</center>
	</form>
</html>