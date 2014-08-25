<?php
include("../../config/config.php");
require('../../thaipdfclass.php');

function chk_null($data,$type="text")
{
	$re_data = "";
	if($data=="")
	{
		$re_data = "<ไม่มีข้อมูล>";
	}
	else
	{
		if($type=="money")
		{
			$re_data = number_format($data,2,".",",");
		}
		else
		{
			$re_data = $data;
		}
	}
	
	return $re_data;
}

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$tempID = $_GET['assetid'];
$iduser = $_SESSION['av_iduser'];
$qr_user = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$iduser'");
$rs_user = pg_fetch_array($qr_user);
$user_name = $rs_user['fullname'];
$realdata = $_GET["realdata"];

//ตรวจสอบว่าไอดีที่ถูกส่งมานั้นมีการอนุมัติข้อมูลแล้วหรือไม่
		$qry_chk_biz = pg_query("select \"Approved\",\"assetID\" from \"thcap_asset_biz_temp\" where \"tempID\"='$tempID'");
		list($appvstatus,$assetID) = pg_fetch_array($qry_chk_biz);
		
		//หาก realdata มีค่า = 1 แสดงว่ามาจากเมนู ดูสินทรัพย์สำหรับเช่า-ขาย ให้แสดงข้อมูลจริง
		if($realdata == '1'){
			$assetID = $tempID;
			$appvstatus = 't';
		}
		
		//หากอนุมัติแล้วให้เอาข้อมูลจากตารางจริง
	IF($appvstatus == 't'){	
		$qry_asset_biz = pg_query("select * from \"thcap_asset_biz\" where \"assetID\"='$assetID'");
	
		$qry_asset_biz_detail_temp = pg_query("select a.*,b.\"model_name\",c.\"brand_name\" from \"thcap_asset_biz_detail\" a
																left join \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
																left join \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
																where a.\"assetID\" = '$assetID'");
	//หากยังไม่อนุมัติให้เอาข้อมูลจากตาราง temp	
	}else{
		$assetID = $tempID;
		$qry_asset_biz = pg_query("select * from \"thcap_asset_biz_temp\" where \"tempID\"='$assetID'");
		
		$qry_asset_biz_detail_temp = pg_query("			select a.*,b.*,c.\"model_name\",d.\"brand_name\" 
														from \"thcap_asset_biz_temp\" a
														left join \"thcap_asset_biz_detail_temp\" b 
														ON b.\"doerID\" = a.\"doerID\" AND b.\"doerStamp\" = a.\"doerStamp\" AND 
														b.\"appvID\" = a.\"appvID\" AND b.\"appvStamp\" = a.\"appvStamp\" AND b.\"Approved\" = a.\"Approved\" 
														
														
														left join \"thcap_asset_biz_model\" c ON b.\"model\" = c.\"modelID\"
														left join \"thcap_asset_biz_brand\" d ON b.\"brand\" = d.\"brandID\"
														where a.\"tempID\" = '$assetID'");
														
														
	}

while($res_asset_biz = pg_fetch_array($qry_asset_biz))
{
	$compID = $res_asset_biz["compID"]; // ID บริษัท (ผู้ซื้อ)
	$corpID = $res_asset_biz["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
	$PurchaseOrder = $res_asset_biz["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
	$receiptNumber = $res_asset_biz["receiptNumber"]; // เลขที่ใบเสร็จ
	$buyDate = $res_asset_biz["buyDate"]; // วันที่ซื้อ
	$payDate = $res_asset_biz["payDate"]; // วันที่จ่ายเงิน
	$vatStatus = $res_asset_biz["vatStatus"]; // ประเภท vat
	$beforeVat = $res_asset_biz["beforeVat"]; // ราคาก่อน vat
	$mainVAT_value = $res_asset_biz["VAT_value"]; // ยอด vat
	$afterVat = $res_asset_biz["afterVat"]; // ราคาหลังรวม vat
	$pathFileReceipt = $res_asset_biz["pathFileReceipt"]; // ไฟล์ใบเสร็จ
	$pathFilePO = $res_asset_biz["pathFilePO"]; // ไฟล์ใบสั่งซื้อ
	
	if($vatStatus == 1)
	{
		$vatStatusTXT = "VAT แยก";
	}
	elseif($vatStatus == 2)
	{
		$vatStatusTXT = "VAT รวม";
	}
	else
	{
		$vatStatusTXT = "ไม่ระบุ";
	}
}

// หาชื่อบริษัท (ผู้ซื้อ)
$qry_nameCom = pg_query("select * from public.\"thcap_company\" where \"compID\" = '$compID' ");
while($result_name = pg_fetch_array($qry_nameCom))
{
	$compThaiName = $result_name["compThaiName"]; // ชื่อของ บริษัท (ผู้ซื้อ)
}

// หาชื่อบริษัท (ผู้ขาย)
$qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
while($result_name = pg_fetch_array($qry_nameCorp))
{
	$fullnameCorp = $result_name["full_name"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
}


class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);

$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายการสินทรัพย์สำหรับเช่า-ขาย");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : $user_name");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ : $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);

$pdf->SetXY(5,45);
$buss_name=iconv('UTF-8','windows-874',"ผู้ซื้อ : $compThaiName");
$pdf->MultiCell(115,4,$buss_name,0,'L',0);

$pdf->SetXY(120,45);
$buss_name=iconv('UTF-8','windows-874',"ผู้ขาย : $fullnameCorp");
$pdf->MultiCell(115,4,$buss_name,0,'L',0);

$pdf->SetXY(235,45);
$buss_name=iconv('UTF-8','windows-874',"ประเภท VAT : $vatStatusTXT");
$pdf->MultiCell(60,4,$buss_name,0,'L',0);

$pdf->SetXY(5,55);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบสั่งซื้อ : $PurchaseOrder");
$pdf->MultiCell(72,4,$buss_name,0,'L',0);

$pdf->SetXY(77,55);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ : $receiptNumber");
$pdf->MultiCell(72,4,$buss_name,0,'L',0);

$pdf->SetXY(149,55);
$buss_name=iconv('UTF-8','windows-874',"วันที่ซื้อ : $buyDate");
$pdf->MultiCell(72,4,$buss_name,0,'L',0);

$pdf->SetXY(221,55);
$buss_name=iconv('UTF-8','windows-874',"วันที่จ่ายเงิน : $payDate");
$pdf->MultiCell(72,4,$buss_name,0,'L',0);

$pdf->SetXY(5,57);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,65);
$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,65);
$buss_name=iconv('UTF-8','windows-874',"ประเภทสินทรัพย์");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(50,65);
$buss_name=iconv('UTF-8','windows-874',"ชื่อยี่ห้อ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,65);
$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(115,65);
$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้า (SN.)");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(145,65);
$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้ารอง (2nd SN.)");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(180,65);
$buss_name=iconv('UTF-8','windows-874',"ต้นทุน/ชิ้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(205,65);
$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(230,65);
$buss_name=iconv('UTF-8','windows-874',"สถานะสินค้า");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,65);
$buss_name=iconv('UTF-8','windows-874',"การมีอยู่ของสินค้า");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(5,67);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$start = 75;
$row = 1;

$number = 1;
while($res_asset_biz_detail_temp = pg_fetch_array($qry_asset_biz_detail_temp))
{
	$brand = $res_asset_biz_detail_temp["brand_name"]; // ชื่อยี่ห้อ
	$astypeID = $res_asset_biz_detail_temp["astypeID"]; // ประเภทสินทรัพย์
	$model = $res_asset_biz_detail_temp["model_name"]; // ชื่อรุ่น
	$explanation = str_replace("\r\n"," ",str_replace("®","",$res_asset_biz_detail_temp["explanation"])); // คำอธิบาย
	$pricePerUnit = $res_asset_biz_detail_temp["pricePerUnit"]; // ต้นทุนราคารวมภาษี/ชิ้น
	$productCode = $res_asset_biz_detail_temp["productCode"]; // รหัสสินค้า
	$secondaryID = $res_asset_biz_detail_temp["secondaryID"]; // รหัสสินค้ารอง
	$VAT_value = $res_asset_biz_detail_temp["VAT_value"]; // ยอด VAT
	$ProductStatusID = $res_asset_biz_detail_temp["ProductStatusID"]; // สถานะสินค้า
	$materialisticStatus = $res_asset_biz_detail_temp["materialisticStatus"];
	if($materialisticStatus=="0")
	{
		$materialisticStatus = "ไม่มีสินค้าแล้ว";
	}
	else if($materialisticStatus=="1")
	{
		$materialisticStatus = "มีสินค้าพร้อมใช้";
	}
	else if($materialisticStatus=="2")
	{
		$materialisticStatus = "สินค้าถูกนำไปใช้อยู่";
	}
	
	// หาชื่อประเภทสินทรัพย์
	$qry_astype = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeID\" = '$astypeID' ");
	while($result_astype = pg_fetch_array($qry_astype))
	{
		$astypeName = $result_astype["astypeName"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
	}
	
	// หาชื่อสถานะสินค้า
	if($ProductStatusID != "") // ถ้าไม่ว่าง
	{
		$qry_pStatus = pg_query("select * from public.\"ProductStatus\" where \"ProductStatusID\" = '$ProductStatusID' ");
		$row_pStatus = pg_num_rows($qry_pStatus);
		if($row_pStatus > 0) // ถ้ามีข้อมูล
		{
			while($result_pStatus = pg_fetch_array($qry_pStatus))
			{
				$ProductStatusName = $result_pStatus["ProductStatusName"]; // ชื่อของ สถานะสินค้า
			}
		}
		else // ถ้าไม่มีข้อมูล
		{
			$ProductStatusName = "";
		}
	}
	else // ถ้าไม่ได้ระบุไว้
	{
		$ProductStatusName = "";
	}
		
	if($row==5)
	{
		$start = 75;
		$row = 1;
		
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);

		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"(THCAP) รายการสินทรัพย์สำหรับเช่า-ขาย");
		$pdf->MultiCell(290,4,$title,0,'C',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : $user_name");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(5,35);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ : $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);
		
		$pdf->SetFont('AngsanaNew','',14);
		
		$pdf->SetXY(5,45);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ซื้อ : $compThaiName");
		$pdf->MultiCell(115,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(120,45);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ขาย : $fullnameCorp");
		$pdf->MultiCell(115,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(235,45);
		$buss_name=iconv('UTF-8','windows-874',"ประเภท VAT : $vatStatusTXT");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(5,55);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบสั่งซื้อ : $PurchaseOrder");
		$pdf->MultiCell(72,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(77,55);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ : $receiptNumber");
		$pdf->MultiCell(72,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(149,55);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ซื้อ : $buyDate");
		$pdf->MultiCell(72,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(221,55);
		$buss_name=iconv('UTF-8','windows-874',"วันที่จ่ายเงิน : $payDate");
		$pdf->MultiCell(72,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(5,57);
		$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,65);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(20,65);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทสินทรัพย์");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(50,65);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อยี่ห้อ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(80,65);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(115,65);
		$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้า (SN.)");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(145,65);
		$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้ารอง (2nd SN.)");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(180,65);
		$buss_name=iconv('UTF-8','windows-874',"ต้นทุน/ชิ้น");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(205,65);
		$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(230,65);
		$buss_name=iconv('UTF-8','windows-874',"สถานะสินค้า");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(255,65);
		$buss_name=iconv('UTF-8','windows-874',"การมีอยู่ของสินค้า");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(5,67);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	}
		
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$start);
	$buss_name=iconv('UTF-8','windows-874',$number);
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(20,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($astypeName));
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(50,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($brand));
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(80,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($model));
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(115,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($productCode));
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(145,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($secondaryID));
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(180,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($pricePerUnit,"money"));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(205,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($VAT_value,"money"));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(230,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($ProductStatusName));
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(255,$start);
	$buss_name=iconv('UTF-8','windows-874',chk_null($materialisticStatus));
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);
	
	$start = $start+9;
	
	$pdf->SetXY(5,$start);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย : ".chk_null($explanation));
	$pdf->MultiCell(290,4,$buss_name,0,'L',0);
	
	$start = $start+19;
	
	$pdf->SetXY(5,$start-8);
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);
	$row++;
	$number++;
}
if(($row-1)<4)
{
	$pdf->SetFont('AngsanaNew','B',14);
	
	$pdf->SetXY(100,$start);
	$buss_name=iconv('UTF-8','windows-874',"ราคาก่อน VAT : ".chk_null($beforeVat,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(163,$start);
	$buss_name=iconv('UTF-8','windows-874',"ยอด VAT : ".chk_null($mainVAT_value,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(226,$start);
	$buss_name=iconv('UTF-8','windows-874',"ราคารวม VAT : ".chk_null($afterVat,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
}
else
{
	$pdf->AddPage();
	
	$pdf->SetFont('AngsanaNew','B',14);
	
	$pdf->SetXY(100,10);
	$buss_name=iconv('UTF-8','windows-874',"ราคาก่อน VAT : ".chk_null($beforeVat,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(163,10);
	$buss_name=iconv('UTF-8','windows-874',"ยอด VAT : ".chk_null($mainVAT_value,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(226,10);
	$buss_name=iconv('UTF-8','windows-874',"ราคารวม VAT : ".chk_null($afterVat,"money"));
	$pdf->MultiCell(63,4,$buss_name,0,'R',0);
}

$pdf->Output();
?>