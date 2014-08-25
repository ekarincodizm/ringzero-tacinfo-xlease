<?php
session_start();
include("../../config/config.php");
$userkey=$_SESSION["av_iduser"];
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
$buss_name=iconv('UTF-8','windows-874',"รายงานเงินสดประจำวัน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

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
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(55,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

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
$query=pg_query("select * from \"VUserReceiptCash\" WHERE \"PostDate\"='$datepicker' and \"UserIDAccept\" ='$userkey' ORDER BY \"UserIDAccept\",\"refreceipt\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $UserIDAccept = $resvc['UserIDAccept'];
    $refreceipt = $resvc['refreceipt'];
    $IDNO = $resvc['IDNO'];
    $A_NAME = trim($resvc['A_NAME']);
    $A_SIRNAME = trim($resvc['A_SIRNAME']);
    $TypePay = $resvc['TypePay'];
    $TName = $resvc['TName'];
    $AmtPay = $resvc['AmtPay'];
	$PostTime = $resvc['PostTime'];
	if($PostTime ==""){
		$PostTime="-";
	}else{
		$PostTime=substr($PostTime,0,5);
	}
	
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }
    
    $pdf->SetFont('AngsanaNew','B',13);
	
	//กรณีที่ไม่ใช่ชื่อคนที่ 1 ให้รวมเงิน
    if(($UserIDAccept != $old_UserIDAccept) && ($nub != 1 )){ //and $nub < 45
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
	if($UserIDAccept != $old_UserIDAccept and $nub != 46){
        $query1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$UserIDAccept'");
        if($resvc1=pg_fetch_array($query1)){
            $fullname = $resvc1['fullname'];
        }
		
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($UserIDAccept)");
        $pdf->MultiCell(100,4,$buss_name,0,'L',0);
        
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
        $buss_name=iconv('UTF-8','windows-874',"รายงานเงินสดประจำวัน");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

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
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(55,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

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
    
		if($UserIDAccept != $old_UserIDAccept){
			$query1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$UserIDAccept'");
			if($resvc1=pg_fetch_array($query1)){
				$fullname = $resvc1['fullname'];
			}
			
			$pdf->SetFont('AngsanaNew','B',13);
			$pdf->SetXY(5,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน $fullname ($UserIDAccept)");
			$pdf->MultiCell(100,4,$buss_name,0,'L',0);
			
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
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(55,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$A_NAME $A_SIRNAME");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(95,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$regis");
    $pdf->MultiCell(15,4,$buss_name,0,'L',0);
    
    $pdf->SetXY(110,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$TypePay");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$TName");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(165,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$PostTime");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(180,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($AmtPay,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
    $cline += 5;
    $nub+=1;
    
    $old_UserIDAccept = $UserIDAccept;
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
        $buss_name=iconv('UTF-8','windows-874',"รายงานเงินสดประจำวัน");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

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
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(55,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

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