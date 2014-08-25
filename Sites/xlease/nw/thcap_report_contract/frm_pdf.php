<?php
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$year = pg_escape_string($_GET["year"]);

$txthead .=" ประจำปี : ".$year;
$qry_type=pg_query(" select \"conType\" from \"thcap_contract_type\" order by \"conType\" asc ");

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

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
$pdf->SetAutoPageBreak(true,0);
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',$txthead);
$pdf->Text(5,26,$gmm);

$pdf->SetFont('AngsanaNew','',12);
										
$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',9);
$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,29); 
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(73,29); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(88,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(103.5,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(125,29); 
$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
$pdf->SetXY(150,29); 
$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
$pdf->SetXY(175,29); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(210,29); 
$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
$pdf->MultiCell(10,4,$buss_name,0,'R',0);

$pdf->SetXY(220,29); 
$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);


$pdf->SetXY(230,29); 
$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(255,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(265,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(279,29); 
$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,34); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 40;
$i = 1;
$j = 0;  
				
while($res_type=pg_fetch_array($qry_type))
{
	list($type)=$res_type;	
					
					
					if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
										$pdf->MultiCell(290,4,$title,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12); 
										$pdf->SetXY(4,22); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
										$pdf->MultiCell(285,4,$buss_name,0,'R',0);

										$pdf->SetXY(10,15);
										$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
										$pdf->MultiCell(290,4,$buss_name,0,'C',0);

										$gmm=iconv('UTF-8','windows-874',$txthead);
										$pdf->Text(5,26,$gmm);

										$pdf->SetFont('AngsanaNew','',12);
										
										$pdf->SetXY(4,24); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(290,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','B',9);
										$pdf->SetXY(4,29); 
										$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(30,29); 
										$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
										$pdf->SetXY(150,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
										$pdf->SetXY(175,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);

										$pdf->SetXY(210,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(10,4,$buss_name,0,'R',0);

										$pdf->SetXY(220,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(10,4,$buss_name,0,'C',0);


										$pdf->SetXY(230,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(255,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(265,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetXY(279,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12);
										$pdf->SetXY(4,34); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(285,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','',10);
										$cline = 40;
										$i = 1;
										$j = 0; 

									}

							// -----------
					
					
					
					
					
					$pdf->SetFont('AngsanaNew','B',15);
					$pdf->SetXY(5,$cline);
					$title=iconv('UTF-8','windows-874',"--- ".$type." -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
					$pdf->MultiCell(290,4,$title,0,'L',0);
					
					$cline += 5;
					$i +=1;
					
						if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
										$pdf->MultiCell(290,4,$title,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12); 
										$pdf->SetXY(4,22); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
										$pdf->MultiCell(285,4,$buss_name,0,'R',0);

										$pdf->SetXY(10,15);
										$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
										$pdf->MultiCell(290,4,$buss_name,0,'C',0);

										$gmm=iconv('UTF-8','windows-874',$txthead);
										$pdf->Text(5,26,$gmm);

										$pdf->SetFont('AngsanaNew','',12);
										
										$pdf->SetXY(4,24); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(290,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','B',9);
										$pdf->SetXY(4,29); 
										$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(30,29); 
										$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
										$pdf->SetXY(150,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
										$pdf->SetXY(175,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);

										$pdf->SetXY(210,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(10,4,$buss_name,0,'R',0);

										$pdf->SetXY(220,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(10,4,$buss_name,0,'C',0);


										$pdf->SetXY(230,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(255,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(265,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetXY(279,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12);
										$pdf->SetXY(4,34); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(285,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','',10);
										$cline = 40;
										$i = 1;
										$j = 0; 

									}

							// -----------
					
						$qry_fr=pg_query("select * from \"thcap_contract\" where EXTRACT(YEAR FROM \"conDate\")='$year'  and \"conType\"='$type' ORDER BY \"contractID\"  ASC");
						$rows_connew = pg_num_rows($qry_fr);
						$rows_con = pg_num_rows($qry_fr);
						if($rows_con > 0){ //หากประเภทนี้มีข้อมูล
							while($re_connew = pg_fetch_array($qry_fr)){
										
							//หาชื่อผู้กู้หลัก
							$contractID = $re_connew["contractID"];
							$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
							list($thcap_fullname) = pg_fetch_array($qry_cusname);
										
							//หาวันที่ปิดบัญชี
							$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
							$dateclosere = pg_fetch_array($dateclosesql);
							$dateclose = $dateclosere['thcap_checkcontractcloseddate'];

									if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										
										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
										$pdf->MultiCell(290,4,$title,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12); 
										$pdf->SetXY(4,22); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
										$pdf->MultiCell(285,4,$buss_name,0,'R',0);

										$pdf->SetXY(10,15);
										$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
										$pdf->MultiCell(290,4,$buss_name,0,'C',0);

										$gmm=iconv('UTF-8','windows-874',$txthead);
										$pdf->Text(5,26,$gmm);

										$pdf->SetFont('AngsanaNew','',12);
										
										$pdf->SetXY(4,24); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(290,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','B',9);
										$pdf->SetXY(4,29); 
										$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(30,29); 
										$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
										$pdf->SetXY(150,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
										$pdf->SetXY(175,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);

										$pdf->SetXY(210,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(10,4,$buss_name,0,'R',0);

										$pdf->SetXY(220,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(10,4,$buss_name,0,'C',0);


										$pdf->SetXY(230,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(255,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(265,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetXY(279,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12);
										$pdf->SetXY(4,34); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(285,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','',10);
										$cline = 40;
										$i = 1;
										$j = 0; 

									}

							// -----------

							$pdf->SetFont('AngsanaNew','',10);

							$pdf->SetXY(4,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["contractID"]);
							$pdf->MultiCell(29,4,$buss_name,0,'L',0);

							$pdf->SetXY(30,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$thcap_fullname);
							$pdf->MultiCell(70,4,$buss_name,0,'L',0);

							$pdf->SetXY(75,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conType"]);
							$pdf->MultiCell(13,4,$buss_name,0,'C',0);

							$pdf->SetXY(88,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conDate"]);
							$pdf->MultiCell(15,4,$buss_name,0,'C',0);

							$pdf->SetXY(103,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conStartDate"]);
							$pdf->MultiCell(15,4,$buss_name,0,'C',0);

							$pdf->SetXY(125,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conCredit"],2));
							$pdf->MultiCell(25,4,$buss_name,0,'R',0);

							$pdf->SetXY(150,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conLoanAmt"],2));
							$pdf->MultiCell(25,4,$buss_name,0,'R',0);
							  
							$pdf->SetXY(175,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conFinAmtExtVat"],2));
							$pdf->MultiCell(25,4,$buss_name,0,'R',0);

							$pdf->SetXY(210,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conLoanIniRate"]);
							$pdf->MultiCell(10,4,$buss_name,0,'C',0);

							$pdf->SetXY(220,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conTerm"]);
							$pdf->MultiCell(10,4,$buss_name,0,'C',0);

							$pdf->SetXY(230,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conMinPay"],2));
							$pdf->MultiCell(20,4,$buss_name,0,'R',0);

							$pdf->SetXY(250,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conFirstDue"]);
							$pdf->MultiCell(15,4,$buss_name,0,'R',0);

							$pdf->SetXY(265,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conEndDate"]);
							$pdf->MultiCell(15,4,$buss_name,0,'R',0);

							$pdf->SetXY(279,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conClosedFee"],2));
							$pdf->MultiCell(15,4,$buss_name,0,'C',0);

							// -----------

							$cline+=5; 
							$i+=1;  
							$numrows++;
												
							unset($thcap_fullname); //ทำลายตัวแปรเก็บชื่อผู้กู้หลัก  เพื่อป้องกันการแสดงซ้ำซ้อนของข้อมูล 
							   
							}
							
							if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
										$pdf->MultiCell(290,4,$title,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12); 
										$pdf->SetXY(4,22); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
										$pdf->MultiCell(285,4,$buss_name,0,'R',0);

										$pdf->SetXY(10,15);
										$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
										$pdf->MultiCell(290,4,$buss_name,0,'C',0);

										$gmm=iconv('UTF-8','windows-874',$txthead);
										$pdf->Text(5,26,$gmm);

										$pdf->SetFont('AngsanaNew','',12);
										
										$pdf->SetXY(4,24); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(290,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','B',9);
										$pdf->SetXY(4,29); 
										$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(30,29); 
										$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
										$pdf->SetXY(150,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
										$pdf->SetXY(175,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);

										$pdf->SetXY(210,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(10,4,$buss_name,0,'R',0);

										$pdf->SetXY(220,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(10,4,$buss_name,0,'C',0);


										$pdf->SetXY(230,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(255,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(265,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetXY(279,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','',12);
										$pdf->SetXY(4,34); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(285,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','',10);
										$cline = 40;
										$i = 1;
										$j = 0; 

									}

							// -----------
							$pdf->SetFont('AngsanaNew','B',10);
							$pdf->SetXY(5,$cline+2); 
							$buss_name=iconv('UTF-8','windows-874',"ประเภท ".$type.":".$rows_connew." สัญญา");
							$pdf->MultiCell(70,4,$buss_name,0,'L',0);
							$cline+=10; 
							$i +=2;
							
							
							
							
					}else{
						if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
										$pdf->MultiCell(290,4,$title,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12); 
										$pdf->SetXY(4,22); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
										$pdf->MultiCell(285,4,$buss_name,0,'R',0);

										$pdf->SetXY(10,15);
										$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
										$pdf->MultiCell(290,4,$buss_name,0,'C',0);

										$gmm=iconv('UTF-8','windows-874',$txthead);
										$pdf->Text(5,26,$gmm);

										$pdf->SetFont('AngsanaNew','',12);
										
										$pdf->SetXY(4,24); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(290,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','B',9);
										$pdf->SetXY(4,29); 
										$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(30,29); 
										$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
										$pdf->SetXY(150,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
										$pdf->SetXY(175,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(25,4,$buss_name,0,'C',0);

										$pdf->SetXY(210,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(10,4,$buss_name,0,'R',0);

										$pdf->SetXY(220,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(10,4,$buss_name,0,'C',0);


										$pdf->SetXY(230,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(255,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(265,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetXY(279,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(15,4,$buss_name,0,'C',0);

										$pdf->SetFont('AngsanaNew','',12);
										$pdf->SetXY(4,34); 
										$buss_name=iconv('UTF-8','windows-874',"");
										$pdf->MultiCell(285,4,$buss_name,'B','L',0);

										$pdf->SetFont('AngsanaNew','',10);
										$cline = 40;
										$i = 1;
										$j = 0; 

									}

							// -----------
						
						$pdf->SetXY(5,$cline); 
						$buss_name=iconv('UTF-8','windows-874'," ---------------- ไม่มีข้อมูล ----------------");
						$pdf->MultiCell(270,4,$buss_name,0,'C',0);
						
						$cline+=5; 
						$i +=1;
					}
				
}	

if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
	$pdf->AddPage(); 
	$cline = 37; 
	$i=1; 


	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(10,10);
	$pdf->SetXY(10,10);
	$title=iconv('UTF-8','windows-874',"(THCAP)แสดงรายละเอียดข้อมูลสัญญา");
	$pdf->MultiCell(290,4,$title,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12); 
	$pdf->SetXY(4,22); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
	$pdf->MultiCell(285,4,$buss_name,0,'R',0);

	$pdf->SetXY(10,15);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
	$pdf->MultiCell(290,4,$buss_name,0,'C',0);

	$gmm=iconv('UTF-8','windows-874',$txthead);
	$pdf->Text(5,26,$gmm);

	$pdf->SetFont('AngsanaNew','',12);
										
	$pdf->SetXY(4,24); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(290,4,$buss_name,'B','L',0);

	$pdf->SetFont('AngsanaNew','B',9);
	$pdf->SetXY(4,29); 
	$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(30,29); 
	$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
	$pdf->MultiCell(35,4,$buss_name,0,'C',0);

	$pdf->SetXY(73,29); 
	$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(88,29); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(103.5,29); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้");
	$pdf->MultiCell(25,4,$buss_name,0,'L',0);

	$pdf->SetXY(125,29); 
	$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
  
	$pdf->SetXY(150,29); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);
										
	$pdf->SetXY(175,29); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดเงินลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
	$pdf->MultiCell(25,4,$buss_name,0,'C',0);

	$pdf->SetXY(210,29); 
	$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
	$pdf->MultiCell(10,4,$buss_name,0,'R',0);

	$pdf->SetXY(220,29); 
	$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
	$pdf->MultiCell(10,4,$buss_name,0,'C',0);


	$pdf->SetXY(230,29); 
	$buss_name=iconv('UTF-8','windows-874',"ยอดชำระ\n ขั้นต่ำ");
	$pdf->MultiCell(20,4,$buss_name,0,'C',0);

	$pdf->SetXY(255,29); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่ \nเริ่มจ่าย");
	$pdf->MultiCell(20,4,$buss_name,0,'L',0);

	$pdf->SetXY(265,29); 
	$buss_name=iconv('UTF-8','windows-874',"วันที่  \nสิ้นสุดสัญญา");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetXY(279,29); 
	$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
	$pdf->MultiCell(15,4,$buss_name,0,'C',0);

	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(4,34); 
	$buss_name=iconv('UTF-8','windows-874',"");
	$pdf->MultiCell(285,4,$buss_name,'B','L',0);

	$pdf->SetFont('AngsanaNew','',10);
	$cline = 40;
	$i = 1;
	$j = 0; 

}

$pdf->Output();
?>