<?php
include("../../config/config.php");
require('../../thaipdfclass.php');

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$type = $_GET['type'];
$order = $_GET['order'];

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

if($type=="all")
{
	$display = "สินค้าทั้งหมด(รวมสินค้าที่ให้เช่า - ขายไปแล้ว)";
}
else if($type=="active")
{
	$display = "สินค้าที่คงเหลือในบริษัท";
}
else
{
	$display = "สินค้าที่ให้เช่า-ขายไปแล้ว";
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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);

$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานสินทรัพย์สำหรับเช่า-ขาย");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ข้อมูลที่แสดง : ".$display);
$pdf->MultiCell(290,4,$buss_name1,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetXY(5,27);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,33);
$buss_name=iconv('UTF-8','windows-874',"ประเภทสินทรัพย์");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(55,33);
$buss_name=iconv('UTF-8','windows-874',"ชื่อยี่ห้อ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(85,33);
$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(120,33);
$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้า");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,39);
$buss_name=iconv('UTF-8','windows-874',"(SN.)");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,33);
$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้ารอง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,39);
$buss_name=iconv('UTF-8','windows-874',"(2nd SN.)");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(170,33);
$buss_name=iconv('UTF-8','windows-874',"ต้นทุน/ชิ้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(195,33);
$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(220,33);
$buss_name=iconv('UTF-8','windows-874',"สถานะสินค้า");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(255,33);
$buss_name=iconv('UTF-8','windows-874',"การมีอยู่ของสินค้า");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);


$pdf->SetXY(5,41);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$start = 50;
$row = 1;

if($type=="all")
{
	$q = "select * from \"vthcap_asset_biz_detail\" order by \"".$order."\"";
}
else if($type=="active")
{
	$q = "select * from \"vthcap_asset_biz_detail\" where \"materialisticstatus\"='มีสินค้าพร้อมใช้' order by \"".$order."\"";
}
else
{
	$q = "select * from \"vthcap_asset_biz_detail\" where \"materialisticstatus\"<>'มีสินค้าพร้อมใช้' order by \"".$order."\"";
}
$qr = pg_query($q);
if($qr)
{
	$number = 1;
	while($rs = pg_fetch_array($qr))
	{
		$brand = chk_null($rs['brand']);
		$astypeName = chk_null($rs['astypeName']);	//ประเภทสินทรัพย์
		$model = chk_null($rs['model']);
		$explanation = str_replace("\r\n"," ",str_replace("®","",chk_null($rs['explanation'])));	//คำอธิบาย
		$pricePerUnit = chk_null($rs['pricePerUnit'],"money");	//ราคาต่อหน่วย
		$productCode = chk_null($rs['productCode']);	//รหัสสินค้าหลัก
		$receiptNumber = chk_null($rs['receiptNumber']);	//เลขที่ใบเสร็จ
		$PurchaseOrder = chk_null($rs['PurchaseOrder']);	//เลขที่ใบสั่งซื้อ
		$secondaryID = chk_null($rs['secondaryID']);	//รหัสสินค้ารอง	
		$VAT_value = chk_null($rs['VAT_value'],"money");	//vat
		$ProductStatusName = chk_null($rs['ProductStatusName']);	//สถานะสินค้า
		$materialisticstatus = chk_null($rs['materialisticstatus']);	//สถานะการใช้งาน
		$RealmaterialisticStatus = chk_null($rs['RealmaterialisticStatus']);	//สถานะการใช้งานค่าจริง
		$contractID = $rs['contractID']; //เลขที่สัญญาที่ผูกกับสินค้า
		
		//หาว่าทรัพย์สินที่นำไปใช้ผูกกับสัญญาใด
		if($RealmaterialisticStatus!='1')
			{
				if($contractID!=""){
					$materialContract = $contractID;
				}else{
					$materialContract = "<ไม่พบเลขที่สัญญา>";
				}
			}
			else
			{
				$materialContract = $materialisticstatus;
			}
		
		if($row==6)
		{
			$start = 45;
			$row = 1;
			
			$pdf->AddPage();
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
			$pdf->MultiCell(290,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานสินทรัพย์สำหรับเช่า-ขาย");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(5,25);
			$buss_name1=iconv('UTF-8','windows-874',"ข้อมูลที่แสดง : ".$display);
			$pdf->MultiCell(290,4,$buss_name1,0,'L',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(290,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32);
			$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(20,32);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทสินทรัพย์");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(55,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อยี่ห้อ");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(85,32);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อรุ่น");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(120,32);
			$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้า");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(120,38);
			$buss_name=iconv('UTF-8','windows-874',"(SN.)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(145,32);
			$buss_name=iconv('UTF-8','windows-874',"รหัสสินค้ารอง");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(145,38);
			$buss_name=iconv('UTF-8','windows-874',"(2nd SN.)");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(170,32);
			$buss_name=iconv('UTF-8','windows-874',"ต้นทุน/ชิ้น");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(195,32);
			$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(220,32);
			$buss_name=iconv('UTF-8','windows-874',"สถานะสินค้า");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(255,32);
			$buss_name=iconv('UTF-8','windows-874',"การมีอยู่ของสินค้า");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);
			
			
			$pdf->SetXY(5,39);
			$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$start);
		$buss_name=iconv('UTF-8','windows-874',$number);
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(20,$start);
		$buss_name=iconv('UTF-8','windows-874',$astypeName);
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(55,$start);
		$buss_name=iconv('UTF-8','windows-874',$brand);
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(85,$start);
		$buss_name=iconv('UTF-8','windows-874',$model);
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(120,$start);
		$buss_name=iconv('UTF-8','windows-874',$productCode);
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(145,$start);
		$buss_name=iconv('UTF-8','windows-874',$secondaryID);
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(170,$start);
		$buss_name=iconv('UTF-8','windows-874',$pricePerUnit);
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(195,$start);
		$buss_name=iconv('UTF-8','windows-874',$VAT_value);
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(220,$start);
		$buss_name=iconv('UTF-8','windows-874',$ProductStatusName);
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(258,$start);
		$buss_name=iconv('UTF-8','windows-874',$materialContract);
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$start = $start+9;
		
		$pdf->SetXY(5,$start);
		$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย : ".$explanation);
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);
		
		$start = $start+19;
		
		$pdf->SetXY(5,$start-8);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);
		$row++;
		$number++;
	}
}

$pdf->Output();
?>