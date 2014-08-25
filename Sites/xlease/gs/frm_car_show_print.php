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
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"วันนัด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           

    $qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' AND \"TypeDep\" is null ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);  
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $TaxValue = $res_name["TaxValue"];  
            $ApointmentDate = $res_name["ApointmentDate"];
                if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $MeterTax = $res_name["MeterTax"];
                if($MeterTax == 'f'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"]; 
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];   
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"]; 
			$C_StartDate = $res_name2["C_StartDate"];
            $C_StartDate = date("Y-m-d",strtotime($C_StartDate));
			
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }   
        } 

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
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่ม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"วันครบกำหนด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"รูปแบบ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"วันนัด");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

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
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,$cline); 
$buss_name=iconv('UTF-8','windows-874',$C_StartDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',$TaxDueDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(145,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_meter);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(175,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ApointmentDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$cline+=5; 
$i+=1; 
      
}  

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น $rows รายการ");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>