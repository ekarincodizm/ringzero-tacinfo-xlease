<?php
include("../config/config.php");

$nowdate = Date('Y-m-d');

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

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"Approve Voucher");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลิสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"รายการอนุมัติแล้ว");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"Voucher ID");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียด");
$pdf->MultiCell(55,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินสด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงินในเช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้เบิก");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(190,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$cline = 37;
$i = 0;
$j = 0;
                  
$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"qpprove_id\" is not null ORDER BY \"vc_id\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $sum = 0;
    $vc_id = $res_name["vc_id"];
    $print_date = $res_name["print_date"];
    $vc_detail = $res_name["vc_detail"]; $arr_detail = explode("\n",$vc_detail); $vc_detail = $arr_detail[0];
    $cash_amt = $res_name["cash_amt"];
    $cq_id = $res_name["cq_id"];
    $cq_amt = $res_name["cq_amt"];
    $maker_id = $res_name["maker_id"];
    $cancel = $res_name["cancel"]; if($cancel == "f"){ $show_cancel = "ปกติ"; }else{ $show_cancel = "ยกเลิก"; }
    $sum = $cash_amt+$cq_amt;
    $acb_id = $res_name["acb_id"];
    
    if(substr($acb_id,0,1) != "I"){
        continue;
    }
    
    $i += 1;
if($i > 45){

$pdf->AddPage(); 
$cline = 37;
$i=0; 

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลิสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"Voucher ID");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียด");
$pdf->MultiCell(55,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินสด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงินในเช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้เบิก");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(190,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',13); 

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$vc_id");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$print_date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$vc_detail");
$pdf->MultiCell(55,4,$buss_name,0,'L',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($cash_amt,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$cq_id");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($cq_amt,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',$maker_id);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(190,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_cancel);
$pdf->MultiCell(10,4,$buss_name,0,'L',0);

$cline+=5; 
$i+=1;       
}

$pdf->SetXY(4,$cline-4); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>