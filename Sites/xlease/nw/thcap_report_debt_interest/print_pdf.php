<?php
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$user_id = $_SESSION['av_iduser'];

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$type = pg_escape_string($_GET["type"]); // ประเภท
$Sdate = pg_escape_string($_GET["Sdate"]); // วันที่เริ่ม
$Edate = pg_escape_string($_GET["Edate"]); // วันที่สิ้นสุด
$month = pg_escape_string($_GET["month"]); // เดือนที่เลือก
$year = pg_escape_string($_GET["year"]); // ปีที่เลือก
$whereContract = pg_escape_string($_GET["whereContract"]); // เลขที่สัญญา
$selectStyle = pg_escape_string($_GET["selectStyle"]); // รูปแบบการแสดง

if($selectStyle == "allStyle"){$selectStyleText = "แสดงการตั้งหนี้ทั้งหมด";}
elseif($selectStyle == "receiptStyle"){$selectStyleText = "แสดงเฉพาะที่ออกโดยใบเสร็จ";}
elseif($selectStyle == "autoStyle"){$selectStyleText = "แสดงเฉพาะที่สร้างอัตโนมัติโดยระบบ";}

$nameMonthTH = nameMonthTH($month);
$yearTH = $year+543;

// หาชื่อผู้พิมพ์รายงาน
$qry_user_fullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$user_id' ");
$user_fullname = pg_result($qry_user_fullname,0);

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

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP)รายงานตั้งหนี้ดอกเบี้ย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,16); 
$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : ".$user_fullname);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,18);
$buss_name=iconv('UTF-8','windows-874',"รูปแบบการแสดง : $selectStyleText");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

if($type == "year"){$gmm=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
if($type == "month"){$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
if($type == "between"){$gmm=iconv('UTF-8','windows-874',"ระหว่าง $Sdate ถึง $Edate");}
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(3,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มคิดดอกเบี้ย");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่สิ้นสุดการคิดดอกเบี้ย");
$pdf->MultiCell(35,4,$buss_name,0,'R',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่คิดดอกเบี้ยเพิ่ม");
$pdf->MultiCell(35,4,$buss_name,0,'R',0);
  
$pdf->SetXY(235,30); 
$buss_name=iconv('UTF-8','windows-874',"โดย");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(260,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ถูกตั้ง");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  
                        
if($whereContract != "")
{
	$where_other = "and \"contractID\" = '$whereContract' ";
}
else
{
	$where_other = "";
}

if($selectStyle == "receiptStyle")
{
	$where_other .= "and \"isReceiveReal\" > '0' ";
}
elseif($selectStyle == "autoStyle")
{
	$where_other .= "and \"isReceiveReal\" = '0' ";
}

if($type == "between")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and \"genDate\" >= '$Sdate'
						and \"genDate\" <= '$Edate'
						$where_other
						order by \"genDate\" ");
}
elseif($type == "month")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,6,2) = '$month'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						$where_other
						order by \"genDate\" ");
}
elseif($type == "year")
{
	$qry = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						$where_other
						order by \"genDate\" ");
}
				
$num_row = pg_num_rows($qry);
$i = 1;
$a = 1;
$allNewInterest = 0; // ยอดรวมทั้งหมด
$sunNewInterestForMonth = 0; // ยอดรวมของแต่ละเดือน
		
				
while($res=pg_fetch_array($qry)){
	
		$genDate = $res["genDate"]; // วันที่ตั้งหนี้
		$contractID = $res["contractID"]; // เลขที่สัญญา
		$MainCusName = $res["MainCusName"]; // ชื่อผู้กู้หลัก
		$lastPrinciple = $res["lastPrinciple"]; // เงินต้น
		$interestRate = $res["interestRate"]; // อัตราดอกเบี้ย
		$startIntDate = $res["startIntDate"]; // วันที่เริ่มคิดดอกเบี้ยรายการนี้
		$endIntDate = $res["endIntDate"]; //วันที่สิ้นสุดการคิดดอกเบี้ยรายการนี้
		$numIntDays = $res["numIntDays"]; // จำนวนวันที่คิดดอกเบี้ยเพิ่ม
		$isReceiveReal = $res["isReceiveReal"]; // ถ้า isReceiveReal > 0 คือ ด้วยใบเสร็จ = 0 คือด้วยระบบ
		$newInterest = $res["newInterest"]; // จำนวนดอกเบี้ยที่ถูกตั้ง
		
		$allNewInterest += $newInterest; // ยอดรวมทั้งหมด
		
		if($a == 1){$nowMonth = substr($genDate,5,2);}
		
		if($isReceiveReal == 0)
		{
			$txt_isReceiveReal = "สร้างอัตโนมัติโดยระบบ";
		}
		elseif($isReceiveReal > 0)
		{
			$txt_isReceiveReal = "ออกโดยใบเสร็จ";
		}
		else
		{
			$txt_isReceiveReal = "";
		}

		if($i > 29){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานตั้งหนี้ดอกเบี้ย");
			$pdf->MultiCell(280,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,16); 
			$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : ".$user_fullname);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(280,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(4,18);
			$buss_name=iconv('UTF-8','windows-874',"รูปแบบการแสดง : $selectStyleText");
			$pdf->MultiCell(285,4,$buss_name,0,'L',0);

			if($type == "year"){$gmm=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
			if($type == "month"){$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
			if($type == "between"){$gmm=iconv('UTF-8','windows-874',"ระหว่าง $Sdate ถึง $Edate");}
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetXY(3,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(100,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,30); 
			$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มคิดดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่สิ้นสุดการคิดดอกเบี้ย");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่คิดดอกเบี้ยเพิ่ม");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			  
			$pdf->SetXY(235,30); 
			$buss_name=iconv('UTF-8','windows-874',"โดย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ถูกตั้ง");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0; 

	}

	// ถ้าเลือกแบบ ปี ให้แสดงยอดรวมของแต่ละเดือนด้วย
	if($type == "year" && $nowMonth != substr($genDate,5,2))
	{
	
		if($i > 29){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานตั้งหนี้ดอกเบี้ย");
			$pdf->MultiCell(280,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,16); 
			$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : ".$user_fullname);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(280,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(4,18);
			$buss_name=iconv('UTF-8','windows-874',"รูปแบบการแสดง : $selectStyleText");
			$pdf->MultiCell(285,4,$buss_name,0,'L',0);

			if($type == "year"){$gmm=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
			if($type == "month"){$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
			if($type == "between"){$gmm=iconv('UTF-8','windows-874',"ระหว่าง $Sdate ถึง $Edate");}
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetXY(3,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(100,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,30); 
			$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มคิดดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่สิ้นสุดการคิดดอกเบี้ย");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่คิดดอกเบี้ยเพิ่ม");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			  
			$pdf->SetXY(235,30); 
			$buss_name=iconv('UTF-8','windows-874',"โดย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ถูกตั้ง");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0; 

	}
	
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(220,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมของเดือน ".nameMonthTH($nowMonth));
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(260,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sunNewInterestForMonth,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		
		$pdf->SetXY(220,$cline-5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(70,4,$buss_name,'B','R',0);
		
		$pdf->SetXY(220,$cline+5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(70,4,$buss_name,'T','R',0);

		
		$sunNewInterestForMonth = 0;
		$pdf->SetFont('AngsanaNew','',10);
		$cline += 5;
		$i++;
		$a = 1;
	}else{
		$a += 1;
	}
	
	
// -----------

	


$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(3,$cline); 
$buss_name=iconv('UTF-8','windows-874',$genDate);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,$cline); 
$buss_name=iconv('UTF-8','windows-874',$contractID);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(50,$cline); 
$buss_name=iconv('UTF-8','windows-874',$MainCusName);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($lastPrinciple,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',$interestRate);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$startIntDate);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$endIntDate);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$numIntDays);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);
  
$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$txt_isReceiveReal);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(260,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($newInterest,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

// -----------

$cline+=5; 
$i+=1;

	
$sunNewInterestForMonth += $newInterest; // ยอดรวมของแต่ละเดือน
       
}  

if($type == "year")
{
		if($i > 29){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานตั้งหนี้ดอกเบี้ย");
			$pdf->MultiCell(280,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,16); 
			$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : ".$user_fullname);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(280,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(4,18);
			$buss_name=iconv('UTF-8','windows-874',"รูปแบบการแสดง : $selectStyleText");
			$pdf->MultiCell(285,4,$buss_name,0,'L',0);

			if($type == "year"){$gmm=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
			if($type == "month"){$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
			if($type == "between"){$gmm=iconv('UTF-8','windows-874',"ระหว่าง $Sdate ถึง $Edate");}
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetXY(3,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(100,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,30); 
			$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มคิดดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่สิ้นสุดการคิดดอกเบี้ย");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่คิดดอกเบี้ยเพิ่ม");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			  
			$pdf->SetXY(235,30); 
			$buss_name=iconv('UTF-8','windows-874',"โดย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ถูกตั้ง");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0; 

	}

	
		$nowMonth != substr($genDate,5,2);
		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(220,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมของเดือน ".nameMonthTH($nowMonth));
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);
		
		$pdf->SetXY(260,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sunNewInterestForMonth,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			
		$pdf->SetXY(220,$cline-5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(70,4,$buss_name,'B','R',0);
		
		$pdf->SetXY(220,$cline+5);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(70,4,$buss_name,'T','R',0);
		
		$cline += 5;
		$i++;
}

if($i > 29){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานตั้งหนี้ดอกเบี้ย");
			$pdf->MultiCell(280,4,$title,0,'C',0);
			
			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,16); 
			$buss_name=iconv('UTF-8','windows-874',"ผู้พิมพ์ : ".$user_fullname);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12); 
			$pdf->SetXY(4,22); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
			$pdf->MultiCell(285,4,$buss_name,0,'R',0);

			$pdf->SetXY(10,15);
			$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
			$pdf->MultiCell(280,4,$buss_name,0,'C',0);
			
			$pdf->SetXY(4,18);
			$buss_name=iconv('UTF-8','windows-874',"รูปแบบการแสดง : $selectStyleText");
			$pdf->MultiCell(285,4,$buss_name,0,'L',0);

			if($type == "year"){$gmm=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
			if($type == "month"){$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
			if($type == "between"){$gmm=iconv('UTF-8','windows-874',"ระหว่าง $Sdate ถึง $Edate");}
			$pdf->Text(5,26,$gmm);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetXY(3,30);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(15,4,$buss_name,0,'C',0);

			$pdf->SetXY(20,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(50,4,$buss_name,0,'C',0);

			$pdf->SetXY(100,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,30); 
			$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(140,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่เริ่มคิดดอกเบี้ย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(165,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่สิ้นสุดการคิดดอกเบี้ย");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่คิดดอกเบี้ยเพิ่ม");
			$pdf->MultiCell(35,4,$buss_name,0,'R',0);
			  
			$pdf->SetXY(235,30); 
			$buss_name=iconv('UTF-8','windows-874',"โดย");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนดอกเบี้ยที่ถูกตั้ง");
			$pdf->MultiCell(30,4,$buss_name,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0; 

	}


$cline += 5;
$pdf->SetFont('AngsanaNew','B',14);
//ขีดเส้นขั้นรวมเส้นแรก

$pdf->SetXY(260,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,'B','R',0);



$pdf->SetXY(220,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);


// ผลรวมจำนวนเงิน
$pdf->SetXY(260,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',number_format($allNewInterest,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

//ขีดเส้นขั้นรวม ใต้จำนวนเงินรวม


$pdf->SetXY(260,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,'B','R',0);

$pdf->SetXY(260,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,'B','R',0);

$pdf->Output();
?>