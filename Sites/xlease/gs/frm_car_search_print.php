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
$title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           

    $qry_if=pg_query("SELECT * FROM \"VCarregistemp\" WHERE \"C_StartDate\" is not null ORDER BY \"C_REGIS\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $g_year = date('Y');
		$IDNO2=$res_if["IDNO"];
        $CarID = $res_if["CarID"];
        $C_YEAR = $res_if["C_YEAR"];
            $plusyear = $g_year - $C_YEAR;
            if($plusyear < 7) $numplus = 6; else $numplus = 4; 
        $C_StartDate = $res_if["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate));
            
        $qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO2'");
        if($res_name=pg_fetch_array($qry_name)){
            $IDNO = $res_name["IDNO"];
            $CusID = $res_name["CusID"];
            $full_name = $res_name["full_name"];
            $asset_type = $res_name["asset_type"];   
            $C_REGIS = $res_name["C_REGIS"];
            $car_regis = $res_name["car_regis"]; 
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }   
        }
        $TaxDueDate = "";
        $MeterTax = "";
        $qry_name=pg_query("select * from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' ORDER BY \"IDCarTax\" ASC ");
        while($res_name=pg_fetch_array($qry_name)){
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $MeterTax = $res_name["MeterTax"];  
        }
        
        if(empty($MeterTax)){
            $show_meter = "มิเตอร์";
        }else{
            if($MeterTax == 't'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
        }
        
        if(empty($TaxDueDate)){
            $g_month = date("m",strtotime($C_StartDate));
            $g_day = date("d",strtotime($C_StartDate));
            $g_year_ms = date("Y",strtotime($C_StartDate));
            $g_year_lob =date("Y", strtotime("-1 year",strtotime($g_year)));
            if($numplus == 6){
                if($g_year_lob == $g_year_ms){
                    if($g_month >= 7){
                        $get_date = $g_year_ms."-".$g_month."-".$g_day;
                    }else{
                        $get_date = $g_year."-".$g_month."-".$g_day;
                    }
                }else{
                    $get_date = $g_year."-".$g_month."-".$g_day;
                }
            }elseif($numplus == 4){
                if($g_year_lob == $g_year_ms){
                    if($g_month >= 9){
                        $get_date = $g_year_ms."-".$g_month."-".$g_day;
                    }else{
                        $get_date = $g_year."-".$g_month."-".$g_day;
                    }
                }else{
                    $get_date = $g_year."-".$g_month."-".$g_day;
                }
            }
            
            //$get_date = $g_year."-".$g_month."-".$g_day;
            $dateselect = $get_date;
        }else{
            $dateselect = $TaxDueDate;
        }    
        
        $qry_close=pg_query("select \"P_ACCLOSE\" from \"Fp\" WHERE \"IDNO\"='$IDNO' AND \"CusID\"='$CusID'");
        if($res_close=pg_fetch_array($qry_close)){
            $P_ACCLOSE = $res_close["P_ACCLOSE"];
        }else{
            $P_ACCLOSE = "null";
        }
 
        $date_check =date("Y-m-d", strtotime("+$numplus month",strtotime($dateselect)));
        $strYear = date("Y",strtotime($date_check));
        $strMonth = date("m",strtotime($date_check));
            
        if($strYear == $yy AND $strMonth == $mm) $active = 1;
        else $active = 0;   
        
        if($active == 1){
            $nubs+=1;

if($i > 45){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);  

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);
 
$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(105,$cline);
$buss_name=iconv('UTF-8','windows-874',$C_StartDate);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(135,$cline); 
if($P_ACCLOSE == 't'){
    $buss_name=iconv('UTF-8','windows-874',$date_check." [ปิดแล้ว]");
}elseif($P_ACCLOSE == 'null'){
    $buss_name=iconv('UTF-8','windows-874',$date_check." [ไม่พบข้อมูล]");
}else{}
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_meter);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$cline+=5; 
$i+=1; 

        }
      
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น $nubs รายการ");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>