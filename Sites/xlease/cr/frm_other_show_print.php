<?php
include("../config/config.php");

$d = pg_escape_string($_GET['d']);
$nowdate = date("Y/m/d");

$trndate=pg_query("select conversiondatetothaitext('$d')");  
$restrn=pg_fetch_result($trndate,0);

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

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ ค่าอื่นๆ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(65,4,$buss_name,0,'C',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           

        $qry_name=pg_query("select * from carregis.\"CarTaxDue\" 
        where (\"TaxDueDate\" = '$d') AND (\"TypeDep\"!='101' AND \"TypeDep\"!='105' AND \"TypeDep\"!='-1') ORDER BY \"IDNO\" ASC ");
        
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"]; $summary += $CusAmt;
            $TypeDep = $res_name["TypeDep"];
            $TaxValue = $res_name["TaxValue"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
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
        
       $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
        if($res_name4=pg_fetch_array($qry_name4)){
            $TName = $res_name4["TName"];
        }

if($i > 45){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ ค่าอื่นๆ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(65,4,$buss_name,0,'C',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(75,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TaxDueDate);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(105,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($CusAmt,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',9);
$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TName);
$pdf->MultiCell(65,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(185,$cline); 
if( empty($TaxValue) ) $buss_name=iconv('UTF-8','windows-874',"รอชำระเงิน"); else $buss_name=iconv('UTF-8','windows-874',"สำเร็จ");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$cline+=5; 
$i+=1; 
}  

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$nub = $i-1;

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(70,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nub รายการ  รวมยอดเงิน : ".number_format($summary,2));
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>