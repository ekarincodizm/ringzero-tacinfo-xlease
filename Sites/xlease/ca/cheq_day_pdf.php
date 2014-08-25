<?php
include("../config/config.php");

$d = pg_escape_string($_GET['d']);
$nowdate = nowDate();//ดึง วันที่จาก server
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
$title=iconv('UTF-8','windows-874',"รายงาน รับเช็คประจำวัน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"ThaiAce Leasing (NV)");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $d");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(105,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"ค่า");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$nub = 0;
$old_id="";
/*================================================*/
$qry_fr=pg_query("select * from \"VDetailCheque\" WHERE \"ReceiptDate\"='$d' AND \"Accept\"='true' ORDER BY \"PostID\" ");
$num=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
	$nub+=1;
	$ChequeNo = $res_fr["ChequeNo"];
    $BankName = $res_fr["BankName"];
    $BankBranch = $res_fr["BankBranch"];
    $IDNO = $res_fr["IDNO"];
    $TypePay = $res_fr["TypePay"];
    $CusAmount = $res_fr["CusAmount"]; $CusAmount = round($CusAmount,2);
    $sum_CusAmount += $CusAmount;
    
    $qry_vc=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $TName = $res_vc["TName"];
    }
    
	if($nub == 46){
		$cline = 37;
		$nub=2;
		$pdf->AddPage();
		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"รายงาน รับเช็คประจำวัน");
		$pdf->MultiCell(190,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(10,16);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
		$pdf->MultiCell(190,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,23);
		$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $d");
		$pdf->MultiCell(80,4,$buss_name,0,'L',0);

		$pdf->SetXY(120,23);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(80,4,$buss_name,0,'R',0);

		$pdf->SetXY(4,24); 
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(196,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,30); 
		$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,30); 
		$buss_name=iconv('UTF-8','windows-874',"Bank");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(75,30); 
		$buss_name=iconv('UTF-8','windows-874',"สาขา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(105,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(135,30); 
		$buss_name=iconv('UTF-8','windows-874',"ค่า");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(165,30); 
		$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(185,30); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(196,4,$buss_name,0,'C',0);
	}
	
	if($nub!=1){
		if($old_id != $ChequeNo){ //กรณีเลขที่เช็คไม่เท่ากันให้แสดงผลรวม
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_s_lum");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(25,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_ChequeNo");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_BankName");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(75,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_BankBranch");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(105,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(135,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($tmp_CusAmount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);

			$pdf->SetXY(185,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($tmp_sum_rows,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);
			
			if($nub == 46){ 
				$cline += 14;
			}else{
				$cline += 5;
			}	
		}else{ //กรณีเท่ากันยังไม่ต้องแสดงผลรวม
			$pdf->SetFont('AngsanaNew','',10);
			$pdf->SetXY(5,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_s_lum");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(25,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_ChequeNo");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_BankName");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(75,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_BankBranch");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(105,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(135,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,$cline); 
			$buss_name=iconv('UTF-8','windows-874',number_format($tmp_CusAmount,2));
			$pdf->MultiCell(20,4,$buss_name,0,'R',0);

			$pdf->SetXY(185,$cline); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);
			
			if($nub == 46){ 
				$cline += 14;
			}else{
				$cline += 5;
			}
		}
	} //end nub!=1
	if($old_id != $ChequeNo){
		$lum += 1;
		$s_lum = $lum;
		$old_id = $ChequeNo;
				
		if($nub == 1){
			$sum_rows += $CusAmount;
		}else{
			$sum_rows = 0;
			$sum_rows += $CusAmount;
		}
	}else{
		$s_lum = "";
		$old_id = $old_id;
		$sum_rows += $CusAmount;
	}
	
    $tmp_s_lum = $s_lum;
    $tmp_ChequeNo = $ChequeNo;
    $tmp_BankName = $BankName;
    $tmp_BankBranch = $BankBranch;
    $tmp_IDNO = $IDNO;
    $tmp_TName = $TName;
    $tmp_CusAmount = $CusAmount;
    $tmp_sum_rows = $sum_rows;
} //end while

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_s_lum");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_ChequeNo");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_BankName");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(75,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_BankBranch");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(105,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(135,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($tmp_CusAmount,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($tmp_sum_rows,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

/*===============================================*/       

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

if($num > 0){
	$cline+=5;
	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $lum รายการ");
	$pdf->MultiCell(50,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(55,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"ผลรวม ".number_format($sum_CusAmount,2));
	$pdf->MultiCell(150,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(5,$cline); 
	$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}
$pdf->Output();
?>