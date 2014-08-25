<?php
include("../../config/config.php");
$assetid = $_POST["assetid"];
$assetDetailID = $_POST["assetDetailID"];
//หาว่าใครเป็นผู้อนุมัติรายการ
	$sel_qry = pg_query("SELECT \"appvID\" FROM \"thcap_asset_biz_detail_temp\" WHERE \"assetID\" = '$assetid'");
	list($appvuser) = pg_fetch_array($sel_qry);
	//หากอนุมัติโดยระบบ
	if($appvuser == '000'){
		//ตรวจสอบว่ารายการที่ยกเลิกนั้น มีสินค้าถูกนำไปใช้โดยสัญญาอื่นหรือไม่
			$qry_chk = pg_query("SELECT * FROM thcap_asset_biz_detail where \"assetDetailID\" != '$assetDetailID' AND \"assetID\" = '$assetid' AND \"materialisticStatus\" = '2' ");
			$row_chk = pg_num_rows($qry_chk);
			IF($row_chk == 0){
				echo "555";
			}else{
				echo "2";			
			}
	}else{
		echo "1";
	}
?>	