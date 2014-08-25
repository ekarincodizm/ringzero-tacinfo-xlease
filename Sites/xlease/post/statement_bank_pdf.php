<?php
include("../config/config.php");
$datepicker = $_GET['date'];
$company = $_SESSION['session_company_code'];
//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header() {
        $this->SetFont('AngsanaNew','',13);
        $this->SetXY(10,17); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(275,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();

$pdf->AddPage();

$page = $pdf->PageNo();

$cline=10;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

// ============================= SCB ================================
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/

$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;


$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'SCB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'SCB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'SCB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row1 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }
	
	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
    
    if($O_memo != $old_memo AND $nub != 1){
        
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/

$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
        
        $pdf->SetXY(11,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
        $pdf->MultiCell(273,4,$buss_name,0,'R',0);
        $sum_sub = 0;
        $cline+=6;
        $nub_line += 1;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub += $O_MONEY;
    $sum_all += $O_MONEY;


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}

$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=6;
$nub_line += 1;

}
if($sum_sub > 0){
    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
// END SCB

if($company == "AVL"){
// ============================= OC - CCA ================================
$nub = 0;
$sum_sub = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"O_BANK\"='CCA')
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"R_Bank\"='CCA')
ORDER BY \"PayType\",\"O_memo\" DESC, \"IDNO\" ASC
");
$num_row2 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
	
	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/

$cline+=6;
$nub_line += 1;

}

if($num_row1 == 0 && $num_row2 == 0){
    
    if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row1 != 0 || $num_row2 != 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย CCA          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;

    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม SCB          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}

}else{

if($num_row1 == 0){
    
    if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row1 != 0){
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม SCB          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
    
}
// END OC - CCA


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;
} else {
    $nub_line += 2;
}

// ============================= TMB ================================
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'TMB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'TMB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'TMB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }

    if($O_memo != $old_memo AND $nub != 1){
        
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
        
        $pdf->SetXY(11,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
        $pdf->MultiCell(273,4,$buss_name,0,'R',0);
        $sum_sub = 0;
        $cline+=6;
        $nub_line += 1;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=6;
$nub_line += 1;

}
if($num_row == 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row != 0){
    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เงินโอน) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม TMB          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
// END TMB

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;
} else {
    $nub_line += 2;
}

// ============================= KTB ================================
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"KTB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'KTB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'KTB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'KTB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
	
	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
    
    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }
    
    if($O_memo != $old_memo AND $nub != 1){
        
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"KTB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}        

        $pdf->SetXY(11,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
        $pdf->MultiCell(273,4,$buss_name,0,'R',0);
        $sum_sub = 0;
        $cline+=6;
        $nub_line += 1;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"KTB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=6;
$nub_line += 1;

}
if($sum_sub > 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"KTB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย $old_memo          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
// END KTB


if($company == "THA"){
// ============================= OC - CCA ================================
$nub = 0;
$sum_sub = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"O_BANK\"='CCA')
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"R_Bank\"='CCA')
ORDER BY \"PayType\",\"O_memo\" DESC, \"IDNO\" ASC
");
$num_row2 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=6;
$nub_line += 1;

}

if($num_row == 0 && $num_row2 == 0){
    
    if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row != 0 || $num_row2 != 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย CCA          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;

    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม KTB          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}

}else{

if($num_row == 0){
    
    if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row != 0){
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"SCB วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม KTB          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
    
}
// END OC - CCA




if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;
} else {
    $nub_line += 2;
}

// ============================= TMB (เช็คธนาคาร) ================================
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'OC' AND \"O_BANK\" = 'CU' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'OC' AND \"R_Bank\" = 'CU' )
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
	
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;


if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}
    
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_DATE");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$full_name");
$pdf->MultiCell(64,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$regis");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$TName");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$PayType");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$O_BANK");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',"$fullnameuseracc");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=6;
$nub_line += 1;

}
if($num_row == 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"- ไม่พบข้อมูล -");
    $pdf->MultiCell(273,4,$buss_name,0,'C',0);
    $cline+=6;
    $nub_line += 1;
}

if($num_row != 0){
    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมย่อย OC CU          ".number_format($sum_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
    
if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"ผลรวม TMB (ตามบัญชี)          ".number_format($sum_all_sub,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}

if($sum_all > 0){

if($nub_line > 20){
    $pdf->AddPage();
    $cline = 10;
    $nub_line = 0;

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$title,0,'C',0);
$cline+=7;

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"Statement Bank ด้านรับ");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(11,$cline); 
$buss_name=iconv('UTF-8','windows-874',"TMB (เช็คธนาคาร) วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);
$cline+=2;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;

$pdf->SetXY(11,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(36,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(61,$cline);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(86,$cline);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(64,4,$buss_name,0,'C',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',"ค่าอะไร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

/*
$pdf->SetXY(255,$cline);
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
*/
$cline+=1;

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);
$cline+=6;
    
}    

    $pdf->SetXY(11,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น          ".number_format($sum_all,2));
    $pdf->MultiCell(273,4,$buss_name,0,'R',0);
    $cline+=6;
    $nub_line += 1;
}
// END TMB (ตามบัญชี)

$pdf->Output();
?>
