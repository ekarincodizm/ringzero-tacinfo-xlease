<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$gdate = pg_escape_string($_GET['gdate']);

$trndate=pg_query("select conversiondatetothaitext('$gdate')");  
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
$title=iconv('UTF-8','windows-874',"รายงานการแจ้งประกันภัย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประกันภัยภาคบังคับ (พรบ.) - ประจำวันที่ ".$restrn);
$pdf->Text(6,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(194,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"บริษัทประกัน");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(23,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขกรรมธรรม์");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(48,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(73,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(47,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"วันเริ่มคุ้มครอง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าเบิ้ยประกัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;        

$qry_inf=pg_query("select \"InsCompany\" from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $company = $res_inf["InsCompany"];

    $summary = 0;
    $qry_if=pg_query("select \"Company\", \"IDNO\", \"StartDate\", \"InsFIDNO\", \"InsID\", \"Premium\", \"EndDate\" from \"insure\".\"InsureForce\" WHERE \"Company\" = '$company' AND \"DoDate\" = '$gdate' AND \"Cancel\"='FALSE' ORDER BY \"InsID\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $InsFIDNO = $res_if["InsFIDNO"];
        $Company = $res_if["Company"];
        $InsID = $res_if["InsID"];
        $IDNO = $res_if["IDNO"];
        $StartDate = $res_if["StartDate"];
        $EndDate = $res_if["EndDate"];
        $Premium = $res_if["Premium"];
            $summary+=$Premium;
        
        $qry_name=pg_query("select \"full_name\", \"C_REGIS\" from insure.\"VInsForceDetail\" WHERE \"InsFIDNO\"='$InsFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $full_name = $res_name["full_name"];
            $C_REGIS = $res_name["C_REGIS"];
        }

if($i > 45){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานการแจ้งประกันภัย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประกันภัยภาคบังคับ (พรบ.) - ประจำวันที่ ".$restrn);
$pdf->Text(6,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(194,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"บริษัทประกัน");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(23,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขกรรมธรรม์");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(48,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(73,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(47,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"วันเริ่มคุ้มครอง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่าเบิ้ยประกัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}     
    
        $pdf->SetFont('AngsanaNew','',10); 
         
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$Company);
        $pdf->MultiCell(18,4,$buss_name,0,'C',0);

        $pdf->SetXY(23,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$InsID);
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(48,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$IDNO);
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(73,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$full_name);
        $pdf->MultiCell(47,4,$buss_name,0,'L',0);

        $pdf->SetXY(120,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$C_REGIS);
        $pdf->MultiCell(15,4,$buss_name,0,'L',0);

        $pdf->SetXY(135,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$StartDate);
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);
        
        $pdf->SetXY(155,$cline); 
        $buss_name=iconv('UTF-8','windows-874',$EndDate);
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(175,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($Premium,2));
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);
        
        $cline+=5;
        $i+=1;
    }
    
    if($rows > 0){
        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(4,$cline-4); 
        $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(196,4,$buss_name,0,'C',0);
        
        $pdf->SetFont('AngsanaNew','B',10);
        $pdf->SetXY(108.5,$cline+1); 
        $s_down=iconv('UTF-8','windows-874',"ทั้งหมด $rows รายการ   รวมยอดเงิน  ".number_format($summary,2));
        $pdf->MultiCell(84,4,$s_down,0,'R',0);
        
        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(4,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(196,4,$buss_name,0,'C',0);
        
        $cline+=7;
    }

}
                   
$pdf->Output();
?>