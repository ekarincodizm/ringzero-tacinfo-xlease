<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$coneditID = $_POST["coneditID"];
$contractidsend = $_POST["contractidsend"];
$status = 0;

pg_query("BEGIN");

	$qry_in = pg_query("	UPDATE \"thcap_contract_edit\"
							SET  	\"status_edit\" = '1', 
									\"user_do\" = '$id_user', 
									\"user_do_datetime\" = LOCALTIMESTAMP(0), 
									\"status_app\" = '0'
							WHERE 	\"coneditID\" = '$coneditID' ");
	IF($qry_in){}else{ $status++; }

if($status == 0)
{
	pg_query("COMMIT");
	
	
	echo "<script type='text/javascript'>alert('แก้ไขสำเร็จรอการตรวจสอบ ')</script>";
	echo "<script type=\"text/javascript\"> 
				opener.location.reload(true);
				self.close();
		  </script>	";
	exit();
	
}
else
{
	
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_show_data.php?hdcontractid=$contractidsend\">";
	echo "<script type='text/javascript'>alert('เกิดข้อผิดพลาดไม่สามารถบันทึกการแก้ไขได้')</script>";
	echo "Error Save [".$strSQL."]";
}	
?>