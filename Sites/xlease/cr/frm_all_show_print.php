<?php
set_time_limit(0);
ini_set('memory_limit','1024M');
include("../config/config.php");
if(!empty($_GET['mm'])){ $mm = pg_escape_string($_GET['mm']); }
if(!empty($_GET['yy'])){ $yy = pg_escape_string($_GET['yy']); }

$nowdate = date("Y/m/d");
$lineend = 30;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(282,4,$buss_name,0,'R',0);
    }
 
}


$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $mm/$yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(287,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,5,$buss_name,1,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(30,5,$buss_name,1,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 35;
$i = 1;
$j = 0;                           
$qry_name=pg_query("select \"IDCarTax\",\"IDNO\",\"CusAmt\",\"TypeDep\",\"ApointmentDate\",\"TaxDueDate\",\"BookIn\" 
from carregis.\"CarTaxDue\" where (EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy') ORDER BY \"IDNO\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDCarTax = $res_name["IDCarTax"];
    $IDNO = $res_name["IDNO"];
    $CusAmt = $res_name["CusAmt"];
    $TypeDep = $res_name["TypeDep"];
    $ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
    $TaxDueDate = $res_name["TaxDueDate"];
        $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
    $BookIn = $res_name["BookIn"];

$qry_name8=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
    if($res_name8=pg_fetch_array($qry_name8)){
        $TDName = $res_name8["TName"];
    }
    
    $O_DATE = "";
    $O_RECEIPT = "";
    $O_MONEY = "";
    $PayType = "";
    $qry_vcus=pg_query("select \"O_DATE\",\"O_RECEIPT\",\"O_MONEY\",\"PayType\" from \"FOtherpay\" WHERE  \"RefAnyID\"='$IDCarTax'");
if($resvc=pg_fetch_array($qry_vcus)){
        $O_DATE = $resvc["O_DATE"];
        $O_RECEIPT = $resvc["O_RECEIPT"];
        $O_MONEY = $resvc["O_MONEY"];
        $PayType = $resvc["PayType"];
        $sum_O_MONEY+=$O_MONEY;
}  

$qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
				left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
				left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
				WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");      

$num_cartemp=pg_num_rows($qry_name2);
if($num_cartemp==0){
	//กรณีเป็น Gas 
	$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
	LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
	LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
	WHERE \"IDNO\"='$IDNO' ");
}

//$qry_name2=pg_query("select * from \"VContacttest\" WHERE \"IDNO\"='$IDNO' ");
if($res_name2=pg_fetch_array($qry_name2)){
    $asset_id = $res_name2["asset_id"];
    $full_name = $res_name2["full_name"];
    $asset_type = $res_name2["asset_type"];
    $C_REGIS = $res_name2["C_REGIS"];
    $car_regis = $res_name2["car_regis"];
    if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
}else{
    $full_name = "ไม่พบข้อมูล";
    $show_regis = "ไม่พบข้อมูล";
}

if($i > $lineend){
    $pdf->AddPage(); 
    $cline = 35; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $mm/$yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(287,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,5,$buss_name,1,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(30,5,$buss_name,1,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline);

if($BookIn != 't'){
    $buss_name=iconv('UTF-8','windows-874',$IDNO);
}else{
    $buss_name=iconv('UTF-8','windows-874',"[R] ".$IDNO);
}

$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDCarTax);
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(45,5,$buss_name,1,'L',0);

$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,5,$buss_name,1,'L',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TDName);
$pdf->MultiCell(30,5,$buss_name,1,'L',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($CusAmt,2));
$pdf->MultiCell(20,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$O_DATE);
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$O_RECEIPT);
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(25,5,$buss_name,1,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$PayType);
$pdf->MultiCell(20,5,$buss_name,1,'C',0);


$i+=1;
$cline+=5;
$BillNumber = "";   
$in = 0;
$qry_detail=pg_query("select * from carregis.\"DetailCarTax\" where (\"IDCarTax\" = '$IDCarTax') ");
$rows_dt = pg_num_rows($qry_detail);
while($res_detail=pg_fetch_array($qry_detail)){
    $TaxValue = $res_detail["TaxValue"];
    $BillNumber = $res_detail["BillNumber"];
    $TypePay = $res_detail["TypePay"];
    $CoPayDate = $res_detail["CoPayDate"];
    
    if(!empty($TypePay)){
        $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
        if($res_name4=pg_fetch_array($qry_name4)){
            $TName = $res_name4["TName"];
        }
    }

    $s_TaxValue+=$TaxValue;
    $sum_TaxValue+=$TaxValue;

if($i > $lineend){
    $pdf->AddPage(); 
    $cline = 35; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $mm/$yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(287,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,5,$buss_name,1,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(30,5,$buss_name,1,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

}

$in+=1;

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(45,5,$buss_name,1,'C',0);

$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TName);
$pdf->MultiCell(30,5,$buss_name,1,'L',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$BillNumber);
$pdf->MultiCell(20,5,$buss_name,1,'L',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',$CoPayDate);
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($TaxValue,2));
$pdf->MultiCell(20,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);


if($rows_dt==$in){
    $i+=1;
    $cline+=5;

if($i > $lineend){
    $pdf->AddPage(); 
    $cline = 35; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $mm/$yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(287,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDCarTax");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,5,$buss_name,1,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(30,5,$buss_name,1,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(245,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,5,$buss_name,1,'C',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

}    

    $pdf->SetFont('AngsanaNew','B',10);
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมเงิน");
    $pdf->MultiCell(175,5,$buss_name,1,'R',0);
    
    $pdf->SetXY(180,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($s_TaxValue,2));
    $pdf->MultiCell(20,5,$buss_name,1,'R',0);
    
    $pdf->SetXY(200,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(90,5,$buss_name,1,'C',0);
    
    $in=0;
    $s_TaxValue = 0;
}


$cline+=5; 
$i+=1;
}}


    $pdf->SetFont('AngsanaNew','B',10);
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน ทั้งหมด");
    $pdf->MultiCell(175,5,$buss_name,1,'R',0);
    
    $pdf->SetXY(180,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum_TaxValue,2));
    $pdf->MultiCell(20,5,$buss_name,1,'R',0);
    
    $pdf->SetXY(200,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(45,5,$buss_name,1,'C',0);
    
    $pdf->SetXY(245,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum_O_MONEY,2));
    $pdf->MultiCell(25,5,$buss_name,1,'R',0);
    
    $pdf->SetXY(270,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"");
    $pdf->MultiCell(20,5,$buss_name,1,'R',0);

$pdf->Output();
?>