<?php
include("../config/config.php");

if(!empty($_GET['m'])) {$m = pg_escape_string($_GET['m']);}
if(!empty($_GET['y'])) {$y = pg_escape_string($_GET['y']);}
$nowdate = date("Y/m/d");

//$trndate=pg_query("select conversiondatetothaitext('$d')");  
//$restrn=pg_fetch_result($trndate,0);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
 
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
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $m/$y");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(289,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874'," ชื่อ/สกุล");
$pdf->MultiCell(40,5,$buss_name,1,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ลูกค้าชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(40,5,$buss_name,1,'C',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าธรรมเนียม");
$pdf->MultiCell(18,5,$buss_name,1,'C',0);

$pdf->SetXY(218,30); 
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(233,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(248,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(18,5,$buss_name,1,'C',0);

$pdf->SetXY(266,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(281,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(13,5,$buss_name,1,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 35;
$i = 1;
$j = 0;                           

        $qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where EXTRACT(MONTH FROM \"ApointmentDate\")='$m' AND EXTRACT(YEAR FROM \"ApointmentDate\")='$y' ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);  
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypeDep = $res_name["TypeDep"];
            $ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
            $MeterTax = $res_name["MeterTax"];
            $TaxValue = $res_name["TaxValue"];
            $ChargeValue = $res_name["ChargeValue"];
            $BillNumber = $res_name["BillNumber"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
                
                $summary = $TaxValue+$ChargeValue;
                
                $sum_TaxValue+=$TaxValue;
                $sum_ChargeValue+=$ChargeValue;
                $sum_CusAmt+=$CusAmt;
                $sum_summary+=$summary;
            
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
        
        
            $O_DATE = "";
            $O_RECEIPT = "";
            $O_MONEY = "";
            $PayType = "";
        $qry_vcus=pg_query("select * from \"FOtherpay\" WHERE  \"RefAnyID\"='$IDCarTax'");
        if($resvc=pg_fetch_array($qry_vcus)){
            $O_DATE = $resvc["O_DATE"];
            $O_RECEIPT = $resvc["O_RECEIPT"];
            $O_MONEY = $resvc["O_MONEY"];
            $PayType = $resvc["PayType"];
            $sum_O_MONEY+=$O_MONEY;
        }
        
        
        if(!empty($TypeDep)){
        
        $TName = "";
        $pieces = explode(",", $TypeDep);
        for($i=0; $i<count($pieces);$i++){
                $get_type = $pieces[$i];
                $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$get_type' ");
                if($res_name4=pg_fetch_array($qry_name4)){
                    if(count($pieces) == $i+1){  
                        $TName .= $res_name4["TName"];
                    }else{
                        $TName .= $res_name4["TName"].",";
                    }
                }
        }
        
        }else{
            if($MeterTax == 't'){
                $TName = "มิเตอร์/ภาษี";
            }else{
                $TName = "มิเตอร์";
            }
        }

if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 35; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระทะเบียนรถ");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $m/$y");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(289,4,$buss_name,0,'R',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874'," ชื่อ/สกุล");
$pdf->MultiCell(40,5,$buss_name,1,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทบริการ");
$pdf->MultiCell(50,5,$buss_name,1,'C',0);

$pdf->SetXY(130,30); 
$buss_name=iconv('UTF-8','windows-874',"ลูกค้าชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(40,5,$buss_name,1,'C',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าธรรมเนียม");
$pdf->MultiCell(18,5,$buss_name,1,'C',0);

$pdf->SetXY(218,30); 
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(233,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(248,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(18,5,$buss_name,1,'C',0);

$pdf->SetXY(266,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(281,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(13,5,$buss_name,1,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(20,5,$buss_name,1,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,5,$buss_name,1,'L',0);

$pdf->SetXY(65,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(15,5,$buss_name,1,'L',0);

$pdf->SetFont('AngsanaNew','',9);
$pdf->SetXY(80,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TName);
$pdf->MultiCell(50,5,$buss_name,1,'L',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($CusAmt,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(145,$cline); 
$buss_name=iconv('UTF-8','windows-874',$BillNumber);
$pdf->MultiCell(40,5,$buss_name,1,'L',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($TaxValue,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($ChargeValue,2));
$pdf->MultiCell(18,5,$buss_name,1,'R',0);

$pdf->SetXY(218,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($summary,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(233,$cline); 
$buss_name=iconv('UTF-8','windows-874',$O_DATE);
$pdf->MultiCell(15,5,$buss_name,1,'C',0);

$pdf->SetXY(248,$cline); 
$buss_name=iconv('UTF-8','windows-874',$O_RECEIPT);
$pdf->MultiCell(18,5,$buss_name,1,'L',0);

$pdf->SetXY(266,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(281,$cline); 
$buss_name=iconv('UTF-8','windows-874',$PayType);
$pdf->MultiCell(13,5,$buss_name,1,'L',0);

$cline+=5; 
$i+=1; 
}  


$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $rows รายการ");
$pdf->MultiCell(75,5,$buss_name,1,'L',0);

$pdf->SetXY(80,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน");
$pdf->MultiCell(50,5,$buss_name,1,'R',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_CusAmt,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(145,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(40,5,$buss_name,1,'R',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_TaxValue,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_ChargeValue,2));
$pdf->MultiCell(18,5,$buss_name,1,'R',0);

$pdf->SetXY(218,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_summary,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(233,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(33,5,$buss_name,1,'C',0);

$pdf->SetXY(266,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_O_MONEY,2));
$pdf->MultiCell(15,5,$buss_name,1,'R',0);

$pdf->SetXY(281,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(13,5,$buss_name,1,'C',0);

$pdf->Output();
?>