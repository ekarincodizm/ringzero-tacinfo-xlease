<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$company = pg_escape_string($_GET['company']);

$thainame = $_SESSION["session_company_thainame"];

$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" WHERE \"InsCompany\"='$company' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $InsFullName = $res_inf["InsFullName"];
}
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
$title=iconv('UTF-8','windows-874',"การจ่ายเงินบริษัทประกัน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $thainame");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประกันภัยภาคบังคับ (พรบ.) - ".$InsFullName);
$pdf->Text(6,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(194,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"InsID");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"StartDate");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"Premium");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"NetPremium");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"CoPayInsAmt");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
                          
$qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE \"Company\"='$company' AND \"Cancel\"='FALSE' AND \"CoPayInsReady\"='FALSE' AND \"CoPayInsID\" is not null ORDER BY \"InsID\" ASC");
while($res_if=pg_fetch_array($qry_if)){
    $j+=1;
        $InsFIDNO = $res_if["InsFIDNO"];
        $IDNO = $res_if["IDNO"];
        $StartDate = $res_if["StartDate"];
        $InsID = $res_if["InsID"];
        $Premium = $res_if["Premium"];
        $CoPayInsAmt = $res_if["CoPayInsAmt"];
        $CoPayInsID = $res_if["CoPayInsID"];
        $NetPremium = $res_if["NetPremium"];
        
        $qry_name=pg_query("select full_name from insure.\"VInsForceDetail\" WHERE \"InsFIDNO\"='$InsFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $fullname = $res_name["full_name"];    
        }
    
    $sum_premium +=  $Premium;
    $sum_netpremium +=  $NetPremium;
    $sum_copay +=  $CoPayInsAmt;

if($i > 45){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"การจ่ายเงินบริษัทประกัน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $thainame");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประกันภัยภาคบังคับ (พรบ.) - ".$InsFullName);
$pdf->Text(6,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(194,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"InsID");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"StartDate");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"Premium");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"NetPremium");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"CoPayInsAmt");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
$pdf->SetXY(5,$cline); 
$s_date=iconv('UTF-8','windows-874',$InsID);
$pdf->MultiCell(25,4,$s_date,0,'C',0);
    
$pdf->SetXY(30,$cline); 
$idno=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(25,4,$idno,0,'C',0);   

$pdf->SetXY(55,$cline); 
$s_name=iconv('UTF-8','windows-874',$fullname);
$pdf->MultiCell(50,4,$s_name,0,'L',0);

$pdf->SetXY(105,$cline); 
$s_asset_name=iconv('UTF-8','windows-874',$StartDate);
$pdf->MultiCell(15,4,$s_asset_name,0,'C',0);

$pdf->SetXY(120,$cline); 
$s_asset_regis=iconv('UTF-8','windows-874',number_format($Premium,2));
$pdf->MultiCell(25,4,$s_asset_regis,0,'R',0);

$pdf->SetXY(145,$cline); 
$s_asset_regis=iconv('UTF-8','windows-874',number_format($NetPremium,2));
$pdf->MultiCell(25,4,$s_asset_regis,0,'R',0);

$pdf->SetXY(170,$cline); 
$s_down=iconv('UTF-8','windows-874',number_format($CoPayInsAmt,2));
$pdf->MultiCell(25,4,$s_down,0,'R',0);

$cline+=5; 
$i+=1;       
} 

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(120,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"____________________   ____________________   ____________________");
$pdf->MultiCell(76,4,$buss_name,0,'L',0);

$pdf->SetXY(85,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ   รวมยอดเงิน");
$pdf->MultiCell(35,4,$buss_name,0,'R',0);

$pdf->SetXY(120,$cline+1); 
$s_down=iconv('UTF-8','windows-874',number_format($sum_premium,2));
$pdf->MultiCell(25,4,$s_down,0,'R',0);

$pdf->SetXY(145,$cline+1);
$s_down=iconv('UTF-8','windows-874',number_format($sum_netpremium,2));
$pdf->MultiCell(25,4,$s_down,0,'R',0);

$pdf->SetXY(170,$cline+1); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sum_copay,2));
$pdf->MultiCell(25,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(120,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"____________________   ____________________   ____________________");
$pdf->MultiCell(76,4,$buss_name,0,'L',0);

$pdf->SetXY(120,$cline+2.5); 
$buss_name=iconv('UTF-8','windows-874',"____________________   ____________________   ____________________");
$pdf->MultiCell(76,4,$buss_name,0,'L',0);


    $qry_rm=pg_query("select \"Remark\" from \"insure\".\"PayToInsure\" WHERE \"PayID\"='$CoPayInsID'");
    if($res_name=pg_fetch_array($qry_rm)){
        $Remark = $res_name["Remark"];    
    }

$pdf->SetXY(5,$cline+5); 
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+5.5); 
$buss_name=iconv('UTF-8','windows-874',"________");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,$cline+9); 
$buss_name=iconv('UTF-8','windows-874',$Remark);
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->Output();
?>