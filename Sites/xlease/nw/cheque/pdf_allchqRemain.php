<?php
session_start();
include("../../config/config.php");
$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

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

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อบริษัท");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(55,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(85,32);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(45,4,$buss_name,0,'C',0);

$pdf->SetXY(170,32);
$buss_name=iconv('UTF-8','windows-874',"เช็คคงเหลือ (เล่ม)");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;
$qryreceipt=pg_query("select count(a.\"detailID\") as nub,a.\"BAccount\",\"BName\",\"BBranch\",\"BCompany\" from \"cheque_detail\" a
	left join \"BankInt\" b on a.\"BAccount\"=b.\"BAccount\"
	where a.\"detailID\" IN (select \"detailID\" from \"cheque_order\" where stscheque='FALSE' and \"isChq\"='1')
	group by a.\"BAccount\",\"BName\",\"BBranch\",\"BCompany\"");
$i=0;
$allsum=0;
while($result=pg_fetch_array($qryreceipt)){
   list($allnub,$BAccount,$BName,$BBranch,$BCompany)=$result;
   
   //นับว่าแต่ละรายการมีเช็คคงเหลือทั้งหมดกี่ใบ
	$qrynubchq=pg_query("select count(\"chequeNum\") as nubchq from cheque_order a
	left join cheque_detail b on a.\"detailID\"=b.\"detailID\"
	where a.\"stscheque\"='FALSE' and \"BAccount\"='$BAccount'");
	$res=pg_fetch_array($qrynubchq);
	list($nubchq)=$res;
	
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

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);
		
        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

       $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อบริษัท");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(55,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(85,32);
		$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(125,32);
		$buss_name=iconv('UTF-8','windows-874',"สาขา");
		$pdf->MultiCell(45,4,$buss_name,0,'C',0);

		$pdf->SetXY(170,32);
		$buss_name=iconv('UTF-8','windows-874',"เช็คคงเหลือ (เล่ม)");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);
		
        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
//show all record
    $pdf->SetFont('AngsanaNew','',10);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BCompany");
    $pdf->MultiCell(50,4,$buss_name,0,'L',0);

    $pdf->SetXY(55,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BAccount");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(85,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BName");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BBranch");
    $pdf->MultiCell(45,4,$buss_name,0,'L',0);
    
    $pdf->SetXY(170,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$allnub ($nubchq ใบ)");
    $pdf->MultiCell(35,4,$buss_name,0,'C',0);
	
    $cline += 5;
    $nub+=1;
	$allsum=$allsum+$allnub;
} //end while 

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;
$pdf->SetFont('AngsanaNew','B',10);

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเล่มทั้งหมด  $allsum   เล่ม");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);
		   
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

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อบริษัท");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(55,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(85,32);
		$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(125,32);
		$buss_name=iconv('UTF-8','windows-874',"สาขา");
		$pdf->MultiCell(45,4,$buss_name,0,'C',0);

		$pdf->SetXY(170,32);
		$buss_name=iconv('UTF-8','windows-874',"เช็คคงเหลือ (เล่ม)");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }
$pdf->Output();
?>