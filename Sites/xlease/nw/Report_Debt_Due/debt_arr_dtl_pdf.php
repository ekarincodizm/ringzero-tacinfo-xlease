<?php
include("../../config/config.php");
$nowdate = date('Y-m-d H:m:s');
$id = $_GET['id'];


										$qry_namemain=pg_query("select \"tpDesc\" from account.\"thcap_typePay\"
	where \"tpID\" = '$id' ");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$tpDesc2=trim($resnamemain["tpDesc"]);
		//$A_MOBILE=trim($resnamemain["A_MOBILE"]);
	}
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
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานหนี้ค้างชำระของ $id $tpDesc2");
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
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(74,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อเรียกหนี้");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(143,30); 
$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(167,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(182,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$a = 0;
$qry_debt_due=pg_query("select c.\"tpID\",b.\"typePayLeft\" as total, c.\"tpDesc\",c.\"tpFullDesc\", b.\"contractID\",b.\"doerStamp\", b.\"typePayRefValue\" from thcap_temp_otherpay_debt b ,account.\"thcap_typePay\" c
where b.\"typePayID\" = c.\"tpID\" and b.\"debtStatus\"='1' and b.\"typePayID\" ='$id' order by b.\"contractID\",b.\"typePayRefValue\" ,b.\"doerStamp\"  "); 
							$row_debt_due = pg_num_rows($qry_debt_due);
							

while($res_fc = pg_fetch_array($qry_debt_due))
										{
											$i++;
											$a++;
											$contractID =trim($res_fc["contractID"]);
											$typePayID =trim($res_fc["tpID"]);
											$total =trim($res_fc["total"]);
											$tpDesc =trim($res_fc["tpDesc"]);
											$doerStamp= substr(trim($res_fc["doerStamp"]),0,19);
											$typePayRefValue=trim($res_fc["typePayRefValue"]);
											$tpFullDesc =trim($res_fc["tpFullDesc"]);
											if($tpFullDesc=="")$tpFullDesc="-";
											$qry_namemain=pg_query("select thcap_fullname from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" ='0'");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$name3=trim($resnamemain["thcap_fullname"]);
		//$A_MOBILE=trim($resnamemain["A_MOBILE"]);
	}

		if($i > 45)
		{ 
			$pdf->AddPage(); $cline = 37; $i=1; 

			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานหนี้ค้างชำระของ $id $tpDesc2");
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
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(74,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อเรียกหนี้");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(143,30); 
$buss_name=iconv('UTF-8','windows-874',"คำอธิบาย");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(167,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(182,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
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
		$buss_name=iconv('UTF-8','windows-874',"$contractID");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(35,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$name3");
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(77,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$doerStamp");
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(100,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$typePayID - $tpDesc");
		$pdf->MultiCell(55,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(148,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$tpFullDesc");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(163,$cline); 
		$buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
		$pdf->MultiCell(16,4,$buss_name,0,'R',0);
		

		$pdf->SetXY(178,$cline); 
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

$pdf->SetXY(178,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_total,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>