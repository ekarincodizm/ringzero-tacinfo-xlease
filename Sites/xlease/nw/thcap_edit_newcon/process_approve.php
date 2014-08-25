<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$cmd = $_POST["cmd"];
$coneditID = $_POST["coneditID"];
$note = checknull($_POST["note"]);
$status = 0;

pg_query("BEGIN");


IF($cmd == 'app'){

	$qry_in = pg_query("	UPDATE \"thcap_contract_edit\"
							SET  	\"status_app\" = '1', 
									\"user_app\" = '$id_user', 
									\"app_datetime\" = LOCALTIMESTAMP(0)
							WHERE 	\"coneditID\" = '$coneditID' ");
	IF($qry_in){}else{ $status++; }

	
}else if($cmd == 'not'){

	$qry_sel = pg_query("	SELECT \"contractID\" 
							FROM \"thcap_contract_edit\"
							WHERE \"coneditID\" = '$coneditID'
						");
						
	list($conidrein) = pg_fetch_array($qry_sel);					


	$qry_in = pg_query("	INSERT INTO \"thcap_contract_edit\"(
										\"contractID\", 
										\"status_edit\"
										)
								VALUES(
										'$conidrein',
										'0'
									  )
					 ");
	IF($qry_in){}else{ $status++; }


	
	$qry_up = pg_query("	UPDATE \"thcap_contract_edit\"
							SET  	\"status_app\" = '2', 
									\"user_app\" = '$id_user', 
									\"app_datetime\" = LOCALTIMESTAMP(0), 
									\"noteapp\" = $note
							WHERE 	\"coneditID\" = '$coneditID' ");
	IF($qry_up){}else{ $status++; }




}else{
	$status++;
}	
	
	
	
	
if($status == 0)
{
	pg_query("COMMIT");
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกรายการเรียบร้อย');
				opener.location.reload(true);
				self.close();";
	$script.= '</script>';
	echo $script;
}
else
{
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');
				opener.location.reload(true);
				self.close();";
	$script.= '</script>';
	echo $script;
	
}	
?>