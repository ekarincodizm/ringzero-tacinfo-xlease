<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

//ตรวจสอบประเภทสินเชื่อ ----------------------------------------------------------------------
$type = $_GET['type'];

	if($type == 'a1'){
		$type1 = conLoanAmt;
		$type2 = conFinanceAmount;
		$headertext = "ยอดสินเชื่อ";
	}else if($type == 'a2'){
		$type1 = contractID;
		$headertext = "จำนวนสัญญา";
	}else if($type == 'a3'){
		$type1 = "";
		$headertext = "ยอดเฉลี่ยสินเชื่อต่อสัญญา";
	}
//--------------------------------------------	
// สร้างเงินไขในการหาตามประเภท conType --------------------
$contype = $_GET['contypee'];
$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == ""){
			$contypeqry = "\"conType\" = '$contype[$con]' ";
		}else{
			$contypeqry = $contypeqry."OR \"conType\" = '$contype[$con]' ";
		}		
	}
}	
if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
}	
//---------------------------------------------
//รับค่าวันเดือนปี -------------------------------------------------------------------------------------
$year5 = $_GET['Ystart'];	
$m5 = $_GET['Mstart'];

$year = $_GET['Ystart'];	
$m1 = $_GET['Mstart'];
$playback = $_GET['report'];
//---------------------------------------------
//จำนวนเดือนที่ต้องการดูย้อนหลัง ------------------------------------------------------------------
if($playback != ''){		
	$stop = $playback;
}

$headertext = $headertext." ย้อนหลัง ".$stop." เดือน";	
	
//---------------------------------------------	
//หาจำนวนวันในเดือนนั้นๆ -------------------------------------------------------------------------	
$objQuery = pg_query("SELECT \"gen_numDaysInMonth\"($m1,$year) ");
list($day) = pg_fetch_array($objQuery);
$date = $year."-".$m1."-"."01";
$datedes = $year."-".$m1."-".$day;
//---------------------------------------------	

$nowdate = Date('Y-m-d');

//------------------- PDF -------------------//
class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
 
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
$title=iconv('UTF-8','windows-874',"สถิติยอดขายสินเชื่อ");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,16);
$monthyear=iconv('UTF-8','windows-874',$headertext);
$pdf->MultiCell(290,4,$monthyear,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','B',12);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"*เดือนที่ไม่มีข้อมูลจะไม่แสดง !");
$pdf->MultiCell(285,5,$buss_name,0,'L',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(285,5,$buss_name,0,'R',0);


$cline = 32;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"เดือน - ปี");
$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

$pdf->SetXY(35,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(45,5.5,$buss_name,1,'C',0);

$pdf->SetXY(80,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(50,5.5,$buss_name,1,'C',0);

$pdf->SetXY(130,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุดสัญญา");
$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',"ระยะเวลาสัญญา");
$pdf->MultiCell(35,5.5,$buss_name,1,'C',0);

$pdf->SetXY(225,$cline);
$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
$pdf->MultiCell(20,5.5,$buss_name,1,'C',0);

$pdf->SetXY(245,$cline);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินกู้");
$pdf->MultiCell(45,5.5,$buss_name,1,'C',0);

$nub = 0;
$cline += 0;
for($i=1;$i<=$stop;$i++){

	if($type == 'a1'){	
		$strSQL = "
					SELECT 	\"$type1\" as \"conLoanAmt\",\"conDate\",\"contractID\",\"conLoanIniRate\",\"conEndDate\" 
					FROM 	\"thcap_mg_contract\" 
					WHERE 	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					UNION
					
					SELECT 	\"$type2\" as \"conLoanAmt\",\"conDate\",\"contractID\",\"conLoanIniRate\",\"conEndDate\" 
					FROM 	\"thcap_lease_contract\" 
					WHERE 	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					ORDER BY \"contractID\"
				 ";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		$text = 'รายการทั้งหมดของเดือน';
		$textm ='รวมยอดสินเชื่อของเดือน';
		$bath = 'บาท';
		
		$amttextm ='รวมยอดสินเชื่อทั้งหมด';
		$amttext = 'รายการทั้งหมด';
		$roww = $roww + $nrows;
		
	}else if($type == 'a2'){
		$strSQL = "		SELECT 	\"contractID\",\"conType\" 
						FROM 	\"thcap_mg_contract\" 
						WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
						
						UNION
						
						SELECT 	\"contractID\",\"conType\" 
						FROM 	\"thcap_lease_contract\" 
						WHERE	\"conDate\" Between '$date' AND  '$datedes' $contypeqry
						
						ORDER BY \"contractID\"
					";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		
		$text = 'จำนวนสัญญาทั้งหมด';
		$roww = $roww + $nrows;	
				
	}else if($type == 'a3'){
		$strSQL = "
					SELECT \"contractID\",\"conLoanAmt\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
					FROM \"thcap_mg_contract\" 
					WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					UNION
					
					SELECT \"contractID\",\"conFinanceAmount\" as \"conLoanAmt\",\"conDate\",\"conLoanIniRate\",\"conEndDate\" 
					FROM \"thcap_lease_contract\" 
					WHERE \"conDate\" Between '$date' AND  '$datedes' $contypeqry
					
					ORDER BY \"contractID\"
				 ";
		$objQuery = pg_query($strSQL);
		$nrows = pg_num_rows($objQuery);
		
		$text = 'รายการทั้งหมดของเดือน';
		$textm ='รวมยอดสินเชื่อของเดือน';
		$bath = 'บาท';
		
		$amttextm ='รวมยอดสินเชื่อทั้งหมด';
		$amttext = 'รายการทั้งหมด';
		$roww = $roww + $nrows;			
	}
	
			list($year,$m2,$day)=explode('-',$date);
			if($m2=="01"){
				$txtmonth="มกราคม";
			}else if($m2=="02"){
				$txtmonth="กุมภาพันธ์";
			}else if($m2=="03"){
				$txtmonth="มีนาคม";
			}else if($m2=="04"){
				$txtmonth="เมษายน";
			}else if($m2=="05"){
				$txtmonth="พฤษภาคม";
			}else if($m2=="06"){
				$txtmonth="มิถุนายน";
			}else if($m2=="07"){
				$txtmonth="กรกฎาคม";
			}else if($m2=="08"){
				$txtmonth="สิงหาคม";
			}else if($m2=="09"){
				$txtmonth="กันยายน";
			}else if($m2=="10"){
				$txtmonth="ตุลาคม";
			}else if($m2=="11"){
				$txtmonth="พฤศจิกายน";
			}else if($m2=="12"){
				$txtmonth="ธันวาคม";
			}
			
			
			
				
	
if(($type == 'a1' OR $type == 'a3') && $nrows > 0){
		
		$sm = 0;
		$suma1 = 0;
		while($results = pg_fetch_array($objQuery)){
			$conid =  $results["contractID"];
					
			$maincussql = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' and \"CusState\" = '0' ");
			$remaincus = pg_fetch_array($maincussql);		
			

			if($nub > 24){
				
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(285,5.5,$buss_name,'B','L',0);
			
			
				$nub = 0;
				$pdf->AddPage();				
				$pdf->SetFont('AngsanaNew','B',12);
				
				

				$pdf->SetXY(5,20);
				$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
				$pdf->MultiCell(285,5,$buss_name,0,'R',0);
				
				$pdf->SetXY(5,21);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(285,4,$buss_name,'B','C',0);


				$cline = 27;
				
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"เดือน - ปี");
				$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(35,$cline);
				$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
				$pdf->MultiCell(45,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(80,$cline);
				$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
				$pdf->MultiCell(50,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(130,$cline);
				$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
				$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(160,$cline);
				$buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุดสัญญา");
				$pdf->MultiCell(30,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(190,$cline);
				$buss_name=iconv('UTF-8','windows-874',"ระยะเวลาสัญญา");
				$pdf->MultiCell(35,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(225,$cline);
				$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
				$pdf->MultiCell(20,5.5,$buss_name,1,'C',0);

				$pdf->SetXY(245,$cline);
				$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินกู้");
				$pdf->MultiCell(45,5.5,$buss_name,1,'C',0);
				$cline += 0;
			}
			
			$pdf->SetFont('AngsanaNew','',10);		
			$cline += 5.5;			
			
			if($sm == 0){
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',$txtmonth." ".$year);
				$pdf->MultiCell(30,5.5,$buss_name,'L','C',0);
			}
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(30,5.5,$buss_name,'L','C',0);
			
			$pdf->SetXY(35,$cline);
			$buss_name=iconv('UTF-8','windows-874',$conid);
			$pdf->MultiCell(45,5.5,$buss_name,'L','L',0);
			
			$pdf->SetXY(80,$cline);
			$buss_name=iconv('UTF-8','windows-874',$remaincus["thcap_fullname"]);
			$pdf->MultiCell(50,5.5,$buss_name,'L','L',0);
			
			$pdf->SetXY(130,$cline);
			$buss_name=iconv('UTF-8','windows-874',$results["conDate"]);
			$pdf->MultiCell(30,5.5,$buss_name,'L','C',0);
			
			$conenddate = $results["conEndDate"];
			if($conenddate == ""){ $conenddate = "-";}
			
			$pdf->SetXY(160,$cline);
			$buss_name=iconv('UTF-8','windows-874',$conenddate);
			$pdf->MultiCell(30,5.5,$buss_name,'L','C',0);
			
			$qry_times	= pg_query("SELECT \"thcap_getLoanLength\"('$conid')");	
			list($times) = pg_fetch_array($qry_times);
			if($times == ""){$times = "-";};
							
			$pdf->SetXY(190,$cline);
			$buss_name=iconv('UTF-8','windows-874',$times);
			$pdf->MultiCell(35,5.5,$buss_name,'L','R',0);
			
			$pdf->SetXY(225,$cline);
			$buss_name=iconv('UTF-8','windows-874',$results["conLoanIniRate"]." %");
			$pdf->MultiCell(20,5.5,$buss_name,'L','R',0);
			
			$pdf->SetXY(245,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($results["conLoanAmt"],2));
			$pdf->MultiCell(45,5.5,$buss_name,'LR','R',0);
			
			$suma1 += $results["conLoanAmt"];
			$sm++;
			$nub++;
		}
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,5.5,$buss_name,'B','L',0);
		
		$cline += 5.5;
		
		$pdf->SetXY(190,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nrows รายการ");
		$pdf->MultiCell(35,5.5,$buss_name,0,'R',0);
				
		$pdf->SetXY(225,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมยอดสินเชื่อของเดือน");
		$pdf->MultiCell(30,5.5,$buss_name,0,'R',0);
		
		$pdf->SetXY(245,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($suma1,2));
		$pdf->MultiCell(45,5.5,$buss_name,0,'R',0);
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,5.5,$buss_name,'LR','L',0);
		
		
		if($type=='a3'){
			if($nub > 24){
				$nub = 0;
				$pdf->AddPage();				
				$cline = 27;				
			}
			$suma3 = $suma1/$nrows;
			$cline += 5.5;
			$pdf->SetXY(225,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเฉลี่ยต่อสัญญา");
			$pdf->MultiCell(30,5.5,$buss_name,0,'R',0);
			
			$pdf->SetXY(245,$cline);
			$buss_name=iconv('UTF-8','windows-874',number_format($suma3,2));
			$pdf->MultiCell(45,5.5,$buss_name,0,'R',0);
			$nub++;
		
		}
		
		
			
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,5.5,$buss_name,'LRB','L',0);
	}else if($type == 'a2'){
	
		$contypenum = $_GET['contypee'];
		$contypenum = explode("@",$contypenum);
		for($con = 0;$con<sizeof($contypenum);$con++){
			if($contypenum[$con] != ""){
				$contypenumrow = 0;
				$strSQL1 = "
							
								SELECT 	\"contractID\",\"conType\" 
								FROM 	\"thcap_mg_contract\" 
								WHERE	(\"conDate\" Between '$date' AND  '$datedes') AND \"conType\" = '$contypenum[$con]'
								
								UNION
								
								SELECT 	\"contractID\",\"conType\" 
								FROM 	\"thcap_lease_contract\" 
								WHERE	(\"conDate\" Between '$date' AND  '$datedes') AND \"conType\" = '$contypenum[$con]'
								
								ORDER BY \"contractID\"
							";
				$objQuery1 = pg_query($strSQL1);
				$rowcon1 = pg_num_rows($objQuery1);
				$sum1 = $sum1.$contypenum[$con].":".$rowcon1." ";
				}	
		}
		
		$textm = "แบ่งรายการเป็น "	;
		if($i == 1){ $datedescontype = $datedes;}
		$datecontype = $date;	
		
		if($nub > 50){
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(285,5.5,$buss_name,'B','L',0);
				$nub = 0;
				$pdf->AddPage();				
				$cline = 27;
				$pdf->SetXY(5,$cline);
				$buss_name=iconv('UTF-8','windows-874',"");
				$pdf->MultiCell(285,5.5,$buss_name,'B','L',0);	
			}
	
		$cline += 5.5;
		
		$pdf->SetXY(90,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtmonth  $year    ทั้งหมด $nrows รายการ $textm $sum1");
		$pdf->MultiCell(200,5.5,$buss_name,0,'R',0);
						
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,5.5,$buss_name,'LR','L',0);
		
		$sum1 = "";
		$textm ="";
		$nub++;
	}
	
	$date = date("Y-m-d", strtotime("-1 month", strtotime($date)));
	list($year2,$m2,$d2) = explode("-",$date);
	$strSQL = "SELECT \"gen_numDaysInMonth\"($m2,$year2) ";
	$objQuery = pg_query($strSQL);
	$re1= pg_fetch_array($objQuery);
	list($day2)=$re1;				
	$datedes = $year2."-".$m2."-".$day2;
	
	$sumnrow += $nrows;
	$suma12 += $suma1;
	$suma1 = "";
	$nub++;
}

	

	if($type != 'a2'){
	
		if($nub > 24){
				$nub = 0;
				$pdf->AddPage();				
				$cline = 27;				
			}
			
		$textshow =  "รวมยอดสินเชื่อทั้งหมด  ".number_format($suma12,2);
	}
	
	if($type == 'a2'){	
		
		if($nub > 55){
				$nub = 0;
				$pdf->AddPage();				
				$cline = 27;				
			}
	
		$contypenum = $_GET['contypee'];
		$contypenum = explode("@",$contypenum);
		for($con = 0;$con<sizeof($contypenum);$con++){
			if($contypenum[$con] != ""){
				$contypenumrow = 0;
			$strSQL1 = "
							SELECT 	\"contractID\",\"conType\" 
							FROM 	\"thcap_mg_contract\" 
							WHERE	(\"conDate\" Between '$datecontype' AND  '$datedescontype') AND \"conType\" = '$contypenum[$con]'
							
							UNION
							
							SELECT 	\"contractID\",\"conType\" 
							FROM 	\"thcap_lease_contract\" 
							WHERE	(\"conDate\" Between '$datecontype' AND  '$datedescontype') AND \"conType\" = '$contypenum[$con]'
							
							ORDER BY \"contractID\"
						";
			$objQuery1 = pg_query($strSQL1);
			$rowcon1 = pg_num_rows($objQuery1);
			$amtsum1 = $amtsum1.$contypenum[$con].":".$rowcon1." ";
			}	
		}
		$textshow = "แบ่งรายการเป็น  ".$amtsum1;			
	}

	$pdf->SetFont('AngsanaNew','B',12);
	$cline += 5.5;
	$pdf->SetXY(90,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รายการทั้งหมด รวม $sumnrow  รายการ ".$textshow);
	$pdf->MultiCell(200,10,$buss_name,0,'R',0);
	
	
	
	if($type=='a3'){
	
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,20,$buss_name,'TLRB','L',0);
	
		$avgall = $suma12/$sumnrow ;
		$cline += 10;
		$pdf->SetXY(190,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเฉลี่ยต่อสัญญา   ".number_format($avgall,2));
		$pdf->MultiCell(100,10,$buss_name,0,'R',0);
		$nub++;
	}else{
		
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(285,10,$buss_name,'TLRB','L',0);

	}	
	
	
	
	
$pdf->Output();
?>