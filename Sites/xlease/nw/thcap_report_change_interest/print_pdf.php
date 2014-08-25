<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$type = $_GET["type"]; // ประเภทการค้นหา d = วัน/เดือน/ปี | m = รายเดือย-ปี  |  y = รายปี  | c = รายสัญญา

$day = $_GET["day"]; // วันที่เลือก
$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก
$contractID = $_GET["contractID"]; // เลขที่สัญญา

$nameMonthTH = nameMonthTH($month);
$yearTH = $year+543;

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการปรับอัตราดอกเบี้ย");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//----- หัวเลขที่สัญญา
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
if($type=="d"){$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $day เดือน $nameMonthTH ปี พ.ศ. $yearTH");}
elseif($type=="m"){$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
elseif($type=="y"){$buss_name=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
elseif($type=="c"){$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา $contractID");}
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
//----- จบหัวเลขที่สัญญา

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่มีผล");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(80,33);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(125,33);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยในระบบ (เดิม)");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(165,33);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยในระบบ (ใหม่)");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

if($type == "d")
{
	$qry = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
						(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
						a.\"conIntCurRate\" as \"newRate\"
						from \"thcap_mg_contract_current\" a
						where substr(a.\"effectiveDate\"::character varying,9,2) = '$day'
						and substr(a.\"effectiveDate\"::character varying,6,2) = '$month'
						and substr(a.\"effectiveDate\"::character varying,1,4) = '$year'
						and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
						order by a.\"effectiveDate\", a.\"rev\" ");
}
elseif($type == "m")
{
	$qry = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
						(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
						a.\"conIntCurRate\" as \"newRate\"
						from \"thcap_mg_contract_current\" a
						where substr(a.\"effectiveDate\"::character varying,6,2) = '$month'
						and substr(a.\"effectiveDate\"::character varying,1,4) = '$year'
						and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
						order by a.\"effectiveDate\", a.\"rev\" ");
}
elseif($type == "y")
{
	$qry = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
						(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
						a.\"conIntCurRate\" as \"newRate\"
						from \"thcap_mg_contract_current\" a
						where substr(a.\"effectiveDate\"::character varying,1,4) = '$year'
						and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
						order by a.\"effectiveDate\", a.\"rev\" ");
}
elseif($type == "c")
{
	$qry = pg_query("select a.\"effectiveDate\", a.\"contractID\", a.\"rev\",
						(select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1)) as \"oldRate\",
						a.\"conIntCurRate\" as \"newRate\"
						from \"thcap_mg_contract_current\" a
						where a.\"contractID\" = '$contractID'
						and a.\"conIntCurRate\" <> (select b.\"conIntCurRate\" from \"thcap_mg_contract_current\" b where b.\"contractID\" = a.\"contractID\" and b.\"rev\" = (a.\"rev\" - 1))
						order by a.\"effectiveDate\", a.\"rev\" ");
}
$num_row = pg_num_rows($qry);
$i = 1;

while($res = pg_fetch_array($qry))
{
	$effectiveDate = $res["effectiveDate"]; // วันเวลาที่มีผล
	$contractID = $res["contractID"]; // เลขที่สัญญา
	$oldRate = $res["oldRate"]; // ดอกเบี้ยในระบบ (เดิม)
	$newRate = $res["newRate"]; // ดอกเบี้ยในระบบ (ใหม่)
	
	//ค้นหาชื่อผู้กู้หลัก
	$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
	if($resnamemain = pg_fetch_array($qry_namemain)){
		$name3 = trim($resnamemain["thcap_fullname"]);
	}
	
	if($nub == 46)
	{ // ขึ้นหน้าใหม่
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการปรับอัตราดอกเบี้ย");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		if($type=="d"){$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $day เดือน $nameMonthTH ปี พ.ศ. $yearTH");}
		elseif($type=="m"){$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");}
		elseif($type=="y"){$buss_name=iconv('UTF-8','windows-874',"ประจำปี พ.ศ. $yearTH");}
		elseif($type=="c"){$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา $contractID");}
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);
		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่มีผล");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(35,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(80,33);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(35,4,$buss_name,0,'C',0);

		$pdf->SetXY(125,33);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยในระบบ (เดิม)");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(165,33);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยในระบบ (ใหม่)");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$effectiveDate");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(35,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$contractID");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(80,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$name3");
	$pdf->MultiCell(35,4,$buss_name,0,'L',0);

	$pdf->SetXY(125,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$oldRate %");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(165,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$newRate %");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	/*
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	*/
    
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}

if($num_row > 0)
{
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline-4);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

$pdf->Output();
?>