<?php
include("../../config/config.php");
require('../../thaipdfclass.php');

$contractID = $_GET['contractid'];
$currentDate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$doer_id = $_SESSION['av_iduser'];

$q = "select \"title\",\"fname\",\"lname\" from \"fuser\" where \"id_user\"='$doer_id'";
$qr = pg_query($q);
$rs = pg_fetch_array($qr);
$doer = $rs['title'].$rs['fname']."  ".$rs['lname'];

$pdf=new ThaiPDF('L' ,'mm','a4');  

$pdf->SetLeftMargin(20);
$pdf->SetTopMargin(20);
$pdf->SetThaiFont();

$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',20); 

$pdf->SetXY(20,20);
$title=iconv('UTF-8','windows-874','คำนวนภาษีหัก ณ ที่จ่าย');
$pdf->MultiCell(245,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetFont('AngsanaNew','B',14); 
$pdf->SetFillColor(2, 152,201);  

$pdf->SetXY(20,30);
$title=iconv('UTF-8','windows-874','รหัสประเภทค่าใช้จ่าย');
$pdf->MultiCell(20,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(40,30);
$title=iconv('UTF-8','windows-874','รายการ');
$pdf->MultiCell(30,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(70,30);
$title=iconv('UTF-8','windows-874','ค่าอ้างอิงของค่าใช้จ่าย');
$pdf->MultiCell(20,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(90,30);
$title=iconv('UTF-8','windows-874','วันที่ตั้งหนี้');
$pdf->MultiCell(20,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(110,30);
$title=iconv('UTF-8','windows-874','ผู้ตั้งหนี้');
$pdf->MultiCell(30,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(140,30);
$title=iconv('UTF-8','windows-874','วันเวลาตั้งหนี้');
$pdf->MultiCell(30,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(170,30);
$title=iconv('UTF-8','windows-874','จำนวนหนี้(ไม่รวม vat)');
$pdf->MultiCell(25,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(195,30);
$title=iconv('UTF-8','windows-874','ภาษีมูลค่าเพิ่ม');
$pdf->MultiCell(20,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(215,30);
$title=iconv('UTF-8','windows-874','จำนวนหนี้(รวม vat)');
$pdf->MultiCell(25,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(240,30);
$title=iconv('UTF-8','windows-874','ภาษีหัก ณ ที่จ่าย');
$pdf->MultiCell(25,5,$title,0,'C',0); //รายละเอียดการรับชำระ

$pdf->SetXY(20,38);
$title=iconv('UTF-8','windows-874','-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->MultiCell(245,5,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);

$qry_other = pg_query("select * from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
$row_other = pg_num_rows($qry_other);
if($row_other > 0)
{
	$qry_sum_other = pg_query("select sum(\"typePayAmt\") as \"summoney\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' ");
	while($res_sum = pg_fetch_array($qry_sum_other))
	{
		$summoney = $res_sum["summoney"]; // เงินรวม
	}
}
if($row_other > 0)
{
	$t = 0;
	$all_typePayAmt = 0;
	$all_whtAmt = 0;
	$all_debtNet = 0;
	$all_debtVat = 0;
	$start_y = 45;
	while($res_name=pg_fetch_array($qry_other))
	{
		$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
		$typePayRefValue=trim($res_name["typePayRefValue"]);
		$typePayRefDate=trim($res_name["typePayRefDate"]);
		$typePayAmt=trim($res_name["typePayAmt"]);
		$doerID=trim($res_name["doerID"]); 
		$doerStamp=trim($res_name["doerStamp"]);
		$debtID=trim($res_name["debtID"]);
		$debtNet = trim($res_name['debtNet']);
		$debtVat = trim($res_name['debtVat']);
		//$contractID=trim($res_name["contractID"]);
		
		$whtAmtfunc = pg_query("SELECT \"thcap_checkdebtwht\"('$debtID','$currentDate')");					
		$whtAmt1 = pg_fetch_array($whtAmtfunc);
		$whtAmt = $whtAmt1['thcap_checkdebtwht'];
		
		$all_typePayAmt = $all_typePayAmt+$typePayAmt;
		$all_whtAmt = $whtAmt+$all_whtAmt;
		$all_debtNet = $all_debtNet+$debtNet;
		$all_debtVat = $all_debtVat+$debtVat;
			
		$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
			
		if($doerID == "000")
		{
			$doerName = "อัตโนมัติโดยระบบ";
		}
		else
		{
			$doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
			while($res_username=pg_fetch_array($doerusername))
			{
				$doerName=$res_username["fullname"];
			}
		}
		
		$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
		while($res_type=pg_fetch_array($qry_type))
		{
			$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
		}
		
		$due = ""; // กำหนดวันดิวเป็นค่าว่าง เพื่อไม่ให้เก็บค่าเก่ามาใช้
		
		if($typePayID == "1003")
		{
			//-----------------ตัดส่วนเกินออก
			$search = strpos($typePayRefValue,"-");
			if($search)
			{
				$subtypePayRefValue = explode("-", $typePayRefValue);
				$typePayRefValue = $subtypePayRefValue[0];
			}
			//-----------------จบการตัดส่วนเกินออก
			
			$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
			while($res_due=pg_fetch_array($qry_due))
			{
				$ptDate=trim($res_due["ptDate"]); // วันดิว
				$due = "($ptDate)";
			}
		}
		else
		{
			$due = "";
		}
		$pdf->SetXY(20,$start_y);
		$title=iconv('UTF-8','windows-874',$typePayID);
		$pdf->MultiCell(20,5,$title,0,'C',0);
		
		$pdf->SetXY(40,$start_y);
		$title=iconv('UTF-8','windows-874',$tpDesc);
		$pdf->MultiCell(30,5,$title,0,'C',0);
		
		$pdf->SetXY(70,$start_y);
		$title=iconv('UTF-8','windows-874',$typePayRefValue." ".$due);
		$pdf->MultiCell(20,5,$title,0,'C',0);
		
		$pdf->SetXY(90,$start_y);
		$title=iconv('UTF-8','windows-874',$typePayRefDate);
		$pdf->MultiCell(20,5,$title,0,'C',0);
		
		$pdf->SetXY(110,$start_y);
		$title=iconv('UTF-8','windows-874',$doerName);
		$pdf->MultiCell(30,5,$title,0,'C',0);
		
		$pdf->SetXY(140,$start_y);
		$title=iconv('UTF-8','windows-874',$doerStamp);
		$pdf->MultiCell(30,5,$title,0,'C',0);
		
		$pdf->SetXY(170,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($debtNet,2));
		$pdf->MultiCell(25,5,$title,0,'C',0);
		
		$pdf->SetXY(195,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($debtVat,2));
		$pdf->MultiCell(20,5,$title,0,'C',0);
		
		$pdf->SetXY(215,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($typePayAmt,2));
		$pdf->MultiCell(25,5,$title,0,'C',0);
		
		$pdf->SetXY(240,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($whtAmt,2,'.',','));
		$pdf->MultiCell(25,5,$title,0,'C',0);
		
		$pdf->SetXY(20,$start_y+8);
		$title=iconv('UTF-8','windows-874','-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
		$pdf->MultiCell(245,5,$title,0,'C',0);
		
		$start_y+=15;
	}
	if($row_other!=0)
	{
		$pdf->SetFont('AngsanaNew','B',14);
		
		$pdf->SetXY(20,$start_y);
		$title=iconv('UTF-8','windows-874','ยอดรวม');
		$pdf->MultiCell(150,5,$title,0,'C',0);
		
		$pdf->SetXY(170,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($all_debtNet,2,'.',','));
		$pdf->MultiCell(25,5,$title,0,'C',0);
		
		$pdf->SetXY(195,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($all_debtVat,2,'.',','));
		$pdf->MultiCell(20,5,$title,0,'C',0);
		
		$pdf->SetXY(215,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($all_typePayAmt,2,'.',','));
		$pdf->MultiCell(25,5,$title,0,'C',0);
		
		$pdf->SetXY(240,$start_y);
		$title=iconv('UTF-8','windows-874',number_format($all_whtAmt,2,'.',','));
		$pdf->MultiCell(25,5,$title,0,'C',0);
	}
}
else
{
	$pdf->SetFont('AngsanaNew','B',14);
	
	$pdf->SetXY(20,$start_y);
	$title=iconv('UTF-8','windows-874','********************************** ไม่มีรายการ **********************************');
	$pdf->MultiCell(245,5,$title,0,'C',0);
}
$start_y+=20;

$pdf->SetFont('AngsanaNew','B',14);

$pdf->SetXY(20,$start_y);
$title=iconv('UTF-8','windows-874','ผู้ทำรายการ : '.$doer);
$pdf->MultiCell(245,5,$title,0,'R',0);

$start_y+=10;

$pdf->SetXY(20,$start_y);
$title=iconv('UTF-8','windows-874','วันที่ทำรายการ : '.$currentDate);
$pdf->MultiCell(245,5,$title,0,'R',0);
$pdf->Output(); //open pdf
?>