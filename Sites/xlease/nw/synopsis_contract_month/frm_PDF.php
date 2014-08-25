<?php
session_start();
require('../../thaipdfclass.php');
include('../../config/config.php');

$year = $_GET['year'];
$month = $_GET['month'];		
		
$sql = pg_query("SELECT * FROM thcap_contract where (EXTRACT(YEAR FROM \"conDate\")='$year') AND (EXTRACT(MONTH FROM \"conDate\")='$month') order by \"conDate\""); 
		
		
	$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม ","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน ","ธันวาคม");
		for($i=0; $i<=11; $i++){
			$m = $i+1;
				if($m > 0 && $m < 10){
					$m = "0".$m;
				}
			if($m == $month){
				$mm = $thaimonth[$i];
			
			}
				
		}			
							
			
		
	
	
	$pdf=new ThaiPDF('L' ,'mm','a4');
	$pdf->SetThaiFont();
	$pdf->AddPage();
	
	
	$pdf->SetXY(0,10);
	$pdf->SetFont('AngsanaNew','B',20);	
	$head=iconv('UTF-8','windows-874',"(THCAP) รายงานสรุปสัญญาใหม่ประจำเดือน");
	$pdf->MultiCell(300,10,$head,0,'C',0);
	$pdf->Ln();
	
	$pdf->SetXY(14,30);
	$pdf->SetFont('AngsanaNew','',16);	
	$head=iconv('UTF-8','windows-874',"ประจำเดือน"." ".$mm." "." ปี ".$year);
	$pdf->MultiCell(210,3,$head,0,'L',0);
	
	$pdf->SetXY(14,30);
	$pdf->SetFont('AngsanaNew','',16);	
	$head=iconv('UTF-8','windows-874',"วันที่พิมพ์ "." ".Date('Y-m-d'));
	$pdf->MultiCell(270,3,$head,0,'R',0);
	
	$X = 15;
	$Y = 35;
	
	$header=array('วันที่ทำสัญญา','เลขที่สัญญา','ประเภทสัญญา','รูปแบบสัญญา','จำนวนเงิน','อัตราดอกเบี้ยตกลง','จำนวนเดือนที่ผ่อนคือ','จำนวนเงินขั้นต่ำที่ต้องจ่าย','วันที่จ่ายครั้งแรก');

	//Colors, line width and bold font
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('AngsanaNew','',14);
		//Header
		$w=array(20,30,25,25,40,27,35,40,25);
		$pdf->SetXY($X,$Y);
		for($i=0;$i<count($header);$i++){
			
			$pdf->Cell($w[$i],7,iconv('UTF-8','windows-874',$header[$i]),'TLBR',0,'C',true);
			}
		$pdf->Ln();
		
		//Color and font restoration
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		//Data
		$fill=true;
		
		
		$Y = $Y+1.2	;
		$rows = 1;

while($result = pg_fetch_array($sql)){

	//รูปแบบสัญญา 
		if($result['conCredit'] == null && $result['conLoanAmt'] != null){ 
				$prototype = 'สัญญาเงินกู้';		  
		}else{	
				if($result['conCredit'] != null && $result['conLoanAmt'] == null){
					$prototype = 'สัญญาวงเงิน';
				}else{
					if($result['conCredit'] != null && $result['conLoanAmt'] != null){
						$prototype = 'สัญญาวงเงิน/สัญญาเงินกู้';
					}else{
						$prototype = 'ไม่ระบุ';
					}
				}
		  }
	//จำนวนเงิน 
		if($result['conLoanAmt'] != null AND $result['conCredit'] != null){ 
			$money = number_format($result['conLoanAmt'],2)."(".number_format($result['conCredit'],2).")"; 		
		}else{
				if($result['conLoanAmt'] == null AND $result['conCredit'] != null){ 
					$money = "0.00"."(".number_format($result['conCredit'],2).")";
				}else{
					$money = number_format($result['conLoanAmt'],2);
				}	
		} 

	
		if($rows == 24){
				
			$Y = $Y+6;
			
			$pdf->SetXY($X,$Y);
			$pdf->Cell(array_sum($w),0,'','T');
			$pdf->AddPage();
			
			$pdf->SetXY(0,10);
			$pdf->SetFont('AngsanaNew','B',20);	
			$head=iconv('UTF-8','windows-874',"(THCAP) รายงานสรุปสัญญาใหม่ประจำเดือน");
			$pdf->MultiCell(300,10,$head,0,'C',0);
			$pdf->Ln();
			
			$pdf->SetXY(14,30);
			$pdf->SetFont('AngsanaNew','',16);	
			$head=iconv('UTF-8','windows-874',"ประจำเดือน"." ".$mm." "." ปี ".$year);
			$pdf->MultiCell(210,3,$head,0,'L',0);
			
			$pdf->SetXY(14,30);
			$pdf->SetFont('AngsanaNew','',16);	
			$head=iconv('UTF-8','windows-874',"วันที่พิมพ์ "." ".Date('Y-m-d'));
			$pdf->MultiCell(270,3,$head,0,'R',0);
		
				$X = 15;
				$Y = 35;
					
				$header=array('วันที่ทำสัญญา','เลขที่สัญญา','ประเภทสัญญา','รูปแบบสัญญา','จำนวนเงิน','อัตราดอกเบี้ยตกลง','จำนวนเดือนที่ผ่อนคือ','จำนวนเงินขั้นต่ำที่ต้องจ่าย','วันที่จ่ายครั้งแรก');

			//Colors, line width and bold font
				$pdf->SetFillColor(255,255,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(128,0,0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('AngsanaNew','',14);
				//Header
				$w=array(20,30,25,25,40,27,35,40,25);
				$pdf->SetXY($X,$Y);
				for($i=0;$i<count($header);$i++){
					
					$pdf->Cell($w[$i],7,iconv('UTF-8','windows-874',$header[$i]),'TLBR',0,'C',true);
					}
				$pdf->Ln();
				
				//Color and font restoration
				$pdf->SetFillColor(255,255,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				//Data
				$fill=true;
				
				
				$Y = $Y+1.2	;
				$rows = 1;
			
		}
		$pdf->SetFont('AngsanaNew','',13);					
		$data=array($result['conDate'],$result['contractID'],$result['conType'],$prototype,$money,$result['conLoanIniRate'],
					$result['conTerm'],number_format($result['conMinPay'],2),$result['conFirstDue']);
			
			$Y = $Y+6;
			$pdf->SetXY($X,$Y);
			$pdf->Cell($w[0],6,iconv('UTF-8','windows-874',$data[0]),'LR',0,'C',$fill);
			$pdf->Cell($w[1],6,iconv('UTF-8','windows-874',$data[1]),'LR',0,'C',$fill);
			$pdf->Cell($w[2],6,iconv('UTF-8','windows-874',$data[2]),'LR',0,'C',$fill);
			$pdf->Cell($w[3],6,iconv('UTF-8','windows-874',$data[3]),'LR',0,'C',$fill);
			$pdf->Cell($w[4],6,iconv('UTF-8','windows-874',$data[4]),'LR',0,'R',$fill);
			$pdf->Cell($w[5],6,iconv('UTF-8','windows-874',$data[5]),'LR',0,'C',$fill);
			$pdf->Cell($w[6],6,iconv('UTF-8','windows-874',$data[6]),'LR',0,'C',$fill);
			$pdf->Cell($w[7],6,iconv('UTF-8','windows-874',$data[7]),'LR',0,'R',$fill);
			$pdf->Cell($w[8],6,iconv('UTF-8','windows-874',$data[8]),'LR',0,'C',$fill);
			$pdf->Ln(0);
			$fill=!$fill;
			$rows++;
}
		$Y = $Y+6;
		$pdf->SetXY($X,$Y);
		$pdf->Cell(array_sum($w),0,'','T');
	

	$pdf->Output();
?>
</body>
</html>

