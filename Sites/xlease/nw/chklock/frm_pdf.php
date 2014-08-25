<?php
session_start();
include("../../config/config.php");

$nowdate = nowDate();
$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];
$status = $_POST["status"];


//------------------- PDF -------------------//
require('../../thaipdfclass.php');

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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานการตรวจสอบสัญญา Lock หรือ Unlock");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ระหว่าง $startDate ถึง $endDate");
$pdf->MultiCell(60,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(50,33);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(70,33);
$buss_name=iconv('UTF-8','windows-874',"ตรวจสอบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->Line(100,30.2,100,264);

$pdf->SetXY(100,33);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(145,33);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(165,33);
$buss_name=iconv('UTF-8','windows-874',"ตรวจสอบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,B,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;

if($status == "" || $status == 1)
{
	$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where \"LockContact\" = 'TRUE' and (\"P_STDATE\" between '$startDate' and '$endDate') order by \"IDNO\"");
}
else if($status == 2)
{
	$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where \"LockContact\" = 'FALSE' and (\"P_STDATE\" between '$startDate' and '$endDate') order by \"IDNO\"");
}
else if($status == 3)
{
	$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where (\"P_STDATE\" between '$startDate' and '$endDate') order by \"LockContact\" ");
}

$num_row=pg_num_rows($query);
$i=0;
while($row = pg_fetch_array($query))
{
	$IDNO = $row["IDNO"];
	$LockContact = $row["LockContact"];
		
	if($LockContact == 't')
	{
		$txtlock = "LOCK";
	}
	else
	{
		$txtlock = "UNLOCK";
	}
	
	if($nub > 90){
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
      
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานการตรวจสอบสัญญา Lock หรือ Unlock");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ระหว่าง $startDate ถึง $endDate");
		$pdf->MultiCell(60,4,$buss_name,0,'L',0);
	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(20,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,33);
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(70,33);
		$buss_name=iconv('UTF-8','windows-874',"ตรวจสอบ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->Line(100,30.2,100,264);

		$pdf->SetXY(100,33);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(115,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(145,33);
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(165,33);
		$buss_name=iconv('UTF-8','windows-874',"ตรวจสอบ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,B,'C',0);
	}
	
	$i++;
	if($nub<46){
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$i");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(20,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$IDNO");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtlock");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(70,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(83,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,3,$buss_name,1,'C',0);
	}else{
		if($nub==46){
			$cline = 40;
		}
		
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(100,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$i");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(115,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$IDNO");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(145,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$txtlock");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(165,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(178,$cline);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(5,3,$buss_name,1,'C',0);

	}
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(200,4,$buss_name,B,'C',0);
	$cline += 5;
	$nub+=1;	
}

if($num_row = 0){
    $pdf->SetFont('AngsanaNew','B',13);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>