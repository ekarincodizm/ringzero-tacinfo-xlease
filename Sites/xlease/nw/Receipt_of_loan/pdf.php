<?php
session_start();

require('../../thaipdfclass.php');
include('../../config/config.php');


		
		$data = $_POST['search_pdf'];
		list($ID,$nouse)=explode("#",$data);
			$sql = pg_query("SELECT * FROM thcap_mg_3dreceipt where  \"threceiptID\" = '$ID'");
			$re = pg_fetch_array($sql);
			
			
			
			
					$id_user = $re['id_user'];	
						
					$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
					$result=pg_fetch_array($qry_name); 
					$thaiacename = $result["fullname"];
			
		$cusname = $re['cusname'];
		$cusaddress = $re['cusaddress'];
		$datenow = $re['Date'];
		$sum = $re['money1']+$re['money2']+$re['money3']+$re['money4']+$re['money5']+$re['money6']+$re['money7']+$re['money8']+$re['money9']+$re['money10'];
		$summoney = number_format($sum,2);
		$sumtext = convert($summoney);
		
						if($re['list1'] != ""){					
							
							$list[] = $re['listdetail1'];
							$money[] = $re['money1'];
						}
						if($re['list2'] != ""){
							
							$list[] = $re['listdetail2'];
							$money[] = $re['money2'];
						}
						if($re['list3'] != ""){
							
							$list[] = $re['listdetail3'];
							$money[] = $re['money3'];
						}
						if($re['list4'] != ""){
							
							$list[] = $re['listdetail4'];
							$money[] = $re['money4'];
						}
						if($re['list5'] != ""){
							
							$list[] = $re['listdetail5'];
							$money[] = $re['money5'];
						}
						if($re['list6'] != ""){
							
							$list[] = $re['listdetail6'];
							$money[] = $re['money6'];
						}
						if($re['list7'] != ""){
							
							$list[] = $re['listdetail7'];
							$money[] = $re['money7'];
						}
						if($re['list8'] != ""){
							
							$list[] = $re['listdetail8'];
							$money[] = $re['money8'];
						}
						if($re['list9'] != ""){
							
							$list[] = $re['listdetail9'];
							$money[] = $re['money9'];
						}
						if($re['list10'] != ""){
							
							$list[] = $re['listdetail10'];
							$money[] = $re['money10'];
						}
		
		
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
		
		
	$pdf=new ThaiPDF();
	$pdf->SetThaiFont();
	$pdf->AddPage();
	
	
	$Y = 20;
	$X = 10;
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+15;
	$pdf->SetFont('AngsanaNew','B',16);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"เลขที่ ".$ID);
	$pdf->MultiCell(180,4,$title,0,'R',0);
	$pdf->Ln();
	
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"วันที่ ".$date);
	$pdf->MultiCell(180,4,$title,0,'R',0);
	$pdf->Ln();
	
	
	$Y = $Y+8;
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ใบรับเงิน ");
	$pdf->MultiCell(200,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+15;
	$X = 40;
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ได้รับเงินจาก  ");
	$pdf->MultiCell(190,4,$title,0,'L',0);
	$pdf->Ln();
	
	$X = 60;
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$cusname);
	$pdf->MultiCell(190,4,$title,0,'L',0);
	$pdf->Ln();
	
	$X = 40;
	$Y = $Y+8;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ที่อยู่  ");
	$pdf->MultiCell(20,4,$title,0,'L',0);
	$pdf->Ln();
	
	$X = 60;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$cusaddress);
	$pdf->MultiCell(190,4,$title,0,'L',0);
	$pdf->Ln();
	
	$header=array(' ลำดับที่ ',' รายการ ',' จำนวนเงิน ');
	
	
	
	
	$Y = $Y+15;
	$X = 35;
	
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('AngsanaNew','B',14);
		
		$w=array(30,95,30);
		$pdf->SetXY($X,$Y);
		for($i=0;$i<count($header);$i++){
			
			$pdf->Cell($w[$i],11,iconv('UTF-8','windows-874',$header[$i]),'LRTB',0,'C',true);
			
			}
		$pdf->Ln();

	$pdf->SetTextColor(0);
	$pdf->SetFont('');	
		
	$fill=true;
	$Y = $Y+6.1;
	$pdf->SetFont('AngsanaNew','',12);
	for($z=0;$z<sizeof($list);$z++){
		$a = $z + 1;
		$Y = $Y+5;
		$data=array($a,$list[$z],$money[$z]);
				$pdf->SetXY($X,$Y);
				$pdf->Cell($w[0],5,iconv('UTF-8','windows-874',$data[0]),'LR',0,'C',$fill);
				$pdf->Cell($w[1],5,iconv('UTF-8','windows-874',$data[1]),'LR',0,'C',$fill);
				$pdf->Cell($w[2],5,iconv('UTF-8','windows-874',$data[2]),'LR',0,'C',$fill);			
				$pdf->Ln(0);
				$fill=!$fill;
				
	}
	
	$Y = $Y+5;
	$pdf->SetXY($X,$Y);
	$pdf->Cell($w[0],7,iconv('UTF-8','windows-874',"รวม"),'LRTB',0,'C',$fill);
	$pdf->Cell($w[1],7,iconv('UTF-8','windows-874',"( ".$sumtext." )"),'LRTB',0,'C',$fill);
	$pdf->Cell($w[2],7,iconv('UTF-8','windows-874',$summoney),'LRTB',0,'C',$fill);			
	$pdf->Ln(0);
	
	
	$Y = $Y+15;
	$X = 40;
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ข้าพเจ้า ");
	$pdf->MultiCell(15,4,$title,0,'L',0);
	$pdf->Ln();
	
	$X = 60;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$re['cusname']);
	$pdf->MultiCell(45,4,$title,0,'L',0);
	$pdf->Ln();
	
	
	$X = 105;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ยินดีชำระ  ค่าใช้จ่ายตามรายการข้างต้น  ทุกประการ ");
	$pdf->MultiCell(100,4,$title,0,'L',0);
	$pdf->Ln();
	
	$Y = $Y+6;
	$X = 40;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ข้าพเจ้า ");
	$pdf->MultiCell(15,4,$title,0,'L',0);
	$pdf->Ln();
	
	$X = 60;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',$thaiacename);
	$pdf->MultiCell(45,4,$title,0,'L',0);
	$pdf->Ln();
	
	
	$X = 105;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ได้รับค่าใช้จ่ายจากผู้ชำระครบถ้วนแล้วในวันนี้");
	$pdf->MultiCell(100,4,$title,0,'L',0);
	$pdf->Ln();
	
	$Y = $Y+30;
	$X = 120;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ลงชื่อ..........................................ผู้ชำระ");
	$pdf->MultiCell(90,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+5;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"( ".$cusname." )");
	$pdf->MultiCell(90,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+30;
	$X = 120;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874'," ลงชื่อ..........................................ผู้รับเงิน");
	$pdf->MultiCell(90,4,$title,0,'C',0);
	$pdf->Ln();
	
	$Y = $Y+5;
	$pdf->SetXY($X,$Y);
	$title=iconv('UTF-8','windows-874',"( ".$thaiacename." )");
	$pdf->MultiCell(90,4,$title,0,'C',0);
	$pdf->Ln();
	
	
	
	
	
	$pdf->Output();
	
	
	
	
	
	
	
	
	
///Function///

function convert($number){ 
  $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
  $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
  $number = str_replace(",","",$number); 
  $number = str_replace(" ","",$number); 
  $number = str_replace("บาท","",$number); 
  $number = explode(".",$number); 
  if(sizeof($number)>2){ 
    return 'ทศนิยมหลายตัวนะจ๊ะ'; 
    exit; 
  } 
  $strlen = strlen($number[0]); 
  $convert = ''; 
  for($i=0;$i<$strlen;$i++){ 
    $n = substr($number[0], $i,1); 
    if($n!=0){ 
      if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
      elseif($i==($strlen-2) AND $n==2){ $convert .= 'ยี่'; } 
      elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
      else{ $convert .= $txtnum1[$n]; } 
      $convert .= $txtnum2[$strlen-$i-1]; 
    } 
  } 
  $convert .= 'บาท'; 
  if($number[1]=='0' OR $number[1]=='00' OR $number[1]==''){ 
    $convert .= 'ถ้วน'; 
  }else{ 
    $strlen = strlen($number[1]); 
    for($i=0;$i<$strlen;$i++){ 
      $n = substr($number[1], $i,1); 
      if($n!=0){ 
        if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';} 
        elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';} 
        elseif($i==($strlen-2) AND $n==1){$convert .= '';} 
        else{ $convert .= $txtnum1[$n];} 
        $convert .= $txtnum2[$strlen-$i-1]; 
      } 
    } 
    $convert .= 'สตางค์'; 
  } 
  return $convert;  
}	
	
	
	
	
	
?>
</body>
</html>

