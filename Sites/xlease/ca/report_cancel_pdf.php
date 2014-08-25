<?php
include("../config/config.php");

$tday=pg_escape_string($_GET["tday"]);
$type=pg_escape_string($_GET["type"]);
$nowdate = date('Y/m/d');

$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
 
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
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"(ยกเลิกใบเสร็จ) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"ReceiptID");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30);
$buss_name=iconv('UTF-8','windows-874',"Cancel_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30);
$buss_name=iconv('UTF-8','windows-874',"Money");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30);
$buss_name=iconv('UTF-8','windows-874',"PrintDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30);
$buss_name=iconv('UTF-8','windows-874',"RecDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"Ref_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"Admin_Approve");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"ผลการอนุมัติ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(225,30); 
$buss_name=iconv('UTF-8','windows-874',"Memo");
$pdf->MultiCell(65,4,$buss_name,0,'C',0);

/*$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);*/

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
$sum_1 = 0;
$sum_2 = 0;

$qry_fr=pg_query("select \"ref_receipt\",\"IDNO\",\"admin_approve\",\"c_money\",\"statusApprove\",\"c_receipt\",\"c_date\",\"ref_prndate\",\"ref_recdate\"
,\"paytypefrom\",\"c_memo\"
 from \"CancelReceipt\" WHERE (\"c_date\"='$tday') ORDER BY \"c_receipt\" ASC ");
while($res_if=pg_fetch_array($qry_fr)){
    
    $ref_receipt = $res_if["ref_receipt"];
    
    $sub_ref = substr($ref_receipt,2,1);
    
    if($type == 1){
        $chk = $sub_ref == 'R';
    }elseif($type == 2){
        $chk = $sub_ref != 'R';
    }
    
    if($chk){
        
    $SIDNO = $res_if["IDNO"];
    $qry_cc1=pg_query("select \"VatValue\" from \"VAccPayment\" WHERE \"IDNO\"='$SIDNO' LIMIT(1)");
    if($res_cc1=pg_fetch_array($qry_cc1)){
        //$vats = $res_cc1['VatValue'];
    }
    
	// ทำรายการอนุมัติแล้วหรือยัง
    if($res_if["admin_approve"] == 't'){
        $show_app = "อนุมัติแล้ว";
        $sum_1+=$res_if["c_money"]+$vats;
    }else{
        $show_app = "ยังไม่อนุมัติ";
        $sum_2+=$res_if["c_money"]+$vats;
    }
	
	// ผลการอนุมัติ
	if($res_if["statusApprove"] == 't'){
        $status_app = "อนุมัติ";
        $sum_status_app_1+=$res_if["c_money"]+$vat;
	}elseif($res_if["statusApprove"] == 'f'){
        $status_app = "ไม่อนุมัติ";
        $sum_status_app_2+=$res_if["c_money"]+$vat;
    }else{
        $status_app = "";
        $sum_status_app_3+=$res_if["c_money"]+$vat;
    }
    
        $aa+=1;
        $j+=1;
        $c_receipt = $res_if["c_receipt"];
        $IDNO = $res_if["IDNO"];
        $c_date = $res_if["c_date"];
        $c_money = $res_if["c_money"]+$vats;
        $ref_prndate = $res_if["ref_prndate"];
        $ref_recdate = $res_if["ref_recdate"];
        $ref_receipt = $res_if["ref_receipt"];
        $paytypefrom = $res_if["paytypefrom"];
        $c_memo = $res_if["c_memo"];
		
		//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$c_receipt'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$c_receipt'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$c_receipt'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$c_receipt'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$c_receipt'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ
        
if($i > 30){
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"(ยกเลิกใบเสร็จ) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"ReceiptID");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30);
$buss_name=iconv('UTF-8','windows-874',"Cancel_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30);
$buss_name=iconv('UTF-8','windows-874',"Money");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30);
$buss_name=iconv('UTF-8','windows-874',"PrintDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30);
$buss_name=iconv('UTF-8','windows-874',"RecDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"Ref_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,30); 
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"Admin_Approve");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"ผลการอนุมัติ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(225,30); 
$buss_name=iconv('UTF-8','windows-874',"Memo");
$pdf->MultiCell(65,4,$buss_name,0,'C',0);

/*$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้ออกใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);*/

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$aa);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$c_receipt);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(35,$cline);
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,$cline); 
$buss_name=iconv('UTF-8','windows-874',$c_date);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($c_money,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(100,$cline);
$buss_name=iconv('UTF-8','windows-874',$ref_prndate);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ref_recdate);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ref_receipt);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',$paytypefrom);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(175,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_app);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$status_app);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(225,$cline);
$buss_name=iconv('UTF-8','windows-874',$c_memo);
$pdf->MultiCell(65,4,$buss_name,0,'L',0);

/*$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$fullnameuseracc);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);*/

$cline+=5; 
$i+=1;       
}
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',11);

        $sum_1 = number_format($sum_1,2);
        $sum_2 = number_format($sum_2,2);
		
		$sum_status_app_1 = number_format($sum_status_app_1,2);
        $sum_status_app_2 = number_format($sum_status_app_2,2);
		$sum_status_app_3 = number_format($sum_status_app_3,2);

$pdf->SetXY(194,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ      รวมอนุมัติแล้ว $sum_1 | รวมยังไม่อนุมัติ $sum_2");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(194,$cline+8); 
$buss_name=iconv('UTF-8','windows-874',"รวมอนุมัติ $sum_status_app_1 | รวมไม่อนุมัติ $sum_status_app_2 | รวมรออนุมัติ $sum_status_app_3");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->Output();
?>