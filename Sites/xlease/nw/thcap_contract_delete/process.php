<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$contractid = $_POST["conid"];
$id_user = $_SESSION["av_iduser"];
$note = checknull($_POST["note"]);
$status = 0;



pg_query("BEGIN");

	
	$check1 = pg_query("SELECT * FROM \"thcap_v_receipt_details\" where \"contractID\" = '$contractid'");
	$rowcheck1 = pg_num_rows($check1);
	$check2 = pg_query("SELECT * FROM finance.\"thcap_receive_cheque\" where \"revChqToCCID\" = '$contractid'");
	$rowcheck2 = pg_num_rows($check2);
	$check3 = pg_query("SELECT * FROM finance.\"thcap_receive_transfer\" where \"contractID\" = '$contractid'");
	$rowcheck3 = pg_num_rows($check3);

	
IF($rowcheck1 == 0 AND $rowcheck2 == 0 AND $rowcheck3 == 0){
	//-============================ Start ==================================-
	
	
	$qry_del = pg_query("DELETE FROM \"thcap_addrContractID\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("UPDATE \"thcap_temp_otherpay_debt\" SET \"debtStatus\" = '3'  WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_addrContractID_temp\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_ContactCus\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_selas = pg_query("SELECT  \"assetDetailID\" FROM thcap_contract_asset where \"contractID\" = '$contractid'");
	while($resultassetDetailID = pg_fetch_array($qry_selas)){
		$assetDetailID = $resultassetDetailID["assetDetailID"];
		$qry_upass = pg_query("		UPDATE \"thcap_asset_biz_detail\"
								   SET  \"materialisticStatus\"='1'
									WHERE \"assetDetailID\"='$assetDetailID'
								");
		IF($qry_upass){}else{ $status++; echo $qry_upass."n"; }
	}
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_asset\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_asset_temp\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_edit\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_fa_bill\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_fa_bill_temp\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract_temp\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM \"thcap_mg_contract_current\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }	
	
	$qry_del = pg_query("DELETE FROM account.\"thcap_payTerm_temp\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_del = pg_query("DELETE FROM account.\"thcap_payTerm\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	
	
	$qry_del = pg_query("DELETE FROM \"thcap_contract\" WHERE \"contractID\" = '$contractid' ");
	IF($qry_del){}else{ $status++; echo $qry_del."n"; }
	
	$qry_in = pg_query("INSERT INTO thcap_contract_delete_log( 
																\"contractID\", 
																\"doerID\", 
																doertime, 
																note
															)
													VALUES (	
																'$contractid', 
																'$id_user', 
																LOCALTIMESTAMP(0), 
																$note
															)
					");
	IF($qry_in){}else{ $status++; echo $qry_in."n"; }
}else{
	$status++;
}
	
IF($status == 0){
	pg_query("COMMIT");
	echo "<center><h1> ลบเลขที่สัญญา  $contractid ออกจาระบบ เรียบร้อยแล้ว </h1></center>";
	echo "<meta http-equiv=\"refresh\" content=\"3; URL=frm_index.php\">";

}else{
	pg_query("ROLLBACK");
	echo "<center><h1> เกิดข้อผิดพลาดไม่สามารถลบเลขที่สัญญา ออกจากระบบได้ <p>อาจเป็นสัญญาที่มีการชำระแล้ว ! </h1></center>";
	echo "<meta http-equiv=\"refresh\" content=\"10; URL=frm_index.php\">";
}	

?>