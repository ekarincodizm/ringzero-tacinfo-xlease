<?php
session_start();
include("../../../config/config.php");
include("../../function/nameMonth.php");
set_time_limit(0);

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// ปัจจุบันในส่วนนี้ไม่อัพเดทข้อมูลตามการแก้ไขครั้งล่าสุด 2014-08-26
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//รับค่าข้อมูล
$month = pg_escape_string($_GET['month']); //รับเดือน
$year = pg_escape_string($_GET['year']); //รับปี
$contype = pg_escape_string($_GET['contype']); //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง
$nowdatetxtshow = Date('d-m-Y'); //วันที่ปัจจุบัน
$monthtxtshow = nameMonthTH($month);	//แปลงเดือนเป็นภาษาไทย

//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อแสดงประเภทสัญญาที่แสดงบนหัวรายงาน
for($con = 0;$con < sizeof($contypechk) ; $con++){
		if($contypetxtshow == ""){
			$contypetxtshow = $contypechk[$con];
		}else{
			$contypetxtshow = $contypetxtshow.",".$contypechk[$con];
		}	
}
	
//หาวันที่สุดท้ายของเดือน	
/*$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$focusdate=$year.'-'.$month.'-'.$day;*/

	
// ============================================================================================
//หาวันที่สุดท้ายของเดือน (ตรวจสอบแล้ว 2014-02-06)
// ============================================================================================
$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
list($day)=pg_fetch_array($qryday);			
//กำหนดวันที่สนใจเพื่อนำเข้า function
$vfocusdate=$year.'-'.$month.'-'.$day;
// วันแรกของปี สำหรับรายการที่ต้องการตัวเลขภายในปีที่เลือก ตั้งแต่ต้นปี
$vfirstdateofyear=$year.'-01-01';
	
//------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,22.5);
$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
$pdf->MultiCell(200,4,$buss_name,'','L',0);

$pdf->SetXY(5,58.5);
$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
$pdf->MultiCell(200,4,$buss_name,'','R',0);

$pdf->SetXY(60,22.5);
$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
$pdf->MultiCell(200,4,$buss_name,'','L',0);

$pdf->SetXY(5,22.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,26.5);
$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,30.5);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,34.5);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,38.5);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,42.5);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,46.5);
$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,50.5);
$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,54.5);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,58.5);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,59);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(5,64);
$buss_name=iconv('UTF-8','windows-874',"อันดับ");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,64);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(35,64);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(73,64);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(88,64);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(107,64);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(126,64);
$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(145,64);
$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',8.5);
$pdf->SetXY(163,64);
$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(172,64);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(188,64);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
$pdf->SetXY(5,69);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$cline = 73;
$nub=1;

//วนแสดงข้อมูล
for($con = 0;$con < sizeof($contypechk) ; $con++){
	
	if($contypechk[$con] != ""){ //หากมีประเภทสัญญาถูกส่งมา
		//แสดงประเภทอยู่ด้านบนข้อมูล
		if($nub > 40)
		{											
			$pdf->AddPage();
			
			$page = $pdf->PageNo();
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','R',0);

			$pdf->SetXY(60,22.5);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,26.5);
			$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,30.5);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,34.5);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,38.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,42.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,46.5);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,50.5);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,54.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,59);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(5,64);
			$buss_name=iconv('UTF-8','windows-874',"อันดับ");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(15,64);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,64);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(73,64);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(88,64);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(107,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(126,64);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
			$pdf->MultiCell(22,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(145,64);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(163,64);
			$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(188,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			$pdf->SetXY(5,69);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$cline = 73;
			$nub=1;
			
		}	
		
		//แสดงประเภทอยู่ด้านบนข้อมูล	
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"--- $contypechk[$con] -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);
		$cline += 5;
		$nub+=1;
		
		// ============================================================================================
		//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น (ตรวจสอบแล้ว 2014-02-06)
		// และสัญญาดังกล่าวจะต้องยังไม่ปิดบัญชี
		// ============================================================================================
		$qrycontract=pg_query("	select
								\"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\",'$vfocusdate') as conclosedate
								from
									\"thcap_contract\" 
								where
									\"conStartDate\" <= '$vfocusdate' and 
									\"conType\" = '$contypechk[$con]' 
								order by \"contractID\"
				");

		//นับจำนวนข้อมูลที่ค้นพบ	
		$rownum = pg_num_rows($qrycontract);
		
		// ============================================================================================
		//ดักการแสดงข้อมูล (ตรวจสอบแล้ว 2014-02-06)
		// ============================================================================================
		if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
			$listallrows=0;
			$i=0;
			while($rescon=pg_fetch_array($qrycontract)){
				$contractID=$rescon["contractID"];
				$conclosedate=$rescon["conclosedate"];
				$i++;	
				
				// ============================================================================================
				// ตรวจสอบว่าถ้าสัญญาดังกล่าวปิดสัญญา ไม่ว่าจะด้วยชำระเสร็จสิ้น หรือด้วยขายหนี้ หรือถูกยึดแล้ว จะต้องปิดสัญญา (เฉพาะสัญญาที่ปิดในปีก่อนๆ สำหรับปิดในปีนี้ ยังต้องแสดงอยู่ เพราะต้องแสดงยอดรับรู้รายได้ปีนั้นๆ แต่ให้เงินต้น/ลูกหนี้ เหลือ = 0)
				// ============================================================================================
				if ($conclosedate <= $vfocusdate AND $conclosedate != '' AND $conclosedate < $vfirstdateofyear)
				{
					$i--;
					continue;
				}
				
				// ============================================================================================
				// หาประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
				$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
				list($contype)=pg_fetch_array($qrytype);
						
				// ============================================================================================
				// หาชื่อลูกค้า (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
						$sqlcus = pg_query("SELECT thcap_fullname from\"vthcap_ContactCus_detail\"
						where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
						list($fullname) = pg_fetch_array($sqlcus);
						
						// ============================================================================================
						// ยอดสินเชื่อเริ่มแรก ตามประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){

							$qrystartamt=pg_query("	select 
														\"conLoanAmt\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$qrystartamt=pg_query("	select 
														\"conFinAmtExtVat\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}
						list($conLoanAmt)=pg_fetch_array($qrystartamt);
						$conLoanAmt_prin = number_format($conLoanAmt,2);

						// ============================================================================================
						//จำนวนเงินต้นคงเหลือ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1 = pg_query("SELECT \"thcap_getPrincipleOfGenCloseMonth\"('$contractID','$year','$month')");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$sql1 = pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$vfocusdate')");	
						}
						
						list($getPrincipleOfGenCloseMonth) = pg_fetch_array($sql1);
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getPrincipleOfGenCloseMonthshow = 0.00;
							$getPrincipleOfGenCloseMonth = 0.00;
						}
						$getPrincipleOfGenCloseMonthshow = number_format($getPrincipleOfGenCloseMonth,2);

						// ============================================================================================
						// หาดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระถึงสิ้นเดือนที่เลือก (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1 = pg_query("SELECT \"thcap_getInterestOfGenCloseMonth\"('$contractID','$year','$month')");
							list($getInterestOfGenCloseMonth) = pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
							// ===================================================================================================
							// หาหนี้ลูกหนี้คงเหลือทั้งสัญญา (เงินต้น + ดอกเบี้ย ก่อนภาษีมูลค่าเพิ่ม)
							// ===================================================================================================
								$sumin_prin1=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
								$sql1=pg_query("							
										SELECT
											MIN(\"totaldebt_left\") -- หนี้คงเหลือ
										FROM
											\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
										WHERE 
											\"receiveDate\"::date <= '$vfocusdate'::date AND
											\"contractID\" = '$contractID'
								");
								list($sumin_prin1)=pg_fetch_array($sql1);
							
								// ถ้าไม่มีข้อมูลใดๆเลยอาจจะยังไม่เคยจ่าย ให้ใช้ยอดเงินต้นคงเหลือแรกสุดก่อนรายการ
								if($sumin_prin1=="") {
									$sql1=pg_query("							
											SELECT
												MAX(\"totaldebt_before\"), -- หนี้คงเหลือ
												MAX(\"totalinterest_before\") -- ดอกเบี้ยเริ่มต้น
											FROM
												\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
											WHERE
												\"contractID\" = '$contractID'
									");
									list($sumin_prin1,$start_interest)=pg_fetch_array($sql1);
								}
							
								// เงินต้นทุกกรณี โดยเฉพาะ FL จะต้อง + ค่าซากเข้าไปด้วย
								$residue=""; // ค่าซาก
								$sql1=pg_query("							
										SELECT
											\"conResidualValue\" -- หนี้คงเหลือ
										FROM
											\"public\".\"thcap_lease_contract\"
										WHERE 
											\"contractID\" = '$contractID'
								");
								list($residue)=pg_fetch_array($sql1);
								
								// *todo ยังไม่รองรับการ update ค่าซากที่ปิดสัญญา หากชำระมาแล้ว
								// หากมีค่าซากจะต้องเพิ่มค่าซากลงไปในยอดลูกหนี้ด้วย
								if ($residue=="") 
									$residue = 0.00;
								else
									$sumin_prin1 += $residue;
							
							$getInterestALLacc_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
							$sql1=pg_query("							
									SELECT
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังรับชำระ
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALLacc_as_lastreceivedate)=pg_fetch_array($sql1);
							if ($getInterestALLacc_as_lastreceivedate == "") $getInterestALLacc_as_lastreceivedate = 0.00;

							$getInterestALLacc_as_focusdate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\"<='$vfocusdate'
							");
							list($getInterestALLacc_as_focusdate)=pg_fetch_array($sql1);
							
							$getInterestALL_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือจากการรับชำระทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql2=pg_query("							
									SELECT 
										MIN(\"totalinterest_left\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALL_as_lastreceivedate)=pg_fetch_array($sql2);
							if ($getInterestALL_as_lastreceivedate == "") $getInterestALL_as_lastreceivedate = $start_interest; // ถ้าไม่มีแสดงว่าอาจไม่เคยรับชำระ ดอกเบี้ยจะเท่ากับดอกเบี้ยเริ่มต้น

							
							// ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว
							$getInterestOfGenCloseMonth = $getInterestALLacc_as_focusdate - $getInterestALLacc_as_lastreceivedate;
							
							// ถ้าดอกเบี้ยคงค้าง น้อยกว่า 0 แสดงว่าจ่ายถึงปัจจุบัน
							if($getInterestOfGenCloseMonth < 0){
								$getInterestOfGenCloseMonth = 0.00;
							}
						}
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getInterestOfGenCloseMonth = 0.00;
						}
						
						$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
						$getInterestALL_format = number_format($getInterestALL_as_lastreceivedate,2);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 !!! NOTE: ถ้า comment ในส่วนนี้ออกระบบ จะแสดงยอดลูกหนี้คงเหลือของสัญญา EIR ประเภทนั้นๆ
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') $sumin_prin1 = 0.00;
						
						// ============================================================================================
						// หาดอกเบี้ยที่รับรู้รายได้ไม่เกิน 3 เดือนถึงสิ้นเดือนที่เลือก
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1=pg_query("							
									SELECT 
										SUM(\"realize_amount\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"public\".\"thcap_temp_int_acc\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"intacc_date\"<='$vfocusdate' AND
										\"intacc_date\">='$vfirstdateofyear'
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
						
							
							// - todo แก้ไขเป็น Query ก่อนหน้า ตัวอย่างปัญหา BH-BK01-5500004
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้วในปีนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										(
											( \"voucherID_realize\" IS NOT NULL AND \"voucherDate\" >= '$vfirstdateofyear'::date AND \"voucherDate\" <= '$vfocusdate'::date) OR
											( \"voucherID_realize\" IS NULL AND \"accdate\" >= '$vfirstdateofyear' AND \"accdate\" <= '2012-01-01')
										)
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}
						$getAccruedInterestshow = number_format($getAccruedInterest,2);
						
						// ============================================================================================
						// รวมคงเหลือที่จะต้องรับชำระ
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							// หนี้คงเหลือคือเงินต้น + ดอกเบี้ยถึงสิ้นเดือนที่ปิดบัญชี
							$sumin_prin1 = $getPrincipleOfGenCloseMonth + $getInterestOfGenCloseMonth;
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
							
							// หนี้คงเหลือ คือ ยอดผ่อนทั้งหมดทที่เหลือหลังรับชำระล่าสุด
							$sumin_prin1 = $sumin_prin1;
						}
						$sumin_prin = number_format($sumin_prin1,2);
						
						// ============================================================================================
						// จำนวนวันที่ค้าง			
						// ============================================================================================
						$sql1 = pg_query("SELECT \"thcap_get_all_backdays\"('$contractID','$vfocusdate',1)");
						list($thcap_backDueNumDays) = pg_fetch_array($sql1);
						if($thcap_backDueNumDays == ""){ $thcap_backDueNumDays = "-"; }
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางการเงิน)
						// ============================================================================================
						if($getInterestALL_as_lastreceivedate==""){
							$getInterestALL_format="";
							
						}
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางบัญชี)
						// ============================================================================================
						if(	$contype=='HIRE_PURCHASE' or 
							$contype=='LEASING' or
							$contype=='GUARANTEED_INVESTMENT' or
							$contype=='FACTORING' or
							$contype=='PROMISSORY_NOTE' or
							$contype=='SALE_ON_CONSIGNMENT' ) {
							
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\" >= '2013-01-01' AND
										(
											(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
											(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$vfocusdate'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
										)
							");
							list($getInterestLeftAcc)=pg_fetch_array($sql1);
							// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
							if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
								$getInterestLeftAcc = 0.00;
							}
							
							$getInterestLeftAccshow = number_format($getInterestLeftAcc,2); // ตัวเลขสำหรับนำไปแสดง
						}
								
				//if($sumin_prin > 0){ //หากเงินคงเหลือมากกว่า ศูนย์จึงจะแสดงข้อมูล										
					if($nub > 40)
					{											
						$pdf->AddPage();
						
						$page = $pdf->PageNo();
						$pdf->SetFont('AngsanaNew','B',18);
						$pdf->SetXY(5,10);
						$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
						$pdf->MultiCell(200,4,$title,0,'C',0);

						$pdf->SetFont('AngsanaNew','',12);
						$pdf->SetXY(5,16);
						$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
						$pdf->MultiCell(200,4,$buss_name,0,'C',0);

						$pdf->SetXY(5,22.5);
						$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
						$pdf->MultiCell(200,4,$buss_name,'','L',0);

						$pdf->SetXY(5,58.5);
						$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
						$pdf->MultiCell(200,4,$buss_name,'','R',0);

						$pdf->SetXY(60,22.5);
						$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
						$pdf->MultiCell(200,4,$buss_name,'','L',0);

						$pdf->SetXY(5,22.5);
						$buss_name=iconv('UTF-8','windows-874',"");
						$pdf->MultiCell(200,4,$buss_name,'B','L',0);

						$pdf->SetFont('AngsanaNew','B',10);
						$pdf->SetXY(5,26.5);
						$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetFont('AngsanaNew','',10);
						$pdf->SetXY(5,30.5);
						$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,34.5);
						$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,38.5);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,42.5);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,46.5);
						$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,50.5);
						$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,54.5);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,58.5);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
						$pdf->MultiCell(200,4,$buss_name,0,'L',0);

						$pdf->SetXY(5,59);
						$buss_name=iconv('UTF-8','windows-874',"");
						$pdf->MultiCell(200,4,$buss_name,'B','C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(5,64);
						$buss_name=iconv('UTF-8','windows-874',"อันดับ");
						$pdf->MultiCell(10,4,$buss_name,0,'C',0);

						$pdf->SetXY(15,64);
						$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetXY(35,64);
						$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
						$pdf->MultiCell(40,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(73,64);
						$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(88,64);
						$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(107,64);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(126,64);
						$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
						$pdf->MultiCell(22,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(145,64);
						$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetFont('AngsanaNew','B',8.5);
						$pdf->SetXY(163,64);
						$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
						$pdf->MultiCell(10,4,$buss_name,0,'C',0);

						$pdf->SetXY(172,64);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);

						$pdf->SetXY(188,64);
						$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
						$pdf->MultiCell(20,4,$buss_name,0,'C',0);
						$pdf->SetXY(5,69);
						$buss_name=iconv('UTF-8','windows-874',"");
						$pdf->MultiCell(200,4,$buss_name,'B','C',0);

						$pdf->SetFont('AngsanaNew','',12);
						$cline = 73;
						$nub=1;
						
					}
						
					//แสดงข้อมูล
					$pdf->SetFont('AngsanaNew','',9);
					$pdf->SetXY(4,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$i");
					$pdf->MultiCell(10,4,$buss_name,0,'L',0);

					$pdf->SetXY(10,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$contractID");
					$pdf->MultiCell(28,4,$buss_name,0,'L',0);

					$pdf->SetFont('AngsanaNew','',8);
					$pdf->SetXY(35,$cline);
					$buss_name=iconv('UTF-8','windows-874',"$fullname");
					$pdf->MultiCell(45,4,$buss_name,0,'L',0);
					
					$pdf->SetFont('AngsanaNew','',9);
					$pdf->SetXY(70,$cline);
					$buss_name=iconv('UTF-8','windows-874',$conLoanAmt_prin);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(86,$cline);
					$buss_name=iconv('UTF-8','windows-874',$getPrincipleOfGenCloseMonthshow);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);

					$pdf->SetXY(105,$cline);
					$buss_name=iconv('UTF-8','windows-874',$getInterestOfGenCloseMonthshow);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(125,$cline);
					$buss_name=iconv('UTF-8','windows-874',$getAccruedInterestshow);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(142,$cline);
					$buss_name=iconv('UTF-8','windows-874',$sumin_prin);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(160,$cline);
					$buss_name=iconv('UTF-8','windows-874',$thcap_backDueNumDays);
					$pdf->MultiCell(10,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(170,$cline);
					$buss_name=iconv('UTF-8','windows-874',$getInterestALL_format);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					$pdf->SetXY(185,$cline);
					$buss_name=iconv('UTF-8','windows-874',$getInterestLeftAccshow);
					$pdf->MultiCell(20,4,$buss_name,0,'R',0);
					
					//หาผลรวมของจำนวนเงินแต่ละประเภทสัญญา
					$sum_conLoanAmt += $conLoanAmt; //รวมยอดสินเชื่อเริ่มแรก
					$listgetPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; //รวมเงินต้นคงเหลือ
					$listgetInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  //ดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
					$listgetAccruedInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
					$listgetAccruedAccInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
					$listsumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
					$listgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
					$listgetInterestLeftAcc += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
					$listallrows += 1;

					//หาผลรวมของจำนวนเงินทั้งหมด
					$sum_conLoanAmt_all += $conLoanAmt;//รวมยอดสินเชื่อเริ่มแรกทั้งหมด
					$getPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; // รวมเงินต้นคงเหลือ
					$getInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  // รวมดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
					$getAccruedInterestsum += $getAccruedInterest; // รวมดอกเบี้ยรับรู้รายได้ 3 เดือน
					$getInterestLeftAccsum += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
					$sumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
					$sumgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
							
					$allrows += 1; //จำนวนข้อมูลทั้งหมด 
					$cline += 5;
					$nub+=1;
							
				//}
					
				//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล	
			
				unset($sumin_prin);
				unset($thcap_backDueNumDays);
				unset($getInterestOfGenCloseMonthshow);
				unset($getPrincipleOfGenCloseMonthshow);
				unset($getInterestOfGenCloseMonth);
				unset($getPrincipleOfGenCloseMonth);
				unset($sumin_prin1);
				unset($getInterestALLacc);
				unset($conLoanAmt);		
				unset($getInterestALLacc_as_lastreceivedate);
				unset($getInterestALLacc_as_focusdate);
				unset($getInterestALL_as_lastreceivedate);
				unset($getInterestLeftAcc);
				unset($getInterestLeftAccshow);
			}//ปิด While
											
			if($nub > 40)
			{											
				$pdf->AddPage();
				
				$page = $pdf->PageNo();
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,22.5);
				$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
				$pdf->MultiCell(200,4,$buss_name,'','L',0);

				$pdf->SetXY(5,58.5);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
				$pdf->MultiCell(200,4,$buss_name,'','R',0);

				$pdf->SetXY(60,22.5);
				$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
				$pdf->MultiCell(200,4,$buss_name,'','L',0);

				$pdf->SetXY(5,22.5);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','L',0);

				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(5,26.5);
				$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(5,30.5);
				$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,34.5);
				$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,38.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,42.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,46.5);
				$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,50.5);
				$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,54.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,58.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,59);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(5,64);
				$buss_name=iconv('UTF-8','windows-874',"อันดับ");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);

				$pdf->SetXY(15,64);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(35,64);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(73,64);
				$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(88,64);
				$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(107,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(126,64);
				$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
				$pdf->MultiCell(22,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(145,64);
				$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(163,64);
				$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);

				$pdf->SetXY(172,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(188,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				$pdf->SetXY(5,69);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$cline = 73;
				$nub=1;						
			}
						
		//แสดงผลรวมของแต่ละประเภทสัญญา {	
			$pdf->SetFont('AngsanaNew','B',10);

			$pdf->SetXY(5,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',"รวม $contypechk[$con]");
			$pdf->MultiCell(70,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(70,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($sum_conLoanAmt,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(86,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listgetPrincipleOfGenCloseMonthsum,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(105,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listgetInterestOfGenCloseMonthsum,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(125,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listgetAccruedInterestsum,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(142,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listsumin_prinsum,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
									
			$pdf->SetXY(170,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listgetInterestALL,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(185,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',number_format($listgetInterestLeftAcc,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(75,$cline - 2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(95,$cline - 2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(115,$cline-2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(135,$cline-2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(155,$cline-2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(50,4,$buss_name,'B','R',0);
			
			$pdf->SetXY(75,$cline + 2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(95,$cline + 2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(115,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(135,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(155,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(50,4,$buss_name,'B','R',0);
			
			$pdf->SetXY(75,$cline+2.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(95,$cline+2.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(115,$cline+2.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(135,$cline+2.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,'B','R',0);
			$pdf->SetXY(155,$cline+2.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(50,4,$buss_name,'B','R',0);
			$cline += 10;
			$nub+=2;
						
			// }
			//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล			 
			unset($sum_conLoanAmt);
			unset($listgetPrincipleOfGenCloseMonthsum);
			unset($listgetInterestOfGenCloseMonthsum);
			unset($listgetAccruedInterestsum);
			unset($listsumin_prinsum);		 
			unset($listgetInterestALL);	
			unset($listgetInterestLeftAcc);
						
		}ELSE{ //หากไม่มีข้อมูล
			if($nub > 40)
			{											
				$pdf->AddPage();
				
				$page = $pdf->PageNo();
				$pdf->SetFont('AngsanaNew','B',18);
				$pdf->SetXY(5,10);
				$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
				$pdf->MultiCell(200,4,$title,0,'C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$pdf->SetXY(5,16);
				$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
				$pdf->MultiCell(200,4,$buss_name,0,'C',0);

				$pdf->SetXY(5,22.5);
				$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
				$pdf->MultiCell(200,4,$buss_name,'','L',0);

				$pdf->SetXY(5,58.5);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
				$pdf->MultiCell(200,4,$buss_name,'','R',0);

				$pdf->SetXY(60,22.5);
				$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
				$pdf->MultiCell(200,4,$buss_name,'','L',0);

				$pdf->SetXY(5,22.5);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','L',0);

				$pdf->SetFont('AngsanaNew','B',10);
				$pdf->SetXY(5,26.5);
				$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetFont('AngsanaNew','',10);
				$pdf->SetXY(5,30.5);
				$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,34.5);
				$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,38.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,42.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,46.5);
				$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,50.5);
				$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,54.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,58.5);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
				$pdf->MultiCell(200,4,$buss_name,0,'L',0);

				$pdf->SetXY(5,59);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(5,64);
				$buss_name=iconv('UTF-8','windows-874',"อันดับ");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);

				$pdf->SetXY(15,64);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(35,64);
				$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
				$pdf->MultiCell(40,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(73,64);
				$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(88,64);
				$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(107,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(126,64);
				$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
				$pdf->MultiCell(22,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(145,64);
				$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetFont('AngsanaNew','B',8.5);
				$pdf->SetXY(163,64);
				$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
				$pdf->MultiCell(10,4,$buss_name,0,'C',0);

				$pdf->SetXY(172,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);

				$pdf->SetXY(188,64);
				$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
				$pdf->MultiCell(20,4,$buss_name,0,'C',0);
				$pdf->SetXY(5,69);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(200,4,$buss_name,'B','C',0);

				$pdf->SetFont('AngsanaNew','',12);
				$cline = 73;
				$nub=1;	
				
			}
								
			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"--- ไม่มีข้อมูล ---");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			$cline += 5;
			$nub+=1;		
		}
	}//หากมีประเภทสัญญาถูกส่งมา		
} //จบการวนประเภทสัญญา for			
			

//แสดงผลรวมทั้งหมด					
if($nub > 40)
{											
			$pdf->AddPage();
	
			$page = $pdf->PageNo();
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','R',0);

			$pdf->SetXY(60,22.5);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,26.5);
			$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,30.5);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,34.5);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,38.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,42.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,46.5);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,50.5);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,54.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,59);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(5,64);
			$buss_name=iconv('UTF-8','windows-874',"อันดับ");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(15,64);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,64);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(73,64);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(88,64);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(107,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(126,64);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
			$pdf->MultiCell(22,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(145,64);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(163,64);
			$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(188,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			$pdf->SetXY(5,69);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$cline = 73;
			$nub=1;
	
}
	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"--- รวม -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);
$cline += 5;
$nub+=1;

if($nub > 40)
{											
			$pdf->AddPage();
	
			$page = $pdf->PageNo();
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"รายงานของเดือน $monthtxtshow ค.ศ. $year");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ออกรายงาน $nowdatetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','R',0);

			$pdf->SetXY(60,22.5);
			$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา  $contypetxtshow");
			$pdf->MultiCell(200,4,$buss_name,'','L',0);

			$pdf->SetXY(5,22.5);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','B',10);
			$pdf->SetXY(5,26.5);
			$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,30.5);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเริ่มแรก : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,34.5);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,38.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับ : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,42.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,46.5);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน) : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,50.5);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือที่จะต้องรับชำระ : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,54.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,58.5);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี) : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetXY(5,59);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(5,64);
			$buss_name=iconv('UTF-8','windows-874',"อันดับ");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(15,64);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,64);
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(40,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(73,64);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ\nเริ่มแรก");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(88,64);
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นคงเหลือ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(107,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้น\nที่ยังไม่ได้รับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(126,64);
			$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ยในรอบปี\n(รับรู้ไม่เกิน 3 เดือน)");
			$pdf->MultiCell(22,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(145,64);
			$buss_name=iconv('UTF-8','windows-874',"รวมคงเหลือ\nที่จะต้องรับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','B',8.5);
			$pdf->SetXY(163,64);
			$buss_name=iconv('UTF-8','windows-874',"จำนวน\nวันที่ค้าง");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(172,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(การเงิน)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(188,64);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือ\nทั้งสัญญา(บัญชี)");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			$pdf->SetXY(5,69);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(200,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$cline = 73;
			$nub=1;
	
}
$pdf->SetFont('AngsanaNew','B',10);	
$pdf->SetXY(5,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

$pdf->SetXY(70,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_conLoanAmt_all,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(86,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($getPrincipleOfGenCloseMonthsum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(105,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($getInterestOfGenCloseMonthsum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(125,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($getAccruedInterestsum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(142,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($sumin_prinsum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(170,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($sumgetInterestALL,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(185,$cline+2);
$buss_name=iconv('UTF-8','windows-874',number_format($getInterestLeftAccsum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(75,$cline - 2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(95,$cline - 2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(115,$cline-2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(135,$cline-2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(155,$cline-2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,4,$buss_name,'B','R',0);

$pdf->SetXY(75,$cline + 2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(95,$cline + 2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(115,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(135,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(155,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,4,$buss_name,'B','R',0);

$pdf->SetXY(75,$cline+2.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(95,$cline+2.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(115,$cline+2.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(135,$cline+2.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);
$pdf->SetXY(155,$cline+2.5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,4,$buss_name,'B','R',0);

$pdf->SetXY(5,$cline+5);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','C',0);
    
  

$pdf->Output();
?>