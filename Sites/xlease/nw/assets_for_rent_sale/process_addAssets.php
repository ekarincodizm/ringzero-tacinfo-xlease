<?php
include("../../config/config.php");
include("../function/checknull.php");
include('class.upload.php');
include("function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$nowdateTime = date("YmdHis");

$appvauto = $_POST["autoappv"]; //หากมีค่าเป็น 't' ให้อนุมัติรายการนี้อัตโนมัติโดยระบบ

pg_query("BEGIN");
$status = 0;

$rowDetail = $_POST["rowDetail"]; // จำนวนหลักทรัพย์ทั้งหมด
$buyer = $_POST["buyer"]; // ผู้ซื้อ
$seller = $_POST["seller"]; // ผู้ขาย
list($CusID,$CusName) = explode('#',$seller); // รหัสผู้ขาย และชื่อผู้ขาย
$datepicker_buy = $_POST["datepicker_buy"]; // วันที่ซื้อ
$datepicker_pay = $_POST["datepicker_pay"]; // วันที่จ่ายเงิน
$receiptNumber = $_POST["receiptNumber"]; // เลขที่ใบเสร็จ
$PurchaseOrder = $_POST["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
$howVat = $_POST["howVat"]; // ประเภท VAT || splitVat = VAT แยก || mixVat = VAT รวม

// ข้อมูลจากเมนูแก้ไข
$reusePO = $_POST["reusePO"]; // check ว่าใช้ไฟล์ใบสั่งซื้อเดิมหรือไม่ ถ้าเป็น on คือใช้ไฟล์ใบสั่งซื้อเดิม
$reuseReceipt = $_POST["reuseReceipt"]; // check ว่าใช้ไฟล์ใบเสร็จเดิมหรือไม่ ถ้าเป็น on คือใช้ไฟล์ใบเสร็จเดิม
$oldFilePO = $_POST["oldFilePO"]; // ไฟล์ใบสั่งซื้อเดิม
$oldFileReceipt = $_POST["oldFileReceipt"]; // ไฟล์ใบเสร็จเดิม

if($howVat == "splitVat")
{
	$howVat = "1";
}
elseif($howVat == "mixVat")
{
	$howVat = "2";
}

$beforeVat = $_POST["beforeVat"]; // ราคาก่อน VAT
$myVat = $_POST["myVat"]; // ยอด VAT
$afterVat = $_POST["afterVat"]; // ราคาหลังรวม VAT

// กำหนดค่าที่จะเอาไป where
if($receiptNumber == "" && $PurchaseOrder != "")
{
	$tempWhere = "and \"receiptNumber\" is null and \"PurchaseOrder\" = '$PurchaseOrder' ";
}
elseif($receiptNumber != "" && $PurchaseOrder == "")
{
	$tempWhere = "and \"receiptNumber\" = '$receiptNumber' and \"PurchaseOrder\" is null ";
}
elseif($receiptNumber != "" && $PurchaseOrder != "")
{
	$tempWhere = "and \"receiptNumber\" = '$receiptNumber' and \"PurchaseOrder\" = '$PurchaseOrder' ";
}

$myVat = checknull($myVat);
$howVat = checknull($howVat);
$beforeVat = checknull($beforeVat);
$afterVat = checknull($afterVat);
$datepicker_pay = checknull($datepicker_pay);
$receiptNumber = checknull($receiptNumber);
$PurchaseOrder = checknull($PurchaseOrder);

//add file upload เลขที่ใบสั่งซื้อ POPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPO
if($reusePO != "on")
{ // ถ้าไม่ได้ใช้ไฟล์เดิมจากเมนูแก้ไข
	$cli = (isset($argc) && $argc > 1);
	if ($cli) {
		if (isset($argv[1])) $_GET['file'] = $argv[1];
		if (isset($argv[2])) $_GET['dir'] = $argv[2];
		if (isset($argv[3])) $_GET['pics'] = $argv[3];
	}

	// set variables
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../upload/asset_bill');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

	$files = array();
	foreach ($_FILES["filePO"] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	foreach ($files as $file) {
		$handle = new Upload($file);

		if($handle->uploaded) {
			// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
			//$prepend = date("YmdHis")."_";
			$handle->file_name_body_pre = $prepend;
			$handle->Process($dir_dest);    
			if ($handle->processed) 
			{
				$pathfile=$handle->file_dst_name;
				
				$filePO_oldfile = $pathfile;			
				$filePO_newfile = md5_file("../upload/asset_bill/$pathfile", FALSE);
				
				$filePO_cuttext = split("\.",$pathfile);
				$filePO_nubtext = count($filePO_cuttext);
				$filePO_newfile = "$filePO_newfile.".$filePO_cuttext[$filePO_nubtext-1];
				
				$filePO_newfile = $nowdateTime."_".$filePO_newfile; // ใส่วันเวลาไว้หน้าไฟล์
				
				$filePOfile = "'$filePO_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
				
				$flgRename = rename("../upload/asset_bill/$filePO_oldfile", "../upload/asset_bill/$filePO_newfile");
				if($flgRename)
				{
					//echo "บันทึกสำเร็จ";
				}
				else
				{
					echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
					$status++;
				}
			}
			else
			{
				echo '<fieldset>';
				echo '  <legend>file not uploaded to the wanted location</legend>';
				echo '  Error: ' . $handle->error . '';
				echo '</fieldset>';
				$status++;
				$filePOfile = "NULL";
			}
		}
		else
		{
			$filePOfile = "NULL";
		}
	}
}
else
{ // ถ้าใช้ไฟล์เดิมจากเมนูแก้ไข
	$filePOfile = checknull($oldFilePO);
}
// จบ add file upload เลขที่ใบสั่งซื้อ
//POPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPOPO

//add file upload เลขที่ใบเสร็จ RECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPT
if($reuseReceipt != "on")
{
	$cli = (isset($argc) && $argc > 1);
	if ($cli) {
		if (isset($argv[1])) $_GET['file'] = $argv[1];
		if (isset($argv[2])) $_GET['dir'] = $argv[2];
		if (isset($argv[3])) $_GET['pics'] = $argv[3];
	}

	// set variables
	$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../upload/asset_bill');
	$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

	$files = array();
	foreach ($_FILES["fileReceipt"] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	foreach ($files as $file) {
		$handle = new Upload($file);

		if($handle->uploaded) {
			// ใส่วันที่และเวลาเข้าไป prepend หน้าไฟล์เพื่อป้องกันกรณี upload ไฟล์ชื่อซ้ำ
			//$prepend = date("YmdHis")."_";
			$handle->file_name_body_pre = $prepend;
			$handle->Process($dir_dest);    
			if ($handle->processed) 
			{
				$pathfile=$handle->file_dst_name;
				
				$fileReceipt_oldfile = $pathfile;			
				$fileReceipt_newfile = md5_file("../upload/asset_bill/$pathfile", FALSE);
				
				$fileReceipt_cuttext = split("\.",$pathfile);
				$fileReceipt_nubtext = count($fileReceipt_cuttext);
				$fileReceipt_newfile = "$fileReceipt_newfile.".$fileReceipt_cuttext[$fileReceipt_nubtext-1];
				
				$fileReceipt_newfile = $nowdateTime."_".$fileReceipt_newfile; // ใส่วันเวลาไว้หน้าไฟล์
				
				$fileReceiptfile = "'$fileReceipt_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
				
				$flgRename = rename("../upload/asset_bill/$fileReceipt_oldfile", "../upload/asset_bill/$fileReceipt_newfile");
				if($flgRename)
				{
					//echo "บันทึกสำเร็จ";
				}
				else
				{
					echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
					$status++;
				}
			}
			else
			{
				echo '<fieldset>';
				echo '  <legend>file not uploaded to the wanted location</legend>';
				echo '  Error: ' . $handle->error . '';
				echo '</fieldset>';
				$status++;
				$fileReceiptfile = "NULL";
			}
		}
		else
		{
			$fileReceiptfile = "NULL";
		}
	}
}
else
{
	$fileReceiptfile = checknull($oldFileReceipt);
}
// จบ add file upload เลขที่ใบเสร็จ
//RECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPTRECEIPT

$qry_addAssets = "insert into public.\"thcap_asset_biz_temp\"(\"assetID\", \"compID\", \"corpID\", \"buyDate\", \"payDate\", \"doerID\", \"doerStamp\", \"receiptNumber\", \"vatStatus\", \"beforeVat\", \"VAT_value\", \"afterVat\", \"PurchaseOrder\", \"pathFilePO\", \"pathFileReceipt\")
					values('0', '$buyer', '$CusID', '$datepicker_buy', $datepicker_pay, '$id_user', '$logs_any_time', $receiptNumber, $howVat, $beforeVat, $myVat, $afterVat, $PurchaseOrder, $filePOfile, $fileReceiptfile) ";
if($result = pg_query($qry_addAssets)){
	// หารหัส temp
	$qryTemp = pg_query("select max(\"tempID\") from \"thcap_asset_biz_temp\" where \"assetID\" = '0' and \"compID\" = '$buyer' and \"corpID\" = '$CusID'
						and \"doerID\" = '$id_user' and \"doerStamp\" = '$logs_any_time' $tempWhere ");
	$tempID = pg_fetch_result($qryTemp,0);
}
else{
	$status++;
}

for($i=1; $i<=$rowDetail; $i++)
{
	$brand[$i] = $_POST["brand$i"]; // ชื่อยี่ห้อ
	$assetType[$i] = $_POST["assets$i"]; // รหัสประเภทสินทรัพย์
	$model[$i] = $_POST["model$i"]; // ชื่อรุ่น
	$explanation[$i] = $_POST["explanation$i"]; // คำอธิบาย
	$costPerPiece[$i] = $_POST["costPerPiece$i"]; // ราคาต่อชิ้น
	$codeProduct[$i] = $_POST["codeProduct$i"]; // รหัสสินค้า
	$codeSecondary[$i] = $_POST["codeSecondary$i"]; // รหัสสินค้ารอง
	$vatValue[$i] = $_POST["vatValue$i"]; // ยอด vat แต่ละรายการ
	$productStatus[$i] = $_POST["productStatus$i"]; // สถานะสินค้า
	$amount[$i] = $_POST["amount$i"]; // จำนวนชิ้น
	
	// เก็บประวัติการเลือก brand กับ assetType
	SetFavoriteAssetType($brand[$i],$assetType[$i]); 
	
	if(!is_numeric($amount[$i]))
	{ // ถ้าจำนวนชิ้นไม่ใช่ตัวเลข
		$status++;
		echo "จำนวนชิ้นไม่ใช่ตัวเลข<br>";
		continue;
	}
	
	$codeProduct[$i] = checknull($codeProduct[$i]);
	$explanation[$i] = checknull($explanation[$i]);
	$costPerPiece[$i] = checknull($costPerPiece[$i]);
	$codeSecondary[$i] = checknull($codeSecondary[$i]);
	$vatValue[$i] = checknull($vatValue[$i]);
	$productStatus[$i] = checknull($productStatus[$i]);
	
	for($x=1; $x<=$amount[$i]; $x++)
	{
		$qry_addAssetDetail = "insert into public.\"thcap_asset_biz_detail_temp\"(\"assetID\", \"brand\", \"astypeID\", \"model\", \"explanation\", \"pricePerUnit\", \"productCode\", \"doerID\", \"doerStamp\", \"receiptNumber\", \"secondaryID\", \"VAT_value\", \"ProductStatusID\", \"tempAssetID\")
							values('0', '$brand[$i]', '$assetType[$i]', '$model[$i]', $explanation[$i], $costPerPiece[$i], $codeProduct[$i], '$id_user', '$logs_any_time', $receiptNumber, $codeSecondary[$i], $vatValue[$i], $productStatus[$i], '$tempID')";
		if($result = pg_query($qry_addAssetDetail)){
		}
		else{
			$status++;
		}
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ซื้อสินทรัพย์สำหรับเช่า-ขาย', '$logs_any_time')";
		if($result = pg_query($sqlaction)){}else{$status++;}
	//ACTIONLOG---
	
	pg_query("COMMIT");
	
	IF($appvauto == 't'){
		$txtauto = "อนุมัติโดยระบบอัตโนมัติ";
	?>
		<form name="frm" action="process_appvAssets.php" method="POST">
			<input type="hidden" name="method" value="approve">
			<input type="hidden" name="statusapp" value="1">
			<input type="hidden" name="tempID" value="<?php echo $tempID; ?>">
			<input type="hidden" name="chksell" value="1">
			<input type="hidden" name="rule" value="1">
			<input type="hidden" name="cusContact" value="<?php echo $txtauto; ?>">
			<input type="hidden" name="cusPost" value="<?php echo $txtauto; ?>">
			<input type="hidden" name="cusTel" value="0000000">
			<input type="hidden" name="dateContact" value="<?php echo nowDateTime();?>">
			<input type="hidden" name="note" value="<?php echo $txtauto; ?>">
			<input type="hidden" name="appvauto" value="<?php echo $appvauto; ?>">
			
		</form>
	<?php	
		echo "<script type='text/javascript'>document.frm.submit();</script>";
	
	}else{
	
	
		echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
		//echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
		?>
		 <meta http-equiv='refresh' content='2; URL=frm_Index.php'> 
		<?php
	
	}
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	//echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
	?>
	<meta http-equiv='refresh' content='4; URL=frm_Index.php'>
	<?php
}
//--------------- จบการบันทึกข้อมูล
?>
</html>