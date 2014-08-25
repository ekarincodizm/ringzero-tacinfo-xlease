<?php
include("../../config/config.php");
$nowdate = date('Y-m-d H:m:s');

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

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
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้อื่นๆค้างชำระ");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(5,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(11,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทรับชำระ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(102,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดรวมค้างชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$a = 0;
$qry_debt_due=pg_query("select c.\"tpID\", sum(b.\"typePayLeft\") as total, c.\"tpDesc\", c.\"tpFullDesc\" from thcap_temp_otherpay_debt b ,account.\"thcap_typePay\" c
where b.\"typePayID\" = c.\"tpID\" and b.\"debtStatus\"='1' group by c.\"tpID\", c.\"tpDesc\", c.\"tpFullDesc\" order by c.\"tpID\", c.\"tpDesc\", c.\"tpFullDesc\"  "); 
							$row_debt_due = pg_num_rows($qry_debt_due);


while($res_fc = pg_fetch_array($qry_debt_due))
										{
											$i++;
											$a++;
											$typePayID =trim($res_fc["tpID"]);
											$total =trim($res_fc["total"]);
											$tpDesc =trim($res_fc["tpDesc"]);
											$tpFullDesc =trim($res_fc["tpFullDesc"]);

		if($i > 45)
		{ 
			$pdf->AddPage(); $cline = 37; $i=1; 

			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานยอดหนี้อื่นๆค้างชำระ");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);


			$pdf->SetXY(120,23);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
			$pdf->MultiCell(80,4,$buss_name,0,'R',0);

			$pdf->SetXY(5,24); 
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(196,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',10);

			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"ลำดับ");
			$pdf->MultiCell(10,4,$buss_name,0,'C',0);

			$pdf->SetXY(11,30); 
			$buss_name=iconv('UTF-8','windows-874',"ประเภทรับชำระ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(30,30); 
			$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
			$pdf->MultiCell(70,4,$buss_name,0,'C',0);

			$pdf->SetXY(102,30); 
			$buss_name=iconv('UTF-8','windows-874',"ยอดรวมค้างชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,32); 
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(196,4,$buss_name,0,'C',0);
		}

		$pdf->SetFont('AngsanaNew','',10); 

		$pdf->SetXY(5,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$a);
		$pdf->MultiCell(10,4,$buss_name,0,'C',0);

		$pdf->SetXY(11,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$typePayID");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(35,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$tpDesc - $tpFullDesc");
		$pdf->MultiCell(70,4,$buss_name,0,'L',0);

		$pdf->SetXY(98,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($total,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);

		

		$cline+=5;
		
		$sum_total += $total; 
}
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอด $a รายการ");
$pdf->MultiCell(226,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(98,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_total,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>