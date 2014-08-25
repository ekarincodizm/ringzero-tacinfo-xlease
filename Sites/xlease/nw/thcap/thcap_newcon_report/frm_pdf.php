<?php
include("../../../config/config.php");
include("../../../core/core_functions.php");
include("../../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$type = pg_escape_string($_GET["type"]);
$contype = pg_escape_string($_GET["contype"]);
$find_date = pg_escape_string($_GET["find_date"]);// conDate  =วันที่ทำสัญญา  or conStartDate=วันที่เริ่มกู้/รับของ

//ตรวจสอบ ว่า ค้นหาจาก:
if ($find_date=='conDate'){// วันที่ทำสัญญา
	$conditionfind=" \"conDate\" ";
	$find="วันที่ทำสัญญา";
	
}
else if($find_date=='conStartDate'){//วันที่เริ่มกู้/รับของ
	$conditionfind=" \"conStartDate\" ";
	$find="วันที่เริ่มกู้/รับของ";
}

if($type == 'sm'){
	$mm = pg_escape_string($_GET["month"]);
	$yy = pg_escape_string($_GET["year"]);
	$txtm = nameMonthTH($mm);
	$condition = " EXTRACT(MONTH FROM $conditionfind) = '$mm' AND EXTRACT(YEAR FROM $conditionfind) = '$yy' ";
	$txthead = "รายงานการเปิดสัญญาของเดือน".$txtm." ปี ค.ศ.".$yy;
}else if($type == 'sy'){
	$yy = pg_escape_string($_GET["year"]);
	$condition = " EXTRACT(YEAR FROM $conditionfind) = '$yy' ";
	$txthead = "รายงานการเปิดสัญญาของปี  ค.ศ.".$yy;
}else{
	echo "<center><h1>ERROR ! เกิดความผิดพลาดในการแสดงผล</h1></center>";
	exit();
}
$txthead .=" โดยค้นหาจาก : ".$find;

//นำประเภทของสัญญาที่เลือกมาตัด @ แยกประเภทออกจากกัน เพื่อใช้เป็นเงื่อนไขในการค้นหา
$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == ""){
			$contypeqry = "\"conType\" = '$contype[$con]' ";
		}else{
			$contypeqry = $contypeqry."OR \"conType\" = '$contype[$con]' ";
		}
		
		if($textshowtype == ""){
			$textshowtype = $contype[$con];
		}else{
			$textshowtype = $textshowtype.",$contype[$con]";
		}	
	}
}
// เติม AND ไปข้างหน้า ใช้ในกรณีที่มีเงื่อนไขอื่นด้วยนอกจากเงื่อนไขอย่างเดียว
if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
	$condition = $condition.$contypeqry;
}

//ตัวแปลเก็บ field เพื่อใช้เรียงข้อมูล
$strSort = $_GET["sort"];
if($strSort == ""){
	$strSort = "conType";
}
//เรียงจากน้อยไปมากหรือมากไปน้อย
$strOrder = $_GET["order"];
if($strOrder == ""){
	$strOrder = "ASC";
}

//คำสั่ง query ข้อมูล
$qry_connew = pg_query("SELECT * FROM thcap_contract where $condition order by \"$strSort\" $strOrder ");


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
$pdf->SetAutoPageBreak(true,0);
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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

$pdf->SetFont('AngsanaNew','B',12);
$gmm=iconv('UTF-8','windows-874',"ประเภทสัญญา : ".$textshowtype);
$pdf->Text(105,26,$gmm);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',9);
$pdf->SetXY(4,29); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,29); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(73,29); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(88,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(103.5,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(125,29); 
$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
$pdf->SetXY(145,29); 
$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);


$pdf->SetXY(156,29); 
$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(170,29); 
$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(190,29); 
$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);


$pdf->SetXY(204,29); 
$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(224,29); 
$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(232,29); 
$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(248,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(258,29); 
$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(275,29); 
$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,34); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 40;
$i = 1;
$j = 0;  
				
for($con = 0;$con < sizeof($contype) ; $con++){
				if($contype[$con] != ""){	
					$listconCreditsum = 0; //ยอดรวม วงเงิน
					$listconLoanAmtsum = 0; //ยอดรวม ยอดกู้
					$listconFinAmtExtVatsum = 0; //ยอดรวม ยอดลงทุน/ยอดจัด
					$listconMinPaysum = 0; //ยอดรวม ยอดจ่ายขั้นต่ำต่อเดือน
					$listconResidualValue = 0;//ยอดค่าซาก
					$listdebtNet = 0;//ยอดเงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)
					
					
					if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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
					$title=iconv('UTF-8','windows-874',"--- ".$contype[$con]." -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
					$pdf->MultiCell(290,4,$title,0,'L',0);
					
					$cline += 5;
					$i +=1;
					
						if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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
					
					
					
					
					
					
					
					
					
					
					
					
					
						//คำสั่ง query ข้อมูล
						$qry_connew = pg_query("SELECT * FROM thcap_contract where \"conType\" = '$contype[$con]' AND $condition order by \"$strSort\" $strOrder ");		
						$rows_connew = pg_num_rows($qry_connew);
						if($rows_connew > 0){ //หากประเภทนี้มีข้อมูล
					
							while($re_connew=pg_fetch_array($qry_connew)){
			
									//หาชื่อผู้กู้หลัก
										$contractID = $re_connew["contractID"];
										$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
										list($thcap_fullname) = pg_fetch_array($qry_cusname);										
									
									//ค่าซาก (ก่อนภาษีมูลค่าเพิ่ม)										
										$conResidualValuesql = pg_query("SELECT thcap_get_all_residuevalue('$contractID','1')");
										$conResidual= pg_fetch_array($conResidualValuesql);
										$conResidualValue = $conResidual['thcap_get_all_residuevalue'];
										if($conResidualValue !=""){
											$conResidualValue=number_format($conResidualValue,2);
											$listconResidualValue += $conResidual['thcap_get_all_residuevalue'];	
											$conResidualValuesum += $conResidual['thcap_get_all_residuevalue'];										
										}
									//เงินดาวน์ (ก่อนภาษีมูลค่าเพิ่ม)
										$qry_debtNet = pg_query("select SUM(\"debtNet\") as \"debtNet\" from \"thcap_temp_otherpay_debt\"  
										where \"typePayID\" LIKE '%996'  and \"contractID\"='$contractID'");
										$re_debtNet = pg_fetch_array($qry_debtNet);
										$debtNet = $re_debtNet["debtNet"];
										if($debtNet !=""){ 
											$debtNet= number_format($debtNet,2);
											$listdebtNet += $re_debtNet["debtNet"];
											$debtNetsum += $re_debtNet["debtNet"];
										}	
										

									if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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

							$pdf->SetXY(115,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conCredit"],2));
							$pdf->MultiCell(23,4,$buss_name,0,'R',0);

							$pdf->SetXY(133,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conLoanAmt"],2));
							$pdf->MultiCell(23,4,$buss_name,0,'R',0);
							  
							$pdf->SetXY(151,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conFinAmtExtVat"],2));
							$pdf->MultiCell(23,4,$buss_name,0,'R',0);
							
							$pdf->SetXY(171,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$debtNet);
							$pdf->MultiCell(23,4,$buss_name,0,'R',0);
							
							$pdf->SetXY(190,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$conResidualValue);
							$pdf->MultiCell(23,4,$buss_name,0,'R',0);

							$pdf->SetXY(215,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conLoanIniRate"]);
							$pdf->MultiCell(23,4,$buss_name,0,'L',0);

							$pdf->SetXY(220,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conTerm"]);
							$pdf->MultiCell(15,4,$buss_name,0,'C',0);

							$pdf->SetXY(229,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conMinPay"],2));
							$pdf->MultiCell(20,4,$buss_name,0,'R',0);

							$pdf->SetXY(249,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conFirstDue"]);
							$pdf->MultiCell(15,4,$buss_name,0,'R',0);

							$pdf->SetXY(264,$cline); 
							$buss_name=iconv('UTF-8','windows-874',$re_connew["conEndDate"]);
							$pdf->MultiCell(15,4,$buss_name,0,'R',0);

							$pdf->SetXY(260,$cline); 
							$buss_name=iconv('UTF-8','windows-874',number_format($re_connew["conClosedFee"],2));
							$pdf->MultiCell(30,4,$buss_name,0,'R',0);

							// -----------

							$cline+=5; 
							$i+=1;  

												$numrows++;
												
												//รวมของแต่ละประเภทสัญญา
												$listconCreditsum += $re_connew["conCredit"]; //ยอดรวม วงเงิน
												$listconLoanAmtsum += $re_connew["conLoanAmt"]; //ยอดรวม ยอดกู้
												$listconFinAmtExtVatsum += $re_connew["conFinAmtExtVat"]; //ยอดรวม ยอดลงทุน/ยอดจัด
												$listconMinPaysum += $re_connew["conMinPay"]; //ยอดรวม ยอดจ่ายขั้นต่ำต่อเดือน
												
												
												//รวมผลรวมทั้งหมด	
												$conCreditsum += $re_connew["conCredit"]; //ยอดรวม วงเงิน
												$conLoanAmtsum += $re_connew["conLoanAmt"]; //ยอดรวม ยอดกู้
												$conFinAmtExtVatsum += $re_connew["conFinAmtExtVat"]; //ยอดรวม ยอดลงทุน/ยอดจัด
												$conMinPaysum += $re_connew["conMinPay"]; //ยอดรวม ยอดจ่ายขั้นต่ำต่อเดือน
												
												unset($thcap_fullname); //ทำลายตัวแปรเก็บชื่อผู้กู้หลัก  เพื่อป้องกันการแสดงซ้ำซ้อนของข้อมูล 
							   
							}
							
							if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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
							$buss_name=iconv('UTF-8','windows-874',"ประเภท ".$contype[$con].":".$rows_connew." สัญญา");
							$pdf->MultiCell(70,4,$buss_name,0,'L',0);
							
							$pdf->SetXY(65,$cline+2); 
							$buss_name=iconv('UTF-8','windows-874',"รวม");
							$pdf->MultiCell(30,4,$buss_name,0,'R',0);


							// ผลรวมจำนวนเงิน							

							$pdf->SetXY(122,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','L',0);

							$pdf->SetXY(140,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(159,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);	
							
							$pdf->SetXY(179,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(198,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(234,$cline-2); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);								
							  
							$pdf->SetXY(108,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',number_format($listconCreditsum,2));
							$pdf->MultiCell(30,4,$s_intall,0,'R',0);

							$pdf->SetXY(126,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',number_format($listconLoanAmtsum,2));
							$pdf->MultiCell(30,4,$s_intall,0,'R',0);

							$pdf->SetXY(145,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',number_format($listconFinAmtExtVatsum,2));
							$pdf->MultiCell(30,4,$s_intall,0,'R',0);	
							
							
							if($listdebtNet ==""){$listdebtNet=""; }
							else {$listdebtNet=number_format($listdebtNet,2);}
							$pdf->SetXY(171,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',$listdebtNet);
							$pdf->MultiCell(23,4,$s_intall,0,'R',0);
							
							if($listconResidualValue ==""){$listconResidualValue=""; }
							else {$listconResidualValue=number_format($listconResidualValue,2);}							
							$pdf->SetXY(190,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',$listconResidualValue);
							$pdf->MultiCell(23,4,$s_intall,0,'R',0);

							$pdf->SetXY(229,$cline+2); 
							$s_intall=iconv('UTF-8','windows-874',number_format($listconMinPaysum,2));
							$pdf->MultiCell(20,4,$s_intall,0,'R',0);
							
							$pdf->SetXY(122,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(122,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','L',0);

							$pdf->SetXY(140,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','L',0);

							$pdf->SetXY(140,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);
							
							$pdf->SetXY(159,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(159,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);
							
							$pdf->SetXY(179,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(179,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);
							
							$pdf->SetXY(198,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(198,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(234,$cline+3); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);

							$pdf->SetXY(234,$cline+3.5); 
							$buss_name=iconv('UTF-8','windows-874',"");
							$pdf->MultiCell(15,4,$buss_name,'B','R',0);
							
							$cline+=10; 
							$i +=2;
							
							
							
							
					}else{
						if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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
}	

if($i > 29){  //หาบรรทัดข้อมูลเกิน 30 จะขึ้นหน้าใหม่
										$pdf->AddPage(); 
										$cline = 37; 
										$i=1; 


										$pdf->SetFont('AngsanaNew','B',15);
										$pdf->SetXY(10,10);
										$title=iconv('UTF-8','windows-874',"(THCAP)รายงานการเปิดสัญญา");
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
										$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุล ผู้กู้หลัก");
										$pdf->MultiCell(35,4,$buss_name,0,'C',0);

										$pdf->SetXY(73,29); 
										$buss_name=iconv('UTF-8','windows-874',"ประเภทสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(88,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่ทำสัญญา");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(103.5,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มกู้\n/รับของ");
										$pdf->MultiCell(25,4,$buss_name,0,'L',0);

										$pdf->SetXY(125,29); 
										$buss_name=iconv('UTF-8','windows-874',"วงเงิน");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);
  
										$pdf->SetXY(145,29); 
										$buss_name=iconv('UTF-8','windows-874',"ยอดกู้");
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);


										$pdf->SetXY(156,29); 
										$buss_name=iconv('UTF-8','windows-874'," ยอดลงทุน/ยอดจัด\n(ก่อนภาษีมูลค่าเพิ่ม)"); 
										$pdf->MultiCell(30,4,$buss_name,0,'L',0);

										$pdf->SetXY(170,29); 
										$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์          \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);

										$pdf->SetXY(190,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่าซาก           \n(ก่อนภาษีมูลค่าเพิ่ม)");
										$pdf->MultiCell(25,4,$buss_name,0,'R',0);


										$pdf->SetXY(204,29); 
										$buss_name=iconv('UTF-8','windows-874',"อัตรา\nดอกเบี้ย");
										$pdf->MultiCell(30,4,$buss_name,0,'C',0);

										$pdf->SetXY(224,29); 
										$buss_name=iconv('UTF-8','windows-874',"จำนวน\n เดือน");
										$pdf->MultiCell(20,4,$buss_name,0,'L',0);

										$pdf->SetXY(232,29); 
										$buss_name=iconv('UTF-8','windows-874',"ค่างวด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

										$pdf->SetXY(248,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มจ่าย");
										$pdf->MultiCell(15,4,$buss_name,0,'R',0);

										$pdf->SetXY(258,29); 
										$buss_name=iconv('UTF-8','windows-874',"วันที่      \nสิ้นสุดสัญญา");
										$pdf->MultiCell(20,4,$buss_name,0,'R',0);

										$pdf->SetFont('AngsanaNew','B',10);
										$pdf->SetXY(275,29); 
										$buss_name=iconv('UTF-8','windows-874',"% ค่า\nเสียหายปิด");
										$pdf->MultiCell(20,4,$buss_name,0,'C',0);

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
$title=iconv('UTF-8','windows-874',"--- ผลรวม --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
$pdf->MultiCell(290,4,$title,0,'L',0);
$cline+=5; 
$i +=1;
$pdf->SetFont('AngsanaNew','B',10);
//ขีดเส้นขั้นรวม 4 เส้นแรก

$pdf->SetXY(122,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(140,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(159,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(179,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(198,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(234,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมสัญญาทั้งหมด ".$numrows." สัญญา");
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(65,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


// ผลรวมจำนวนเงิน
  
$pdf->SetXY(108,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',number_format($conCreditsum,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

$pdf->SetXY(126,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',number_format($conLoanAmtsum,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

$pdf->SetXY(145,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',number_format($conFinAmtExtVatsum,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

if($debtNetsum ==""){$debtNetsum=""; }
else {$debtNetsum=number_format($debtNetsum,2);}
$pdf->SetXY(164,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',$debtNetsum );
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

if($conResidualValuesum ==""){$conResidualValuesum=""; }
else {$conResidualValuesum=number_format($conResidualValuesum,2);}
$pdf->SetXY(183,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',$conResidualValuesum);
$pdf->MultiCell(30,4,$s_intall,0,'R',0);


$pdf->SetXY(219,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',number_format($conMinPaysum,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

//ขีดเส้นขั้นรวม ใต้จำนวนเงินรวม
$pdf->SetXY(122,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(140,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(159,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(179,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(198,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(234,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(122,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(140,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(159,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(179,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->SetXY(198,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);


$pdf->SetXY(234,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(15,4,$buss_name,'B','R',0);

$pdf->Output();
?>