<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$user_id = $_SESSION["av_iduser"];
$contractID = $_POST['conid'];
$mainID = $_POST['mainID'];
$all_pick_itm = $_POST['all_pick_itm'];	//array รายการสินค้า
$sum_pick_itm = sizeof($all_pick_itm);
pg_query("BEGIN");
if($sum_pick_itm!=0)
	{
		// หาประเภทสัญญา
		$qry_conType = pg_query("select \"conType\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
		$conType = pg_fetch_result($qry_conType,0);
		
		$sql4 = pg_query("SELECT * FROM \"thcap_addrContractID\" WHERE \"contractID\" = '$contractID'");
		$result4 = pg_fetch_array($sql4);
	
	
		$A_NO = checknull($result4["A_NO"]);
		$A_SUBNO = checknull($result4["A_SUBNO"]);
		$A_SOI = checknull($result4["A_SOI"]);
		$A_RD = checknull($result4["A_RD"]);
		$A_TUM = checknull($result4["A_TUM"]);
		$A_AUM = checknull($result4["A_AUM"]);
		$A_PRO = checknull($result4["A_PRO"]);
		$A_POST = checknull($result4["A_POST"]);		
		$A_ROOM = checknull($result4["A_ROOM"]);
		$A_FLOOR = checknull($result4["A_FLOOR"]);
		$A_BUILDING = checknull($result4["A_BUILDING"]);
		$A_VILLAGE = checknull($result4["A_VILLAGE"]);
	
	
		foreach($all_pick_itm as $val)
		{
			$itm_data = split(",",$val);
			$rcid = $itm_data[0];
			$asset_id = $itm_data[1];
			$isSameContract = $itm_data[2];
			$addressid = $itm_data[3];
			$asset_address_id = "";
			if($isSameContract=="0")
			{
				$asset_address_id = "'".$addressid."'";
			}
			else if($isSameContract=="1")
			{
			
			
				$q_chk = str_replace("=null"," is null","select \"asset_addressID\" from \"thcap_contract_asset_address\" where \"Room\"=$A_ROOM and \"Floor\"=$A_FLOOR and \"HomeNumber\"=$A_NO and \"Building\"=$A_BUILDING and \"Moo\"=$A_SUBNO and \"Village\"=$A_VILLAGE and \"Soi\"=$A_SOI and \"Road\"=$A_RD and \"Tambon\"=$A_TUM and \"District\"=$A_AUM and \"Province\"=$A_PRO and \"Zipcode\"=$A_POST and \"customerID\"='$mainID'");
				$qr_chk = pg_query($q_chk);
				if($qr_chk)
				{
					$row_chk = pg_num_rows($qr_chk);
					if($row_chk==0)
					{
						$qr_ins_addr = pg_query("insert into \"thcap_contract_asset_address\"(\"Room\",\"Floor\",\"HomeNumber\",\"Building\",\"Moo\",\"Village\",\"Soi\",\"Road\",\"Tambon\",\"District\",\"Province\",\"Zipcode\",\"customerID\",\"doer\",\"doerStamp\") values($A_ROOM,$A_FLOOR,$A_NO,$A_BUILDING,$A_SUBNO,$A_VILLAGE,$A_SOI,$A_RD,$A_TUM,$A_AUM,$A_PRO,$A_POST,'$mainID','$user_id',LOCALTIMESTAMP(0)) returning \"asset_addressID\"");
						if($qr_ins_addr)
						{
							$rs_ins_addr = pg_fetch_array($qr_ins_addr);
							$asset_address_id = "'".$rs_ins_addr['asset_addressID']."'";
						}
						else
						{
							$status++;
						}
					}
					else
					{
						$rs_addr = pg_fetch_array($qr_chk);
						$asset_address_id = "'".$rs_addr['asset_addressID']."'";
					}
				}
				else
				{
					$status++;
				}
			}
			else if($isSameContract=="")
			{
				$asset_address_id = "null";
			}
			
			$qq = pg_query("select \"edittime\" from \"thcap_contract_asset_temp\" where \"contractID\" = '$contractID' AND \"assetDetailID\" = '$asset_id'");
			echo $maxedit;
			list($maxedit)=pg_fetch_array($qq);
			if($maxedit != ""){
				$maxedit = $maxedit+1;
			}else{
				$maxedit = '0';	
			}
			
			//----- ตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----
				// หารหัส ใบเสร็จ/ใบสั่งซื้อ
				$qry_sAssetID = pg_query("select \"assetID\" from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$asset_id' ");
				$sAssetID = pg_fetch_result($qry_sAssetID,0);
				
				// ตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่
				$qry_chkAssetCancel = pg_query("select \"Approved\" from \"thcap_asset_cancel\" where \"assetID\" = '$sAssetID' and (\"Approved\" is null or \"Approved\" = 't') ");
				$row_chkAssetCancel = pg_num_rows($qry_chkAssetCancel);
				if($row_chkAssetCancel > 0)
				{ // ถ้ามีการทำรายการยกเลิก
					$chkAssetCancel = pg_fetch_result($qry_chkAssetCancel,0);
					if($chkAssetCancel == "")
					{
						$status++;
						echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $asset_id ถูกขอยกเลิกอยู่ในขณะนี้<br>";
					}
					else
					{
						$status++;
						echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $asset_id ถูกยกเลิกไปแล้วในขณะนี้<br>";
					}
				}
			//----- จบการตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----

			$q = "insert into \"thcap_contract_asset_temp\"(\"contractID\",\"assetDetailID\",\"doerID\",\"doerStamp\",\"assetAddress\",\"appvID\",\"appvStamp\",\"edittime\",\"Approved\") values('$contractID','$asset_id','$user_id',LOCALTIMESTAMP(0),$asset_address_id,'000',LOCALTIMESTAMP(0),'$maxedit','TRUE')";
			$qr = pg_query($q);
			if(!$qr)
			{
				echo $q;
				$status++;
			}
			
			// กำหนดการ update สถานะสินทรัพย์
			if($conType == "BH" || $conType == "HP"){$as_status_id = "2";}
			elseif($conType == "FL"){$as_status_id = "3";}
			elseif($conType == "MG" || $conType == "JV" || $conType == "UF" || $conType == "CG" || $conType == "SM"){$as_status_id = "4";}
			else{$status++; echo "ประเภทสัญญา ยังไม่รองรับการกำหนดสถานะสินทรัพย์";}
			
			$q1 = "update \"thcap_asset_biz_detail\" set \"materialisticStatus\" = '2', \"as_status_id\" = '$as_status_id' where \"assetDetailID\"='$asset_id'";
			$qr1 = pg_query($q1);
			if(!$qr1)
			{
				echo $q1;
				$status++;
			}
			$qup = "INSERT INTO thcap_contract_asset(
											\"contractID\", 
											\"assetDetailID\", 
											\"materialisticStatus\", 
											\"assetAddress\"
											)
									VALUES 
											(
											'$contractID', 
											'$asset_id', 
											'2', 
											$asset_address_id
											)";
			$qrup = pg_query($qup);
			if(!$qrup)
			{
				echo $qup;
				$status++;
			}
			
			unset($maxedit);
		}
	}
if($status == 0)
{
	pg_query("COMMIT");
		echo "<script type='text/javascript'>alert(' Success ')</script>";
		echo "<script type='text/javascript'>
				opener.location.reload(true);
				self.close();
			  </script>";
}else{
	pg_query("ROLLBACK");
		echo "<script type='text/javascript'>alert(' error ')</script>";
		echo "<script type='text/javascript'>window.close();</script>";
}	

?>