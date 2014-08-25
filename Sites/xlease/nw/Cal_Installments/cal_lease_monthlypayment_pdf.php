<?php
session_start();
require('../../config/config.php');
require('../../thaipdfclass.php');	

$userid = $_SESSION["av_iduser"]; //รหัสผู้ใช้งาน
$timestamp = date("Y-m-d H:i:s"); //วันเวลาที่ใช้งาน
//หาชื่อผู้ใช้
$qry_username = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$userid'");
list($fullname_doer) = pg_fetch_array($qry_username);
	
		$pay04_datestart = $_GET["datestart"]; //วันที่ทำสัญญา
		$pay04_investment = $_GET["investment"]; //ยอดจัด/ยอดลงทุน
		$pay04_notvat = $_GET["vat"]; //รวม VAT หรือไม่
		$pay04_vatcal = $_GET["vatcal"]; //VAT
		$pay04_interest = $_GET["interest"]; //อัตราดอกเบี้ยต่อปี
		$pay04_month = $_GET["month"]; //จำนวนเดือน
		IF($pay04_notvat == 'sumvat'){
			$vattxt = "รวม";
		}else{
			$vattxt = "ไม่รวม";
		}
		
		IF($pay04_notvat == 'sumvat'){
		
			/*    
			- กรณี ratio เลือกรวม VAT
			ยอดผ่อนต่อเดือน = ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) - [ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) * (7/107)]
			ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
			[] - ปัดเศษ 2 ตำแหน่ง
			() - ไม่ปัดเศษ
			*/
			/*__คำนวณแบบคิดเศษทศนิยม____________*/
			$paypermonthsumvat = round($pay04_investment * (((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 
			$paypermonth = $paypermonthsumvat - round($paypermonthsumvat*(7/107),2); //ยอดผ่อนต่อเดือน	
			$paypermonthshow = number_format($paypermonth ,2);
			$paypermonthsumvatshow = number_format($paypermonthsumvat ,2);
			/*__คำนวณแบบปัดเศษทศนิยม____________*/
			$paypermonthsumvat2 = round($pay04_investment * (((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 
			$paypermonth2 = ceil($paypermonthsumvat2 - round($paypermonthsumvat2*(7/107),2)); //ยอดผ่อนต่อเดือน	
			$paypermonthshow2 = number_format($paypermonth2,2);
			$paypermonthsumvatshow2 = number_format($paypermonthsumvat2 ,2);
			
			
		}else{
		
			/* 	
			-กรณี ratio เลือก ไม่รวม VAT
			ยอดผ่อนต่อเดือน = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
			ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดผ่อนต่อเดือน * ((อัตราภาษี+100)/100)] 
			[] - ปัดเศษ 2 ตำแหน่ง
			() - ไม่ปัดเศษ
			*/
			/*__คำนวณแบบคิดเศษทศนิยม____________*/
			$paypermonth = round($pay04_investment * ((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month),2); //ยอดผ่อนต่อเดือน
			$paypermonthsumvat = round($paypermonth * (($pay04_vatcal+100)/100),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 	
			$paypermonthshow = number_format($paypermonth ,2);
			$paypermonthsumvatshow = number_format($paypermonthsumvat,2);
			/*__คำนวณแบบปัดเศษทศนิยม____________*/
			$paypermonth2 = ceil($pay04_investment * ((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)); //ยอดผ่อนต่อเดือน
			$paypermonthsumvat2 = round($paypermonth2 * (($pay04_vatcal+100)/100),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 	
			$paypermonthshow2 = number_format($paypermonth2 ,2);
			$paypermonthsumvatshow2 = number_format($paypermonthsumvat2 ,2);		
				
		}
	
	
	$pdf=new ThaiPDF('P' ,'mm','a4');
	$pdf->SetThaiFont();
	$pdf->AddPage();
	$pdf->AliasNbPages( 'tp' );
	$pdf->PageNo();
	$pdf->Image("images/water_line.png",20,50,180); 
	
	
	$pdf->SetXY(0,10);
	$pdf->SetFont('AngsanaNew','B',16);	
	$head=iconv('UTF-8','windows-874'," คำนวณหายอดผ่อนต่อเดือน");
	$pdf->MultiCell(210,3,$head,0,'C',0);
	$pdf->Ln();	
	
	$X = 25;
	$Y = 25;
	$line = 6;
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$head1=iconv('UTF-8','windows-874',"ผู้พิมพ์: ".$fullname_doer." วันเวลาที่พิมพ์: ".$timestamp);
	$pdf->MultiCell(200,0,$head1,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"วันที่ทำสัญญา: $pay04_datestart");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head1=iconv('UTF-8','windows-874',"ยอดจัด/ยอดลงทุน: ".number_format($pay04_investment,2)." บาท");
	$pdf->MultiCell(200,0,$head1,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"รวม VAT หรือไม่: $vattxt");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	IF($pay04_notvat != 'sumvat'){
		$pdf->SetXY($X,$Y += $line);
		$head3=iconv('UTF-8','windows-874',"VAT: ".number_format($pay04_vatcal,2)." %");
		$pdf->MultiCell(200,0,$head3,0,'L',0);
	}	
	
	$pdf->SetXY($X,$Y += $line);
	$head2=iconv('UTF-8','windows-874',"อัตราดอกเบี้ยต่อปี: ".number_format($pay04_interest,2)." %");
	$pdf->MultiCell(200,0,$head2,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"จำนวนเดือน: $pay04_month");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	
	$pdf->SetXY($X,$Y += $line+5);
	$head3=iconv('UTF-8','windows-874',"คำนวณแบบคิดเศษทศนิยม");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
			
	$pdf->SetXY($X,$Y += $line+3);
	$head3=iconv('UTF-8','windows-874',"ยอดผ่อนต่อเดือน: ".$paypermonthshow." บาท");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) : ".$paypermonthsumvatshow." บาท");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	
	$pdf->SetXY($X,$Y += $line+5);
	$head3=iconv('UTF-8','windows-874',"คำนวณแบบปัดเศษทศนิยม");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line+3);
	$head3=iconv('UTF-8','windows-874',"ยอดผ่อนต่อเดือน: ".$paypermonthshow2." บาท");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม): ".$paypermonthsumvatshow2." บาท");
	$pdf->MultiCell(200,0,$head3,0,'L',0);
	
	$pdf->Output();
	
?>	