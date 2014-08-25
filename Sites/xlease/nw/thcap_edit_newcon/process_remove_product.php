<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$conid = $_POST["conid"];
$assetid = $_POST["assetid"];
$assetDetailID = $_POST["assetDetailID"];
$status = 0;

pg_query("BEGIN");

	//หาว่าใครเป็นผู้อนุมัติรายการ
	$sel_qry = pg_query("SELECT \"appvID\" FROM \"thcap_asset_biz_detail_temp\" WHERE \"assetID\" = '$assetid'");
	list($appvuser) = pg_fetch_array($sel_qry);
	//หากอนุมัติโดยระบบ
	if($appvuser == '000'){
	
			//ตรวจสอบว่ารายการที่ยกเลิกนั้น มีสินค้าถูกนำไปใช้โดยสัญญาอื่นหรือไม่
			$qry_chk = pg_query("SELECT * FROM thcap_asset_biz_detail where \"assetDetailID\" != '$assetDetailID' AND \"assetID\" = '$assetid' AND \"materialisticStatus\" = '2' ");
			$row_chk = pg_num_rows($qry_chk);
			//หากมีสินค้าอื่นในใบเสร็จเดียวกันที่ยังคงสถานะถูกนำไปใช้อยู่ให้เปลี่ยนสถานะเป็น พร้อมใช้งานแทน
			IF($row_chk > 0){
			
				$qry_up = pg_query("	UPDATE \"thcap_asset_biz_detail\"
							SET  	\"materialisticStatus\" = '1', \"as_status_id\" = '1'
							WHERE 	\"assetDetailID\" = '$assetDetailID' ");
				IF($qry_up){}else{ $status++; }

				$qry_del = pg_query("	DELETE FROM thcap_contract_asset
										WHERE \"contractID\" = '$conid' AND \"assetDetailID\" = '$assetDetailID' ");
				IF($qry_del){}else{ $status++; }	
			
			
			}ELSE{ //หากไม่มีสินค้าใดถูกนำไปใช้แล้วให้ยกเลิกสินค้าและใบเสร็จนั้นๆเลย
	
					// ตรวจสอบก่อนว่า มีการขอยกเลิกไปแล้วหรือยัง
					$qry_chkHave = pg_query("select * from \"thcap_asset_cancel\" where \"assetID\" = '$assetid' and \"Approved\" is null ");
					$rowChkHave = pg_num_rows($qry_chkHave);

					// ตรวจสอบก่อนว่า เคยมีรายการในใบเสร็จใบสั่งซื้อนี้ถูกยกเลิกไปแล้วหรือยัง
					$qry_chkList = pg_query("select b.* from \"thcap_asset_biz_detail\" a, \"thcap_contract_asset\" b where a.\"assetDetailID\" = b.\"assetDetailID\" and a.\"assetID\" = '$assetid' and b.\"contractID\" != '$conid'");
					$rowChkList = pg_num_rows($qry_chkList);

					if($rowChkHave > 0)
					{ // ถ้ามีบางรายการในใบเสร็จถูกนำไปใช้แล้ว
						$status++;
						if($rowChkHave > 0)
						{
							echo $error = "ไม่สามารถทำรายการได้ เนื่องจากมีการขอยกเลิกใบเสร็จนี้อยู่แล้ว";
							exit();
						}
						elseif($rowChkList > 0)
						{
							echo $error = "ไม่สามารถทำรายการได้ เนื่องจากมีบางรายการใน ใบเสร็จ/ใบสั่งซื้อ ถูกในไปใช้แล้ว";
							exit();
						}

					}
					else
					{
						$qryAddCancel = "INSERT INTO \"thcap_asset_cancel\"(\"assetID\", \"doerID\", \"doerStamp\", \"reason\")
										VALUES('$assetid','$id_user',LOCALTIMESTAMP(0),'ยกเลิกผ่านเมนู (THCAP) ใส่รายละเอียดสัญญา BH') returning \"cancelID\"";
						if($chkAddCancel = pg_query($qryAddCancel)){}else{$status++;}
						list($cancelID) = pg_fetch_array($chkAddCancel);
					
						// ACTIONLOG
						if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ยกเลิกใบเสร็จ/ใบสั่งซื้อ สินทรัพย์สำหรับเช่า-ขาย', LOCALTIMESTAMP(0))")); else $status++;
						// ACTIONLOG---
					}

					$qryOKCancel = "UPDATE \"thcap_asset_cancel\" SET \"Approved\" = 'TRUE', \"appvID\" = '000', \"appvStamp\" = LOCALTIMESTAMP(0)
								where \"cancelID\" = '$cancelID' and \"Approved\" is null ";
					if($chkOKCancel = pg_query($qryOKCancel)){}else{$status++;}
					
					// หารหัส ใบเสร็จ/ใบสั่งซื้อ
					$qry_sAsset = pg_query("select * from \"thcap_asset_cancel\" where \"cancelID\" = '$cancelID' ");
					while($res_sAsset = pg_fetch_array($qry_sAsset))
					{
						$assetID = $res_sAsset["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
					}
					
					// update ตารางใบเสร็จ/ใบสั่งซื้อ
					$qryMainCancel = "UPDATE \"thcap_asset_biz\" SET \"ActiveStatus\" = '0'
									where \"assetID\" = '$assetID' and \"ActiveStatus\" = '1' ";
					if($chkMainCancel = pg_query($qryMainCancel)){}else{$status++;}
					
					// update สินทรัพย์แต่ละตัวให้ถูกยกเลิกไปด้วย
					$qrySonCancel = "UPDATE \"thcap_asset_biz_detail\" SET \"materialisticStatus\" = '9', \"as_status_id\" = '0'
									where \"assetID\" = '$assetID' ";
					if($chkSonCancel = pg_query($qrySonCancel)){}else{$status++;}

					
					$qry_del = pg_query("	DELETE FROM \"thcap_contract_asset\"
											WHERE \"contractID\" = '$conid' AND \"assetDetailID\" IN (SELECT \"assetDetailID\" FROM thcap_asset_biz_detail WHERE \"assetID\" = '$assetID')");
					IF($qry_del){}else{ $status++; }	
					
					
					
					//ACTIONLOG
					if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติยกเลิกใบเสร็จ/ใบสั่งซื้อ สินทรัพย์สำหรับเช่า-ขาย', LOCALTIMESTAMP(0))")); else $status++;
					//ACTIONLOG---
					
			}		
	
	}else{
	
			
			$qry_up = pg_query("	UPDATE \"thcap_asset_biz_detail\"
							SET  	\"materialisticStatus\" = '1', \"as_status_id\" = '1'
							WHERE 	\"assetDetailID\" = '$assetDetailID' ");
			IF($qry_up){}else{ $status++; }

			$qry_del = pg_query("	DELETE FROM thcap_contract_asset
									WHERE \"contractID\" = '$conid' AND \"assetDetailID\" = '$assetDetailID' ");
			IF($qry_del){}else{ $status++; }	
			
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