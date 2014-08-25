<?php
session_start();
include("../config/config.php");
include("../core/core_functions.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$idno = $_POST["idno"];

$qry_vcon=pg_query("select * from \"VContact\" WHERE  \"IDNO\"='$idno'");
if($re_vcon=pg_fetch_array($qry_vcon)){
     $full_name = $re_vcon["full_name"];
}


//------------------- PDF -------------------//
require('../thaipdfclass.php');

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
$title=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานชำระค่าอื่นๆ");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"IDNO : $idno  $full_name");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(42,33);
$buss_name=iconv('UTF-8','windows-874',"รหัส");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(62,33);
$buss_name=iconv('UTF-8','windows-874',"รายการ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,33);
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(110,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่อ้างถึง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(130,33);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,33);
$buss_name=iconv('UTF-8','windows-874',"คำอธิบายในระบบเก่า ณ วันจ่าย");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry_vcus=pg_query("select * from \"FOtherpay\" WHERE  \"IDNO\"='$idno' AND \"Cancel\"='false' ORDER BY \"O_DATE\",\"O_RECEIPT\" ASC");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
	while($resvc=pg_fetch_array($qry_vcus))
	{        
		$qry_name=pg_query("select \"TName\" from \"TypePay\" WHERE  \"TypeID\"='$resvc[O_Type]' ");
		$resname=pg_fetch_array($qry_name);
		
        $O_DATE = $resvc["O_DATE"];
        $O_RECEIPT = $resvc["O_RECEIPT"];
        $O_Type = $resvc["O_Type"];
        $TName = $resname["TName"];
		
        if(empty($resvc['O_BANK']) && empty($resvc['PayType'])){
            $PayType = "";
        }else{
            $PayType = "$resvc[O_BANK] / $resvc[PayType]";
        }
		
        $RefAnyID = $resvc["RefAnyID"];
		
        $O_MONEY = $resvc["O_MONEY"];
		$O_memo = $resvc["O_memo"];
	
		
	
	if($nub == 46)
	{
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
		
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานชำระค่าอื่นๆ");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"IDNO : $idno  $full_name");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(42,33);
		$buss_name=iconv('UTF-8','windows-874',"รหัส");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(62,33);
		$buss_name=iconv('UTF-8','windows-874',"รายการ");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,33);
		$buss_name=iconv('UTF-8','windows-874',"PayType");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่อ้างถึง");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(130,33);
		$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(160,33);
		$buss_name=iconv('UTF-8','windows-874',"คำอธิบายในระบบเก่า ณ วันจ่าย");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(25,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(42,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$O_Type");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(62,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$TName");
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(86,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$PayType");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$RefAnyID");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(130,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
	$pdf->MultiCell(25,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(160,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$O_memo");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	/*
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	*/
    
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}

}


if($rows > 0){
    $pdf->SetFont('AngsanaNew','B',13);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN

$qry_vcus=pg_query("select * from \"VFrNotPaymentButUseVat\" WHERE  \"IDNO\"='$idno' ORDER BY \"R_Date\",\"R_Receipt\" ASC");
$rows2 = pg_num_rows($qry_vcus);
if($rows2 > 0)
{
	
		if($nub == 46)
		{
			$nub = 1;
			$cline = 40;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานชำระค่าอื่นๆ");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"IDNO : $idno  $full_name");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
			$cline += 5;
			$nub+=1;
			$a += 1;
			$i++;
		}	
	
			
			$cline += 5;
			$nub+=1;
	
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"----- รายการที่มี VAT -----");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+2);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
			$cline += 7;
			$nub+=1;
			
		if($nub < 46)
		{
			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(25,$cline);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รหัส");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(70,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รายการ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(95,$cline);
			$buss_name=iconv('UTF-8','windows-874',"PayType");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(117,$cline);
			$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,$cline);
			$buss_name=iconv('UTF-8','windows-874',"vat");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(160,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวม");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,$cline+1);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
			
			$cline += 6;
			$nub+=3;
		}
		
	while($resvc=pg_fetch_array($qry_vcus))
	{		
        $R_Date = $resvc["R_Date"];
        $Receipt = $resvc["R_Receipt"]."/".$resvc["V_Receipt"];
        $R_DueNo = $resvc["R_DueNo"];
        $typepay_name = $resvc["typepay_name"];
        
        if(empty($resvc['R_Bank']) && empty($resvc['PayType'])){
            $PayType = "";
        }else{
            $PayType = "$resvc[R_Bank] / $resvc[PayType]";
        }
		
        $value = $resvc["value"];
        $vat = $resvc["vat"];
        $money = $resvc["money"];
		
		if($nub == 46)
		{
			$nub = 1;
			$cline = 40;
			$pdf->AddPage();
			
			
			$pdf->SetFont('AngsanaNew','B',18);
			$pdf->SetXY(5,10);
			$title=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
			$pdf->MultiCell(200,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,16);
			$buss_name=iconv('UTF-8','windows-874',"รายงานชำระค่าอื่นๆ");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"IDNO : $idno  $full_name");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,25);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(200,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,26);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,33);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(25,33);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,33);
			$buss_name=iconv('UTF-8','windows-874',"รหัส");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(70,33);
			$buss_name=iconv('UTF-8','windows-874',"รายการ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(95,33);
			$buss_name=iconv('UTF-8','windows-874',"PayType");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(117,33);
			$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,33);
			$buss_name=iconv('UTF-8','windows-874',"vat");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(160,33);
			$buss_name=iconv('UTF-8','windows-874',"รวม");
			$pdf->MultiCell(35,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',14);
			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		}
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$R_Date");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$Receipt");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(50,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$R_DueNo");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(70,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$typepay_name");
		$pdf->MultiCell(25,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(95,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$PayType");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(117,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($value,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(140,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($vat,2));
		$pdf->MultiCell(20,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(160,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($money,2));
		$pdf->MultiCell(35,4,$buss_name,0,'R',0);
	}
}

//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
$pdf->Output();
?>