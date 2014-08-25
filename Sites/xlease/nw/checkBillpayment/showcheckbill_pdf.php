<?php
session_start();
include("../../config/config.php");

$datepicker = $_GET['date'];
$nowdate = nowDate();

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบเงินโอน-Billpayment");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(204,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่โอน");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(41,32);
$buss_name=iconv('UTF-8','windows-874',"เวลาที่โอน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(56,32);
$buss_name=iconv('UTF-8','windows-874',"terminal_id");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(71,32);
$buss_name=iconv('UTF-8','windows-874',"ref_idno");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(86,32);
$buss_name=iconv('UTF-8','windows-874',"ref1");
$pdf->MultiCell(14,4,$buss_name,0,'C',0);

$pdf->SetXY(100,32);
$buss_name=iconv('UTF-8','windows-874',"ref2");
$pdf->MultiCell(14,4,$buss_name,0,'C',0);

$pdf->SetXY(114,32);
$buss_name=iconv('UTF-8','windows-874',"ref_name");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(154,32);
$buss_name=iconv('UTF-8','windows-874',"post_to_idno");
$pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->SetXY(171,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(189,32);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(204,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',12);
$cline = 39;
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"post_on_date\"='$datepicker' ORDER BY \"bank_no\",\"terminal_id\",\"tr_date\",\"tr_time\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $nub2++;
    $bank_no = $resvc['bank_no'];
    $tr_date = $resvc['tr_date'];
    $tr_time = $resvc['tr_time'];
    $terminal_id = $resvc['terminal_id'];
    $ref1 = trim($resvc['ref1']);
    $ref2 = trim($resvc['ref2']);
    $ref_name = $resvc['ref_name'];
    $post_to_idno = $resvc['post_to_idno'];
    $amt = $resvc['amt'];
	$id_tranpay=$resvc['id_tranpay'];
	
	//ref_idno
	if($ref1 == "" and $ref2 ==""){
		$ref_idno="-";
	}else{
		$qry_refidno=pg_query("SELECT * FROM \"Fp\" where \"TranIDRef1\"='$ref1' and  \"TranIDRef2\"='$ref2'");
		$num_refidno=pg_num_rows($qry_refidno);
		if($res_refidno=pg_fetch_array($qry_refidno)){
			$ref_idno=$res_refidno["IDNO"];
		}
	}
    
    if(($old_bank != $bank_no) && $n!=1){
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name รวม $nub รายการ");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);
        
        $cline += 5;
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name ยอดรวม ".number_format($sum_bank,2)." บาท");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);
        
        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
        
        $cline += 6;
        $nub = 0;
        $sum_bank = 0;
    }
    
    $bankname = "";
    $query2=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
    if($resvc2=pg_fetch_array($query2)){
        $bankname = $resvc2['bankname'];
    }
    
    if($nub2 == 46){
        $nub2 = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"รายงานตรวจสอบเงินโอน-Billpayment");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(204,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่โอน");
		$pdf->MultiCell(16,4,$buss_name,0,'C',0);

		$pdf->SetXY(41,32);
		$buss_name=iconv('UTF-8','windows-874',"เวลาที่โอน");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(56,32);
		$buss_name=iconv('UTF-8','windows-874',"terminal_id");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(71,32);
		$buss_name=iconv('UTF-8','windows-874',"ref_idno");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(86,32);
		$buss_name=iconv('UTF-8','windows-874',"ref1");
		$pdf->MultiCell(14,4,$buss_name,0,'C',0);

		$pdf->SetXY(100,32);
		$buss_name=iconv('UTF-8','windows-874',"ref2");
		$pdf->MultiCell(14,4,$buss_name,0,'C',0);

		$pdf->SetXY(114,32);
		$buss_name=iconv('UTF-8','windows-874',"ref_name");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(154,32);
		$buss_name=iconv('UTF-8','windows-874',"post_to_idno");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);

		$pdf->SetXY(171,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(189,32);
		$buss_name=iconv('UTF-8','windows-874',"สถานะ");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(204,4,$buss_name,0,'C',0);
    }

	$qry_check=pg_query("select * from \"TranPay_audit\" where id_tranpay='$id_tranpay'");
	$numrowcheck=pg_num_rows($qry_check);
	if($numrowcheck==0){
		$txtcheck="ยังไม่ตรวจ";
	}else{
		if($rescheck=pg_fetch_array($qry_check)){
			$result=$rescheck["result"];
		}
		if($result==1){
			$txtcheck="ผ่าน";
		}else if($result==9){
			$txtcheck="ผิดปกติ";
		}
	}
	
    $pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$bankname");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tr_date");
    $pdf->MultiCell(16,4,$buss_name,0,'C',0);

    $pdf->SetXY(41,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tr_time");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(56,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$terminal_id");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(71,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref_idno");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(86,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref1");
    $pdf->MultiCell(14,4,$buss_name,0,'C',0);

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref2");
    $pdf->MultiCell(14,4,$buss_name,0,'C',0);

    $pdf->SetXY(114,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref_name");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

	if($ref_idno != $post_to_idno and $ref_idno !="-"){
		$pdf->SetXY(154,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$post_to_idno *");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);
	}else{
		$pdf->SetXY(154,$cline);
		$buss_name=iconv('UTF-8','windows-874',"$post_to_idno");
		$pdf->MultiCell(17,4,$buss_name,0,'C',0);
	}

    $pdf->SetXY(171,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
    $pdf->MultiCell(18,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(189,$cline);
    $buss_name=iconv('UTF-8','windows-874',$txtcheck);
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
    
$cline += 5;
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
$sum_bank += $amt;
$sum_all_bank += $amt;
}

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name รวม $nub รายการ");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name ยอดรวม ".number_format($sum_bank,2)." บาท");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดรวมทั้งหมด ".number_format($sum_all_bank,2)." บาท");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->Output();
?>