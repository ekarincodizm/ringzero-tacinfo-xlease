<?php
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$appvauto = pg_escape_string($_POST["appvauto"]); //หากมีค่าเป็น 't' ให้อนุมัติรายการนี้อัตโนมัติโดยระบบ
IF($appvauto == 't')
{
	$addUser = '000';
	$stsapp = pg_escape_string($_POST["statusapp"]); // อนุมัติ อัตโนมัติ จากเมนู "(THCAP) ใส่รายละเอียดสัญญา BH"
}
else
{
	$addUser = $_SESSION["av_iduser"];
	if(isset($_POST["submitbutton"])) // อนุมัติจากเมนู "(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย"
	{
		$stsapp='1';//อนุมัติ
	}
	else
	{
		$stsapp='0';//ไม่อนุมัติ
	}
}
$addStamp = "LOCALTIMESTAMP(0)";

$method=$_REQUEST["method"];
$note = pg_escape_string($_POST["note"]);

pg_query("BEGIN WORK");
$status = 0;
			
if($method=="approve")
{ // ถ้ามาจากหน้า ทำรายการอนุมัติ

	/*$corpID = $_POST["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
	$receiptNumber = $_POST["receiptNumber"]; // เลขที่ใบเสร็จ
	$doerID = $_POST["doerID"]; // ผู้ทำรายการ
	$doerStamp = $_POST["doerStamp"]; // วันเวลาที่ทำรายการ*/
	
	$tempID = pg_escape_string($_POST["tempID"]); // รหัส temp
	$statusSellBuy = pg_escape_string($_POST["chksell"]);
	$ruleSellBuy = pg_escape_string($_POST["rule"]);
	if($ruleSellBuy==""){$ruleSellBuy="null";}
	$cusContact = pg_escape_string($_POST["cusContact"]);
	$cusPost = pg_escape_string($_POST["cusPost"]);
	$cusTel = pg_escape_string($_POST["cusTel"]);
	$dateContact = pg_escape_string($_POST["dateContact"]);
	$note = pg_escape_string($_POST["note"]);
				
	//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
	//$qry_check=pg_query("select * from \"thcap_asset_biz_temp\" where \"corpID\" = '$corpID' and \"receiptNumber\" = '$receiptNumber' and \"doerID\" = '$doerID' and \"doerStamp\" = '$doerStamp' and \"Approved\" is null ");
	$qry_check = pg_query("select * from \"thcap_asset_biz_temp\" where \"tempID\" = '$tempID' and \"Approved\" IS NULL ");
	$num_check = pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
	if($num_check == 0){
		$status=-1;
	}
	else
	{
		// หาข้อมูลเบื่องต้น
		while($res_tempData = pg_fetch_array($qry_check))
		{
			$corpID = $res_tempData["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
			$receiptNumber = $res_tempData["receiptNumber"]; // เลขที่ใบเสร็จ
			$doerID = $res_tempData["doerID"]; // ผู้ทำรายการ
			$doerStamp = $res_tempData["doerStamp"]; // วันเวลาที่ทำรายการ
			$buyDate = $res_tempData["buyDate"]; // วันที่ซื้อ
			$payDate = $res_tempData["payDate"]; // วันที่จ่ายเงิน
			$pathFilePO = $res_tempData["pathFilePO"]; // เลขที่ใบสั่งซื้อ
		}
		
		if($stsapp=="1")
		{ //กรณีอนุมัติ
			//ดึงข้อมูลจากตาราง thcap_asset_biz_temp insert ในตาราง thcap_asset_biz
			$insAssets="insert into \"thcap_asset_biz\"(\"compID\",\"corpID\", \"buyDate\", \"payDate\", \"receiptNumber\", \"beforeVat\", \"VAT_value\", \"afterVat\", \"vatStatus\", \"PurchaseOrder\", \"pathFileReceipt\", \"pathFilePO\")
			select \"compID\",\"corpID\", \"buyDate\", \"payDate\", \"receiptNumber\", \"beforeVat\", \"VAT_value\", \"afterVat\", \"vatStatus\", \"PurchaseOrder\", \"pathFileReceipt\", \"pathFilePO\" from \"thcap_asset_biz_temp\"
			where \"tempID\" = '$tempID' and \"Approved\" is null returning \"assetID\"";		
			if($resins = pg_query($insAssets)){
				list($newAssetID)=pg_fetch_array($resins); // เลขที่กลุ่มสินทรัพย์
			}else{
				$status++;
			}
			
			//บันทึกข้อมูลลงในตารางรับใบกำกับภาษีซื้อด้วย thcap_asset_biz_taxinvoice 
			$instax="INSERT INTO thcap_asset_biz_taxinvoice(\"assetID\", \"statusassetID\")
			VALUES ('$newAssetID','2');";
			if($resintax=pg_query($instax)){
			}else{
				$status++;
			}
			
			/* ยกเลิกใช้โค้ดนี้ เนื่องจากมีการ returning ออกมาจากตอน insert เลย
			if($status == 0)
			{
				if($receiptNumber == "") // ตรวจสอบก่อนว่ามีเลขที่ใบเสร็จหรือไม่
				{
					$receiptNumberChkNull = "\"receiptNumber\" is null";
				}
				else
				{
					$receiptNumberChkNull = "\"receiptNumber\" = '$receiptNumber'";
				}
				
				if($pathFilePO == "") // ตรวจสอบก่อนว่ามีเลขที่ใบสั่งซื้อหรือไม่
				{
					$pathFilePOChkNull = "\"pathFilePO\" is null";
				}
				else
				{
					$pathFilePOChkNull = "\"pathFilePO\" = '$pathFilePO'";
				}
				
				// หาเลขที่กลุ่มสินทรัพย์ใหม่ ที่ได้มา
				
				$qry_groupAsset = pg_query("select max(\"assetID\") as \"maxAssetID\" from \"thcap_asset_biz\" where ($receiptNumberChkNull and $pathFilePOChkNull) and \"buyDate\" = '$buyDate' and \"payDate\" = '$payDate' ");
				while($res_groupAsset = pg_fetch_array($qry_groupAsset))
				{
					$newAssetID = $res_groupAsset["maxAssetID"]; // เลขที่กลุ่มสินทรัพย์
				}
				
			}
			*/
			
			//$qry_detail = pg_query("select * from \"thcap_asset_biz_detail_temp\" where \"receiptNumber\" = '$receiptNumber' and \"doerID\" = '$doerID' and \"doerStamp\" = '$doerStamp' and \"Approved\" is null ");
			$qry_detail = pg_query("select * from \"thcap_asset_biz_detail_temp\" where \"tempAssetID\" = '$tempID' and \"Approved\" is null ");
			while($res_detail = pg_fetch_array($qry_detail))
			{
				$assetID_Detail = $res_detail["assetID"];
				$brand_Detail = $res_detail["brand"];
				$astypeID_Detail = $res_detail["astypeID"];
				$model_Detail = $res_detail["model"];
				$explanation_Detail = $res_detail["explanation"];
				$pricePerUnit_Detail = $res_detail["pricePerUnit"];
				$productCode_Detail = $res_detail["productCode"];
				$receiptNumber_Detail = $res_detail["receiptNumber"];
				$secondaryID_Detail = $res_detail["secondaryID"];
				$VAT_value_Detail = $res_detail["VAT_value"];
				$ProductStatusID_Detail = $res_detail["ProductStatusID"];
				
				$assetID_Detail = checknull($assetID_Detail);
				$brand_Detail = checknull($brand_Detail);
				$astypeID_Detail = checknull($astypeID_Detail);
				$model_Detail = checknull($model_Detail);
				$explanation_Detail = checknull($explanation_Detail);
				$pricePerUnit_Detail = checknull($pricePerUnit_Detail);
				$productCode_Detail = checknull($productCode_Detail);
				$receiptNumber_Detail = checknull($receiptNumber_Detail);
				$secondaryID_Detail = checknull($secondaryID_Detail);
				$VAT_value_Detail = checknull($VAT_value_Detail);
				$ProductStatusID_Detail = checknull($ProductStatusID_Detail);
				
				$insAssets="insert into \"thcap_asset_biz_detail\"(\"assetID\", \"brand\",\"astypeID\", \"model\", \"explanation\", \"pricePerUnit\", \"productCode\", \"receiptNumber\", \"secondaryID\", \"VAT_value\", \"ProductStatusID\", \"as_status_id\")
				values('$newAssetID', $brand_Detail, $astypeID_Detail, $model_Detail, $explanation_Detail, $pricePerUnit_Detail, $productCode_Detail, $receiptNumber_Detail, $secondaryID_Detail, $VAT_value_Detail, $ProductStatusID_Detail, '1') ";				
				if($resins=pg_query($insAssets)){
				}else{
					$status++;
				}
			}
			
			//update ตาราง thcap_asset_biz_temp ว่าได้อนุมัติแล้ว
			$uptemp="UPDATE \"thcap_asset_biz_temp\"
			SET \"Approved\"='true', \"appvID\"='$addUser', \"appvStamp\"=$addStamp, \"assetID\" = '$newAssetID'
			WHERE \"tempID\" = '$tempID' and \"Approved\" is null ";	
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			//update ตาราง thcap_asset_biz_detail_temp ว่าได้อนุมัติแล้ว
			$uptemp="UPDATE \"thcap_asset_biz_detail_temp\"
			SET \"Approved\"='true', \"appvID\"='$addUser', \"appvStamp\"=$addStamp, \"assetID\" = '$newAssetID'
			WHERE \"tempAssetID\" = '$tempID' and \"Approved\" is null ";
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			// insert รายละเอียดการอนุมัติลงในตาราง thcap_asset_biz_appdetail 
			$insdetail="INSERT INTO thcap_asset_biz_appdetail(
				\"tempID\", \"statusSellBuy\", \"ruleSellBuy\", \"cusContact\", \"cusPost\", 
				\"cusTel\", \"dateContact\", note)
			VALUES ('$tempID', '$statusSellBuy', $ruleSellBuy, '$cusContact', '$cusPost', 
				'$cusTel', '$dateContact', '$note');";
			if($resdetail=pg_query($insdetail)){
			}else{
				$status++;
			}
		}
		elseif($stsapp=="0")
		{ //กรณีไม่อนุมัติ
		
			//update ตาราง thcap_asset_biz_temp ว่าไม่อนุมัติ
			$uptemp="UPDATE \"thcap_asset_biz_temp\"
			SET \"Approved\"='false', \"appvID\"='$addUser', \"appvStamp\"=$addStamp
			WHERE \"tempID\" = '$tempID' and \"Approved\" is null ";	
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			//update ตาราง thcap_asset_biz_detail_temp ว่าไม่อนุมัติ
			$uptemp="UPDATE \"thcap_asset_biz_detail_temp\"
			SET \"Approved\"='false', \"appvID\"='$addUser', \"appvStamp\"=$addStamp
			WHERE \"tempAssetID\" = '$tempID' and \"Approved\" is null ";	
			if($resuptemp=pg_query($uptemp)){
			}else{
				$status++;
			}
			
			// insert รายละเอียดการอนุมัติลงในตาราง thcap_asset_biz_appdetail 
			$insdetail="INSERT INTO thcap_asset_biz_appdetail(
				\"tempID\", \"statusSellBuy\", \"ruleSellBuy\", \"cusContact\", \"cusPost\", 
				\"cusTel\", \"dateContact\", note)
			VALUES ('$tempID', '$statusSellBuy', $ruleSellBuy, '$cusContact', '$cusPost', 
				'$cusTel', '$dateContact', '$note');";
			if($resdetail=pg_query($insdetail)){
			}else{
				$status++;
			}
		}
	}
}else if($method=="receiveTax"){ //กรณีกดรับใบเสร็จจากเมนู "(THCAP) รับใบกำกับภาษีซื้อ"
	$assetID=$_POST["assetID"];
	
	$up="UPDATE thcap_asset_biz_taxinvoice SET \"recUser\"='$addUser', \"recStamp\"=$addStamp, \"statusassetID\"='1'
	WHERE \"assetID\"='$assetID'";
	
	if($resup=pg_query($up)){
	}else{
		$status++;
	}
}
if($status==-1){	
	pg_query("ROLLBACK");
	if($method=="approve"){
		$script= '<script language=javascript>';
		$script.= " alert('รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{echo "3";}
}else if($status == 0){
	pg_query("COMMIT");
	IF($appvauto == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		if($method=="approve"){
		$script= '<script language=javascript>';
		$script.= " alert('บันทึกรายการเรียบร้อย');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
		}else{
		echo "1";
		}
	}	
}else{
	pg_query("ROLLBACK");
	IF($appvauto == 't'){
		echo "<center><h2><font color=\"#FF0000\">เพิ่มข้อมูลสินทรัพย์สำเร็จ แต่ไม่สามารถอนุมัติโดยระบบอัตโนมัติได้สำเร็จ<br>กรุณาทำรายการอนุมัติสินทรัพย์ที่เมนู \"(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย\"</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		if($method=="approve"){
			$script= '<script language=javascript>';
			$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');
						opener.location.reload(true);
						self.close();";
			$script.= '</script>';
			echo $script;
		}else{
			echo "2";
		}
	}
}
?>