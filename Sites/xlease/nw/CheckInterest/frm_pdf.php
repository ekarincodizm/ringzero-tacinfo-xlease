<?php
session_start();
include("../../config/config.php");
include("MoneyFunction.php");
set_time_limit(5400);

$nowdate = nowDate();

//------ หาจำนวนรายการ
/*
$t = 0;
$qryR=pg_query("select distinct \"tacReceiveTemp\".\"tacID\" from public.\"tacReceiveTemp\" order by \"tacID\" ");
$numoneR = pg_num_rows($qryR);
while($res=pg_fetch_array($qryR))
{
	$tacID = $res["tacID"];

	$query_ONIDr=mssql_query("select * from TacInvoice where CusID='$tacID' and RecNo='' ");
	$num_ONIDr=mssql_num_rows($query_ONIDr);
	
	if($num_ONIDr > 0)
	{
		$t++;
	}
}
*/
//------ จบการหาจำนวนรายการ


//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
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
$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบสัญญาปิดบัญชี");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
//$buss_name=iconv('UTF-8','windows-874',"ค้างชำระตกหล่น จำนวน : $t รายการ");
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(20,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(80,4,$buss_name,0,'C',0);

$pdf->SetXY(90,33);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือที่ยังไม่จ่าย");
$pdf->MultiCell(80,4,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry=pg_query("select * from public.\"Fp\" where \"P_ACCLOSE\" = 'TRUE' order by \"IDNO\" ");
$numone = pg_num_rows($qry);
while($res=pg_fetch_array($qry))
{
	$tacID = $res["IDNO"];
	
	$money = SearchMoney("$tacID");

	if($money > 0)
	//if(1 > 0)
	{
		if($nub == 46)
		{
			$nub = 1;
			$cline = 40;
			$pdf->AddPage();
        
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',15);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบสัญญาปิดบัญชี");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			//$buss_name=iconv('UTF-8','windows-874',"ค้างชำระตกหล่น จำนวน : รายการ");
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,33);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(80,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(90,33);
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยคงเหลือที่ยังไม่จ่าย");
			$pdf->MultiCell(80,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		}
	
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(20,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$tacID");
		$pdf->MultiCell(80,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(65,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($money,2));
		$pdf->MultiCell(80,4,$buss_name,0,'R',0);
	
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline+1);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
   
		$cline += 5;
		$nub+=1;
		$a += 1;
	}
}

if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>