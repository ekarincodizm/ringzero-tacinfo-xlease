<?php
include("../config/config.php");

if(!empty($_GET['mm'])) { $mm = pg_escape_string($_GET['mm']);}
if(!empty($_GET['yy'])) { $yy = pg_escape_string($_GET['yy']);}
$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF{
    function Header(){
        $this->SetFont('AngsanaNew','',15);
        $this->SetXY(10,5); 
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
$title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,22.5); 
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(20,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(75,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(4,31); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$cline = 37;
/*==============*/
$qry_date = $yy."-".$mm."-01";

$in = 0;
$qry_if=pg_query("SELECT * FROM carregis.\"VAllCar\" ORDER BY \"C_REGIS\" ASC");
while($res_if=pg_fetch_array($qry_if)){
    $C_REGIS = $res_if["C_REGIS"];
    $IDNO = $res_if["IDNO"];
    $C_YEAR = $res_if["C_YEAR"];
    $C_StartDate = $res_if["C_StartDate"];
    $C_COLOR = $res_if["C_COLOR"];
    
    
    $due_date = "";
    $due_amount = 0;
    
    if(!empty($C_StartDate)){
        $CreateThisMonth = pg_query("select carregis.\"CreateThisMonth\"('$qry_date','$C_StartDate')");
        $res_CreateThisMonth = pg_fetch_result($CreateThisMonth,0);
        
        if($res_CreateThisMonth == 1){
            $str_type = "ค่าภาษีประจำปี";
            $str_type_code = "101";
        }elseif($res_CreateThisMonth == 2){
            $str_type = "ตรวจมิเตอร์";
            $str_type_code = "105";
            $due_amount = 300;
        }elseif($res_CreateThisMonth == 0){
            continue;
        }
        
        list($a_styear,$a_stmonth,$a_stday) = split('-',$C_StartDate);
        $due_date = $yy."-".$mm."-".$a_stday;
        
        if( checkdate($mm,$a_stday,$yy) ){
            $due_date = $due_date;
        }else{
            $lastDate = idate('d', mktime(0, 0, 0, ($mm + 1), 0, $yy));
            $due_date = $yy."-".$mm."-".$lastDate;
        }
    }else{
        $str_type = "ไม่พบวันที่เริ่ม";
        $str_type_code = "";
    }

    $full_name = "";
    $asset_id = "";
    $qry_name1=pg_query("SELECT full_name,asset_id FROM \"UNContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_name1=pg_fetch_array($qry_name1)){
        $full_name = $res_name1["full_name"];
        $asset_id = $res_name1["asset_id"];
    }
    
    if($res_CreateThisMonth == 1){
        $qry_ccartax=pg_query("SELECT \"C_TAX_MON\" FROM \"Fc\" WHERE \"CarID\"='$asset_id' ");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $due_amount = $res_ccartax["C_TAX_MON"];
        }
    }
    
    $count_CarTaxDue = 0;
        
    if(!empty($IDNO) AND !empty($C_StartDate) ){
        $qry_ccartax=pg_query("select COUNT(\"IDCarTax\") AS \"C_IDCarTax\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$due_date'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $count_CarTaxDue = $res_ccartax["C_IDCarTax"];
            
            if($count_CarTaxDue != 0){
                continue; //skip already item
            }
        }
    }
    
    $in++;

if($nub > 39){
    $pdf->AddPage();
    $nub=0;
    $cline = 37;
    
    $pdf->SetFont('AngsanaNew','B',15);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี");
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(5,22.5); 
    $buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
    $pdf->MultiCell(100,4,$buss_name,0,'L',0);

    $pdf->SetXY(159,22.5); 
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
    $pdf->MultiCell(40,4,$buss_name,0,'R',0);

    $pdf->SetXY(4,24); 
    $buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
    $pdf->MultiCell(196,4,$buss_name,0,'C',0);

    $pdf->SetXY(5,30); 
    $buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
    $pdf->MultiCell(15,4,$buss_name,0,'L',0);

    $pdf->SetXY(20,30); 
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
    $pdf->MultiCell(25,4,$buss_name,0,'L',0);

    $pdf->SetXY(45,30); 
    $buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
    $pdf->MultiCell(75,4,$buss_name,0,'L',0);

    $pdf->SetXY(120,30); 
    $buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(140,30); 
    $buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
    $pdf->MultiCell(35,4,$buss_name,0,'L',0);

    $pdf->SetXY(175,30); 
    $buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
    $pdf->MultiCell(25,4,$buss_name,0,'L',0);

    $pdf->SetXY(4,31); 
    $buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
    $pdf->MultiCell(196,4,$buss_name,0,'C',0);
    
}

            $pdf->SetFont('AngsanaNew','',15);
            $pdf->SetXY(5,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"$C_REGIS");
            $pdf->MultiCell(15,4,$buss_name,0,'L',0);

            $pdf->SetXY(20,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"$IDNO");
            $pdf->MultiCell(25,4,$buss_name,0,'L',0);

            $pdf->SetXY(45,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"$full_name");
            $pdf->MultiCell(75,4,$buss_name,0,'L',0);

            $pdf->SetXY(120,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"$C_StartDate");
            $pdf->MultiCell(20,4,$buss_name,0,'L',0);

            $pdf->SetXY(140,$cline); 
            $buss_name=iconv('UTF-8','windows-874',"$due_date");
            $pdf->MultiCell(35,4,$buss_name,0,'L',0);

            $pdf->SetXY(175,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$str_type");
            $pdf->MultiCell(25,4,$buss_name,0,'L',0);
            
            $nub++;
            $cline+=6;
}
/*==============*/


$pdf->SetFont('AngsanaNew','',15);

$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น $in รายการ");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(4,$cline+4); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);


$pdf->Output();
?>