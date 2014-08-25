<?php
include("../config/config.php");

$id = pg_escape_string($_GET['id']);
$nowdate = Date('Y-m-d');

$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"vc_id\"='$id' ");
if($res_name=pg_fetch_array($qry_name)){
    $vc_id = $res_name["vc_id"];
    $vc_detail = $res_name["vc_detail"];
    $marker_id = $res_name["marker_id"];
    $approve_id = $res_name["approve_id"];
    $receipt_id = $res_name["receipt_id"];
    $cash_amt = $res_name["cash_amt"];
    $chq_acc_no = $res_name["chq_acc_no"];
    $chque_no = $res_name["chque_no"];
    $do_date = $res_name["do_date"];
    $job_id = $res_name["job_id"];
    $vc_type = $res_name["vc_type"];
    $autoid_abh = $res_name["autoid_abh"];
    $appv_date = $res_name["appv_date"];
    $recp_date = $res_name["recp_date"];
    
    $qry_name2=pg_query("SELECT \"fullname\" FROM \"Vfuser\" WHERE \"id_user\"='$marker_id' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $marker_name = $res_name2["fullname"];
    }
    $qry_name2=pg_query("SELECT \"fullname\" FROM \"Vfuser\" WHERE \"id_user\"='$approve_id' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $approve_name = $res_name2["fullname"];
    }
    
    if( substr($receipt_id,0,2) == "RE" OR substr($receipt_id,0,2) == "VD" ){
        $arr_detail = explode("\n",$vc_detail);
        foreach($arr_detail as $v){
            if(substr($v,0,4)=="REC#"){
                $receipt_name = substr($v,4,strlen($v));
            }
        }
    }else{
        $qry_name2=pg_query("SELECT \"fullname\" FROM \"Vfuser\" WHERE \"id_user\"='$receipt_id' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $receipt_name = $res_name2["fullname"];
        }
    }
    


}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
    }
}

$pdf=new PDF('P' ,'mm','a4half');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 10;

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(50,10,$buss_name,0,'L',0);

$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"ใบสำคัญจ่าย");
$pdf->MultiCell(191,10,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','',13);

$pdf->SetXY(10,$cline+3.5);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,6,$buss_name,0,'L',0);
$cline += 8.5;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สำคัญ : $vc_id");
$pdf->MultiCell(50,6,$buss_name,0,'L',0);

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);
$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่เบิก : $do_date");
$pdf->MultiCell(50,6,$buss_name,0,'L',0);

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"JobID : $job_id");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);

$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียด : ");
$pdf->MultiCell(30,6,$buss_name,0,'L',0);

$pdf->SetXY(40,$cline);
$buss_name=iconv('UTF-8','windows-874',"$vc_detail");
$pdf->MultiCell(160,6,$buss_name,0,'L',0);

$arr_vc_detail = explode("\n",$vc_detail);
$count_vc_detail = count($arr_vc_detail);

if($count_vc_detail < 10)
    $count_vc_detail = 10;

$count_vc_detail = $count_vc_detail*6;
$cline += $count_vc_detail;

if(!empty($cash_amt)){
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เบิกเงินสด : ".number_format($cash_amt,2)." บาท");
    $pdf->MultiCell(191,6,$buss_name,0,'R',0);
    $cline += 6;
}

if(!empty($chq_acc_no)){
    $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
    if($res_chq=pg_fetch_array($qry_chq)){
        $AcID = $res_chq["AcID"];
        $ChqID = $res_chq["ChqID"];
        $DateOnChq = $res_chq["DateOnChq"];
        $Amount = $res_chq["Amount"];
        $TypeOfPay = $res_chq["TypeOfPay"];
        $DoDate = $res_chq["DoDate"];
        $PayTo = $res_chq["PayTo"];
    }
    
    $qry_chq2=pg_query("select * from account.\"ChequeAcc\" WHERE \"AcID\"='$AcID' ");
    if($res_chq2=pg_fetch_array($qry_chq2)){
        $BankName = $res_chq2["BankName"];
    }
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เบิกเช็ค : เลขที่ $ChqID ธนาคาร $BankName วันที่บนเช็ค $DateOnChq ยอดเงิน ".number_format($Amount,2)." บาท");
    $pdf->MultiCell(191,6,$buss_name,0,'R',0);
    $cline += 6;
}

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินที่เบิก : ". number_format($cash_amt+$Amount,2) ." บาท");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',13);

$cline += 1;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,6,$buss_name,0,'L',0);
$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _______________________ ผู้จัดทำ");
$pdf->MultiCell(55,6,$buss_name,0,'L',0);

$pdf->SetXY(75,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _________________________ ผู้อนุมัติ");
$pdf->MultiCell(60,6,$buss_name,0,'C',0);

$pdf->SetXY(147,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _______________________ ผู้รับเงิน");
$pdf->MultiCell(55,6,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"( $marker_name )");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

$pdf->SetXY(75,$cline);
$buss_name=iconv('UTF-8','windows-874',"( $approve_name )");
$pdf->MultiCell(60,6,$buss_name,0,'C',0);

if(!empty($receipt_name)){

$pdf->SetXY(147,$cline);
$buss_name=iconv('UTF-8','windows-874',"( $receipt_name )");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

}

$cline += 5;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"$do_date");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

$pdf->SetXY(75,$cline);
$buss_name=iconv('UTF-8','windows-874',"$appv_date");
$pdf->MultiCell(60,6,$buss_name,0,'C',0);

$pdf->SetXY(147,$cline);
$buss_name=iconv('UTF-8','windows-874',"$recp_date");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

$str_txt = "$approve_id#P";

$up_sql=pg_query("UPDATE account.\"voucher\" SET \"approve_id\"='$str_txt' WHERE \"vc_id\"='$id'");
if(!$up_sql){
    echo "ไม่สามารถบันทึกข้อมูลการพิมพ์ได้";
}

$pdf->Output();
?>