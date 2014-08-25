<?php
session_start();
include("../../config/config.php");

$condate=pg_escape_string($_REQUEST["condate"]);
	
if($condate=="1"){
	$datepicker=pg_escape_string($_REQUEST["datepicker"]);
	$conday="and date(\"keyStamp\")='$datepicker'";
	$txtcon="วันที่ $datepicker";
}else{
	$month=pg_escape_string($_REQUEST["month"]);
	$year=pg_escape_string($_REQUEST["year"]);
	
	if($month=="01"){
		$txtmonth="มกราคม";
	}else if($month=="02"){
		$txtmonth="กุมภาพันธ์";
	}else if($month=="03"){
		$txtmonth="มีนาคม";
	}else if($month=="04"){
		$txtmonth="เมษายน";
	}else if($month=="05"){
		$txtmonth="พฤษภาคม";
	}else if($month=="06"){
		$txtmonth="มิถุนายน";
	}else if($month=="07"){
		$txtmonth="กรกฎาคม";
	}else if($month=="08"){
		$txtmonth="สิงหาคม";
	}else if($month=="09"){
		$txtmonth="กันยายน";
	}else if($month=="10"){
		$txtmonth="ตุลาคม";
	}else if($month=="11"){
		$txtmonth="พฤศจิกายน";
	}else if($month=="12"){
		$txtmonth="ธันวาคม";
	}
	$txtcon="เดือน$txtmonth $year";
		
	$conday="and EXTRACT(MONTH FROM \"keyStamp\")='$month' and EXTRACT(YEAR FROM \"keyStamp\")='$year'";
}
	
$typePay=trim(pg_escape_string($_REQUEST["typePay"]));
	
if($typePay!=""){
	//ค้นหาว่า Type ที่ได้มีชื่อว่าอะไร
	$qrynametype=pg_query("SELECT \"typeName\" FROM cheque_typepay where \"typePay\"='$typePay'");
	$resnametype=pg_fetch_array($qrynametype);
	list($nametype)=$resnametype;
	$contypepay="and a.\"typePay\"='$typePay'";
}else{
	$nametype="ทุกประเภท";
}
	
$company=pg_escape_string($_REQUEST["company"]);
if($company!=""){
	$concompany="and replace(c.\"BCompany\",' ','')='$company'";
}else{
	$company="ทุกบริษัท";
}
	
$cheque=pg_escape_string($_REQUEST["cheque"]);
if($cheque!=""){
	//แยกชื่อธนาคารกับสาขาออกจากกัน
	list($cheque1,$cheque2)=explode("/",$cheque);
	$concheque="and replace(c.\"BName\",' ','')='$cheque1' and replace(c.\"BBranch\",' ','')='$cheque2'";
	$txtcheque="$cheque1 สาขา$cheque2";
}else{
	$txtcheque="ทุกธนาคาร";
}

$nowdate = nowDate();

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

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คจ่าย ประจำ$txtcon");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย: $nametype, ชื่อบริษัท: $company, เช็คธนาคาร: $txtcheque");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย");
$pdf->MultiCell(25,6,$buss_name,0,'C',0);

$pdf->SetXY(30,32);
$buss_name=iconv('UTF-8','windows-874',"เช็คเลขที่");
$pdf->MultiCell(25,6,$buss_name,0,'C',0);

$pdf->SetXY(55,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
$pdf->MultiCell(25,6,$buss_name,0,'C',0);

$pdf->SetXY(80,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,6,$buss_name,0,'C',0);

$pdf->SetXY(105,32);
$buss_name=iconv('UTF-8','windows-874',"สั่งจ่าย");
$pdf->MultiCell(40,6,$buss_name,0,'L',0);

$pdf->SetXY(145,32);
$buss_name=iconv('UTF-8','windows-874',"ประเภทเช็ค");
$pdf->MultiCell(25,6,$buss_name,0,'C',0);

$pdf->SetXY(170,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,6,$buss_name,0,'R',0);

$pdf->SetXY(190,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่สั่งจ่าย");
$pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(208,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
$pdf->MultiCell(40,6,$buss_name,0,'L',0);

$pdf->SetXY(248,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(278,32);
$buss_name=iconv('UTF-8','windows-874',"สถานะเช็ค");
$pdf->MultiCell(15,6,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,6,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',12);
$cline = 41;
$nub = 1;
$qrychq=pg_query("select \"chqpayID\",\"typeName\",\"IDNO\",\"cusPay\",\"moneyPay\",\"datePay\",c.\"BAccount\",
		c.\"BName\",\"chequeNum\",c.\"BCompany\",a.\"typeChq\",a.\"note\",d.\"fullname\",\"keyStamp\",\"statusPay\" from cheque_pay a
		left join cheque_typepay b on a.\"typePay\"=b.\"typePay\"
		left join \"BankInt\" c on a.\"BAccount\"=c.\"BAccount\"
		left join \"Vfuser\" d on a.\"keyUser\"=d.\"id_user\"
		where a.\"appStatus\"='1' $conday $contypepay $concompany $concheque order by \"keyStamp\",a.\"typePay\"");
$i=0;
$sum=0;
while($reschq=pg_fetch_array($qrychq)){
	list($chqpayID,$typeName,$IDNO,$cusPay,$moneyPay,$datePay,$BAccount,$BName,$chequeNum,$BCompany,$typeChq,$note,$keyuser,$keyStamp,$statusPay,$typePay)=$reschq;
	if($IDNO=="") $IDNO="-";
	if($BName=="") $BName="-";
	if($BCompany=="")$BCompany="-";
	
	if($typeChq=="1"){
		$typeChqname="ปกติ";
	}else if($typeChq=="2"){
		$typeChqname="A/C PAYEE ONLY";
	}else{
		$typeChqname="&Co.";	
	}
						
	if($statusPay=="t"){
		$statuschq="ปกติ";
	}else{
		$statuschq="ยกเลิก";
	}
	
    $pdf->SetFont('AngsanaNew','B',12);
	
	//show only new page
    if($nub == 46){
        $nub = 1;
        $cline = 41;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คจ่าย ประจำ$txtcon $typePay");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย: $nametype, ชื่อบริษัท: $company, เช็คธนาคาร: $cheque");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"เช็คเลขที่");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(55,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(80,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(105,32);
		$buss_name=iconv('UTF-8','windows-874',"สั่งจ่าย");
		$pdf->MultiCell(40,6,$buss_name,0,'L',0);

		$pdf->SetXY(145,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทเช็ค");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(170,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(20,6,$buss_name,0,'R',0);

		$pdf->SetXY(190,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่สั่งจ่าย");
		$pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(208,32);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
		$pdf->MultiCell(40,6,$buss_name,0,'L',0);

		$pdf->SetXY(248,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(278,32);
		$buss_name=iconv('UTF-8','windows-874',"สถานะเช็ค");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,35);
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,6,$buss_name,0,'C',0);
	}
	
//show all record
    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typeName");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(30,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$chequeNum");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(55,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BAccount");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(80,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(105,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$cusPay");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(145,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typeChqname");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(170,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($moneyPay,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

 	$pdf->SetXY(190,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$datePay");
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(208,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$keyuser");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(248,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$keyStamp");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(278,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$statuschq");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
 
    $cline += 5;
    $nub+=1;
	$sum=$sum+$moneyPay;
} //end while 

$cline += 6;
$nub+=1;

    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานเช็คจ่าย ประจำ$txtcon $typePay");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย: $nametype, ชื่อบริษัท: $company, เช็คธนาคาร: $cheque");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทการสั่งจ่าย");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(30,32);
		$buss_name=iconv('UTF-8','windows-874',"เช็คเลขที่");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(55,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชี");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(80,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(105,32);
		$buss_name=iconv('UTF-8','windows-874',"สั่งจ่าย");
		$pdf->MultiCell(40,6,$buss_name,0,'L',0);

		$pdf->SetXY(145,32);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทเช็ค");
		$pdf->MultiCell(25,6,$buss_name,0,'C',0);

		$pdf->SetXY(170,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(20,6,$buss_name,0,'R',0);

		$pdf->SetXY(190,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่สั่งจ่าย");
		$pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(208,32);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
		$pdf->MultiCell(40,6,$buss_name,0,'L',0);

		$pdf->SetXY(248,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(278,32);
		$buss_name=iconv('UTF-8','windows-874',"สถานะเช็ค");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,35);
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(290,6,$buss_name,0,'C',0);
    }
	
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline-6);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,6,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(145,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(145,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"______________________________");
$pdf->MultiCell(45,4,$buss_name,0,'R',0);

$pdf->SetXY(145,$cline+1.5);
$buss_name=iconv('UTF-8','windows-874',"______________________________");
$pdf->MultiCell(45,4,$buss_name,0,'R',0);

$pdf->Output();
?>