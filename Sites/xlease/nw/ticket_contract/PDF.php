<?php
session_start();

require('../../thaipdfclass.php');
include('../../config/config.php');
		
		$ID = "FA-BK01-5500002/001";
		$company = " บริษัท  ไทยเอซ  แคปปิตอล  จำกัด";
		$cusname = "บริษัท  แสงเจริญธรรม  เทรดดิ้ง";
		$datenow = nowDate();
		$price = 5000000;
		$money = number_format($price);
		$moneytext = "( ห้าล้านบาทถ้วน )";
		$interest = " พร้อมด้วยดอกเบี้ย ในอัตราร้อยละ 15.00  ต่อปี";
		$companyaddress = "555  ถนนนวมินทร์  แขวงคลองกุ่ม  เขตบึงกุ่ม  กรุงเทพมหานคร  10240";
		$nameemp = "สมชาย เข็มกลัด";
		$dep = "( กรรมการผู้จัดการน้อย )";
		list($year,$month,$day)=explode("-",$datenow);
		if($month == 01){
			$month = "มกราคม";
		}else if($month == 02){
			$month = "กุมภาพันธ์";
		}else if($month == 03){
			$month = "มีนาคม";
		}else if($month == 04){
			$month = "เมษายน";
		}else if($month == 05){
			$month = "พฤษภาคม";	
		}else if($month == 06){
			$month = "มิถุนายน";	
		}else if($month == 07){
			$month = "กรกฎาคม";
		}else if($month == 08){
			$month = "สิงหาคม";
		}else if($month == 09){
			$month = "ตุลาคม";
		}else if($month == 10){
			$month = "กันยายน";
		}else if($month == 11){
			$month = "พฤศจิกายน";
		}else if($month == 12){
			$month = "ธันวาคม";
		}
		$year = $year + 543;
		$date = $day." ".$month." ".$year;
		
		list($year,$month,$day)=explode("-",$datenow);
		if($month == 01){
			$month = "มกราคม";
		}else if($month == 02){
			$month = "กุมภาพันธ์";
		}else if($month == 03){
			$month = "มีนาคม";
		}else if($month == 04){
			$month = "เมษายน";
		}else if($month == 05){
			$month = "พฤษภาคม";	
		}else if($month == 06){
			$month = "มิถุนายน";	
		}else if($month == 07){
			$month = "กรกฎาคม";
		}else if($month == 08){
			$month = "สิงหาคม";
		}else if($month == 09){
			$month = "ตุลาคม";
		}else if($month == 10){
			$month = "กันยายน";
		}else if($month == 11){
			$month = "พฤศจิกายน";
		}else if($month == 12){
			$month = "ธันวาคม";
		}
		$year = $year + 543;	
		$datefinal = $day." ".$month." ".$year;
	$pdf=new ThaiPDF();
	$pdf->SetThaiFont();
	$pdf->AddPage();
	
	
	$Y = 10;
	$X = 0;
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"THAI ACE CAPITAL COMPANY LIMITED");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$pdf->SetFont('AngsanaNew','',16);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$ID);
	$pdf->MultiCell(195,4,$title,0,'R',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"555 ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพมหานคร 10240 โทร. 02-744-2222");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"ตั๋วสัญญาใช้เงิน");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"สถานที่ออกตั๋วสัญญาใช้เงิน"."  ".$company);
	$pdf->MultiCell(175,4,$title,0,'R',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"วันที่  "."  ".$date);
	$pdf->MultiCell(185,4,$title,0,'R',0);
	$pdf->Ln();
	
		
	$Y = $Y+16;
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"ข้าพเจ้า");
	$pdf->MultiCell(45,4,$title,0,'R',0);
	$pdf->Ln();
	
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(110,4,$title,0,'R',0);
	$pdf->Ln();
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"สัญญาว่าจะใช้เงิน จำนวน");
	$pdf->MultiCell(170,4,$title,0,'R',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$money." บาท ".$moneytext);
	$pdf->MultiCell(70,4,$title,0,'R',0);
	$pdf->Ln();

	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"ให้แก่ ".$company);
	$pdf->MultiCell(130,4,$title,0,'R',0);
	$pdf->Ln();
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$interest);
	$pdf->MultiCell(190,4,$title,0,'R',0);
	$pdf->Ln();
	
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"ในวันที่   "." ".$datefinal);
	$pdf->MultiCell(55,4,$title,0,'R',0);
	$pdf->Ln();
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"ณ. ที่ทำการ   "." ".$company);
	$pdf->MultiCell(185,4,$title,0,'R',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$companyaddress);
	$pdf->MultiCell(140,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+20;
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ลงชื่อ  ผู้ออกตั๋วสัญญาใช้เงิน ");
	$pdf->MultiCell(145,4,$title,0,'R',0);
	$pdf->Ln();
	
	$Y = $Y+8;
	$X = 150;
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$nameemp);
	$pdf->MultiCell(45,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+4;
	$pdf->SetFont('AngsanaNew','',8);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$dep);
	$pdf->MultiCell(45,4,$title,0,'C',0);
	$pdf->Ln();
	
	$pdf->SetXY(5,5);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(200,$Y+1,$title,1,'C',0);
	
	$pdf->SetXY(5.1,5.1);
	$title=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(199.8,$Y+0.8,$title,1,'C',0);
	
	/*
	$pdf->SetXY($x,$y);
	$pdf->MultiCell(190,4,'',T,'C',0);
	$pdf->Output();
	
	
	
	$x = 10;
	$y = 10;
	
	for($o=10;$o<=$Y;$o++){
	
	$y = $y+1;
	$pdf->SetXY($x,$y);
	$pdf->MultiCell(190,4,'',LR,'C',0);
	
	
	}
	
	$pdf->SetXY($x,$y);
	$pdf->MultiCell(190,4,'',B,'C',0);
	*/
	$pdf->Output();
?>
</body>
</html>

