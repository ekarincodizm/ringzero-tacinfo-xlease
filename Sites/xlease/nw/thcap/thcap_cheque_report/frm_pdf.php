<?php
include("../../../config/config.php");
include("../../../core/core_functions.php");
include("../../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$strings = $_GET["condition"];
$option = $_GET["option"];
$datecon = $_GET["datecon"];
$opstatus = $_GET["opstatus"];
if($datecon == ""){
	$datecon = nowDate();
}
if($strings=="bankChqDate"){
	$txtcon="แสดงตามวันที่บนเช็ค";
}else{
	$txtcon="แสดงตามวันที่นำเช็คเข้าธนาคาร";
}
if($option == 'day'){
	$condition = " date(a.\"$strings\") = '$datecon' ";
	list($year1,$month1,$day1) = explode("-",$datecon);
	$year1 = $year1+543;
	$monthth = nameMonthTH($month1);
	$txtshow = "$txtcon ของวันที่ ".$day1." ".$monthth." ".$year1;
}else if($option == 'year'){
	$yy = $_GET["yy"];
	$yyth = $yy+543;
	$condition = " EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
	$txtshow = "$txtcon ของปี ".$yyth;
}else{
	$yy = $_GET["yy"];
	$mm = $_GET["mm"];
	$monthth = nameMonthTH($mm);
	$yyth = $yy+543;
	$condition = " EXTRACT(MONTH FROM a.\"$strings\") = '$mm' AND EXTRACT(YEAR FROM a.\"$strings\") = '$yy' ";
	$txtshow = "$txtcon ของเดือน ".$monthth." ปี ".$yyth;
}
if($opstatus != ""){
	$conditionstatus = "AND \"namestatus\" = '$opstatus'";
	$txtstatus = 'แสดงเฉพาะ : '.$opstatus;	
}


// ------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
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

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP)รายงานเช็ค");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',$txtshow);
$pdf->Text(5,26,$gmm);

$gmm=iconv('UTF-8','windows-874',$txtstatus);
$pdf->Text(65,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ลูกค้า");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(77,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(93,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่บนเช็ค");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"ธนาคารที่ออกเช็ค");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(150,30); 
$buss_name=iconv('UTF-8','windows-874',"จ่ายบริษัท");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
$pdf->SetXY(168,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเช็ค");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(182,30); 
$buss_name=iconv('UTF-8','windows-874',"ผู้นำเช็คเข้าธนาคาร");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(210,30); 
$buss_name=iconv('UTF-8','windows-874',"ธนาคารที่นำเข้า");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10); 
$pdf->SetXY(234,28); 
$buss_name=iconv('UTF-8','windows-874',"วันนำเช็ค\nเข้าธนาคาร");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

 
$pdf->SetXY(250,28); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เงินเข้า\nธนาคาร");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(272	,30); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  
                        
$qry_in=pg_query("SELECT a.*,b.* FROM \"finance\".\"V_thcap_receive_cheque_chqManage\" a left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
					 where $condition $conditionstatus");
				

		
$bankChqAmtall = 0;				
while($re_selcol=pg_fetch_array($qry_in)){
	
									$revChqToCCID = $re_selcol["revChqToCCID"];
									$chqKeeperID = $re_selcol["chqKeeperID"];
									$revChqID = $re_selcol["revChqID"];
									$bankChqNo=$re_selcol["bankChqNo"];
									$revChqDate = $re_selcol["revChqDate"]; 
									$bankName = $re_selcol["bankName"]; 
									$bankOutBranch = $re_selcol["bankOutBranch"]; 
									$bankChqToCompID = $re_selcol["bankChqToCompID"]; 
									$bankChqAmt = $re_selcol["bankChqAmt"]; 
									$revChqStatus=$re_selcol["revChqStatus"];
									$bankChqDate=$re_selcol["bankChqDate"];
									//$giveTakerToBankAcc=$re_selcol["giveTakerToBankAcc"];
									$giveTakerID=$re_selcol["giveTakerID"];
									$bankRevResult=$re_selcol["bankRevResult"];
									$chqstampdate=$re_selcol["giveTakerDate"];
									$status=$re_selcol["namestatus"];
									$BID=$re_selcol["BID"];
									
									//ตรวจสอบว่ารออนุมัติคืนลูกค้าอยู่หรือไม่
									// $qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
									// $numchkapp=pg_num_rows($qrychkapp);
									// if($numchkapp>0){
										// $status="อยู่ระหว่างรอขอคืนลูกค้า";
									// }
									
									//หาชื่อลูกค้า
									$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
									list($cusid,$fullname) = pg_fetch_array($qry_cusname);									
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($userfullname) = pg_fetch_array($qry_username);
									
									
									//หาชื่อธนาคาร
									if($BID!=""){
										$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
										list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
									}
									
									//หาวันที่เงินเข้าธนาคาร โดยวันที่นำมาจากตาราง finance.thcap_receive_transfer column "bankRevStamp"
									$qrydate=pg_query("SELECT date(\"bankRevStamp\") FROM finance.thcap_receive_transfer WHERE \"revChqID\"='$revChqID' AND \"revTranStatus\" in ('1','6')");
									list($bankRevStamp)=pg_fetch_array($qrydate);
									if($bankRevStamp==""){
										$bankRevStamp="-";
									}
									
									if($userfullname == ""){ $userfullname = '-'; }
									if($ourbankname == ""){ $ourbankname = '-'; }
									if($BAccount == ""){ $BAccount = '-'; }
									if($chqstampdate == ""){ $chqstampdate = '-'; }	

		if($i > 30){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานเช็ค");
			$pdf->MultiCell(280,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(280,4,$buss_name,0,'C',0);

			$gmm=iconv('UTF-8','windows-874',$txtshow);
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(35,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ลูกค้า");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(77,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
			$pdf->MultiCell(25,4,$buss_name,0,'L',0);

			$pdf->SetXY(93,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่บนเช็ค");
			$pdf->MultiCell(40,4,$buss_name,0,'L',0);

			$pdf->SetXY(120,30); 
			$buss_name=iconv('UTF-8','windows-874',"ธนาคารที่ออกเช็ค");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);

			$pdf->SetXY(150,30); 
			$buss_name=iconv('UTF-8','windows-874',"จ่ายบริษัท");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			  
			$pdf->SetXY(168,30); 
			$buss_name=iconv('UTF-8','windows-874',"ยอดเช็ค");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);

			$pdf->SetXY(182,30); 
			$buss_name=iconv('UTF-8','windows-874',"ผู้นำเช็คเข้าธนาคาร");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);

			$pdf->SetXY(210,30); 
			$buss_name=iconv('UTF-8','windows-874',"ธนาคารที่นำเข้า");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',10); 
			$pdf->SetXY(234,28); 
			$buss_name=iconv('UTF-8','windows-874',"วันนำเช็ค\nเข้าธนาคาร");
			$pdf->MultiCell(15,4,$buss_name,0,'L',0);

			 
			$pdf->SetXY(250,28); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เงินเข้า\nธนาคาร");
			$pdf->MultiCell(25,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(272	,30); 
			$buss_name=iconv('UTF-8','windows-874',"สถานะ");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);


			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1; 

	}

// -----------

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(3,$cline); 
$buss_name=iconv('UTF-8','windows-874',$revChqToCCID);
$pdf->MultiCell(28,4,$buss_name,0,'L',0);

$pdf->SetXY(28,$cline); 
$buss_name=iconv('UTF-8','windows-874',$fullname);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(76,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bankChqNo);
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(95,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bankChqDate);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(112,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bankName);
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(152,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bankChqToCompID);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
$pdf->SetXY(152,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($bankChqAmt,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(183,$cline); 
$buss_name=iconv('UTF-8','windows-874',$userfullname);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(210,$cline); 
$buss_name=iconv('UTF-8','windows-874',$ourbankname."-".$BAccount);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(233,$cline); 
$buss_name=iconv('UTF-8','windows-874',$chqstampdate);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(240,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bankRevStamp);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(242,$cline); 
$buss_name=iconv('UTF-8','windows-874',$status);
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

// -----------

$cline+=5; 
$i+=1;   

$bankChqAmtall += $bankChqAmt; 
unset($ourbankname);
unset($BAccount);   
}  

$pdf->SetFont('AngsanaNew','B',10);
//ขีดเส้นขั้นรวม 3 เส้นแรก

$pdf->SetXY(162,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);



$pdf->SetXY(130,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


// ผลรวมจำนวนเงิน
  
$pdf->SetXY(152,$cline+1.7); 
$s_intall=iconv('UTF-8','windows-874',number_format($bankChqAmtall,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

//ขีดเส้นขั้นรวม ใต้จำนวนเงินรวม
$pdf->SetXY(162,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(162,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);


$pdf->Output();
?>