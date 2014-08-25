<?php
session_start();
require('../../thaipdfclass.php');
include("../../config/config.php");
$cid=pg_escape_string($_GET["cus_lid"]);

$qry_print=pg_query("select \"cusName\",\"addressCon\" from thcap_letter_send WHERE auto_id='$cid'");
$res_print=pg_fetch_array($qry_print);

$cusName=trim($res_print["cusName"]); //ลูกค้าที่รับจดหมายกรณีเลือกที่อยู่ในสัญญา
$addressCon=trim($res_print["addressCon"]); //ที่อยู่ในสัญญา

			
$arti="ส่ง";		

$pdf=new ThaiPDF('P' ,'mm','letter_av');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

if($cusName!=""){		 			
	$pdf->AddPage();
	$pdf->SetFont('AngsanaNew','',18);
			
	$col=48;
				
	$pdf->SetXY(75,40);
	$av_arti=iconv('UTF-8','windows-874',$arti); //ส่ง
	$pdf->MultiCell(180,8,$av_arti,0,'L',0);
			
	$pdf->SetXY(100,$col);
	$av_name=iconv('UTF-8','windows-874',trim($cusName)); //ชื่อ
	
	$GetStringWidth = $pdf->GetStringWidth($av_name);
    $GetStringWidth = ceil( ($GetStringWidth/130) );
    $pdf->SetXY(100,$col+5);
    $pdf->MultiCell(132,6,$av_name,0,'L',0);

    $GetStringWidth=$GetStringWidth*4;
    $col+=(15+$GetStringWidth);
	
	$pdf->SetXY(100,$col);
	$av_ads=iconv('UTF-8','windows-874',$addressCon); 
	$pdf->MultiCell(132,6,$av_ads,0,'L',0);
}

//กรณีเลือกที่ส่งอื่นๆ
$qry_print2=pg_query("select \"receiveName\",\"addrCus\" from thcap_letter_detail WHERE \"sendID\"='$cid'");
$numrows=pg_num_rows($qry_print2);
while($res_print2=pg_fetch_array($qry_print2)){
	$receiveName=trim($res_print2["receiveName"]); //ชื่อลูกค้า
	$addrCus=trim($res_print2["addrCus"]); //ที่อยู่ลูกค้า
	
	$pdf->AddPage();
	$pdf->SetFont('AngsanaNew','',18);
			
	$col=10;
			
	$pdf->SetXY(75,40);
	$av_arti=iconv('UTF-8','windows-874',$arti); //ส่ง
	$pdf->MultiCell(180,8,$av_arti,0,'L',0);
		
		 
	$pdf->SetXY(100,48);
	$av_name=iconv('UTF-8','windows-874',$receiveName); //ชื่อ
	$pdf->MultiCell(130,8,$av_name,0,'L',0);
		
	$pdf->SetXY(100,56);
	$av_ads=iconv('UTF-8','windows-874',$addrCus); 
	$pdf->MultiCell(120,6,$av_ads,0,'L',0);
}
		
$pdf->Output();		
?>