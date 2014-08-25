<?php
session_start();
include("../../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$assetDetailID = $_POST["hdid"];
$codeProduct = $_POST["codeProduct"];
$secondaryID = $_POST["secondaryID"];
$pricePerUnit = $_POST["pricePerUnit"];
$VAT_value = $_POST["VAT_value"];

$receiptNumber = $_POST["receiprid"];

$stauts = 0;


pg_query("BEGIN");


for($i=0;$i<sizeof($assetDetailID);$i++){

	if($codeProduct[$i] != "" && $secondaryID[$i] != ""){	
		$qry_in = pg_query("INSERT INTO thcap_asset_biz_serial_temp(
            \"assetDetailID\", \"productCode\", \"secondaryID\", add_user, 
            add_date, app_status)
		VALUES ('$assetDetailID[$i]', '$codeProduct[$i]', '$secondaryID[$i]', '$id_user', '$datenow', '0')");
		if($qry_in){}else{ $status++; break; }	
	}else if($codeProduct[$i] != "" && $secondaryID[$i] == ""){
	
		$qry_in = pg_query("INSERT INTO thcap_asset_biz_serial_temp(
            \"assetDetailID\", \"productCode\", add_user, 
            add_date, app_status)
		VALUES ('$assetDetailID[$i]', '$codeProduct[$i]', '$id_user', '$datenow', '0')");
		if($qry_in){}else{ $status++; break; }		
	}else if($secondaryID[$i] != "" && $codeProduct[$i] == ""){
		$qry_in = pg_query("INSERT INTO thcap_asset_biz_serial_temp(
            \"assetDetailID\", \"secondaryID\", add_user, 
            add_date, app_status)
		VALUES ('$assetDetailID[$i]', '$secondaryID[$i]', '$id_user', '$datenow', '0')");
		if($qry_in){}else{ $status++; break; }
	}
	
	if($pricePerUnit[$i] != ""){
		$qry_sel = pg_query("select \"serialID\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID[$i]' AND add_date = '$datenow' AND app_status = '0' AND add_user = '$id_user'");
		$row_num = pg_num_rows($qry_sel);
		if($row_num > 0){
			list($serialID) = pg_fetch_array($qry_sel);
			$qry_in = pg_query("UPDATE thcap_asset_biz_serial_temp SET \"pricePerUnit\" = '$pricePerUnit[$i]' where \"serialID\" =  '$serialID'");
			if($qry_in){}else{ $status++; break; }
			unset($serialID);
		}else{
			$qry_in = pg_query("INSERT INTO thcap_asset_biz_serial_temp(\"assetDetailID\",\"pricePerUnit\", add_user, add_date, app_status)
			VALUES ('$assetDetailID[$i]', '$pricePerUnit[$i]', '$id_user', '$datenow', '0')");
			if($qry_in){}else{ $status++; break; }
		}	
		
	}
	
	if($VAT_value[$i] != ""){
		$qry_sel = pg_query("select \"serialID\" FROM thcap_asset_biz_serial_temp where \"assetDetailID\" = '$assetDetailID[$i]' AND add_date = '$datenow' AND app_status = '0' AND add_user = '$id_user'");
		$row_num = pg_num_rows($qry_sel);
		if($row_num > 0){
			list($serialID) = pg_fetch_array($qry_sel);
			$qry_in = pg_query("UPDATE thcap_asset_biz_serial_temp SET \"VAT_value\" = '$VAT_value[$i]' where \"serialID\" =  '$serialID'");
			if($qry_in){}else{ $status++; break; }
			unset($serialID);
		}else{
			$qry_in = pg_query("INSERT INTO thcap_asset_biz_serial_temp(\"assetDetailID\",\"VAT_value\", add_user, add_date, app_status)
			VALUES ('$assetDetailID[$i]', '$VAT_value[$i]', '$id_user', '$datenow', '0')");
			if($qry_in){}else{ $status++; break; }
		}	
		
	}

	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
if($status == 0){
	pg_query("COMMIT");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?reid=$receiptNumber\">";
	echo "<script type='text/javascript'>alert('บันทึกรหัสสำเร็จ')</script>";
}else{
	pg_query("ROLLBACK");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?reid=$receiptNumber\">";
	echo "<script type='text/javascript'>alert('ขออภัย !! เพิ่มรหัสไม่สำเร็จ  โปรดลองใหม่ในภายหลัง')</script>";
}
?>
</head>