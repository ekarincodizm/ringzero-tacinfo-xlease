<?php
session_start();
include("../../config/config.php");

set_time_limit(180);

$datepicker = pg_escape_string($_GET['datepicker']);
$condate = pg_escape_string($_GET['condate']);
$status1 = pg_escape_string($_GET["chkbox1"]);
$status2 = pg_escape_string($_GET["chkbox2"]);
$chksreach = pg_escape_string($_GET['chksh']); // เงื่อนไขหลัก
$subsreach = pg_escape_string($_GET['subsh']); // เงื่อนไขย่อย

if($status1 == 10 && $status2 == NULL)
{
	$txtSubSelect = ":: รวมรายการที่ยกเลิก";
}
else if($status1 == NULL && $status2 == 20)
{
	$txtSubSelect = ":: รวมรายการที่ยกเว้นหนี้ ";
}
else if($status1 == 10 && $status2 == 20)
{
	$txtSubSelect = ":: รวมรายการที่ยกเลิก และยกเว้นหนี้ ";
}
else
{
	//$txtSubSelect = ":: ไม่รวมรายการที่ยกเลิก และยกเว้นหนี้ ";
}

function chk_null($data,$type="text")
{
	$re_data = "";
	if($data=="")
	{
		$re_data = "<ไม่มีข้อมูล>";
	}
	else
	{
		if($type=="money")
		{
			$re_data = number_format($data,2,".",",");
		}
		else
		{
			$re_data = $data;
		}
	}
	
	return $re_data;
}

// หาเงื่อนไขย่อยก่อน
if($subsreach == "type1"){ //ค้นหาตามประเภทสัญญา
	$drop1 = pg_escape_string($_GET['drop1']);
	if ($drop1 == "all")
	{
		$conditiondate_main = "";
	}
	else
	{
		$conditiondate_main = "a.\"contractID\" like '$drop1%' and ";
	}
	if($drop1 == "all"){$subWhere="ค้นหาตามประเภทสัญญา ทั้งหมด";}else{$subWhere="ค้นหาตามประเภทสัญญา $drop1";}
}else if($subsreach == "idno1"){ //ค้นหาตามเลขที่สัญญา
	$txt1 = pg_escape_string($_GET['txt1']);
	if ($txt1 != ""){
		$conditiondate_main = "a.\"contractID\" = '$txt1' and ";
	}else{
		$conditiondate_main = "a.\"contractID\" = '$txt1' and ";
	}
	$subWhere="ค้นหาตามเลขที่สัญญา $txt1";
}else if($subsreach == "type2"){ // ค้นหาตามประเภทหนี้
	$drop2 = pg_escape_string($_GET['drop2']);
	if($drop2 == "1") // รับแทนประกันภัยและพรบ.
	{
		$conditiondate_main = "a.\"typePayID\" in (select \"tpID\" from account.\"thcap_typePay\" where (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%ประกัน%')
							OR (\"tpDesc\" LIKE '%รับแทน%' AND \"tpDesc\" LIKE '%พรบ%')) and ";
		$subWhere = "ค้นหาตามประเภทหนี้ รับแทนประกันภัยและพรบ.";
	}
	elseif($drop2 == "all")
	{
		$conditiondate_main = "";
		$subWhere = "ค้นหาตามประเภทหนี้ ทั้งหมด";
	}
}else if($subsreach == "idno2"){ //ค้นหาตามรหัสหนี้
	$txt2 = pg_escape_string($_GET['txt2']);
	if ($txt2 != ""){
		$conditiondate_main = "b.\"typePayID\" = '$txt2' and ";
	}else{
		$conditiondate_main = "b.\"typePayID\" = '$txt2' and ";
	}
	$subWhere="ค้นหาตามรหัสหนี้ $txt2";
}

// เงื่อนไขหลัก
if($datepicker != ""){
	if($condate==1){
		$txtcondate="ประจำวันที่ทำรายการ";	
		$conditiondate="date(a.\"doerStamp\")='$datepicker'";
		$conditiondate_main .= "date(a.\"doerStamp\")='$datepicker' order by b.\"doerStamp\"";
	}else if($condate==2){
		$txtcondate="ประจำวันที่หนี้มีผล";
		$conditiondate="date(a.\"typePayRefDate\")='$datepicker'";
		$conditiondate_main .= "date(b.\"typePayRefDate\")='$datepicker' order by b.\"typePayRefDate\"";
	}
	$datetext = "วันที่ ".$datepicker;
}else{
$yearsh = pg_escape_string($_GET['yearsh']);
$monthsh = pg_escape_string($_GET['monthsh']);
$monthtext = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $monthtext[$monthsh];
	if($monthsh == "not"){
			if($condate==1){
				$txtcondate="ประจำวันที่ทำรายการ";	
				$conditiondate="EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh'";
				$conditiondate_main .= "EXTRACT(YEAR FROM a.\"doerStamp\"::date)='$yearsh' order by b.\"doerStamp\"";
			}else if($condate==2){
				$txtcondate="ประจำวันที่หนี้มีผล";
				$conditiondate="EXTRACT(YEAR FROM a.\"typePayRefDate\")='$yearsh'";
				$conditiondate_main .= "EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\"";
			}
		$yeartxt = $yearsh + 543;
		$datetext = "ปี ".$yeartxt;		
	}else{
			if($condate==1){
				$txtcondate="ประจำวันที่ทำรายการ";	
				$conditiondate="EXTRACT(MONTH FROM a.\"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh'";
				$conditiondate_main .= "EXTRACT(MONTH FROM a.\"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh' order by b.\"doerStamp\"";
			}else if($condate==2){
				$txtcondate="ประจำวันที่หนี้มีผล";
				$conditiondate="EXTRACT(MONTH FROM a.\"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM a.\"typePayRefDate\"::date)='$yearsh'";
				$conditiondate_main .= "EXTRACT(MONTH FROM b.\"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM b.\"typePayRefDate\"::date)='$yearsh' order by b.\"typePayRefDate\"";
			}
		$yeartxt = $yearsh + 543;
		
		$datetext = "เดือน ".$show_month." ปี ".$yeartxt;	
	}	
}

$nowdate = nowDate();

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
 
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
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการตั้งหนี้");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(27,34);
$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(31,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);


$pdf->SetXY(48,34);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
$pdf->MultiCell(55,8,$buss_name,0,'C',0);

$pdf->SetXY(90,34);
	$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
	$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(48,39);
	// $buss_name=iconv('UTF-8','windows-874',"");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(118,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่หนี้มีผล");
$pdf->MultiCell(18,8,$buss_name,0,'C',0);

$pdf->SetXY(135,34);
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

	// $pdf->SetXY(81,39);
	// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
	// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
$pdf->SetXY(160,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้ขอตั้งหนี้");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(190,34);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่ตั้งหนี้");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(208,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติตั้งหนี้");
$pdf->MultiCell(45,8,$buss_name,0,'C',0);

$pdf->SetXY(243,34);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาอนุมัติตั้งหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(265,34);
$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',12);
$cline = 43;
$nub = 0;
if($status1 == NULL && $status2 == NULL)
{
	$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','0','3') and $conditiondate_main ");
	$numrow_main = pg_num_rows($sql_main);
}
else if($status1 == 10 && $status2 == NULL)
{
	$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','3') and $conditiondate_main ");
	$numrow_main = pg_num_rows($sql_main);
}
else if($status1 == NULL && $status2 == 20)
{
	$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9','0') and $conditiondate_main ");
	$numrow_main = pg_num_rows($sql_main);
}
else if($status1 == 10 && $status2 == 20)
{
	$sql_main = pg_query("select a.\"debtID\" as \"debtID\" from \"thcap_temp_otherpay_debt\" a , \"thcap_v_otherpay_debt_realother\" b where a.\"debtID\" = b.\"debtID\" and a.\"debtStatus\" not in('9') and $conditiondate_main ");
	$numrow_main = pg_num_rows($sql_main);
}
$i=0;
$sum_amt = 0;
$sum_all = 0;
$old_doerID="";

while($resultMain=pg_fetch_array($sql_main)){
	$debtIDMain = $resultMain["debtID"];
	
	$qryreceipt=pg_query("select a.*,to_char(a.\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\",to_char(a.\"appvStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"appvStamp1\",b.\"fullname\" as \"fullname_doer\",d.\"fullname\" as \"fullname_appv\" from \"thcap_v_otherpay_debt_realother\" a
	left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
	left join \"Vfuser\" d on a.\"appvID\"=d.\"id_user\"
	where a.\"debtID\" = '$debtIDMain' order by a.\"typePayRefDate\"");
	$numreceipt=pg_num_rows($qryreceipt);
	
	while($result=pg_fetch_array($qryreceipt)){
		$contractID=$result["contractID"];
		$typePayID=$result["typePayID"];
		$typePayRefValue=$result["typePayRefValue"];
		$typePayRefDate=$result["typePayRefDate"];
		$typePayAmt=$result["typePayAmt"];
		$fullname=$result["fullname_doer"];
		$doerStamp=$result["doerStamp1"];
		$debtStatus=$result["debtStatus"];
		$appvStamp=$result["appvStamp1"];
		$fullname_appv=$result["fullname_appv"];
		$doerID=$result["doerID"];
		
		if($doerID=="000"){
			$fullname="อัตโนมัติโดยระบบ";
		}

		if($debtStatus=="0"){
			$txtdeb="ยกเลิก";
		}else if($debtStatus=="1"){
			$txtdeb="ยังไม่ได้จ่าย/จ่ายไม่ครบ";
		}else if($debtStatus=="2"){
			$txtdeb="จ่ายครบแล้ว";
		}else if($debtStatus=="3"){
			$txtdeb="ยกเว้นหนี้";
		}else if($debtStatus == '5'){
			$txtdeb = 'ลดหนี้เป็น 0.00';
		}else if($debtStatus=="9"){
			$txtdeb="รออนุมัติ";
		}
		// หารายละเอียดค่าใช้จ่ายนั้นๆ
		$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
		while($res_tpDesc = pg_fetch_array($qry_tpDesc))
		{
			$tpDescShow = $res_tpDesc["tpDesc"];
		}
	}
	
    $pdf->SetFont('AngsanaNew','B',12);
	
	//show only new page
    if($nub >= 36){
        $nub = 0;
        $cline = 43;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการยกเว้นหนี้");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,31);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,'B','C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(27,34);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(31,39);
			// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);


		$pdf->SetXY(48,34);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
		$pdf->MultiCell(55,8,$buss_name,0,'C',0);

		$pdf->SetXY(90,34);
			$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(48,39);
			// $buss_name=iconv('UTF-8','windows-874',"");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(118,34);
		$buss_name=iconv('UTF-8','windows-874',"วันที่หนี้มีผล");
		$pdf->MultiCell(18,8,$buss_name,0,'C',0);

		$pdf->SetXY(135,34);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			// $pdf->SetXY(81,39);
			// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
			// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
		$pdf->SetXY(160,34);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ขอตั้งหนี้");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(190,34);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่ตั้งหนี้");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(208,34);
		$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติตั้งหนี้");
		$pdf->MultiCell(45,8,$buss_name,0,'C',0);

		$pdf->SetXY(243,34);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาอนุมัติตั้งหนี้");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(265,34);
		$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,8,$buss_name,'B','C',0);
    
	}
	
//show all record
    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$contractID");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayID");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(50,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tpDescShow");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(90,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(120,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(128,$cline);
    if($typePayAmt != ""){$buss_name=iconv('UTF-8','windows-874',number_format($typePayAmt,2));}
	else{$buss_name=iconv('UTF-8','windows-874',"$typePayAmt");}
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullname");
    $pdf->MultiCell(45,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(195,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$doerStamp");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

 	$pdf->SetXY(206,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullname_appv");
    $pdf->MultiCell(45,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(250,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$appvStamp");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(265,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$txtdeb");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
 
    $cline += 7;
    $nub+=2;
    $sum_amt+=$typePayAmt;
	unset($txtdeb);
} //end while 

$cline += 6;
$nub+=1;

    if($nub >= 36){
        $nub = 0;
        $cline = 43;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการยกเว้นหนี้");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,31);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(27,34);
			$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				// $pdf->SetXY(31,39);
				// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
				// $pdf->MultiCell(18,6,$buss_name,0,'C',0);


			$pdf->SetXY(48,34);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
			$pdf->MultiCell(55,8,$buss_name,0,'C',0);

			$pdf->SetXY(90,34);
				$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
				$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				// $pdf->SetXY(48,39);
				// $buss_name=iconv('UTF-8','windows-874',"");
				// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

			$pdf->SetXY(118,34);
			$buss_name=iconv('UTF-8','windows-874',"วันที่หนี้มีผล");
			$pdf->MultiCell(18,8,$buss_name,0,'C',0);

			$pdf->SetXY(135,34);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

				// $pdf->SetXY(81,39);
				// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
				// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
			$pdf->SetXY(160,34);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ขอตั้งหนี้");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(190,34);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่ตั้งหนี้");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(208,34);
			$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติตั้งหนี้");
			$pdf->MultiCell(45,8,$buss_name,0,'C',0);

			$pdf->SetXY(243,34);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาอนุมัติตั้งหนี้");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			$pdf->SetXY(265,34);
			$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,8,$buss_name,'B','C',0);

    }

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);


//###############################################################################################//
//###################################แสดงในส่วนรายการที่รออนุมัติ########################################//
//###############################################################################################//

$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',25);
$pdf->SetXY(40,10);
$title=iconv('UTF-8','windows-874',"รายการที่รออนุมัติ");
$pdf->MultiCell(50,8,$title,B,'C',0);

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการยกเว้นหนี้");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

/*$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
$pdf->MultiCell(290,4,$buss_name,0,'L',0);*/

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(27,34);
$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(31,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(48,34);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
$pdf->MultiCell(55,8,$buss_name,0,'C',0);

$pdf->SetXY(90,34);
	$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
	$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(48,39);
	// $buss_name=iconv('UTF-8','windows-874',"");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(118,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(18,8,$buss_name,0,'C',0);

$pdf->SetXY(135,34);
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

	// $pdf->SetXY(81,39);
	// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
	// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
$pdf->SetXY(160,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(195,34);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาขอตั้งหนี้");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(208,34);
$buss_name=iconv('UTF-8','windows-874',"เหตุผล");
$pdf->MultiCell(105,8,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',12);
$cline = 43;
$nub = 0;

$qry_fr=pg_query("select a.*,to_char(a.\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\",b.\"fullname\",a.\"debtRemark\"
	from \"thcap_temp_otherpay_debt\" a
	left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
	where a.\"debtStatus\" = '9' order by a.\"doerStamp\" , a.\"debtID\" ");
$nub=pg_num_rows($qry_fr);

$i=0;
$sum_amt = 0;
$sum_all = 0;
$old_doerID="";

while($res_fr=pg_fetch_array($qry_fr)){
	$debtID=$res_fr["debtID"];
	$doerUser=$res_fr["doerUser"];
	$doerStamp=$res_fr["doerStamp1"];
	$fullnameuser=$res_fr["fullname"];
	$remark=trim($res_fr['debtRemark']);
	
	$remark=str_replace("\r\n"," ",chk_null($remark));
	$remark=str_replace("\n"," ",chk_null($remark));
				 		
	$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
	while($res_detail=pg_fetch_array($qry_detail))
	{
		$typePayID = $res_detail["typePayID"];
		$typePayRefValue = $res_detail["typePayRefValue"];
		$typePayRefDate = $res_detail["typePayRefDate"];
		$typePayAmt = $res_detail["typePayAmt"];
		$contractID = $res_detail["contractID"];
	}
				
	// หารายละเอียดค่าใช้จ่ายนั้นๆ
	$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
	while($res_tpDesc = pg_fetch_array($qry_tpDesc))
	{
		$tpDescShow = $res_tpDesc["tpDesc"];
	}
	
    $pdf->SetFont('AngsanaNew','B',12);
	
	//show only new page
    if($nub >= 36){
        $nub = 0;
        $cline = 43;
        $pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(40,10);
		$title=iconv('UTF-8','windows-874',"รายการที่รออนุมัติ");
		$pdf->MultiCell(50,8,$title,B,'C',0);
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการยกเว้นหนี้");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		/*$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);*/

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,31);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,'B','C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(27,34);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(31,39);
			// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);


		$pdf->SetXY(48,34);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
		$pdf->MultiCell(55,8,$buss_name,0,'C',0);

		$pdf->SetXY(90,34);
			$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(48,39);
			// $buss_name=iconv('UTF-8','windows-874',"");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(118,34);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
		$pdf->MultiCell(18,8,$buss_name,0,'C',0);

		$pdf->SetXY(135,34);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			// $pdf->SetXY(81,39);
			// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
			// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
		$pdf->SetXY(160,34);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(195,34);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาขอตั้งหนี้");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(208,34);
		$buss_name=iconv('UTF-8','windows-874',"เหตุผล");
		$pdf->MultiCell(105,8,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,8,$buss_name,'B','C',0);
    
	}
	
//show all record
    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$contractID");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayID");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(50,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tpDescShow");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(90,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(120,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(128,$cline);
    if($typePayAmt != ""){$buss_name=iconv('UTF-8','windows-874',number_format($typePayAmt,2));}
	else{$buss_name=iconv('UTF-8','windows-874',"$typePayAmt");}
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(155,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullnameuser");
    $pdf->MultiCell(45,4,$buss_name,0,'L',0);
	
	$pdf->SetXY(195,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$doerStamp");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(220,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$remark");
	$pdf->MultiCell(75,4,$buss_name,0,'L',0);
 
    $cline += 7;
    $nub+=2;
    $sum_amt+=$typePayAmt;
	
	unset($txtdeb);
	unset($remark);
} //end while 

$cline += 6;
$nub+=1;

    if($nub >= 36){
        $nub = 0;
        $cline = 43;
        $pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',25);
		$pdf->SetXY(40,10);
		$title=iconv('UTF-8','windows-874',"รายการที่รออนุมัติ");
		$pdf->MultiCell(50,8,$title,B,'C',0);
        
        $pdf->SetFont('AngsanaNew','B',18);
        		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการยกเว้นหนี้");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		/*$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"$txtcondate $datetext $subWhere $txtSubSelect");
		$pdf->MultiCell(290,4,$buss_name,0,'L',0);*/

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,31);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,'B','C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(27,34);
			$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
			$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				// $pdf->SetXY(31,39);
				// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
				// $pdf->MultiCell(18,6,$buss_name,0,'C',0);


			$pdf->SetXY(48,34);
			$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
			$pdf->MultiCell(55,8,$buss_name,0,'C',0);

			$pdf->SetXY(90,34);
				$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิง");
				$pdf->MultiCell(30,8,$buss_name,0,'C',0);

				// $pdf->SetXY(48,39);
				// $buss_name=iconv('UTF-8','windows-874',"");
				// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

			$pdf->SetXY(118,34);
			$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
			$pdf->MultiCell(18,8,$buss_name,0,'C',0);

			$pdf->SetXY(135,34);
			$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

				// $pdf->SetXY(81,39);
				// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
				// $pdf->MultiCell(20,6,$buss_name,0,'C',0);
			$pdf->SetXY(160,34);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(190,34);
			$buss_name=iconv('UTF-8','windows-874',"วันเวลาขอตั้งหนี้");
			$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			$pdf->SetXY(208,34);
			$buss_name=iconv('UTF-8','windows-874',"เหตุผล");
			$pdf->MultiCell(105,8,$buss_name,0,'C',0);

			$pdf->SetXY(5,34);
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(290,8,$buss_name,'B','C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,8,$buss_name,'B','C',0);

    }

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);
$pdf->Output();
?>