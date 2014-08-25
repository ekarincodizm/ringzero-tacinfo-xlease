<?php
include("../config/config.php");
set_time_limit(120);
$gdate = pg_escape_string($_GET['date']);
$search = pg_escape_string($_GET['search']);
if($search==2){
	$years = pg_escape_string($_GET['years']);
	$condition="and \"custyear\"='$years'";
}else{
	$condition="";
}
$nowyear = date("Y");
$nowdate = date("Y/m/d");

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

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นคงเหลือ");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,22);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ ณ $gdate");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(10,25); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(10,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,32); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(80,4,$buss_name,0,'C',0);

$pdf->SetXY(150,32); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,34); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$cline = 40;
$i=0;
$qry_name=pg_query("SELECT * FROM account.\"VDebtBalance\" where \"acclosedate\" = '$gdate' $condition ORDER BY custyear,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $nub_all+=1;
    $idno = $res_name["idno"];
    $custyear = $res_name["custyear"];
    $customer_name = $res_name["customer_name"];
    $remain2 = $res_name["remain"];
    $vatpayready = $res_name["vatpayready"];
	
	$RL = $remain2 - 0.01;
	$RR = $remain2 + 0.01;
	
	/* หาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	$qry_name2=pg_query("SELECT * FROM public.\"VAccPayment\" where \"IDNO\" = '$idno' and \"Remine\" >= '$RL' and \"Remine\" <= '$RR' ");
	$rows2 = pg_num_rows($qry_name2);
	while($res_name2=pg_fetch_array($qry_name2))
	{
		$remain = $res_name2["Priciple"];
	}
	
	if($rows2 == 0)
	{
		$qry_name3=pg_query("SELECT * FROM public.\"Fp\" where \"IDNO\" = '$idno' ");
		while($res_name3=pg_fetch_array($qry_name3))
		{
			$remain = $res_name3["P_BEGINX"];
		}
	}
	*/ //จบการหาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	
	//หาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	$qry_name2=pg_query("SELECT * FROM public.\"VCusPayment\" where \"IDNO\" = '$idno' and \"Remine\" >= '$RL' and \"Remine\" <= '$RR' ");
	$rows2 = pg_num_rows($qry_name2);
	while($res_name2=pg_fetch_array($qry_name2))
	{
		$remain = $res_name2["Priciple"];
	}
	
	if($rows2 == 0)
	{
		$qry_name3=pg_query("SELECT * FROM public.\"Fp\" where \"IDNO\" = '$idno' ");
		while($res_name3=pg_fetch_array($qry_name3))
		{
			$remain = $res_name3["P_BEGIN"]; // เงินต้นทางลูกค้า
		}
	}
	//จบการหาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
    
    if($nub_all == 1){
        $pdf->SetXY(10,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ปี $custyear");
        $pdf->MultiCell(30,4,$buss_name,0,'L',0);
        $cline+=5;
        $i+=1;
    }
    
    if($nub_all != 1 && $custyear != $old_custyear){
        $pdf->SetXY(10,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nubyear ราย | รวมเงิน");
        $pdf->MultiCell(100,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_remain_year,2));
        $pdf->MultiCell(40,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i+=1;
        
        $pdf->SetXY(10,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ปี $custyear");
        $pdf->MultiCell(30,4,$buss_name,0,'L',0);
        $cline+=5;
        $i+=1;

        $nubyear = 0;
        $sum_remain_year = 0;
        $sum_vatpayready_year = 0;
    }

    $nubyear+=1;
    $sum_remain_year+=$remain;
    $sum_vatpayready_year+=$vatpayready;
    
    $s_remain += $remain;
    $s_vatpayready += $vatpayready;

if($i > 45){
    $pdf->AddPage();
    $cline = 40;
    $i=0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเงินต้นคงเหลือ");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,22);
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ ณ $gdate");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(10,25); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(10,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,32); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(80,4,$buss_name,0,'C',0);

$pdf->SetXY(150,32); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,34); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',14);

$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',$idno);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$buss_name=iconv('UTF-8','windows-874',$customer_name);
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($remain,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$cline+=5;
$i+=1;
$old_custyear = $custyear;
}

/*$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);
$cline+=3;*/

$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nubyear ราย | รวมเงิน");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_remain_year,2));
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$cline+=5;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);
$cline+=3;

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(10,$cline); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนลูกค้าทั้งหมด = $rows");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_remain,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->Output();
?>