<?php
session_start();

require('../../thaipdfclass.php');
include('../../config/config.php');
include('../function/nameMonth.php');
		
		$checkbook = pg_escape_string($_GET['bookcarid']);
		
		if(empty($checkbook)){
		
			$booksearch = pg_escape_string($_POST['idno_names_search']);
			list($bookcarID,$IDNO1,$cusname1,$ID_register1)=explode("#",$booksearch);
		}else{

			$bookcarID = pg_escape_string($_GET['bookcarid']);
		
		}
		
		$sql = "SELECT \"bookcarID\", cusname, \"IDNO\", \"C_REGIS\", typecheck, radio_month, 
					   radio_price, meter_price, meter_vat_price, participating_start, 
					   participating_end, insurance_price, act_price, insureance_act_price, 
					   other, other_price, sumprice, id_user, date, participating_price, 
					   gas_price, address,remark
						FROM book_car_check where \"bookcarID\" = '$bookcarID' ";

		$sqlquery = pg_query($sql);
		$re = pg_fetch_array($sqlquery);
		$i = 0;
		
		$check = $re['typecheck'];	
		
		if($check == 1){
			$type = ' ตรวจมิเตอร์ ';
		}else if($check == 2){
			$type = 'ตรวจมิเตอร์ + ภาษี';
		}
		
		$date = $re['date'];
		if($date != ""){
			$datenow=$re['date'];
		}else{
			$datenow = nowDate();
		
		}
		// แสดงข้อมูล ในเดือน...
		list($year_in,$month_in,$day_in)=explode("-",$datenow);
		$dateMY= date ("Y-m-d", strtotime("+1 month", strtotime($year_in.'-'.$month_in.'-01')));
		list($year_in,$month_in,$day_in)=explode("-",$dateMY);
		$month_inPDF=nameMonthTH($month_in);
		$type .=' ในเดือน '.$month_inPDF;
		// end
		
		
		$datestart = $re['participating_start'];
		
		
		$dateend = $re['participating_end'];
		$remark=""; //กำหนดค่าเริ่มแรกให้ ramark เป็นค่าว่าง
		if($re[remark]!=""){
			$remark = "*** $re[remark]"; //หมายเหตุ
		}
		$id_user = $re['id_user'];
		
		$qry_name=pg_query("select \"fullname\" from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
		$result=pg_fetch_array($qry_name); 
		$thaiacename = $result["fullname"];
		
		
		
	
	$pdf=new ThaiPDF();
	$pdf->SetThaiFont();
	$pdf->AddPage();
	
	$pdf->SetFont('AngsanaNew','B',24);
	$pdf->SetXY(100,10);
	$title=iconv('UTF-8','windows-874',"หนังสือนัดตรวจสภาพรถ");
	$pdf->MultiCell(90,4,$title,0,'R',0);
	$pdf->Ln();
	$pdf->SetXY(100,35);
	$pdf->SetFont('AngsanaNew','B',20);	
	$head=iconv('UTF-8','windows-874'," วันที่ ".$datenow);
	$pdf->MultiCell(90,3,$head,0,'R',0);
	$pdf->Ln();
	
	$pdf->SetFont('AngsanaNew','B',16);	
	$pdf->SetXY(15,45);
	$head3=iconv('UTF-8','windows-874',"เรียน  ".$re['cusname']);
	$pdf->MultiCell(70,3,$head3,0,'L',0);
	
	
	$pdf->SetXY(80,45);
	$head1=iconv('UTF-8','windows-874',"เลขที่สัญญา  ".$re['IDNO']);
	$pdf->MultiCell(60,3,$head1,0,'L',0);
	
	$pdf->SetXY(100,45);
	$head2=iconv('UTF-8','windows-874',"เลขทะเบียน : ".$re['C_REGIS']);
	$pdf->MultiCell(90,3,$head2,0,'R',0);
	
	
	$Y = 60;
	$pdf->SetFont('AngsanaNew','',14);	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"ด้วยรถยนต์ที่ท่านเช่าซื้อ  จะครบกำหนด   ".$type." บริษัทจึงขอเรียนให้ท่านทราบเพื่อ นัดตรวจสภาพรถ");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," ณ กรมการขนส่งทางบก ตึก 4 ช่องที่ 3 , 4 โดยมีค่าใช้จ่ายต่างๆดังนี้");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	if($re['radio_price'] > 0){
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าวิทยุสื่อสารรายเดือนจำนวน ".$month = $re['radio_month']." เดือน  ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$radio_price = number_format($re['radio_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	}
	
	if($re['meter_price'] > 0){
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าตรวจมิเตอร์ ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$meter_price = number_format($re['meter_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	}
	
	
	if($re['meter_vat_price'] > 0){
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าตรวจมิเตอร์พร้อมภาษี");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$meter_vat_price = number_format($re['meter_vat_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	}
	
	if($re['participating_price'] > 0){
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าเข้าร่วมประจำเดือน ".$datestart." ถึง ".$dateend);
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$participating_price = number_format($re['participating_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	}
	
	if($re['insureance_act_price'] > 0){
	
		if($re['insurance_price'] > 0){ 
		
			$insure = ". ค่าประกัน ".$re['insurance_price'];
			
		}
		if($re['act_price'] > 0){ 
			$act = " ค่า พรบ. ".$re['act_price'];
		}
		
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.$insure.$act);
		
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$participating_price = number_format($re['insureance_act_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	}
	
	$Y = 106;	
	$pdf->SetFont('AngsanaNew','B',16);		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"( โปรดติดต่อฝ่ายประกันเพื่อสอบถามยอดชำระเป็นจำนวนแน่นอน )");
	$pdf->MultiCell(175,3,$head2,0,'C',0);
	
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetFont('AngsanaNew','',14);	
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าใช้ก๊าซ ( กรณีติดตั้งใหม่หรือเปลี่ยนถังก๊าซให้นำใบวิศวะมาด้วยในวันตรวจรถ ) ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$participating_price = number_format($re['gas_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	
	$Y = $Y+6;
	$i = $i+1;
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',$i.". ค่าใช้จ่ายอื่นๆ ( ถ้ามี ) ".$re['other'] );
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," เป็นเงิน ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
		
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',$participating_price = number_format($re['other_price'],2)." บาท");
	$pdf->MultiCell(175,3,$head2,0,'R',0);
	
	$Y = $Y+6;
	$pdf->SetFont('AngsanaNew','B',14);	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"ค่าใช้จ่ายโดยประมาณรวม  ");
	$pdf->MultiCell(140,3,$head2,0,'R',0);
	
	
	$pdf->SetFont('AngsanaNew','B',14);	
	$pdf->SetXY(20,$Y);
	$head2=iconv('UTF-8','windows-874',$sumprice = number_format($re['sumprice'],2)." บาท");
	$pdf->MultiCell(170,3,$head2,0,'R',0);
		
	$Y = $Y+6;	
	$pdf->SetXY(25,$Y);
	$head2=iconv('UTF-8','windows-874',"วิธีการชำระเงิน ชำระ ณ ที่ทำการบริษัท หรือใช้ใบโอนเงินที่ทางบริษัทจัดส่งให้ หรือโอนเงินเข้า ธ.กรุงไทย สาขาถนนนวมินทร์");
	$pdf->MultiCell(175,5,$head2,0,'L',0);
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"  ชื่อบัญชีบริษัทไทยเอชลิสซิ่ง จก.บัญชีกระแสรายวัน เลขที่บัญชี 057-6-018279 โอนแล้วกรุณา โทรแจ้งฝ่ายทะเบียน");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"___________");
	$pdf->MultiCell(105,3,$head2,0,'R',0);
	
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," โทร 0-2744-2222  ต่อ 2359-2366 ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874'," ขอแสดงความนับถือ ");
	$pdf->MultiCell(175,3,$head2,0,'C',0);
	
	$Y = $Y+19;
	$pdf->SetXY(15,$Y);	
	$head2=iconv('UTF-8','windows-874'," ( ".$thaiacename." ) ");
	$pdf->MultiCell(175,3,$head2,0,'C',0);
	
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"เจ้าหน้าที่ฝ่ายทะเบียน ");
	$pdf->MultiCell(175,3,$head2,0,'C',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"ฝ่ายนัดตรวจสภาพรถ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"หมายเหตุ");
	$pdf->MultiCell(20,3,$head2,0,'L',0);	
	
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(27,$Y);
	$head2=iconv('UTF-8','windows-874',"*บริษัทได้ย้ายสำนักงานมาอยู่ที่ 555 ซ.นวมินทร์ 52-54 แขวงคลองกุ่ม เขตบึงกุ่ม กทม. 10240 โทร 0-2744-2222  ต่อ 2359-2366 ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);	
	
	$Y = $Y+6;
	$pdf->SetXY(27,$Y);
	$head2=iconv('UTF-8','windows-874',"**กรณีรถของท่านต่อภาษีปีที่ 7 แล้วจะต้องตรวจทุก 4 เดือน ");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$Y = $Y+6;
	$pdf->SetFont('AngsanaNew','B',20);
	$pdf->SetXY(27,$Y);
	$head2=iconv('UTF-8','windows-874',$remark);
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',12);
	$Y = $Y+6;
	$pdf->SetXY(15,$Y);
	$head2=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(175,3,$head2,0,'L',0);
	
	$pdf->SetFont('AngsanaNew','B',16);	
	$Y = $Y+40;
	$pdf->SetXY(55,$Y);
	$head2=iconv('UTF-8','windows-874',$re['cusname']);
	$pdf->MultiCell(100,6,$head2,0,'L',0);
	
	$Y = $Y+6;
	$pdf->SetXY(55,$Y);
	$head2=iconv('UTF-8','windows-874',$re['address']);
	$pdf->MultiCell(100,6,$head2,0,'L',0);
	
	$pdf->Output();
?>
</body>
</html>

