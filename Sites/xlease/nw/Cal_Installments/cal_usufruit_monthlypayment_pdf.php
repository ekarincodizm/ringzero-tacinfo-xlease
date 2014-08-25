<?php
session_start();
require('../../config/config.php');
require('../../thaipdfclass.php');	

$userid = $_SESSION["av_iduser"]; //รหัสผู้ใช้งาน
$timestamp = date("Y-m-d H:i:s"); //วันเวลาที่ใช้งาน
//หาชื่อผู้ใช้
$qry_username = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$userid'");
list($fullname_doer) = pg_fetch_array($qry_username);
	
		$pay06_datestartcon = $_GET["datestartcon"]; //วันที่ทำสัญญา
		$pay06_investment = $_GET["tbmoney"]; //ยอดจัด/ยอดลงทุน
		$pay06_interest = $_GET["interest"]; //อัตราดอกเบี้ยต่อปี
		$pay06_month = $_GET["month"]; //จำนวนเดือน
		$pay06_payday = $_GET["payday"]; //ชำระทุกวันที่
		$pay06_datestart = $_GET["datestart"]; //วันที่เริ่มจ่าย
		$nw_province = $_GET["nw_province"]; //จังหวัด
		$pay06_payother = $_GET["payother"]; //ค่าใช้จ่ายอื่นๆ
		
		$paypermonth = round($pay06_investment * ((($pay06_interest*($pay06_month/12))+100)/100)*(1/$pay06_month),2); //ยอดผ่อนต่อเดือน
		$paypermonthshow = number_format($paypermonth,2); //ยอดค่าประกันต่อเดือนที่คำนวณได้
		$pay06_investmentshow = number_format($pay06_investment,2);
		$pay06_interestshow = number_format($pay06_interest,2);

		$qry_prov = pg_query("select * from \"price_messenger_rate\" WHERE \"pmr_serial\" = '$nw_province'"); 
		$re_prov = pg_fetch_array($qry_prov);
		$pmr_destination = $re_prov["pmr_destination"]; //ชื่อจังหวัด
		$pmr_price_inc_vat = $re_prov["pmr_price_inc_vat"]; //ค่าใช้จ่าย
		$pmr_price_inc_vatshow = number_format($pmr_price_inc_vat,2);
		
		IF($pay06_payother != ""){
				$pay06_payothershow = number_format($pay06_payother,2);
				$pay06_payothershow = "ค่าใช้จ่ายอื่นๆ $pay06_payothershow บาท";
		}
		
		//รวมเงินที่ต้องเก็บจากลูกค้า
		$total_payother = $pmr_price_inc_vat + $pay06_payother;
		$total_payothershow = number_format($total_payother,2);
		
		//คำนวณค่าเช่าต่อบุคคลภายนอก
			$hire_perother = ceil($paypermonth);
			$v2 = substr($hire_perother,-1,1);
			while($v2 != 0){			
				$hire_perother++;
				$v2 = substr($hire_perother,-1,1);
			}
			$pay_simshow = number_format($hire_perother,2);
			$hire_perother = $hire_perother + (($hire_perother*20)/100);
			$hire_perothershow = number_format($hire_perother,2);
	
	$pdf=new ThaiPDF('L' ,'mm','a4');
	$pdf->SetThaiFont();
	$pdf->AddPage();
	$pdf->AliasNbPages( 'tp' );
	$pdf->PageNo();
	$pdf->Image("images/water_line.png",60,15,180); 
	
	
	$pdf->SetXY(0,10);
	$pdf->SetFont('AngsanaNew','B',18);	
	$head=iconv('UTF-8','windows-874',"ตารางการประกันรายได้");
	$pdf->MultiCell(300,3,$head,0,'C',0);
	$pdf->Ln();	
	
	$X = 10;
	$Y = 25;
	$line = 5;
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$head1=iconv('UTF-8','windows-874',"ผู้พิมพ์: ".$fullname_doer." วันเวลาที่พิมพ์: ".$timestamp);
	$pdf->MultiCell(200,0,$head1,0,'L',0);
	
	$pdf->SetXY($X,$Y += $line);
	$head3=iconv('UTF-8','windows-874',"วันที่ทำสัญญา: $pay06_datestartcon  ยอดลงทุน: ".number_format($pay06_investment,2)." บาท   จำนวนเดือน $pay06_month   ค่าเช่าต่อบุคคลภายนอก $hire_perothershow บาท");
	$pdf->MultiCell(280,5,$head3,0,'L',0);
	
	$newcol = 0;
	$Y2 = 40;
	$X1 = 10;
	$X2 = 25;
	$X3 = 50;
	$X4 = 75;
	$count = 1;
	FOR($i = 1 ;$i <= $pay06_month; $i++){
			
			if($count > 75){
				$pdf->AddPage();
				$pdf->Image("images/water_line.png",60,15,180); 
				
				
				$pdf->SetXY(0,10);
				$pdf->SetFont('AngsanaNew','B',18);	
				$head=iconv('UTF-8','windows-874',"ตารางการประกันรายได้");
				$pdf->MultiCell(300,3,$head,0,'C',0);
				$pdf->Ln();	
				
				$X = 10;
				$Y = 25;
				$line = 6;
				
				$pdf->SetFont('AngsanaNew','',14);
				$pdf->SetXY($X,$Y);
				$head1=iconv('UTF-8','windows-874',"ผู้พิมพ์: ".$fullname_doer." วันเวลาที่พิมพ์: ".$timestamp);
				$pdf->MultiCell(200,0,$head1,0,'L',0);
				
				$pdf->SetXY($X,$Y += $line);
				$head3=iconv('UTF-8','windows-874',"วันที่ทำสัญญา: $pay06_datestartcon  ยอดลงทุน: ".number_format($pay06_investment,2)." บาท   จำนวนเดือน $pay06_month   ค่าเช่าต่อบุคคลภายนอก $hire_perothershow บาท");
				$pdf->MultiCell(280,5,$head3,0,'L',0);
				
				$newcol = 0;
				$Y2 = 40;
				$X1 = 10;
				$X2 = 25;
				$X3 = 50;
				$X4 = 75;
				$count = 1;
			}
			
			if($count == 26){
				$newcol = 0;
				$Y2 = 40;
				$X1 = 105;
				$X2 = 120;
				$X3 = 145;
				$X4 = 170;
			}else if($count == 51){
				$newcol = 0;
				$Y2 = 40;
				$X1 = 200;
				$X2 = 215;
				$X3 = 240;
				$X4 = 265;
			}
			
	
			if($i == 1){
				$nextConDue = $pay06_datestart;
			}else{
				$arrayConDue = explode("-",$nextConDue);						
				$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$pay06_payday,$arrayConDue[0]);
				$nextConDue = date("Y-m-d",$plusConDue);
			}
	
			if($newcol == 0){
				$pdf->SetFont('AngsanaNew','',13);
				$pdf->SetXY($X1,$Y2);
				$head1=iconv('UTF-8','windows-874',"เดือนที่");
				$pdf->MultiCell(15,5,$head1,1,'C',0);
				$pdf->SetXY($X2,$Y2);
				$head1=iconv('UTF-8','windows-874',"วันที่ครบกำหนด");
				$pdf->MultiCell(25,5,$head1,1,'C',0);
				$pdf->SetXY($X3,$Y2);
				$head1=iconv('UTF-8','windows-874',"ยอดประกัน");
				$pdf->MultiCell(25,5,$head1,1,'C',0);
				$pdf->SetXY($X4,$Y2);
				$head1=iconv('UTF-8','windows-874',"วันที่ชำระ");
				$pdf->MultiCell(25,5,$head1,1,'C',0);
				$newcol = 1;
			}
			
			$pdf->SetFont('AngsanaNew','',14);
			$Y2 += 5;
			$pdf->SetXY($X1,$Y2);
			$head1=iconv('UTF-8','windows-874',$i);
			$pdf->MultiCell(15,5,$head1,1,'C',0);
			$pdf->SetXY($X2,$Y2);
			$head1=iconv('UTF-8','windows-874',$nextConDue);
			$pdf->MultiCell(25,5,$head1,1,'C',0);
			$pdf->SetXY($X3,$Y2);
			$head1=iconv('UTF-8','windows-874',$pay_simshow);
			$pdf->MultiCell(25,5,$head1,1,'R',0);
			$pdf->SetXY($X4,$Y2);
			$head1=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(25,5,$head1,1,'R',0);
			$count++;
	}
	

	

	
	$pdf->Output();
	
?>	