<?php
session_start();
include("../../config/config.php");
$condition = $_GET['condition'];
$datepicker = $_GET['date'];
$nowdate = nowDate();

if($condition=="0"){
	$txtcon="b.\"PostDate\"='$datepicker'";
	$txtdate="ประจำวันที่ทำรายการวันที่";
}else{
	$txtcon="date(a.\"D_DatetimeEnterBank\")='$datepicker'";
	$txtdate="ประจำวันที่โอนเงินเข้าธนาคารวันที่";
}
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
$buss_name=iconv('UTF-8','windows-874',"รายงานรับเงินโอน TAC ค่าวิทยุ");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$txtdate $datepicker");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(95,32);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(110,32);
$buss_name=iconv('UTF-8','windows-874',"TypePay");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"TName");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(165,32);
$buss_name=iconv('UTF-8','windows-874',"เวลารับชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;

$query=pg_query("SELECT a.\"D_BankName\", a.\"D_BankAccount\", a.\"D_DatetimeEnterBank\" FROM \"FTACTran\" a 
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	where $txtcon and a.\"cancel\"='FALSE' group by a.\"D_BankName\",a.\"D_BankAccount\", a.\"D_DatetimeEnterBank\"");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){	
    $D_BankName2 = $resvc['D_BankName'];
    $D_BankAccount2 = $resvc['D_BankAccount'];
    $D_DatetimeEnterBank2 = $resvc['D_DatetimeEnterBank'];
	
	$old_BankName = "xx";
	$old_BankAccount = "yy";
	$old_DatetimeEnterBank = "zz";
	
    $query_VContact=pg_query("select * from \"FTACTran\" a
	left join \"TypePay\" c on a.\"TypePay\"=c.\"TypeID\"
	left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
	where \"D_BankName\"='$D_BankName2' and  \"D_BankAccount\"='$D_BankAccount2' and \"D_DatetimeEnterBank\"='$D_DatetimeEnterBank2' and $txtcon");
    while($res_VContact=pg_fetch_array($query_VContact)){
        $auto_id = $res_VContact['auto_id'];
        $PostID = $res_VContact['PostID'];
        $COID = $res_VContact['COID'];
		$fullname = $res_VContact['fullname'];
		$carregis = $res_VContact['carregis'];
		$TypePay = $res_VContact['TypePay'];
        $TName = $res_VContact['TName'];
		$AmtPay = $res_VContact['AmtPay'];
		$refreceipt = $res_VContact['refreceipt']; if($refreceipt=="") $refreceipt="ไม่พบข้อมูล";
		
		$D_BankName = "";
		$D_BankAccount = "";
		$dateEnterBank = "";
		
		$D_BankName = $res_VContact['D_BankName'];
		$D_BankAccount = $res_VContact['D_BankAccount'];
		$D_DatetimeEnterBank = $res_VContact['D_DatetimeEnterBank'];

		$timeEnterBank=substr($D_DatetimeEnterBank,11,8);
    
    $pdf->SetFont('AngsanaNew','B',13);
	
	//กรณีที่ไม่ใช่ชื่อคนที่ 1 ให้รวมเงิน
    if(($D_BankName != $old_BankName) && ($D_BankAccount != $old_BankAccount) && ($D_DatetimeEnterBank != $old_DatetimeEnterBank) && ($nub != 1 )){ //and $nub < 45
        $pdf->SetXY(100,$cline);
        $buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
        $pdf->MultiCell(100,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(5,$cline+1);
        $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
        
        $sum_amt = 0;
		
		if($nub == 46){ 
			$cline += 14;
			$nub=46;
		}else{
			$cline += 7;
			$nub+=1;	
		}		
    }
    
    //กรณีไม่ใช่ชื่อคนเดียวกัน ให้แสดงชื่อผู้รับเงินคนต่อไป
	if(($D_BankName != $old_BankName) && ($D_BankAccount != $old_BankAccount) && ($D_DatetimeEnterBank != $old_DatetimeEnterBank) and $nub != 46){
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชีธนาคาร:$D_BankAccount ชื่อธนาคาร : $D_BankName  วันเวลาที่โอนเงินเข้า : $D_DatetimeEnterBank");
        $pdf->MultiCell(150,4,$buss_name,0,'L',0);
        
		$nub+=1;
		$cline += 5;

    }
    
    $sum_amt+=$AmtPay;
    $sum_amt_all+=$AmtPay;
    
	//show only new page

    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"รายงานรับเงินโอน TAC ค่าวิทยุ");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"$txtdate $datepicker");
        $pdf->MultiCell(150,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
        $pdf->MultiCell(22,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(45,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(50,4,$buss_name,0,'C',0);

        $pdf->SetXY(95,32);
        $buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(110,32);
        $buss_name=iconv('UTF-8','windows-874',"TypePay");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(125,32);
        $buss_name=iconv('UTF-8','windows-874',"TName");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(165,32);
		$buss_name=iconv('UTF-8','windows-874',"เวลารับชำระ");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
		if(($D_BankName != $old_BankName) && ($D_BankAccount != $old_BankAccount) && ($D_DatetimeEnterBank != $old_DatetimeEnterBank)){
			$pdf->SetFont('AngsanaNew','B',13);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่บัญชีธนาคาร:$D_BankAccount ชื่อธนาคาร : $D_BankName  วันเวลาที่โอนเงินเข้า : $D_DatetimeEnterBank");
			$pdf->MultiCell(150,4,$buss_name,0,'L',0);
			
			$nub+=1;
			$cline += 5;
		}
	}
	
	
//show all record
    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$refreceipt");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$COID");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(45,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullname");
    $pdf->MultiCell(50,4,$buss_name,0,'L',0);

    $pdf->SetXY(95,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$carregis");
    $pdf->MultiCell(15,4,$buss_name,0,'L',0);
    
    $pdf->SetXY(110,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$TypePay");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$TName");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(165,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$timeEnterBank");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(180,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($AmtPay,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
    $cline += 5;
    $nub+=1;
    
    $old_BankName = $D_BankName;
	$old_BankAccount = $D_BankAccount;
	$old_DatetimeEnterBank = $dateEnterBank;
	}
} //end while 

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(100,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$cline += 6;
$nub+=1;

    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"รายงานรับเงินโอน TAC ค่าวิทยุ");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"$txtdate $datepicker");
        $pdf->MultiCell(150,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
        $pdf->MultiCell(22,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,32);
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(45,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(50,4,$buss_name,0,'C',0);

        $pdf->SetXY(95,32);
        $buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(110,32);
        $buss_name=iconv('UTF-8','windows-874',"TypePay");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

        $pdf->SetXY(125,32);
        $buss_name=iconv('UTF-8','windows-874',"TName");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(165,32);
		$buss_name=iconv('UTF-8','windows-874',"เวลารับชำระ");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);
		
		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(100,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินทั้งหมด ".number_format($sum_amt_all,2));
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->Output();
?>