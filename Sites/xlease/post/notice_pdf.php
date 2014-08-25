<?php
include("../config/config.php");

$nowdate = Date('Y-m-d');

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
 
    }
 
}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน ออก NT");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"สีรถ");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวดที่ค้าง");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(150,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างถึงปัจจุบัน");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ NT");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;


$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\",MAX(daydelay) as daydelay,MIN(\"DueDate\") as datedue from \"VRemainPayment\" GROUP BY \"IDNO\" ");
while($res_fr=pg_fetch_array($qry_fr)){
    $IDNO = $res_fr["IDNO"];
    $SumDueNo = $res_fr["SumDueNo"];
	$daydelay = $res_fr["daydelay"];
	$DueDate = $res_fr["datedue"]; //วันที่ของงวดที่ค้างชำระ
   
	if($SumDueNo == 1 || $SumDueNo == 2){
		//ดึงจำนวนงวดสุดท้าย และวันที่ครบกำหนดค่างวดสุดท้ายขึ้นมา เพื่อนำมาเปรียบเทียบกับงวดที่ค้างว่าใช่งวดสุดท้าย หรือ รองสุดท้ายหรือไม่
		$qry_fr1 = pg_query("select MAX(\"DueNo\") as \"DueNo1\",MAX(\"DueDate\") as \"DueDate1\" from \"VCusPayment\" where \"IDNO\" = '$IDNO' GROUP BY \"IDNO\"");
		if($res_vc1 = pg_fetch_array($qry_fr1)){
			$DueNo1 = $res_vc1["DueNo1"]; 
			$DueDate1 = $res_vc1["DueDate1"]; //วันที่ที่ต้องชำระงวดสุดท้าย
			$DueNo2 = $DueNo1 - 1;
		}
		
		if($SumDueNo == 1){  //กรณีค้างแค่ 1 งวด (ดูว่าใช่งวดสุดท้ายหรือไม่)
			if($DueDate == $DueDate1){ //เปรียบเทียบงวดที่ค้างล่าสุด ว่าใช่งวดสุดท้ายหรือไม่ ถ้าใช่ให้ + เพิ่ม 3 เดือน(เพื่อดูว่าครบกำหนดออก NT หรือยัง)	
				$DueDateEnd = date("Y-m-d", strtotime("+3 month", strtotime($DueDate1)));
				 //กรณีวันที่ปัจจุบันมากกว่าวันที่ต้องชำระ ให้ออก NT
				if($nowdate > $DueDateEnd) $numDue = 1;else $numDue = 0;
			}else{$numDue = 0;}
						
		}elseif($SumDueNo == 2){
			$qry_fr2 = pg_query("select \"DueDate\" as \"DueDateBeforLast\" from \"VCusPayment\" where \"IDNO\" = '$IDNO' and \"DueNo\" = '$DueNo2'");
			// วันที่ของงวดที่ต้องชำระงวดรองสุดท้าย
			if($res_vc2 = pg_fetch_array($qry_fr2)){$DueDateBeforLast = $res_vc2["DueDateBeforLast"]; }
			if($DueDate == $DueDateBeforLast){ //เปรียบเทียบงวดที่ค้างล่าสุด ว่าใช่งวดรองสุดท้ายหรือไม่ ถ้าใช่ให้ + เพิ่ม 3 เดือน(เพื่อดูว่าครบกำหนดออก NT หรือยัง)
				$DueDateBeforLast1 = date("Y-m-d", strtotime("+3 month", strtotime($DueDateBeforLast)));
				//กรณีวันที่ปัจจุบันมากกว่าวันที่ต้องชำระ ให้ออก NT
				if($nowdate > $DueDateBeforLast1) $numDue = 2;else $numDue = 0;
			}else{$numDue = 0;}
			
		}else{ $numDue = 0;}
		
	}else{ $numDue = 0;}
	
	if($SumDueNo > 2 || $numDue != 0){ 
        $nub+=1;
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $C_COLOR = $res_vc["C_COLOR"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }

    $P_LAWERFEE = "";
    $qry_vc3=pg_query("select \"P_LAWERFEE\" from \"Fp\" WHERE \"IDNO\"='$IDNO' ORDER BY \"P_LAWERFEE\"");
    if($res_vc3=pg_fetch_array($qry_vc3)){
        $P_LAWERFEE = $res_vc3["P_LAWERFEE"];
    }

             
if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"รายงาน  ออก NT");
	$pdf->MultiCell(190,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(10,16);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
	$pdf->MultiCell(190,4,$buss_name,0,'C',0);

	$pdf->SetXY(120,23);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(80,4,$buss_name,0,'R',0);

	$pdf->SetXY(4,24); 
	$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(196,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,30); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(30,4,$buss_name,0,'L',0);

	$pdf->SetXY(35,30); 
	$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
	$pdf->MultiCell(50,4,$buss_name,0,'L',0);

	$pdf->SetXY(85,30); 
	$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);

	$pdf->SetXY(105,30); 
	$buss_name=iconv('UTF-8','windows-874',"สีรถ");
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);

	$pdf->SetXY(125,30); 
	$buss_name=iconv('UTF-8','windows-874',"จำนวนงวดที่ค้าง");
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(150,30); 
	$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างถึงปัจจุบัน");
	$pdf->MultiCell(35,4,$buss_name,0,'L',0);

	$pdf->SetXY(185,30); 
	$buss_name=iconv('UTF-8','windows-874',"สถานะ NT");
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);

	$pdf->SetXY(4,32); 
	$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(196,4,$buss_name,0,'C',0);
}

$pdf->SetFont('AngsanaNew','',10); 

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(35,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(105,$cline); 
$buss_name=iconv('UTF-8','windows-874',$C_COLOR);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(125,$cline); 
$buss_name=iconv('UTF-8','windows-874',$SumDueNo);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',$daydelay);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(185,$cline);

if($P_LAWERFEE == 't'){
    $buss_name=iconv('UTF-8','windows-874',"ออกแล้ว");
}else{
    $buss_name=iconv('UTF-8','windows-874',"รออยู่");
}
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$cline+=5; 
$i+=1;       
}
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>