<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<?php

include('../../config/config.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$autoID = pg_escape_string($_POST["auto_id"]);
$status=0;

pg_query("Begin");

if(isset($_POST["Appv"])){
	$statusAppv = "1";
} else if(isset($_POST["NotAppv"])){
	$statusAppv = "0";
}
$qry_update="update thcap_contract_waive_fa_chqguaranteed 
set \"statusCon\"='$statusAppv',waive_app_user='$user_id',waive_app_stamp='$datenow'
where waive_auto_id='$autoID' "; 
						
	if(pg_query($qry_update)){
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