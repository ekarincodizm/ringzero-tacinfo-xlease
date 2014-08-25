<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$autoid = $_POST['up_autoID'];
$status=0;
	//กำหนดสถานะการใช้งาน
	if($useable==""){
		$doc_status = 0;
	} else {
		$doc_status = 1;
	}
	pg_query("Begin");
	if(isset($_POST['appv'])){
		
			//ตรวจสอบเอกสารถูกต้อง
			$qry_temp = "update thcap_upload_document 
					set \"up_appvID\" = '$user_id',\"up_appvStamp\" = '$datenow',\"Approved\" = '1' 
					where \"up_autoID\" ='$autoid'";
		
			
				if(pg_query($qry_temp)){
				} else {
					$status++;
				}
				
			
	} else if(isset($_POST['notappv'])){
			//ตรวจสอบเอกสารไม่ถูกต้อง
			$qry_temp = "update thcap_upload_document 
					set \"up_appvID\" = '$user_id',\"up_appvStamp\" = '$datenow',\"Approved\" = '0' 
					where \"up_autoID\" ='$autoid'";
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