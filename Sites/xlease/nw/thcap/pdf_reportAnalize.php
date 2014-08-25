<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
include("../function/nameMonth.php");

$m = $_GET['m']; //เดือนที่เลือก
$y = $_GET['y']; //ปีที่เลือก
$tpConType = $_GET['tpConType']; //typeID ที่ต้องการให้แสดง

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$month=nameMonthTH($m);

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
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

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,10,$buss_name,1,'C',0);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(15,6,$buss_name,0,'C',0);
	$pdf->SetXY(5,34);
	$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);

$pdf->SetXY(20,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(90,10,$buss_name,1,'C',0);
$pdf->SetXY(20,30);
$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
$pdf->MultiCell(90,6,$buss_name,0,'C',0);

$pdf->SetXY(110,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);
$pdf->SetXY(110,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);
$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	$pdf->SetXY(140,34);
	$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(170,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);
$pdf->SetXY(170,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	$pdf->SetXY(170,34);
	$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(200,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);
$pdf->SetXY(200,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	$pdf->SetXY(200,34);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(230,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
$pdf->SetXY(230,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	$pdf->SetXY(230,34);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

$pdf->SetXY(260,30);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
$pdf->SetXY(260,30);
$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	$pdf->SetXY(260,34);
	$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก
$datenow=$y."-".$m."-"."01"; //วันที่ที่เลือก
$cline = 40;
$nub = 1;

$qryreceipt=pg_query("select \"tpID\",\"tpDesc\",\"isSubsti\" from account.\"thcap_typePay\" where \"tpConType\"='$tpConType' order by \"tpID\" ");					
$numreceipt=pg_num_rows($qryreceipt);

//ตรวจสอบว่า contype เป็น HP หรือ LEASING หรือไม่ ถ้าใช่จะไม่มีแบ่งค่างวดและดอกเบี้ย
$rescon=pg_query("select \"thcap_get_creditType\"('$tpConType')");
list($contype)=pg_fetch_array($rescon);
						
$i=0;
while($result=pg_fetch_array($qryreceipt)){
/*//กำหนดค่าเริ่มต้นให้กับตัวแปร ทุกครั้งที่วนลูปจะคืนค่าเป็น 0 เพื่อรองรับค่าใหม่////*/
	$sumnetAmt=0; //netAmt
	$sumvatAmt=0; //vatAmt
	$sumwhtAmt=0; //whtAmt
	$sumdebtAmt=0; //debtAmt
													
	$sumdebtAmt_before=0; //ผลรวมก่อนหน้า 1 เดือน
	$sumdebtAmt3=0; //ผลรวมก่อนหน้า 3 เดือน
							
	$a[0]=0;
	$a[1]=0;
	$a[2]=0;
	$a[3]=0;
							
	$aa[3]=0;
	$aaa[3]=0;
	/*////////////*/
							
	$tpID=$result["tpID"];
	$tpDesc=$result["tpDesc"];
	$isSubsti=$result["isSubsti"]; //ถ้าเท่ากับ 1 ไม่ต้องนำมารวม
//ข้อมูลปัจจุบัน************************
	$qryvalue=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y','$m'))");
	$c=0;
	while($resvalue=pg_fetch_array($qryvalue)){
		$a[$c]=$resvalue["unnest"];
		$c++;
	}	
	$sumnetAmt=number_format($a[0],2,'.',''); 
	$sumvatAmt=number_format($a[1],2,'.',''); //vatAmt
	$sumwhtAmt=number_format($a[2],2,'.',''); //whtAmt
	$sumdebtAmt=number_format($a[3],2,'.',''); //debtAmt
//จบข้อมูลปัจจุบัน**********************
							
//ข้อมูล 1 เดือนก่อนหน้า**************************
	//หาเดือนก่อนหน้าที่เลือก 1 เดือน
	$qrymonth=pg_query("SELECT date(date('$datenow') - interval '1 month')");
	list($beformonth)=pg_fetch_array($qrymonth);
	list($y2,$m2,$d2)=explode("-",$beformonth);
	$datebefor=$datenow;
							
	$qryvalue1=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y2','$m2'))");
	$cc=0;
	while($resvalue1=pg_fetch_array($qryvalue1)){
		$aa[$cc]=$resvalue1["unnest"];
		$cc++;
	}	
	$sumdebtAmt_before=number_format($aa[3],2,'.',''); //debtAmt
//จบข้อมูล 1 เดือนก่อนหน้า***************************
						
//ข้อมูล 3 เดือนก่อนหน้า**************************
	$sumdebtThree=0;
	for($h=1;$h<=3;$h++){
		$qrymonth3=pg_query("SELECT date(date('$datebefor') - interval '1 month')");
		list($beformonth3)=pg_fetch_array($qrymonth3);
		list($y3,$m3,$d3)=explode("-",$beformonth3);
																
		$qryvalue3=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y3','$m3'))");
		$ccc=0;
		while($resvalue3=pg_fetch_array($qryvalue3)){
			$aaa[$ccc]=$resvalue3["unnest"];
			$ccc++;
		}	
		$sumdebtAmt3=number_format($aaa[3],2,'.',''); //debtAmt	
		$sumdebtThree=$sumdebtThree+$sumdebtAmt3;
		$datebefor=$y3."-".$m3."-".$d3;
	}		
	//หาเฉลี่ย 3 เดือน
	$sumdebtThree=$sumdebtThree/3;
	$sumdebtThree=number_format($sumdebtThree,2,'.','');
//จบข้อมูล 3เดือนก่อนหน้า***************************
							
//ตรวจสอบว่าเป็นชำระตามสัญญากู้หรือไม่
	$chktype=pg_getminpaytype($tpID);
	$chktypeprinc=pg_getprincipletype($tpID);
	$chktypeint=pg_getinteresttype($tpID);
	
	//ตรวจสอบว่าเป็นเงินค้ำประกันการชำระหนี้หรือไม่
	$chktypesecure=pg_getsecuremoneytype($tpID,2);
							
	//ตรวจสอบว่าเป็นเงินพักรอตัดรายการหรือไม่
	$chktypehold=pg_getholdmoneytype($tpID,2);
							
	if($tpID==$chktypeprinc || $tpID==$chktypeint){
		$sumnetAmt=$sumdebtAmt;
	}	
	
    $pdf->SetFont('AngsanaNew','B',12);
	
	//show only new page
    if($nub == 25){
		$nub = 1;
		$cline = 40;
        $pdf->AddPage();
        
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(285,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
		$pdf->MultiCell(285,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
		$pdf->MultiCell(285,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(285,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(15,10,$buss_name,1,'C',0);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"ประเภท");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);
			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
			$pdf->MultiCell(15,6,$buss_name,0,'C',0);

		$pdf->SetXY(20,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(90,10,$buss_name,1,'C',0);
		$pdf->SetXY(20,30);
		$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
		$pdf->MultiCell(90,6,$buss_name,0,'C',0);

		$pdf->SetXY(110,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);
		$pdf->SetXY(110,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(140,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);
		$pdf->SetXY(140,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
			$pdf->SetXY(140,34);
			$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
			$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(170,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);
		$pdf->SetXY(170,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
			$pdf->SetXY(170,34);
			$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
			$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(200,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);
		$pdf->SetXY(200,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
			$pdf->SetXY(200,34);
			$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
			$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(230,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
		$pdf->SetXY(230,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
			$pdf->SetXY(230,34);
			$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
			$pdf->MultiCell(30,6,$buss_name,0,'C',0);

		$pdf->SetXY(260,30);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
		$pdf->SetXY(260,30);
		$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
			$pdf->SetXY(260,34);
			$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
			$pdf->MultiCell(30,6,$buss_name,0,'C',0);
	}
	
	//show all record
    $pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',$tpID);
	$pdf->MultiCell(15,5,$buss_name,1,'C',0);

	$pdf->SetXY(20,$cline);
	$buss_name=iconv('UTF-8','windows-874',$tpDesc);
	$pdf->MultiCell(90,5,$buss_name,1,'L',0);

	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumnetAmt,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(140,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumvatAmt,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(170,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumwhtAmt,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);

	$pdf->SetXY(200,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumdebtAmt,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);
			
	$pdf->SetXY(230,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumdebtAmt_before,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);
			
	$pdf->SetXY(260,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumdebtThree,2));
	$pdf->MultiCell(30,5,$buss_name,1,'R',0);
 
    $cline += 5;
    $nub+=1;
    if($isSubsti!=1){
		if(($tpID!=$chktype and $tpID!=$chktypesecure and $tpID!=$chktypehold) OR $tpID==$chktype and ($contype=='HIRE_PURCHASE' OR $contype=='LEASING')){
			$sumnet+=$sumnetAmt;
			$sumvat+=$sumvatAmt;
			$sumdebt+=$sumdebtAmt;
			$sumwht+=$sumwhtAmt;
			$sumnet2+=$sumdebtAmt_before;
			$sumbeforetree+=$sumdebtThree;
		}
	}
} //end while 


if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(105,5,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumnet,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(140,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumvat,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(170,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumwht,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$pdf->SetXY(200,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumdebt,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);
			
$pdf->SetXY(230,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumnet2,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);
			
$pdf->SetXY(260,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumbeforetree,2));
$pdf->MultiCell(30,5,$buss_name,1,'R',0);

$cline += 5;
$nub+=1;

if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"* หมายเหตุ  รายการที่ไม่นำมาคำนวณรวม มีดังนี้ ");
$pdf->MultiCell(290,5,$buss_name,0,'L',0);

$cline += 5;
$nub+=1;

if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874'," - ผ่อนชำระตามสัญญากู้/ชำระหนี้ตามตั๋วสัญญา จะไม่ถูกนำมาคำนวณรวมกับยอดรวมเนื่องจากได้แยกเป็น ชำระคืนเงินต้น และดอกเบี้ยไว้แล้ว");
$pdf->MultiCell(290,5,$buss_name,0,'L',0);

$cline += 5;
$nub+=1;

if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874'," - เงินค้ำประกันการชำระหนี้");
$pdf->MultiCell(290,5,$buss_name,0,'L',0);

$cline += 5;
$nub+=1;

if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874'," - เงินพักรอตัดรายการ");
$pdf->MultiCell(290,5,$buss_name,0,'L',0);

$cline += 5;
$nub+=1;

if($nub == 25){
	$nub = 1;
	$cline = 40;
	$pdf->AddPage();
        
	$pdf->SetFont('AngsanaNew','B',18);
	$pdf->SetXY(5,10);
	$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
	$pdf->MultiCell(285,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,16);
	$buss_name=iconv('UTF-8','windows-874',"(THCAP) สรุปรายได้และวิเคราะห์");
	$pdf->MultiCell(285,4,$buss_name,0,'C',0);

	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน$month ค.ศ.$y (ประเภท $tpConType)");
	$pdf->MultiCell(285,4,$buss_name,0,'L',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(5,25);
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(15,10,$buss_name,1,'C',0);
	$pdf->SetXY(5,30);
	$buss_name=iconv('UTF-8','windows-874',"ประเภท");
	$pdf->MultiCell(15,6,$buss_name,0,'C',0);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"รับชำระ");
		$pdf->MultiCell(15,6,$buss_name,0,'C',0);

	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(90,10,$buss_name,1,'C',0);
	$pdf->SetXY(20,30);
	$buss_name=iconv('UTF-8','windows-874',"คำอธิบายประเภทรับชำระ");
	$pdf->MultiCell(90,6,$buss_name,0,'C',0);

	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(110,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระเงิน");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(140,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(140,34);
		$buss_name=iconv('UTF-8','windows-874',"มูลค่าเพิ่ม");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(170,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระภาษี");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(170,34);
		$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);
	$pdf->SetXY(200,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(230,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(230,34);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);

	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(30,10,$buss_name,1,'C',0);	
	$pdf->SetXY(260,30);
	$buss_name=iconv('UTF-8','windows-874',"ยอดรับชำระรวม");
	$pdf->MultiCell(30,6,$buss_name,0,'C',0);
		$pdf->SetXY(260,34);
		$buss_name=iconv('UTF-8','windows-874',"เฉลี่ย 3 เดือนก่อน");
		$pdf->MultiCell(30,6,$buss_name,0,'C',0);
}
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874'," - รายการรับแทนต่าง ๆ");
$pdf->MultiCell(290,5,$buss_name,0,'L',0);

$pdf->Output();
?>