<?php
session_start();
include("../../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$serialID = $_POST["chkapp"];
$stateapp = $_POST["state"];
$status = 0;


if($stateapp == 'app'){
	for($i=0;$i<sizeof($serialID);$i++){
		$qry_sel = pg_query("SELECT \"assetDetailID\", \"productCode\", \"secondaryID\",\"pricePerUnit\",\"VAT_value\" FROM thcap_asset_biz_serial_temp where \"serialID\" = '$serialID[$i]' ");
		list($assetDetailID,$productCode,$secondaryID,$pricePerUnit,$VAT_value) = pg_fetch_array($qry_sel);
		
		if($productCode != "" && $secondaryID != ""){	
			$qry_up = pg_query("UPDATE thcap_asset_biz_detail SET \"productCode\"='$productCode',\"secondaryID\"='$secondaryID' WHERE \"assetDetailID\" = '$assetDetailID' ");
			if($qry_up){}else{ $status++; break; }
			
			$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='1' WHERE \"serialID\"='$serialID[$i]'");
			if($qry_up){}else{ $status++; break; }
			
		}else if($productCode != "" && $secondaryID == ""){
			$qry_up = pg_query("UPDATE thcap_asset_biz_detail SET \"productCode\"='$productCode' WHERE \"assetDetailID\" = '$assetDetailID' ");
			if($qry_up){}else{ $status++; break; }
			
			$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='1' WHERE \"serialID\"='$serialID[$i]'");
			if($qry_up){}else{ $status++; break; }	
		}else if($secondaryID != "" && $productCode == ""){
			$qry_up = pg_query("UPDATE thcap_asset_biz_detail SET \"secondaryID\"='$secondaryID' WHERE \"assetDetailID\" = '$assetDetailID' ");
			if($qry_up){}else{ $status++; break; }
			
			$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='1' WHERE \"serialID\"='$serialID[$i]'");
			if($qry_up){}else{ $status++; break; }
		}
		
		if($pricePerUnit != ""){
			$qry_up = pg_query("UPDATE thcap_asset_biz_detail SET \"pricePerUnit\"='$pricePerUnit' WHERE \"assetDetailID\" = '$assetDetailID' ");
			if($qry_up){}else{ $status++; break; }
			
			$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='1' WHERE \"serialID\"='$serialID[$i]'");
			if($qry_up){}else{ $status++; break; }
		}
		
		if($VAT_value != ""){
			$qry_up = pg_query("UPDATE thcap_asset_biz_detail SET \"VAT_value\"='$VAT_value' WHERE \"assetDetailID\" = '$assetDetailID' ");
			if($qry_up){}else{ $status++; break; }
			
			$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='1' WHERE \"serialID\"='$serialID[$i]'");
			if($qry_up){}else{ $status++; break; }
		}
		
		unset($assetDetailID);
		unset($productCode);
		unset($secondaryID);
		unset($pricePerUnit);
		unset($VAT_value);
		
	}
}else if($stateapp == 'notapp'){
	for($i=0;$i<sizeof($serialID);$i++){
		$qry_up = pg_query("UPDATE thcap_asset_biz_serial_temp SET app_user='$id_user', app_date='$datenow', app_status='2' WHERE \"serialID\"='$serialID[$i]'");
		if($qry_up){}else{ $status++; break; }
	}

}else{
	$status++;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<?php
if($status == 0){
	pg_query("COMMIT");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
	echo "<script type='text/javascript'>alert('อนุมัติสำเร็จ')</script>";
}else{
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
	echo "<script type='text/javascript'>alert('ขออภัย !! อนุมัติไม่สำเร็จ  โปรดลองใหม่ในภายหลัง')</script>";
}
?>
</html>