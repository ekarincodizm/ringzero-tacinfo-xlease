<?php
include('../../config/config.php');

$appid = $_POST['yes'];
$status = 0;

$y = $_POST['check'];

if($y == 'Yes'){
pg_query("BEGIN");

	for($i=0;$i<sizeof($appid);$i++){


		$app = pg_query("select \"threceiptID\" from \"approve_thcap_mg_3dreceipt\"  where \"appreceiptID\" = $appid[$i]");
		$appquery = pg_fetch_array($app);
		$threID = $appquery['threceiptID'];
	

			$sql = "insert into \"thcap_mg_3dreceipt\" select * from \"temp_thcap_mg_3dreceipt\" where \"threceiptID\" = '$threID'";
			$insert = pg_query($sql);
			
					if($insert){									
					}else{
						$status++;									
					}
	
							if($status == 0){
								$strSQL = "UPDATE approve_thcap_mg_3dreceipt SET  status=1 WHERE \"appreceiptID\" = $appid[$i] ";

								$objQuery = pg_query($strSQL);
								
								if($objQuery){
								}else{
									$status++;				
								}
							}else{
								break;								
							}
	}
	if($status == 0){

					pg_query("COMMIT");
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
					echo "<script type='text/javascript'>alert(' การอนุมัติเสร็จสิ้นเรียบร้อย ')</script>";
					exit();

	}else{

								pg_query("ROLLBACK");
								echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
								echo "<script type='text/javascript'>alert('ไม่สามารถอนุมัติได้ โปรดลองใหม่ในภายหลัง')</script>";
								
								exit();

	}
}else if($y == 'No'){
	
	for($i=0;$i<sizeof($appid);$i++){


		$app = pg_query("select \"threceiptID\" from \"approve_thcap_mg_3dreceipt\"  where \"appreceiptID\" = $appid[$i]");
		$appquery = pg_fetch_array($app);
		$threID = $appquery['threceiptID'];
	


			$sql = "DELETE FROM \"temp_thcap_mg_3dreceipt\" WHERE  \"threceiptID\" = '$threID'";
			$insert = pg_query($sql);
			
					if($insert){									
					}else{
						$status++;									
					}
	
							if($status == 0){
								$strSQL = "UPDATE approve_thcap_mg_3dreceipt SET  status = 2 WHERE \"appreceiptID\" = $appid[$i] ";

								$objQuery = pg_query($strSQL);
								
								if($objQuery){
								}else{
									$status++;				
								}
							}else{
								break;								
							}
	}
	if($status == 0){

					pg_query("COMMIT");
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
					echo "<script type='text/javascript'>alert(' ปฎิเสธการอนุมัติเรียบร้อย ')</script>";
					exit();

	}else{

								pg_query("ROLLBACK");
								echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
								echo "<script type='text/javascript'>alert('ไม่สามารถปฎิเสธการอนุมัติได้ โปรดลองใหม่ในภายหลัง')</script>";
								
								exit();

	}

}else{
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
		echo "<script type='text/javascript'>alert(' ไม่พบการเลือกการนุมัติหรือปฎิเสธ กรุณาลองใหม่อีกครั้ง ')</script>";
		exit();


}
?>