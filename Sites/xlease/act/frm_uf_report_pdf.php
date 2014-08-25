<?php
include("../config/config.php");

$nowdate = date("Y/m/d");

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header() {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,22); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
    }
 
}


$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานค้างค่าประกัน");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22);
$buss_name=iconv('UTF-8','windows-874',"ประกันภัยภาคสมัครใจ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(289,4,$buss_name,0,'C',0);

$pdf->SetXY(5,28); 
$buss_name=iconv('UTF-8','windows-874',"บริษัทประกัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,28); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,28); 
$buss_name=iconv('UTF-8','windows-874',"วันที่คุ้มครอง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(80,28); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ภายใน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,28);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ของกรมธรรม์");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(130,28);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(180,28);
$buss_name=iconv('UTF-8','windows-874',"เลขทะเบียน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(205,28); 
$buss_name=iconv('UTF-8','windows-874',"สีรถ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(235,28); 
$buss_name=iconv('UTF-8','windows-874',"เบี้ยประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(265,28); 
$buss_name=iconv('UTF-8','windows-874',"Outstanding");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(5,29); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(289,4,$buss_name,0,'C',0);

$cline = 34;
$i = 1;
$old_company = "";
$qry_inf=pg_query("select \"Company\", \"IDNO\", \"StartDate\", \"InsUFIDNO\", \"InsID\", \"full_name\", \"C_REGIS\", \"C_COLOR\", \"Premium\", \"outstanding\"  from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' ORDER BY \"Company\",\"StartDate\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $nub+=1;
    $nub_all+=1;
    $Company = $res_inf["Company"];
    $IDNO = $res_inf["IDNO"];
    $StartDate = $res_inf["StartDate"];
    $InsFIDNO = $res_inf["InsUFIDNO"];
    $InsID = $res_inf["InsID"];
    $full_name = $res_inf["full_name"];
    $C_REGIS = $res_inf["C_REGIS"];
    $C_COLOR = $res_inf["C_COLOR"];
    $Premium = $res_inf["Premium"]; $Premium = round($Premium,2);
    $outstanding = $res_inf["outstanding"]; $outstanding = round($outstanding,2);
    
    $sum_premium += $Premium;
    $sum_premium_all += $Premium;
    $sum_outstanding += $outstanding;
    $sum_outstanding_all += $outstanding;
    
    if(($Company != $old_company) && $nub!=1){
        
        //sub total
        $pdf->SetFont('AngsanaNew','B',13);
        
        $pdf->SetXY(65,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nub รายการ ผลรวม ");
        $pdf->MultiCell(170,4,$buss_name,0,'R',0);

        $pdf->SetXY(235,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_premium,2));
        $pdf->MultiCell(30,4,$buss_name,0,'R',0);

        $pdf->SetXY(265,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_outstanding,2));
        $pdf->MultiCell(25,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i+=1;
        
        $sum_premium = 0;
        $sum_outstanding = 0;
        $nub = 0;
        $old_company = $Company;
    }else{
        $old_company = $Company;
        
        if($i > 30){ //new page
            $pdf->AddPage(); 
            $cline = 34; 
            $i=1;
            
            $pdf->SetFont('AngsanaNew','B',18);
            $pdf->SetXY(10,10);
            $title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
            $pdf->MultiCell(280,4,$title,0,'C',0);

            $pdf->SetFont('AngsanaNew','',15);
            $pdf->SetXY(10,16);
            $buss_name=iconv('UTF-8','windows-874',"รายงานค้างค่าประกัน");
            $pdf->MultiCell(280,4,$buss_name,0,'C',0);

            $pdf->SetXY(10,22);
            $buss_name=iconv('UTF-8','windows-874',"ประกันภัยภาคสมัครใจ");
            $pdf->MultiCell(280,4,$buss_name,0,'C',0);

            $pdf->SetXY(5,23); 
            $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
            $pdf->MultiCell(289,4,$buss_name,0,'C',0);

            $pdf->SetXY(5,28); 
            $buss_name=iconv('UTF-8','windows-874',"บริษัทประกัน");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(30,28); 
            $buss_name=iconv('UTF-8','windows-874',"IDNO");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(55,28); 
            $buss_name=iconv('UTF-8','windows-874',"วันที่คุ้มครอง");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(80,28); 
            $buss_name=iconv('UTF-8','windows-874',"เลขที่ภายใน");
            $pdf->MultiCell(20,4,$buss_name,0,'C',0);

            $pdf->SetXY(100,28);
            $buss_name=iconv('UTF-8','windows-874',"เลขที่ของกรมธรรม์");
            $pdf->MultiCell(30,4,$buss_name,0,'C',0);

            $pdf->SetXY(130,28);
            $buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
            $pdf->MultiCell(50,4,$buss_name,0,'C',0);

            $pdf->SetXY(180,28);
            $buss_name=iconv('UTF-8','windows-874',"เลขทะเบียน");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(205,28); 
            $buss_name=iconv('UTF-8','windows-874',"สีรถ");
            $pdf->MultiCell(30,4,$buss_name,0,'C',0);

            $pdf->SetXY(235,28); 
            $buss_name=iconv('UTF-8','windows-874',"เบี้ยประกัน");
            $pdf->MultiCell(30,4,$buss_name,0,'C',0);

            $pdf->SetXY(265,28); 
            $buss_name=iconv('UTF-8','windows-874',"Outstanding");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(5,29); 
            $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
            $pdf->MultiCell(289,4,$buss_name,0,'C',0);
            
        }
        
        $pdf->SetFont('AngsanaNew','',13);
        
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$Company);
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(30,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$IDNO);
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(55,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$StartDate);
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);
        
        $pdf->SetXY(80,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$InsFIDNO);
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(100,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$InsID);
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$full_name);
        $pdf->MultiCell(45,4,$buss_name,0,'L',0);

        $pdf->SetXY(185,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$C_REGIS);
        $pdf->MultiCell(20,4,$buss_name,0,'L',0);

        $pdf->SetXY(205,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$C_COLOR);
        $pdf->MultiCell(30,4,$buss_name,0,'L',0);

        $pdf->SetXY(235,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($Premium,2));
        $pdf->MultiCell(30,4,$buss_name,0,'R',0);

        $pdf->SetXY(265,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($outstanding,2));
        $pdf->MultiCell(25,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i+=1;
        
    }
}

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(289,4,$buss_name,0,'C',0);

$cline+=5;

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(65,$cline); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $nub รายการ ผลรวม ");
$pdf->MultiCell(170,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_premium,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_outstanding,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$cline+=5;

$pdf->SetXY(65,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งหมด $nub_all รายการ ผลรวมทั้งสิ้น ");
$pdf->MultiCell(170,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_premium_all,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_outstanding_all,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->Output();
?>