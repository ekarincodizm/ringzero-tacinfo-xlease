<?php
include("../config/config.php");

$mm = $_GET['mm'];
$yy = $_GET['yy'];
$nowyear = date('Y');
$yearlater = 10;

$nowyear2 = date("Y")+543;
$nowdate = date("d-m-")."$nowyear2";

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];
$show_yy = $yy+543;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',13);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->AliasNbPages('tp');
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสรุป รายวันรับเงิน");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(33,32);
$buss_name=iconv('UTF-8','windows-874',"ค่าอื่นๆ");
$pdf->MultiCell(23,4,$buss_name,0,'R',0);

$ln = 56;
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
    $pdf->SetXY($ln,32);
    $buss_name=iconv('UTF-8','windows-874',"ปี ".($i+543));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    $ln += 23;
}

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$cline = 38;
$inub = 0;

$qry_in=pg_query("SELECT * FROM \"Fr\" where EXTRACT(MONTH FROM \"R_Date\")='$mm' AND EXTRACT(YEAR FROM \"R_Date\")='$yy' AND \"Cancel\"='false' 
ORDER BY \"R_Date\",\"R_Receipt\",\"R_DueNo\" ASC ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $R_DueNo = $res_in["R_DueNo"];
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"];    if($j==1) $old_date = $R_Date;
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $cur_year = $res_in["CustYear"];
    $arr_CustYear[] = $res_in["CustYear"];
    
    $vat = 0; //set default VAT.
    $qry_vat=pg_query("select \"VatValue\" from \"FVat\" WHERE (\"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo')");
    if($res_vat=pg_fetch_array($qry_vat)){
        $vat = $res_vat["VatValue"];
    }


    if($R_Date != $old_date){
        
        if($inub > 14){
            $inub = 0;
            $cline = 38;
            $pdf->AddPage();
            
$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสรุป รายวันรับเงิน");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(33,32);
$buss_name=iconv('UTF-8','windows-874',"ค่าอื่นๆ");
$pdf->MultiCell(23,4,$buss_name,0,'R',0);

$ln = 56;
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
    $pdf->SetXY($ln,32);
    $buss_name=iconv('UTF-8','windows-874',"ปี ".($i+543));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    $ln += 23;
}

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
        }
        
        $qry_date_number=@pg_query("select \"c_date_number\"('$old_date')");
        $fm_old_date=@pg_fetch_result($qry_date_number,0);
        
        $inub+=1;
        $pdf->SetXY(10,$cline);
        $buss_name=iconv('UTF-8','windows-874',"$fm_old_date");
        $pdf->MultiCell(23,4,$buss_name,0,'C',0);

        $pdf->SetXY(33,$cline);
        $buss_name=iconv('UTF-8','windows-874',number_format($sumother,2)."\n".number_format($sumothervat,2));
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        
        $ln = 56;
        for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
            $money = number_format(${'sum_'.$i},2);
            $moneyvat = number_format(${'sumvat_'.$i},2);
                $pdf->SetXY($ln,$cline);
                $buss_name=iconv('UTF-8','windows-874',"$money\n$moneyvat");
                $pdf->MultiCell(23,4,$buss_name,0,'R',0);
            $money = 0;
            $moneyvat = 0;
            ${'sum_'.$i} = 0;
            ${'sumvat_'.$i} = 0;
            $ln += 23;
        }
        
        $pdf->SetXY(10,$cline+5);
        $buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(280,4,$buss_name,0,'C',0);
        
        $cline+=10;
        
        $sumother = 0;
        $sumothervat = 0;
    }
    $old_date = $R_Date;
    
    if($R_DueNo > 98){
        $sumother += $R_Money;
        $sumothervat += $vat;
        $allsumother += $R_Money;
        $allsumothervat += $vat;
    }else{
        for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
            if($cur_year == $i){
                ${'sum_'.$i} += $R_Money;
                ${'sumvat_'.$i} += $vat;
                ${'allsum_'.$i} += $R_Money;
                ${'allsumvat_'.$i} += $vat;
            }
        }
    }

}//end while


        if($inub > 14){
            $inub = 0;
            $cline = 38;
            $pdf->AddPage();
            
$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสรุป รายวันรับเงิน");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(33,32);
$buss_name=iconv('UTF-8','windows-874',"ค่าอื่นๆ");
$pdf->MultiCell(23,4,$buss_name,0,'R',0);

$ln = 56;
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
    $pdf->SetXY($ln,32);
    $buss_name=iconv('UTF-8','windows-874',"ปี ".($i+543));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    $ln += 23;
}

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
        }

        $qry_date_number=@pg_query("select \"c_date_number\"('$old_date')");
        $fm_old_date=@pg_fetch_result($qry_date_number,0);

        $inub+=1;
        $pdf->SetXY(10,$cline);
        $buss_name=iconv('UTF-8','windows-874',"$fm_old_date");
        $pdf->MultiCell(23,4,$buss_name,0,'C',0);

        $pdf->SetXY(33,$cline);
        $buss_name=iconv('UTF-8','windows-874',number_format($sumother,2)."\n".number_format($sumothervat,2));
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        
        $ln = 56;
        for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
            $money = number_format(${'sum_'.$i},2);
            $moneyvat = number_format(${'sumvat_'.$i},2);
                $pdf->SetXY($ln,$cline);
                $buss_name=iconv('UTF-8','windows-874',"$money\n$moneyvat");
                $pdf->MultiCell(23,4,$buss_name,0,'R',0);
            $money = 0;
            $moneyvat = 0;
            ${'sum_'.$i} = 0;
            ${'sumvat_'.$i} = 0;
            $ln += 23;
        }
        
        $pdf->SetXY(10,$cline+5);
        $buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(280,4,$buss_name,0,'C',0);

        $cline+=10;

        if($inub > 14){
            $inub = 0;
            $cline = 38;
            $pdf->AddPage();
            
$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสรุป รายวันรับเงิน");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(33,32);
$buss_name=iconv('UTF-8','windows-874',"ค่าอื่นๆ");
$pdf->MultiCell(23,4,$buss_name,0,'R',0);

$ln = 56;
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
    $pdf->SetXY($ln,32);
    $buss_name=iconv('UTF-8','windows-874',"ปี ".($i+543));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    $ln += 23;
}

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
        }

        $pdf->SetXY(10,$cline);
        $buss_name=iconv('UTF-8','windows-874',"Total");
        $pdf->MultiCell(23,4,$buss_name,0,'C',0);

        $pdf->SetXY(33,$cline);
        $buss_name=iconv('UTF-8','windows-874',number_format($allsumother,2)."\n".number_format($allsumothervat,2));
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        
        $ln = 56;
        for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
            $money = number_format(${'allsum_'.$i},2);
            $moneyvat = number_format(${'allsumvat_'.$i},2);
                $pdf->SetXY($ln,$cline);
                $buss_name=iconv('UTF-8','windows-874',"$money\n$moneyvat");
                $pdf->MultiCell(23,4,$buss_name,0,'R',0);
            $money = 0;
            $moneyvat = 0;
            ${'allsum_'.$i} = 0;
            ${'allsumvat_'.$i} = 0;
            $ln += 23;
        }
        
        $pdf->SetXY(10,$cline+5);
        $buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->Output();
?>