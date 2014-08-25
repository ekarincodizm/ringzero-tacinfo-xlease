<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$payid = pg_escape_string($_GET['payid']);

$qry_if=pg_query("select idcompany from gas.\"PoGas\" WHERE \"payid\"='$payid' ORDER BY \"poid\" ASC LIMIT 1");
if($res=pg_fetch_array($qry_if)){
    $idcompany = $res["idcompany"];
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
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

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระให้บริษัทแก๊ส");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"PayID : ".$payid);
$pdf->Text(6,26,$gmm);

$gmm=iconv('UTF-8','windows-874',"บริษัท : ".$idcompany);
$pdf->Text(33,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"ID");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่ติดตั้ง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30);
$buss_name=iconv('UTF-8','windows-874',"Model");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30);
$buss_name=iconv('UTF-8','windows-874',"ใบกำกับ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,30);
$buss_name=iconv('UTF-8','windows-874',"ราคาทุน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,30);
$buss_name=iconv('UTF-8','windows-874',"Vat");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(260,30);
$buss_name=iconv('UTF-8','windows-874',"ผลรวม");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;           

    $qry_if=pg_query("select * from gas.\"PoGas\" WHERE \"payid\"='$payid' ORDER BY \"poid\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res=pg_fetch_array($qry_if)){
        $j+=1;
        $id = $res["poid"];
        $idno = $res["idno"];
        $date = $res["podate"];
        $date_install = $res["date_install"];
        $idcompany = $res["idcompany"];
        $idmodel = $res["idmodel"];
        $costofgas = $res["costofgas"];
        $vatofcost = $res["vatofcost"];
        $bill = $res["bill"];
        $invoice = $res["invoice"];
        $status_pay = $res["status_pay"];
        
        $costofgas = round($costofgas, 2);
        $vatofcost = round($vatofcost, 2);
        
        $s_costofgas += $costofgas;
        $s_vatofcost += $vatofcost;
        $s_all += ($costofgas+$vatofcost);
        
        $qry_name=pg_query("SELECT modelname FROM gas.\"Model\" WHERE \"modelid\" = '$idmodel' ");
        if($res_name=pg_fetch_array($qry_name)){
            $modelname = $res_name["modelname"];
        }

if($i > 30){
    $pdf->AddPage();
    $cline = 37;
    $i=1;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายการชำระให้บริษัทแก๊ส");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"PayID : ".$payid);
$pdf->Text(6,26,$gmm);

$gmm=iconv('UTF-8','windows-874',"บริษัท : ".$idcompany);
$pdf->Text(33,26,$gmm);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"ID");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่ติดตั้ง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30);
$buss_name=iconv('UTF-8','windows-874',"Model");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30);
$buss_name=iconv('UTF-8','windows-874',"ใบกำกับ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,30);
$buss_name=iconv('UTF-8','windows-874',"ราคาทุน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,30);
$buss_name=iconv('UTF-8','windows-874',"Vat");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(260,30);
$buss_name=iconv('UTF-8','windows-874',"ผลรวม");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',$id);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,$cline);
$buss_name=iconv('UTF-8','windows-874',$idno);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$date);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,$cline);
$buss_name=iconv('UTF-8','windows-874',$date_install);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(105,$cline);
$buss_name=iconv('UTF-8','windows-874',$modelname);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline);
$buss_name=iconv('UTF-8','windows-874',$bill);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',$invoice);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($costofgas,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($vatofcost,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(260,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($costofgas+$vatofcost,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;       
}  


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline-3);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(120,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ   รวมยอดเงิน");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(200,$cline+2); 
$s_down=iconv('UTF-8','windows-874',number_format($s_costofgas,2));
$pdf->MultiCell(30,4,$s_down,0,'R',0);

$pdf->SetXY(230,$cline+2); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($s_vatofcost,2));
$pdf->MultiCell(30,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(260,$cline+2); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($s_all,2));
$pdf->MultiCell(30,4,$s_P_BEGINX,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline+3);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(286,4,$buss_name,0,'C',0);

    $qry_rm=pg_query("select \"Remark\" from gas.\"PayToGas\" WHERE \"payid\"='$payid'");
    if($res_name=pg_fetch_array($qry_rm)){
        $Remark = $res_name["Remark"];    
    }

$pdf->SetXY(5,$cline+8); 
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+8); 
$buss_name=iconv('UTF-8','windows-874',"________");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,$cline+12); 
$buss_name=iconv('UTF-8','windows-874',$Remark);
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->Output();
?>