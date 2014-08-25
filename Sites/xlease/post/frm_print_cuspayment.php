<?php
include("../config/config.php");

$idno = $_GET['idno'];
$nowdate = date("Y/m/d");

$qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno'");
if( $res_fp=pg_fetch_array($qry_fp) ){
    $name = trim($res_fp["A_FIRNAME"])."".trim($res_fp["A_NAME"])."  ".trim($res_fp["A_SIRNAME"]);
    $fp_pmonth=$res_fp["P_MONTH"];   
    $fp_pvat=$res_fp["P_VAT"];
        $p_sum = $fp_pmonth+$fp_pvat;
    $fp_ptotal=$res_fp["P_TOTAL"];
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
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
$page = $pdf->PageNo(); 

$cline = 42;

$search_top = $idno;
do{
    $qry_toplv=pg_query("select \"IDNO\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_toplv=pg_fetch_array($qry_toplv); 
        $top_idno[]=$res_toplv["IDNO"];

    $search_top=$res_toplv["IDNO"];
}while(!empty($search_top));

$count_toplv = count($top_idno)-1;
if($count_toplv > 0){

$pdf->AddPage(); 
    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);


    $idno_lob =  $top_idno[$count_toplv-1];
    $qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$idno_lob'");
    if( $res_fp=pg_fetch_array($qry_fp) ){
        $sfp_pmonth=$res_fp["P_MONTH"];   
        $sfp_pvat=$res_fp["P_VAT"];
            $sp_sum = $fp_pmonth+$fp_pvat;
        $sfp_ptotal=$res_fp["P_TOTAL"];
    }

    $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno_lob'");
    if( $res_name=pg_fetch_array($qry_name) ){
        $full_name=$res_name["full_name"];
        $dd_C_REGIS=$res_name["C_REGIS"];
        $dd_C_CARNUM=$res_name["C_CARNUM"];
        $dd_C_COLOR=$res_name["C_COLOR"];
    }


$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno_lob."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($sfp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($sfp_pvat,2)."     รวม ".number_format($sp_sum,2)."     จำนวนงวด ".$sfp_ptotal);
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

    for($i=$count_toplv;$i>0;$i--){
        $sort_idno = $top_idno[$i-1];
        $numberstep +=1;
        
        $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$sort_idno'");
        if( $res_name=pg_fetch_array($qry_name) ){
            $full_name=$res_name["full_name"];
        
            if($count_toplv > 1){
                if($numberstep == 1){
                    $s_name=iconv('UTF-8','windows-874',"");
                }else{
                    $pdf->SetXY(5,$cline);
                    $s_name=iconv('UTF-8','windows-874',"# โอนสิทธิ์ให้    $full_name ($sort_idno) -->");
                    $add_newpage = 1;
                }
            }else{
                $s_name=iconv('UTF-8','windows-874',"");
            }
            $pdf->MultiCell(120,4,$s_name,0,'L',0);
            
            if($add_newpage == 1){
                $pdf->AddPage();
                $cline = 42;
                $inub_page = 0;
                
    $qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$sort_idno'");
    if( $res_fp=pg_fetch_array($qry_fp) ){
        $sfp_pmonth=$res_fp["P_MONTH"];   
        $sfp_pvat=$res_fp["P_VAT"];
            $sp_sum = $fp_pmonth+$fp_pvat;
        $sfp_ptotal=$res_fp["P_TOTAL"];
    }

    $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$sort_idno'");
    if( $res_name=pg_fetch_array($qry_name) ){
        $full_name=$res_name["full_name"];
        $dd_C_REGIS=$res_name["C_REGIS"];
        $dd_C_CARNUM=$res_name["C_CARNUM"];
        $dd_C_COLOR=$res_name["C_COLOR"];
    }
                
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);


$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$sort_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);


$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($sfp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($sfp_pvat,2)."     รวม ".number_format($sp_sum,2)."     จำนวนงวด ".$sfp_ptotal);
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
            
            $qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$sort_idno' ");
            if($re_mny=pg_fetch_array($qry_moneys)){
                $ddbb[] = $re_mny["sum_money_otherpay"];
            }
            
            $qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$sort_idno'");
            $res_total=pg_fetch_array($qry_total); 
            $total_top=$res_total["P_TOTAL"];
            
            //$qry_vcus=pg_query("select * from \"VCusPayment\" WHERE  \"IDNO\"='$sort_idno' order by \"DueDate\" ");
            $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"R_Date\" is not null and \"IDNO\"='$sort_idno' order by \"DueDate\" ");
            while($res_vcus=pg_fetch_array($qry_vcus)) {
                $cline+=5;
                $nub_show += 1;
                if($nub_show == 1){
                    $pm_vat = ($res_vcus[R_Money]+$res_vcus[VatValue])*$total_top;
                    $pm_non_vat = $res_vcus[R_Money]*$total_top;
                }
                
                $pm_vat_lob = $pm_vat-$res_vcus[R_Money]-$res_vcus[VatValue];
                $pm_non_vat_lob = $pm_non_vat-$res_vcus[R_Money];
                
                $pdf->SetXY(5,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueNo]);
                $pdf->MultiCell(10,4,$buss_name,0,'C',0);

                $pdf->SetXY(15,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueDate]);
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(40,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Date]);
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
                $pdf->SetXY(65,$cline);
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Receipt]);
                $pdf->MultiCell(30,4,$buss_name,0,'C',0);

                $pdf->SetXY(95,$cline);
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[R_Money],2));
                $pdf->MultiCell(20,4,$buss_name,0,'C',0);

                $pdf->SetXY(115,$cline);
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[VatValue],2));
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
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Priciple],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
                $pdf->SetXY(215,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Interest],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(240,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[AccuInt],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(265,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[WaitIncome],2));
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
                
                $inub_page += 1;
                if($inub_page == 24){
                    $pdf->AddPage(); 
                    $cline = 42;
                    $inub_page = 0;
                    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$sort_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($sfp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($sfp_pvat,2)."     รวม ".number_format($sp_sum,2)."     จำนวนงวด ".$sfp_ptotal);
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
                
            }
            
            $cline+=5;
            
        }
    }// ปิด for ค้นหาข้อมูลก่อนหน้า
    
$pdf->SetXY(5,$cline);
$s_name=iconv('UTF-8','windows-874',"# โอนสิทธิ์ให้    $name ($idno) -->");
$pdf->MultiCell(120,4,$s_name,0,'L',0);

}// ปิด if top

//----------------------------------- คนที่เลือก
$pdf->AddPage();
$cline = 42;
$inub_page = 0;
$numberstep +=1;

$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno'");
if( $res_name=pg_fetch_array($qry_name) ){
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
}

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$name);
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

$get_numberstep = $numberstep;

$search_under = $idno;
do{
    $qry_toplv=pg_query("select \"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$search_under'");
    $res_toplv=pg_fetch_array($qry_toplv); 
        $under_idno[]=$res_toplv["P_TransferIDNO"];

    $search_under=$res_toplv["P_TransferIDNO"];
}while(!empty($search_under));

            $qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$idno' ");
            if($re_mny=pg_fetch_array($qry_moneys)){
                $ddbb[] = $re_mny["sum_money_otherpay"];
            }
            
            $qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$idno'");
            $res_total=pg_fetch_array($qry_total); 
            $total_top=$res_total["P_TOTAL"];

$nub_show = 0;
$count_under_idno = count($under_idno)-1;

if($count_under_idno == 0){
    $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"IDNO\"='$idno' order by \"DueDate\" ");
}else{
    $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"R_Date\" is not null and \"IDNO\"='$idno' order by \"DueDate\" ");
}
    $num_row9 = pg_num_rows($qry_vcus);
    while($res_vcus=pg_fetch_array($qry_vcus)) {
    $cline+=5;
    $nub_show += 1;
    if($nub_show == 1){
        //$pm_vat = ($res_vcus[R_Money]+$res_vcus[VatValue])*$total_top;
        //$pm_non_vat = $res_vcus[R_Money]*$total_top;
        $pm_vat = ($fp_pmonth+$fp_pvat)*$total_top;
        $pm_non_vat = $fp_pmonth*$total_top;
    }

    //$pm_vat_lob = $pm_vat-$res_vcus[R_Money]-$res_vcus[VatValue];
    //$pm_non_vat_lob = $pm_non_vat-$res_vcus[R_Money];
    
    $pm_vat_lob = $pm_vat-$fp_pmonth-$fp_pvat;
    $pm_non_vat_lob = $pm_non_vat-$fp_pmonth;

    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueNo]);
    $pdf->MultiCell(10,4,$buss_name,0,'C',0);

    $pdf->SetXY(15,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueDate]);
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,$cline); 
    $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Date]);
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
    $pdf->SetXY(65,$cline);
    $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Receipt]);
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
    $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Priciple],2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
    $pdf->SetXY(215,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Interest],2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(240,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[AccuInt],2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(265,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[WaitIncome],2));
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $line_nub2 += 1;
                if($line_nub2==3){
                $pdf->SetXY(4,$cline+1); 
                $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
                $pdf->MultiCell(290,4,$buss_name,0,'C',0);
                    $line_nub2 = 0;
                }

    $pm_vat = $pm_vat_lob;
    $pm_non_vat = $pm_non_vat_lob;
                
    $inub_page += 1;
    if($inub_page == 24 && $num_row9>$inub_page){
        $pdf->AddPage(); 
        $cline = 42;
        $inub_page = 0;
        //$line_nub2 = 0;
                    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$idno."     ชื่อผู้เช่าซื้อ ".$name);
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
                
            }


for($i=0;$i<$count_under_idno;$i++){
    $sort_idno = $under_idno[$i];
    $numberstep +=1;
    $cline+=5;
    
    $qry_fp=pg_query("select A.*,B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$sort_idno'");
    if( $res_fp=pg_fetch_array($qry_fp) ){
        $sfp_pmonth=$res_fp["P_MONTH"];   
        $sfp_pvat=$res_fp["P_VAT"];
            $sp_sum = $fp_pmonth+$fp_pvat;
        $sfp_ptotal=$res_fp["P_TOTAL"];
    }

    $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$sort_idno'");
    if( $res_name=pg_fetch_array($qry_name) ){
        $full_name=$res_name["full_name"];//////////////////////////////////////////////////////////
        $dd_C_REGIS=$res_name["C_REGIS"];
        $dd_C_CARNUM=$res_name["C_CARNUM"];
        $dd_C_COLOR=$res_name["C_COLOR"];
    }
    
    $add_newpage = 0;
    $pdf->SetXY(5,$cline);
    if($count_under_idno > 1){
        if($numberstep == $get_numberstep){
            $s_name=iconv('UTF-8','windows-874',"");
        }else{
            $s_name=iconv('UTF-8','windows-874',"# โอนสิทธิ์ให้    $full_name ($sort_idno) -->");
            $add_newpage = 1;
        }
    }else{
        $s_name=iconv('UTF-8','windows-874',"# โอนสิทธิ์ให้    $full_name ($sort_idno) -->");
        $add_newpage = 1;
    }
    $pdf->MultiCell(120,4,$s_name,0,'L',0);



if($add_newpage == 1){
    $pdf->AddPage();
    $cline = 42;
    $inub_page = 0;
                
                
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$sort_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($sfp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($sfp_pvat,2)."     รวม ".number_format($sp_sum,2)."     จำนวนงวด ".$sfp_ptotal);
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

/*
$pdf->SetXY(5,$cline);
$s_name=iconv('UTF-8','windows-874',"# ผู้เช่าซื้อคนที่ $numberstep    $full_name ($sort_idno)");         
$pdf->MultiCell(120,4,$s_name,0,'L',0);
*/ 
                
$qry_moneys=pg_query("select SUM(\"O_MONEY\") AS \"sum_money_otherpay\" from \"FOtherpay\" WHERE  \"O_Type\"='100' AND \"IDNO\"='$sort_idno' ");
if($re_mny=pg_fetch_array($qry_moneys)){
    $ddbb[] = $re_mny["sum_money_otherpay"];
}
            
$qry_total=pg_query("select \"P_TOTAL\" from \"Fp\" WHERE \"IDNO\"='$sort_idno'");
$res_total=pg_fetch_array($qry_total); 
$total_top=$res_total["P_TOTAL"];

$nub_show = 0;
if($numberstep == ($count_under_idno+$get_numberstep) ){
    $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"IDNO\"='$sort_idno' order by \"DueDate\" ");
}else{     
    $qry_vcus=pg_query("select * from \"VCusPayment\" WHERE \"R_Date\" is not null and \"IDNO\"='$sort_idno' order by \"DueDate\" ");
}
while($res_vcus=pg_fetch_array($qry_vcus)) {
    $cline+=5;
    $nub_show += 1;
    if($nub_show == 1){
        $pm_vat = ($sfp_pmonth+$sfp_pvat)*$total_top;
        $pm_non_vat = $sfp_pmonth*$total_top;
    }
                
                $pm_vat_lob = $pm_vat-$sfp_pmonth-$sfp_pvat;
                $pm_non_vat_lob = $pm_non_vat-$sfp_pmonth;
                
                $pdf->SetXY(5,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueNo]);
                $pdf->MultiCell(10,4,$buss_name,0,'C',0);

                $pdf->SetXY(15,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[DueDate]);
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(40,$cline); 
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Date]);
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
                $pdf->SetXY(65,$cline);
                $buss_name=iconv('UTF-8','windows-874',$res_vcus[R_Receipt]);
                $pdf->MultiCell(30,4,$buss_name,0,'C',0);

                $pdf->SetXY(95,$cline);
                $buss_name=iconv('UTF-8','windows-874',number_format($sfp_pmonth,2));
                $pdf->MultiCell(20,4,$buss_name,0,'C',0);

                $pdf->SetXY(115,$cline);
                $buss_name=iconv('UTF-8','windows-874',number_format($sfp_pvat,2));
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
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Priciple],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);
                
                $pdf->SetXY(215,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[Interest],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(240,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[AccuInt],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $pdf->SetXY(265,$cline); 
                $buss_name=iconv('UTF-8','windows-874',number_format($res_vcus[WaitIncome],2));
                $pdf->MultiCell(25,4,$buss_name,0,'C',0);

                $line_nub3 += 1;
                if($line_nub3==3){
                $pdf->SetXY(4,$cline+1); 
                $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________________________________________________________________________");
                $pdf->MultiCell(290,4,$buss_name,0,'C',0);
                    $line_nub3 = 0;
                }
                
                $pm_vat = $pm_vat_lob;
                $pm_non_vat = $pm_non_vat_lob;
                
                $inub_page += 1;
                if($inub_page == 24){
                    $pdf->AddPage(); 
                    $cline = 42;
                    $inub_page = 0;
                    $line_nub3 = 0;
                    
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการรับรู้รายได้ตามแบบ Sum of The Year Digit");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->Text(260,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา ".$sort_idno."     ชื่อผู้เช่าซื้อ ".$full_name);
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(185,28);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียนรถ $dd_C_REGIS     เลขตัวถัง $dd_C_CARNUM     สีรถ $dd_C_COLOR");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,28);
$buss_name=iconv('UTF-8','windows-874',"ค่างวด ".number_format($sfp_pmonth,2)."     ภาษีมูลค่าเพิ่ม ".number_format($sfp_pvat,2)."     รวม ".number_format($sp_sum,2)."     จำนวนงวด ".$sfp_ptotal);
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
                             
}

}

}// ปิด for

$pdf->Output();

?>