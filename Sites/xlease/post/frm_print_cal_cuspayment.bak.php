<?php
include("../config/config.php");

$d_idno = $_GET['idno'];
$d_date = $_GET['date'];
$f_date = $_GET['f_date'];
$stdate = $_GET['stdate'];
$ldate = $_GET['ldate'];

$nowdate = date("Y/m/d");

$qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$d_idno'");
if( $res_fp=pg_fetch_array($qry_fp) ){
    $name = trim($res_fp["A_FIRNAME"])."".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]);
    $fp_pmonth=$res_fp["P_MONTH"];   
    $fp_pvat=$res_fp["P_VAT"];
        $p_sum = $fp_pmonth+$fp_pvat;
    $fp_ptotal=$res_fp["P_TOTAL"];
}

$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$d_idno'");
if( $res_name=pg_fetch_array($qry_name) ){
    $full_name=$res_name["full_name"];
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header() {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(276,4,$buss_name,0,'R',0);

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
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

$cline = 46;

$qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$d_idno'");
$res_total=pg_fetch_array($qry_total);
$total_top=$res_total["P_TOTAL"];

$qry_before=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$d_idno')  AND (\"DueDate\" BETWEEN '$f_date' AND '$stdate') ");
$n_rowbf=pg_num_rows($qry_before);
$n_bf=$n_rowbf-1;

for($i=0;$i<($n_bf);$i++){
    $resbf=pg_fetch_array($qry_before);
    $aadd = $resbf["CalAmtDelay"];
    $nub_show += 1;
    
    if($nub_show == 1){
        $pm_vat = ($fp_pmonth+$fp_pvat)*$total_top;
        $pm_non_vat = $fp_pmonth*$total_top;
    }
    
    $pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
    $pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

$inub_page += 1;
if($inub_page == 25){
    $pdf->AddPage();
    $cline = 46;
    $inub_page = 1;
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

}

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resbf[DueNo]);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resbf[DueDate]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resbf[R_Date]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$resbf[R_Receipt]);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pmonth,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pvat,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

if($nub_show == 1){
    $pdf->SetXY(135,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
}

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
if($nub_show == 1){
    $pdf->SetXY(160,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
}

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($resbf[Priciple],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resbf[Interest],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resbf[AccuInt],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resbf[WaitIncome],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$line_nub += 1;
if($line_nub==3){
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
$line_nub = 0;
}
                
    
    $ddddd = $resbf["DueDate"];
    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
    $cline += 5;
}


$qry_amt=pg_query("select * ,'$d_date'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$d_idno')  AND (\"DueDate\" BETWEEN '$stdate' AND '$d_date') "); 
while($res_amt=pg_fetch_array($qry_amt)){

$pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
$pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

$inub_page += 1;
if($inub_page == 25){
    $pdf->AddPage();
    $cline = 46;
    $inub_page = 1;
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

}  

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',$res_amt[DueNo]);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_amt[DueDate]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$d_date);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$res_amt[R_Receipt]);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pmonth,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pvat,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

if($nub_show == 1){
    $pdf->SetXY(135,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
}

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
if($nub_show == 1){
    $pdf->SetXY(160,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
}

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[Priciple],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[Interest],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[AccuInt],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_amt[WaitIncome],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$line_nub += 1;
if($line_nub==3){
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
$line_nub = 0;
}

    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
    $ddddd = $res_amt["DueDate"];
    $cline += 5;
}

$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($ddddd)));

$qry_l=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$d_idno')  AND (\"DueDate\" BETWEEN '$DateUpdate' AND '$ldate')  ");

$n_rowl=pg_num_rows($qry_l);
$n_l=$n_rowl;
for($i=0;$i<($n_l);$i++){
    $resl=pg_fetch_array($qry_l);
    
$pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
$pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

$inub_page += 1;
if($inub_page == 25){
    $pdf->AddPage();
    $cline = 46;
    $inub_page = 1;
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ตารางการชำระเงินลูกค้า");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(10,20);
$buss_name=iconv('UTF-8','windows-874',"คำนวณยอด ถึงวันที่ $d_date ");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$d_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($fp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($fp_pvat,2)."     รวม ".number_format($p_sum,2)."     จำนวนงวด ".$fp_ptotal);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,40); 
    $buss_name=iconv('UTF-8','windows-874',"ครบกำหนด");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,35); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,40); 
    $buss_name=iconv('UTF-8','windows-874',"ชำระ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(65,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,35); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างรวม VAT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,35); 
$buss_name=iconv('UTF-8','windows-874',"ยอดค้างไม่รวม VAT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,35); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(215,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,40); 
    $buss_name=iconv('UTF-8','windows-874',"สะสม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);                   

    $pdf->SetXY(265,40); 
    $buss_name=iconv('UTF-8','windows-874',"ค้างรับ");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,41); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);   

}        

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',$resl[DueNo]);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resl[DueDate]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resl[R_Date]);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$resl[R_Receipt]);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pmonth,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($fp_pvat,2));
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

if($nub_show == 1){
    $pdf->SetXY(135,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
}

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_vat_lob,2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
if($nub_show == 1){
    $pdf->SetXY(160,40);
    $buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat,2));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
}

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($pm_non_vat_lob,2));
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($resl[Priciple],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
            
$pdf->SetXY(215,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[Interest],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[AccuInt],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resl[WaitIncome],2));
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$line_nub += 1;
if($line_nub==3){
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
$line_nub = 0;
}

$pm_vat = $pm_vat_lob;
$pm_non_vat = $pm_non_vat_lob;
$cline += 5;
}


$pdf->Output();
?>