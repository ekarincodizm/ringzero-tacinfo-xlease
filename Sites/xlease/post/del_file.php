<?php
session_start();
include("../config/config.php");?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$id_user = $_SESSION["av_iduser"];
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$ID = pg_escape_string($_GET['fpicID']);
$IDno = pg_escape_string($_GET['idno']);
$status = 0;
pg_query("BEGIN");
$sql = "UPDATE \"Fp_document_pic\"
   SET \"status\"=1, \"id_user_delete\"='$id_user',\"date_delete\"='$date'
 WHERE \"fpicID\" = '$ID'";

$query=pg_query($sql);

	if($query){}
	else{
		$status++;
		}
			if($status == 0){
			
				pg_query("COMMIT");
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=tagvatmeter.php?idno=$IDno\">";
				echo "<script type='text/javascript'>alert(' ลบการเพิ่มเอกสารเรียบร้อย ')</script>";
			
			}else{
				pg_query("ROLLBACK");
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=tagvatmeter.php?idno=$IDno\">";
				echo "<script type='text/javascript'>alert(' ไม่สามารถลบเอกสารนี้ได้ กรุณาลองใหม่ในภายหลัง ')</script>";
				echo $sql;
			}

?>