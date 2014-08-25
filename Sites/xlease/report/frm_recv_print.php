<?php
include("../config/config.php");

$mm = $_GET['mm'];
$yy = $_GET['yy'];
$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
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
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;



$j = 0;
$arr_othertype = array();
$qry_in=pg_query("SELECT * FROM \"Fr\" where EXTRACT(MONTH FROM \"R_Date\")='$mm' AND EXTRACT(YEAR FROM \"R_Date\")='$yy' AND \"Cancel\"='false' ORDER BY \"R_Date\",\"R_Receipt\",\"R_DueNo\" ASC ");
$num_row = pg_num_rows($qry_in);
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $show_type = "";
    $R_Bank = "";
    $full_name = "";
    $vat = 0;
    $p_sl = 0;
    $R_Money = 0;
    
    $IDNO = $res_in["IDNO"]; 
    $R_DueNo = $res_in["R_DueNo"];
    $R_Receipt = $res_in["R_Receipt"];
    $R_Date = $res_in["R_Date"];
    $R_Money = $res_in["R_Money"];
    $R_Bank = $res_in["R_Bank"];
    $cur_year = $res_in["CustYear"]+543;

    $qry_date_number=@pg_query("select \"c_date_number\"('$R_Date')");
    $R_Date=@pg_fetch_result($qry_date_number,0);
    
    if($j==1) $old_date = $R_Date;
    
    if($R_DueNo == 0){
        $show_type = "เงินดาวน์";
    }elseif($R_DueNo > 98){
        $qry_type=pg_query("select \"TName\" from \"TypePay\" WHERE (\"TypeID\"='$R_DueNo')");
        if($res_type=pg_fetch_array($qry_type)){
            $TName = $res_type["TName"];
        }
        $show_type = "$TName";
    }else{
        $show_type = "ค่างวด";
    }
    
    $qry_in4=pg_query("select \"P_SL\" from \"Fp\" WHERE (\"IDNO\"='$IDNO' AND \"P_TOTAL\"='$R_DueNo')");
    if($res_in4=pg_fetch_array($qry_in4)){
        $p_sl = $res_in4["P_SL"];
        $p_sl = round($p_sl,2);
    }

    $R_Money = round($R_Money,2);
    $R_Money_fm = number_format($R_Money,2);

    $qry_in2=pg_query("select \"full_name\" from \"VContact\" WHERE (\"IDNO\"='$IDNO')");
    if($res_in2=pg_fetch_array($qry_in2)){
        $full_name = $res_in2["full_name"];
    }
    
    $qry_in3=pg_query("select \"VatValue\" from \"FVat\" WHERE (\"IDNO\"='$IDNO' AND \"V_DueNo\"='$R_DueNo' AND \"Cancel\"='FALSE')");
    if($res_in3=pg_fetch_array($qry_in3)){
        $vat = $res_in3["VatValue"];
    }
    
    $vat = round($vat,2);
    $vat_fm = number_format($vat,2);
    
    
    if($R_Date != $old_date){
        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
                
                if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
                
                $pdf->SetXY(5,$cline); 
                $buss_name=iconv('UTF-8','windows-874',"สรุปปี $v : (ธนาคาร ". number_format(${'cu_'.$v},2) ." Vat ". number_format(${'cu_vat'.$v},2) ." | เงินสด ". number_format(${'ca_'.$v},2) ." Vat ". number_format(${'ca_vat'.$v},2) .")");
                $pdf->MultiCell(150,4,$buss_name,0,'L',0);
                
                $cline+=5;
                $i+=1;
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }

        
        $show_unique_othertype = array_unique($arr_othertype);
        sort($show_unique_othertype);
        foreach($show_unique_othertype as $p){
            $qry_type=pg_query("select \"TName\" from \"TypePay\" WHERE (\"TypeID\"='$p')");
            if($res_type=pg_fetch_array($qry_type)){
                $othertype_name = $res_type["TName"];
            }
            
            if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
            
            $pdf->SetXY(5,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"สรุป/รายได้อื่นๆ : $othertype_name : (ธนาคาร ". number_format(${$p.'zcu'},2) ." Vat ". number_format(${$p.'zcu_vat'},2) ." | เงินสด ". number_format(${$p.'zca'},2) ." Vat ". number_format(${$p.'zca_vat'},2) .")");
            $pdf->MultiCell(150,4,$buss_name,0,'L',0);
            
            $cline+=5;
            $i+=1;

            ${$p.'zcu'} = 0;
            ${$p.'zcu_vat'} = 0;
            ${$p.'zca'} = 0;
            ${$p.'zca_vat'} = 0;
        }

        $arr_othertype = array();
        
        
        $sum_day_cu_fm = number_format($sum_day_cu,2);
        $sum_day_cu_vat_fm = number_format($sum_day_cu_vat,2);
        $sum_day_ca_fm = number_format($sum_day_ca,2);
        $sum_day_ca_vat_fm = number_format($sum_day_ca_vat,2);
        
        if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
        
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"สรุปรายวัน $old_date : (ธนาคาร $sum_day_cu_fm Vat $sum_day_cu_vat_fm | เงินสด $sum_day_ca_fm Vat $sum_day_ca_vat_fm)");
        $pdf->MultiCell(150,4,$buss_name,0,'L',0);
        
        $cline+=5;
        $i+=1;

        $sum_day_cu = 0;
        $sum_day_ca = 0;
        $sum_day_cu_vat = 0;
        $sum_day_ca_vat = 0;
    }
    if($R_DueNo < 99){
        $arr_CustYear[] = $res_in["CustYear"]+543;
    }
    $old_date = $R_Date;

    if($R_DueNo > 98){
        $arr_othertype[] = $R_DueNo;
    }
    
    if($R_Bank == "CU" AND $R_DueNo > 98){
        ${$R_DueNo.'zcu'} += $R_Money-$p_sl;
        ${$R_DueNo.'zcu_vat'} += $vat;
    }elseif( ($R_Bank == "CA" OR $R_Bank == "CCA") AND $R_DueNo > 98 ){
        ${$R_DueNo.'zca'} += $R_Money-$p_sl;
        ${$R_DueNo.'zca_vat'} += $vat;
    }elseif($R_Bank == "CU"){
        ${'cu_'.$cur_year} += $R_Money-$p_sl;
        ${'cu_vat'.$cur_year} += $vat;
    }elseif( $R_Bank == "CA" OR $R_Bank == "CCA" ){
        ${'ca_'.$cur_year} += $R_Money-$p_sl;
        ${'ca_vat'.$cur_year} += $vat;
    }


    if( (empty($cur_year) OR $cur_year == "") AND $R_Bank == "CU" ){
        $R_Bank = "ธนาคาร";
    }elseif( (empty($cur_year) OR $cur_year == "") AND ($R_Bank == "CA" OR $R_Bank == "CCA") ){
        $R_Bank = "เงินสด";
    }elseif($R_Bank == "CU" AND $R_DueNo > 98){
        $R_Bank = "ธนาคาร";
        $sum_day_cu += $R_Money-$p_sl;
        $sum_day_cu_vat += $vat;
        $sum_all_day_cu += $R_Money;
        $sum_all_day_cu_vat += $vat;
    }elseif( ($R_Bank == "CA" OR $R_Bank == "CCA") AND $R_DueNo > 98 ){
        $R_Bank = "เงินสด";
        $sum_day_ca += $R_Money-$p_sl;
        $sum_day_ca_vat += $vat;
        $sum_all_day_ca += $R_Money;
        $sum_all_day_ca_vat += $vat;
    }elseif($R_Bank == "CU"){
        $R_Bank = "ธนาคาร";
        $sum_day_cu += $R_Money-$p_sl;
        $sum_day_cu_vat += $vat;
        $sum_all_day_cu += $R_Money;
        $sum_all_day_cu_vat += $vat;
    }elseif($R_Bank == "CA" OR $R_Bank == "CCA"){
        $R_Bank = "เงินสด";
        $sum_day_ca += $R_Money-$p_sl;
        $sum_day_ca_vat += $vat;
        $sum_all_day_ca += $R_Money;
        $sum_all_day_ca_vat += $vat;
    }else{
        $R_Bank = "Error";
    }
        
    $sum_all_money += ($R_Money-$p_sl);
    $sum_all_vat += $vat;
        

if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);    
    
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$R_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(95,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$R_Receipt");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$show_type [$R_DueNo]");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$R_Money_fm");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$vat_fm");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$R_Bank");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$cline+=5; 
$i+=1;

    if(!empty($p_sl) AND $p_sl != 0){
        $p_sl_fm = number_format($p_sl,2);
        
        $pdf->SetFont('AngsanaNew','',10);    
    
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"$R_Date");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"$IDNO");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(45,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"$full_name");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetXY(95,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"$R_Receipt");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(120,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ส่วนลด");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"- $p_sl_fm");
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(160,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"0.00");
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(180,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"$R_Bank");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);
        
        $cline+=5;
        $i+=1;
    }
    
    if($num_row == $j){
        
        $show_unique_CustYear = array_unique($arr_CustYear);
        sort($show_unique_CustYear);
        foreach($show_unique_CustYear as $v){
            if( ${'cu_'.$v} == 0 && ${'cu_vat'.$v} == 0 && ${'ca_'.$v} == 0 && ${'ca_vat'.$v} == 0 ){
                
            }else{
                
                if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
                
                $pdf->SetXY(5,$cline); 
                $buss_name=iconv('UTF-8','windows-874',"สรุปปี $v : (ธนาคาร ". number_format(${'cu_'.$v},2) ." Vat ". number_format(${'cu_vat'.$v},2) ." | เงินสด ". number_format(${'ca_'.$v},2) ." Vat ". number_format(${'ca_vat'.$v},2) .")");
                $pdf->MultiCell(150,4,$buss_name,0,'L',0);
                
                $cline+=5;
                $i+=1;
            }
            
            ${'cu_'.$v} = 0;
            ${'cu_vat'.$v} = 0;
            ${'ca_'.$v} = 0;
            ${'ca_vat'.$v} = 0;
        }
        $arr_CustYear = array();
        
        $sum_day_cu_fm = number_format($sum_day_cu,2);
        $sum_day_cu_vat_fm = number_format($sum_day_cu_vat,2);
        $sum_day_ca_fm = number_format($sum_day_ca,2);
        $sum_day_ca_vat_fm = number_format($sum_day_ca_vat,2);

        if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
        
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"สรุปรายวัน $old_date : (ธนาคาร $sum_day_cu_fm Vat $sum_day_cu_vat_fm | เงินสด $sum_day_ca_fm Vat $sum_day_ca_vat_fm)");
        $pdf->MultiCell(150,4,$buss_name,0,'L',0);
        
        $cline+=5;
        $i+=1;
        
    }

}

    $all_money_fm = number_format($sum_all_money,2);
    $all_vat_fm = number_format($sum_all_vat,2);
    
    $sum_all_day_cu = number_format($sum_all_day_cu,2);
    $sum_all_day_cu_vat = number_format($sum_all_day_cu_vat,2);
    $sum_all_day_ca = number_format($sum_all_day_ca,2);
    $sum_all_day_ca_vat = number_format($sum_all_day_ca_vat,2);
    
    $pdf->SetFont('AngsanaNew','',12);
    
    $pdf->SetXY(4,$cline-4); 
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(196,4,$buss_name,0,'C',0);
    
    if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันรับเงิน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}
    
    $pdf->SetFont('AngsanaNew','',10);
    
    $pdf->SetXY(5,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"สรุปรวม (ธนาคาร $sum_all_day_cu Vat $sum_all_day_cu_vat | เงินสด $sum_all_day_ca Vat $sum_all_day_ca_vat)");
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);
    
    $pdf->SetXY(110,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ");
    $pdf->MultiCell(35,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(140,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"$all_money_fm");
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(160,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"$all_vat_fm");
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
    $pdf->SetFont('AngsanaNew','',12);
    
    $pdf->SetXY(4,$cline+2); 
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>