<?php
include("../../config/config.php");

$select_Search = pg_escape_string($_GET["datel"]);
$select_bid = pg_escape_string($_GET["bankint"]);
$datefrom = pg_escape_string($_GET["datefrom"]);
$dateto = pg_escape_string($_GET["dateto"]);
$datepicker = pg_escape_string($_GET["datepicker"]);
$month = pg_escape_string($_GET["month"]);
$year = pg_escape_string($_GET["year"]);
$id_user=$_SESSION["av_iduser"];

$queryU=pg_query("select \"fullname\" from \"Vfuser\" where id_user = '$id_user'");
$user=pg_fetch_result($queryU,0);

list($yy,$mm) = explode("-",$date);

$nowdate = nowDateTime();

$mm = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $mm[$month];

$show_yy = $yy+543;


$show_month.$show_yy;

$n=0;			

			if($select_bid!="")
			{
				$n++;
				$sumnub++;
				if($select_Search=='1'){  //ตามวันที่ 
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and sbr_receivedate='$datepicker' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
					$dateTitle="ประจำวันที่ ".$datepicker;
				}
				else if($select_Search=='2'){ //ตามเดือน
					//echo "select *,date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and EXTRACT(MONTH FROM \"sbr_receivedate\") = '$month' AND EXTRACT(YEAR FROM \"sbr_receivedate\") = '$year' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ";
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and EXTRACT(MONTH FROM \"sbr_receivedate\") = '$month' AND EXTRACT(YEAR FROM \"sbr_receivedate\") = '$year' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
					$dateTitle="ประจำเดือน ".$show_month." ปี ค.ศ. ".$year;
				}
				else if($select_Search=='3'){ //ตามช่วง
					$query=pg_query("select \"sbr_receivedate\", \"sbr_details\", \"sbr_chqno\", \"sbr_amtwithdraw\", \"sbr_amtdeposit\", \"sbr_amtoutstanding\", \"sbr_counterservice\", \"sbr_bankbranch\", \"revtranferID\",
									date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_channel='$select_bid' and sbr_receivedate between '$datefrom' and '$dateto' order by \"sbr_receivedate\" ,\"sbr_serial\" asc ");
					$dateTitle="ระหว่าง วันที่ ".$datefrom." ถึง วันที่ ".$dateto;
				}
			}

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,10); 
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
$pdf->SetAutoPageBreak(true,0);

$page = $pdf->PageNo();
$header="(THCAP) STATEMENT BANK";
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',$header);
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$dateTitle");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(2,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่รายการมีผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการทำรายการ");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(63,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่หักบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(113,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเข้าบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(146,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(178,30); 
$buss_name=iconv('UTF-8','windows-874',"หมายเลข");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(195,30); 
$buss_name=iconv('UTF-8','windows-874',"สาขาที่ให้บริการ");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);
 
$pdf->SetXY(218,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่สร้างรายการ");
$pdf->MultiCell(22,4,$buss_name,0,'R',0);

$pdf->SetXY(242,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(265,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  
$n=0;
while($resvc=pg_fetch_array($query)){
					$n++;
					$sbr_receivedate = $resvc['sbr_receivedate']; //วันที่รายการมีผล
					$sbr_details = $resvc['sbr_details']; //รายละเอียดการทำรายการ
					$sbr_chqno = $resvc['sbr_chqno']; //เลขที่เช็ค
					$sbr_amtwithdraw = $resvc['sbr_amtwithdraw'];//จำนวนเงินที่หักบัญชี
					$sbr_amtdeposit = $resvc['sbr_amtdeposit']; //จำนวนเงินเข้าบัญชี
					$sbr_amtoutstanding = $resvc['sbr_amtoutstanding']; //ยอดคงเหลือ
					$sbr_counterservice = $resvc['sbr_counterservice']; //หมายเลข
					$sbr_bankbranch=$resvc['sbr_bankbranch']; //สาขาที่ให้บริการ
					$datecreate=$resvc['datecreate']; //วันที่สร้างรายการ
					$revTranID=$resvc['revtranferID']; //รหัสเงินโอน
					$sumwith+=$sbr_amtwithdraw;
					$sumdep+=$sbr_amtdeposit;
					
					// หารหัสเช็ค
					$qry_revChqID = pg_query("select \"revChqID\" from finance.thcap_receive_transfer where \"revTranID\" = '$revTranID' ");
					$revChqID = pg_fetch_result($qry_revChqID,0);

if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',$header);
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ ".$user);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$dateTitle");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(2,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่รายการมีผล");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดการทำรายการ");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(63,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่หักบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(113,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินเข้าบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(146,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดคงเหลือ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(178,30); 
$buss_name=iconv('UTF-8','windows-874',"หมายเลข");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(195,30); 
$buss_name=iconv('UTF-8','windows-874',"สาขาที่ให้บริการ");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);
 
$pdf->SetXY(218,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่สร้างรายการ");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(242,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสเงินโอน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(265,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสเช็ค");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
  

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

}

// -----------

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(2,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['sbr_receivedate']);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['sbr_details']);
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(63,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['sbr_chqno']);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(80,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resvc['sbr_amtwithdraw'],2,'.',','));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(113,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resvc['sbr_amtdeposit'],2,'.',','));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(146,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($resvc['sbr_amtoutstanding'],2,'.',','));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
  
$pdf->SetXY(178,$cline);
$buss_name=iconv('UTF-8','windows-874',$resvc['sbr_counterservice']);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(195,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['sbr_bankbranch']);
$pdf->MultiCell(22,4,$buss_name,0,'C',0);
 
$pdf->SetXY(218,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['datecreate']);
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(242,$cline); 
$buss_name=iconv('UTF-8','windows-874',$resvc['revtranferID']);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(265,$cline); 
$buss_name=iconv('UTF-8','windows-874',$revChqID);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

// -----------

$cline+=5; 
$i+=1;       
}  

$pdf->SetXY(4,$cline-4.0); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(220,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

$pdf->SetXY(66,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(55,4,$buss_name,0,'L',0);

$pdf->SetXY(90,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($sumwith,2));
$pdf->MultiCell(20,4,$s_down,0,'R',0);

$pdf->SetXY(125,$cline+1.5); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sumdep,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(90,$cline+1.9); 
$s_P_BEGINX=iconv('UTF-8','windows-874',"_______________");
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(90,$cline+3.0); 
$s_P_BEGINX=iconv('UTF-8','windows-874',"_______________");
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(125,$cline+1.9); 
$s_P_BEGINX=iconv('UTF-8','windows-874',"_______________");
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(125,$cline+3.0); 
$s_P_BEGINX=iconv('UTF-8','windows-874',"_______________");
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(4,$cline+5.0); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->Output();
?>