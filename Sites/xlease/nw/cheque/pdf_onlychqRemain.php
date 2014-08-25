<?php
session_start();
include("../../config/config.php");

$BAccount = pg_escape_string($_GET['BAccount']);
$chequebook = pg_escape_string($_GET['chequebook']);

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

//หารายละเอียดของธนาคาร
$qryreport=pg_query("select \"BName\", \"BBranch\", \"BCompany\" from \"BankInt\" where \"BAccount\"='$BAccount'");
$result=pg_fetch_array($qryreport);
list($BName,$BBranch,$BCompany)=$result;

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

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คคงเหลือ");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
						
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ชื่อบริษัท : $BCompany   เลขที่บัญชี : $BAccount   ชื่อธนาคาร : $BName  สาขา : $BBranch");
$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(60,32);
$buss_name=iconv('UTF-8','windows-874',"เล่มที่");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(110,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;
$qryreceipt=pg_query("select chequebook,\"chequeNum\" from cheque_order a
	left join cheque_detail b on a.\"detailID\"=b.\"detailID\"
	where a.\"stscheque\"='FALSE' and \"BAccount\"='$BAccount' and a.chequebook='$chequebook' order by \"chequebook\",\"chequeNum\"");
	
$i=0;
$nub=1;
$sumchq = 0;
$allsum=0;
$chequeold="";
while($result=pg_fetch_array($qryreceipt)){
	list($chequebook,$chequeNum)=$result;
	
	$pdf->SetFont('AngsanaNew','B',10);
	
	//ถ้าเล่มของเช็คไม่เหมือนกัน ให้แสดงรวมรายการในบรรทัดสุดท้าย
    if(($chequeold != $chequebook) && $nub != 1){ //and $nub < 45
	
        $pdf->SetFont('AngsanaNew','B',10);
		$pdf->SetXY(60,$cline);
		$buss_name=iconv('UTF-8','windows-874',"เหลือในเล่ม");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$sumchq ใบ");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
        $pdf->SetXY(5,$cline+1);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
        
        $sumchq = 0;
		
		if($nub == 36){ 
			$cline += 14;
			$nub=36;
		}else{
			$cline += 7;
			$nub+=1;	
		}		
    }
	  
	//show only new page

    if($nub == 36){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',16);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คคงเหลือ");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
								
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ชื่อบริษัท : $BCompany   เลขที่บัญชี : $BAccount   ชื่อธนาคาร : $BName  สาขา : $BBranch");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);
		
		$pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
        $pdf->SetXY(60,32);
		$buss_name=iconv('UTF-8','windows-874',"เล่มที่");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
	}
	
//show all record
	if($chequeold==$chequebook){
		$chequebook2="";
	}else{
		$chequebook2=$chequebook;
	}
	
    $pdf->SetFont('AngsanaNew','',10);
    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$chequebook2");
    $pdf->MultiCell(50,4,$buss_name,0,'C',0);

    $pdf->SetXY(110,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$chequeNum");
    $pdf->MultiCell(50,4,$buss_name,0,'C',0);

    $cline += 5;
    $nub+=1;
    
    $chequeold=$chequebook;	
	$sumchq++;		
	$allsum++;
} //end while 

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(60,$cline);
$buss_name=iconv('UTF-8','windows-874',"เหลือในเล่ม");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',"$sumchq ใบ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(60,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเหลือทั้งหมด");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',"$allsum ใบ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		   
$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;

    if($nub == 36){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',16);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คคงเหลือ");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
								
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ชื่อบริษัท : $BCompany   เลขที่บัญชี : $BAccount   ชื่อธนาคาร : $BName  สาขา : $BBranch");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetFont('AngsanaNew','',14);
        $pdf->SetXY(60,32);
		$buss_name=iconv('UTF-8','windows-874',"เล่มที่");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }
$pdf->Output();
?>