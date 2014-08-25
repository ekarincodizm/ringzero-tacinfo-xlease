<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$newDoc = pg_escape_string($_POST['newDoc']);
$conType = pg_escape_string($_POST['conType']);
$reason = pg_escape_string($_POST['reason']);
$countEdit = $_POST['countEdit'];
$configID = pg_escape_string($_POST['configID']);
$useable = $_POST['useable'];
$conType = pg_escape_string($_POST['conType']);
$autoid = pg_escape_string($_POST['autoID']);
$doc_Ranking = pg_escape_string($_POST['doc_Ranking']);
$status=0;

	pg_query("Begin");
	if(isset($_POST['appv'])){
		if($countEdit!=0){
			//อนุมัติแก้ไขข้อมูล
			$qry_temp = "update thcap_contract_doc_config_temp 
					set \"doc_appvID\" = '$user_id',\"doc_appvStamp\" = '$datenow',doc_status_appv = '1' 
					where \"doc_autoID\" ='$autoid'";
			
			$qry_real = "update thcap_contract_doc_config 
								set \"doc_docName\"='$newDoc',\"doc_statusDoc\"='$useable',\"doc_Ranking\"='$doc_Ranking'
								where \"doc_ConfigID\"='$configID'  ";
			
				if(pg_query($qry_temp)){
				} else {
					$status++;
				}
				if(pg_query($qry_real)){
				} else {
					$status++;
				}
		
		}else{ 
			// อนุมัติเพิ่มข้อมูล
			
			$qry_real = "insert into thcap_contract_doc_config 
						(\"doc_conTypeName\",\"doc_docName\",\"doc_statusDoc\",\"doc_Ranking\")
						values ('$conType','$newDoc','$useable','$doc_Ranking')
						returning \"doc_ConfigID\" ";
			
				if($doc_Con=pg_fetch_result(pg_query($qry_real),0)){
				
					$qry_temp = "update thcap_contract_doc_config_temp 
						set \"doc_appvID\"='$user_id',\"doc_appvStamp\"='$datenow',doc_status_appv='1',\"doc_ConfigID\"='$doc_Con' 
						where \"doc_autoID\"='$autoid'";
						
						if(pg_query($qry_temp)){
						} else {
							$status++;
						}
						
				} else {
					$status++;
				}
				
				
		}	
	} else if(isset($_POST['notappv'])){
			
			$qry_temp = "update thcap_contract_doc_config_temp 
						set \"doc_appvID\"='$user_id',\"doc_appvStamp\"='$datenow',doc_status_appv='0' 
						where \"doc_autoID\"='$autoid'";
	}
	
		
		if(pg_query($qry_temp)){
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