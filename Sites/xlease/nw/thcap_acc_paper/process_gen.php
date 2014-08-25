<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
set_time_limit (60);
$iduser = $_SESSION["av_iduser"];
pg_query("BEGIN");
$status = 0;

$accid 		= pg_escape_string($_POST["accid"]);
$s_month    = pg_escape_string($_POST["s_month"]);
$s_year     = pg_escape_string($_POST["s_year"]);


//ตรวจสอบว่ามีข้อมูลครบหรือไม่
if($s_month !="" and $s_year !="" and $iduser !="" and $accid !="" ){
	//ตรวจสอบว่า บัญชีที่ส่งมามีในตาราง   all_accBook หรือไม่
	$qury_count = pg_query("select \"accBookserial\" from account.\"all_accBook\" where \"accBookserial\" = '$accid'");
	$num_rows   = pg_num_rows($qury_count);
	if($num_rows > 0){
		$s_m=1;//ตั้งแต่ เดือน มกราคม (1)
		$s_month=(int)$s_month;//เดือนที่เลือก
		while(($s_m <=$s_month) and ($s_month < 13)){
			//เรียกใช้  function gen 
			$qry_gen=pg_query("select account.\"thcap_get_ledger_accBook\"('$s_m', '$s_year', '$iduser','$accid',null)");
			$query =pg_fetch_array($qry_gen); 
			list($result)=$query;
			if ($result){}
			else{$status++;}
			$s_m++;
		}
		/*$qry_delete = "DELETE FROM account.\"thcap_ledger_detail\" WHERE \"accBookserial\" = '$accid' AND \"auto_id_ref\" <> '0'";
		$res_delete = pg_query($qry_delete);
		if($res_delete){}else{ $status++; }

		//เรียกใช้  function gen 
		$qry_gen=pg_query("select account.\"thcap_get_ledger_accBook\"('$s_month', '$s_year', '$iduser','$accid',null)");
		$query =pg_fetch_array($qry_gen); 
		list($result)=$query;
		if ($result){}
		else{$status++;}	*/	
	}else{$status++;}
}else{
	$status++;
}
if($status == 0)
{
	pg_query("COMMIT");
	echo "1";
}
else
{
	pg_query("ROLLBACK");
	echo "2";
}
?>